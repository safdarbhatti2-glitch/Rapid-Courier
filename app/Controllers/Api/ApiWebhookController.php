<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Services\WebhookService;

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
            "SELECT id, url, secret, events, status, created_at, updated_at FROM api_webhooks WHERE customer_id = ? ORDER BY created_at DESC",
            [$customerId]
        );

        foreach ($webhooks as &$wh) {
            $wh['events'] = !empty($wh['events']) ? json_decode($wh['events'], true) : [];
        }

        Response::apiSuccess($webhooks, [], 200);
    }

    public function update(Request $request, string $id): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $webhook = Database::fetchOne(
            "SELECT * FROM api_webhooks WHERE id = ? AND customer_id = ?",
            [(int)$id, $customerId]
        );

        if (!$webhook) {
            Response::apiError('WEBHOOK_NOT_FOUND', "Webhook '{$id}' not found.", [], 404);
        }

        $url    = trim($request->input('url', $webhook['url']));
        $status = trim($request->input('status', $webhook['status']));
        $events = $request->input('events', json_decode($webhook['events'], true));

        if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
            Response::apiError('VALIDATION_ERROR', 'A valid HTTP/HTTPS webhook URL is required.', ['url' => 'Invalid URL']);
        }

        if (!in_array($status, ['active', 'disabled', 'failed'], true)) {
            $status = 'active';
        }

        if (!is_array($events) || empty($events)) {
            $events = ['*'];
        }

        Database::execute(
            "UPDATE api_webhooks SET url = ?, status = ?, events = ?, updated_at = ? WHERE id = ?",
            [$url, $status, json_encode($events), date('Y-m-d H:i:s'), $webhook['id']]
        );

        Response::apiSuccess([
            'id'          => (int)$webhook['id'],
            'customer_id' => $customerId,
            'url'         => $url,
            'status'      => $status,
            'events'      => $events
        ], [], 200);
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

    public function test(Request $request, string $id): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $webhook = Database::fetchOne(
            "SELECT * FROM api_webhooks WHERE id = ? AND customer_id = ?",
            [(int)$id, $customerId]
        );

        if (!$webhook) {
            Response::apiError('WEBHOOK_NOT_FOUND', "Webhook '{$id}' not found.", [], 404);
        }

        $result = WebhookService::testDispatch($webhook);

        Response::apiSuccess($result, [], 200);
    }

    public function listDeliveries(Request $request): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $webhookId = (int)$request->input('webhook_id', 0);
        $status    = trim($request->input('status', ''));

        $where = ["wh.customer_id = ?"];
        $params = [$customerId];

        if ($webhookId > 0) {
            $where[] = "d.webhook_id = ?";
            $params[] = $webhookId;
        }

        if (!empty($status)) {
            $where[] = "d.status = ?";
            $params[] = $status;
        }

        $whereSql = implode(' AND ', $where);

        $deliveries = Database::fetchAll(
            "SELECT d.*, wh.url as webhook_url 
             FROM api_webhook_deliveries d 
             JOIN api_webhooks wh ON d.webhook_id = wh.id 
             WHERE {$whereSql} 
             ORDER BY d.created_at DESC LIMIT 50",
            $params
        );

        foreach ($deliveries as &$d) {
            $d['payload'] = !empty($d['payload']) ? json_decode($d['payload'], true) : null;
        }

        Response::apiSuccess($deliveries, [], 200);
    }

    public function retryDelivery(Request $request, string $id): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $delivery = Database::fetchOne(
            "SELECT d.*, wh.customer_id, wh.url, wh.secret 
             FROM api_webhook_deliveries d 
             JOIN api_webhooks wh ON d.webhook_id = wh.id 
             WHERE d.id = ? AND wh.customer_id = ?",
            [(int)$id, $customerId]
        );

        if (!$delivery) {
            Response::apiError('DELIVERY_NOT_FOUND', "Webhook delivery attempt '{$id}' not found or access denied.", [], 404);
        }

        $webhook = [
            'id'     => $delivery['webhook_id'],
            'url'    => $delivery['url'],
            'secret' => $delivery['secret']
        ];

        $result = WebhookService::retryDelivery($delivery, $webhook);

        Response::apiSuccess($result, [], 200);
    }
}
