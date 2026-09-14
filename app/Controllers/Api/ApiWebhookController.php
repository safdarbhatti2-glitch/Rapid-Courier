<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;

class ApiWebhookController
{
    public function register(Request $request): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $url    = trim($request->input('url', ''));
        $events = $request->input('events', ['shipment.created', 'shipment.delivered', 'shipment.cancelled']);

        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            Response::apiError('VALIDATION_ERROR', 'A valid HTTP/HTTPS webhook URL is required.', ['url' => 'Invalid URL']);
        }

        if (!is_array($events) || empty($events)) {
            $events = ['*'];
        }

        $secret = 'whsec_' . bin2hex(random_bytes(24));

        Database::execute(
            "INSERT INTO api_webhooks (customer_id, url, secret, events, status) VALUES (?, ?, ?, ?, 'active')",
            [$customerId, $url, $secret, json_encode($events)]
        );

        $webhookId = Database::lastInsertId();

        $response = [
            'id'          => (int)$webhookId,
            'customer_id' => $customerId,
            'url'         => $url,
            'secret'      => $secret,
            'events'      => $events,
            'status'      => 'active'
        ];

        Response::apiSuccess($response, [], 201);
    }

    public function list(Request $request): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $webhooks = Database::fetchAll(
            "SELECT id, url, secret, events, status, created_at FROM api_webhooks WHERE customer_id = ? ORDER BY created_at DESC",
            [$customerId]
        );

        foreach ($webhooks as &$wh) {
            $wh['events'] = !empty($wh['events']) ? json_decode($wh['events'], true) : [];
        }

        Response::apiSuccess($webhooks, [], 200);
    }

    public function delete(Request $request, string $id): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $webhook = Database::fetchOne(
            "SELECT id FROM api_webhooks WHERE id = ? AND customer_id = ?",
            [(int)$id, $customerId]
        );

        if (!$webhook) {
            Response::apiError('WEBHOOK_NOT_FOUND', "Webhook '{$id}' not found.", [], 404);
        }

        Database::execute("DELETE FROM api_webhooks WHERE id = ?", [$webhook['id']]);

        Response::apiSuccess(['id' => (int)$id, 'deleted' => true], [], 200);
    }
}
