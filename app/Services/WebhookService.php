<?php

namespace App\Services;

use App\Core\Database;
use Exception;

class WebhookService
{
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
     * Dispatch webhook payload over HTTP POST with HMAC signature
     */
    private static function dispatchAsync(array $webhook, string $eventType, string $eventId, array $payloadArray, string $jsonPayload): void
    {
        $startTime = microtime(true);
        $timeHeader = time();
        $signature = 't=' . $timeHeader . ',v1=' . hash_hmac('sha256', $timeHeader . '.' . $jsonPayload, $webhook['secret']);

        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => [
                    'Content-Type: application/json',
                    'User-Agent: RCCourier-Webhook/1.0',
                    'X-RC-Signature: ' . $signature,
                    'X-RC-Event: ' . $eventType,
                    'X-RC-Event-ID: ' . $eventId
                ],
                'content' => $jsonPayload,
                'timeout' => 5,
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($opts);
        $resCode = 0;
        $resBody = null;
        $status = 'success';

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
        try {
            Database::execute(
                "INSERT INTO api_webhook_deliveries (webhook_id, event_type, event_id, payload, response_code, response_body, execution_time_ms, attempt, status) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?)",
                [
                    $webhook['id'],
                    $eventType,
                    $eventId,
                    $jsonPayload,
                    $resCode,
                    $resBody,
                    $executionTimeMs,
                    $status
                ]
            );
        } catch (Exception $e) {
            // Ignore logging failsafe
        }
    }
}
