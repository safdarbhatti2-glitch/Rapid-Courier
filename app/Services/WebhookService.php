<?php

namespace App\Services;

use App\Core\Database;
use Exception;

class WebhookService
{
    /**
     * Generate HMAC SHA256 Signature for Webhook Payload
     */
    public static function generateSignature(int $timestamp, string $jsonPayload, string $secret): string
    {
        return 't=' . $timestamp . ',v1=' . hash_hmac('sha256', $timestamp . '.' . $jsonPayload, $secret);
    }

    /**
     * Trigger shipment event webhooks for a customer
     */
    public static function triggerEvent(string $eventType, int $customerId, array $payloadData): void
    {
        $webhooks = Database::fetchAll(
            "SELECT * FROM api_webhooks WHERE customer_id = ? AND status = 'active'",
            [$customerId]
        );

        if (empty($webhooks)) {
            return;
        }

        $eventId = 'evt_' . bin2hex(random_bytes(8));
        $timestamp = date('Y-m-d\TH:i:s\Z');

        $fullPayload = [
            'event'      => $eventType,
            'event_id'   => $eventId,
            'created_at' => $timestamp,
            'data'       => $payloadData
        ];

        $jsonPayload = json_encode($fullPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        foreach ($webhooks as $webhook) {
            $events = !empty($webhook['events']) ? json_decode($webhook['events'], true) : [];
            if (!in_array('*', $events, true) && !in_array($eventType, $events, true)) {
                continue;
            }

            self::dispatchAsync($webhook, $eventType, $eventId, $fullPayload, $jsonPayload);
        }
    }

    /**
     * Dispatch Test Event to Webhook URL
     */
    public static function testDispatch(array $webhook): array
    {
        $eventId = 'evt_test_' . bin2hex(random_bytes(6));
        $timestamp = date('Y-m-d\TH:i:s\Z');

        $fullPayload = [
            'event'      => 'webhook.test',
            'event_id'   => $eventId,
            'created_at' => $timestamp,
            'data'       => [
                'webhook_id' => (int)$webhook['id'],
                'message'    => 'RC Courier API v1 Webhook Verification Ping'
            ]
        ];

        $jsonPayload = json_encode($fullPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return self::dispatchAsync($webhook, 'webhook.test', $eventId, $fullPayload, $jsonPayload);
    }

    /**
     * Retry a previous webhook delivery attempt
     */
    public static function retryDelivery(array $deliveryRecord, array $webhook): array
    {
        $jsonPayload = $deliveryRecord['payload'];
        $eventType   = $deliveryRecord['event_type'];
        $eventId     = $deliveryRecord['event_id'];
        $nextAttempt = (int)$deliveryRecord['attempt'] + 1;

        $payloadArray = json_decode($jsonPayload, true) ?: [];

        return self::dispatchAsync($webhook, $eventType, $eventId, $payloadArray, $jsonPayload, $nextAttempt);
    }

    /**
     * Dispatch webhook payload over HTTP POST with HMAC signature
     */
    private static function dispatchAsync(array $webhook, string $eventType, string $eventId, array $payloadArray, string $jsonPayload, int $attempt = 1): array
    {
        $startTime  = microtime(true);
        $timeHeader = time();
        $signature  = self::generateSignature($timeHeader, $jsonPayload, $webhook['secret']);

        $opts = [
            'http' => [
                'method'        => 'POST',
                'header'        => [
                    'Content-Type: application/json',
                    'User-Agent: RCCourier-Webhook/1.0',
                    'X-RC-Signature: ' . $signature,
                    'X-RC-Event: ' . $eventType,
                    'X-RC-Event-ID: ' . $eventId
                ],
                'content'       => $jsonPayload,
                'timeout'       => 5,
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($opts);
        $resCode = 0;
        $resBody = null;
        $status  = 'success';

        try {
            $response = @file_get_contents($webhook['url'], false, $context);
            $executionTimeMs = (int)round((microtime(true) - $startTime) * 1000);

            if (isset($http_response_header) && is_array($http_response_header)) {
                if (preg_match('#HTTP/\d\.\d\s+(\d+)#', $http_response_header[0], $matches)) {
                    $resCode = (int)$matches[1];
                }
            }

            $resBody = is_string($response) ? substr($response, 0, 1000) : null;
            if ($resCode < 200 || $resCode >= 300) {
                $status = 'failed';
            }

        } catch (Exception $e) {
            $executionTimeMs = (int)round((microtime(true) - $startTime) * 1000);
            $resCode = 0;
            $resBody = 'Connection Error: ' . $e->getMessage();
            $status = 'failed';
        }

        // Log Webhook Delivery Attempt
        $deliveryId = null;
        try {
            Database::execute(
                "INSERT INTO api_webhook_deliveries (webhook_id, event_type, event_id, payload, response_code, response_body, execution_time_ms, attempt, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $webhook['id'],
                    $eventType,
                    $eventId,
                    $jsonPayload,
                    $resCode,
                    $resBody,
                    $executionTimeMs,
                    $attempt,
                    $status
                ]
            );
            $deliveryId = Database::lastInsertId();
        } catch (Exception $e) {
            // Failsafe logging
        }

        // Check for 5 consecutive failures to auto-disable webhook
        if ($status === 'failed') {
            self::checkAutoDisable((int)$webhook['id']);
        }

        return [
            'delivery_id'       => $deliveryId,
            'webhook_id'        => (int)$webhook['id'],
            'event_type'        => $eventType,
            'event_id'          => $eventId,
            'status'            => $status,
            'response_code'     => $resCode,
            'response_body'     => $resBody,
            'execution_time_ms' => $executionTimeMs,
            'signature'         => $signature
        ];
    }

    /**
     * Disable webhook if the last 5 delivery attempts continuously failed
     */
    private static function checkAutoDisable(int $webhookId): void
    {
        try {
            $recent = Database::fetchAll(
                "SELECT status FROM api_webhook_deliveries WHERE webhook_id = ? ORDER BY id DESC LIMIT 5",
                [$webhookId]
            );

            if (count($recent) >= 5) {
                $allFailed = true;
                foreach ($recent as $r) {
                    if ($r['status'] !== 'failed') {
                        $allFailed = false;
                        break;
                    }
                }

                if ($allFailed) {
                    Database::execute(
                        "UPDATE api_webhooks SET status = 'disabled', updated_at = ? WHERE id = ?",
                        [date('Y-m-d H:i:s'), $webhookId]
                    );
                }
            }
        } catch (Exception $e) {
            // Failsafe
        }
    }
}
