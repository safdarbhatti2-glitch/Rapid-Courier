<?php

namespace App\Controllers\Customer;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Database;
use App\Core\View;
use App\Policies\ShipmentPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\QuotePolicy;

class CustomerController
{
    public function dashboard(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;

        $shipmentsCount = Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments WHERE customer_id = ?", [$customerId])['cnt'] ?? 0;
        $invoicesCount  = Database::fetchOne("SELECT COUNT(*) as cnt FROM invoices WHERE customer_id = ?", [$customerId])['cnt'] ?? 0;
        $quotesCount    = Database::fetchOne("SELECT COUNT(*) as cnt FROM quotes WHERE customer_id = ?", [$customerId])['cnt'] ?? 0;
        $activeCount    = Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments WHERE customer_id = ? AND status NOT IN ('DELIVERED', 'CANCELLED', 'RETURNED')", [$customerId])['cnt'] ?? 0;

        $recentShipments = Database::fetchAll("SELECT s.*, serv.name as service_name FROM shipments s JOIN services serv ON s.service_id = serv.id WHERE s.customer_id = ? ORDER BY s.created_at DESC LIMIT 5", [$customerId]);

        View::render('customer.dashboard', [
            'title'            => 'Customer Dashboard — RC Courier UAE',
            'user'             => $user,
            'shipmentsCount'   => $shipmentsCount,
            'invoicesCount'    => $invoicesCount,
            'quotesCount'      => $quotesCount,
            'activeCount'      => $activeCount,
            'recentShipments'  => $recentShipments
        ], 'customer');
    }

    public function shipments(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;

        $shipments = Database::fetchAll("SELECT s.*, serv.name as service_name, oa.emirate as origin_emirate, da.emirate as destination_emirate FROM shipments s JOIN services serv ON s.service_id = serv.id JOIN customer_addresses oa ON s.origin_address_id = oa.id JOIN customer_addresses da ON s.destination_address_id = da.id WHERE s.customer_id = ? ORDER BY s.created_at DESC", [$customerId]);

        View::render('customer.shipments', [
            'title'     => 'My Shipments — RC Courier UAE',
            'shipments' => $shipments
        ], 'customer');
    }

    public function shipmentDetail(Request $request, string $id): void
    {
        $user = Session::get('user');
        $shipment = Database::fetchOne("SELECT s.*, serv.name as service_name, oa.address_line1 as origin_addr, oa.emirate as origin_emirate, da.address_line1 as dest_addr, da.emirate as dest_emirate FROM shipments s JOIN services serv ON s.service_id = serv.id JOIN customer_addresses oa ON s.origin_address_id = oa.id JOIN customer_addresses da ON s.destination_address_id = da.id WHERE s.id = ?", [$id]);

        if (!$shipment || !ShipmentPolicy::canView($user, $shipment)) {
            Session::setFlash('error', 'Shipment not found or access denied.');
            Response::redirect('/customer/shipments');
        }

        $events = Database::fetchAll("SELECT * FROM shipment_status_events WHERE shipment_id = ? ORDER BY event_time ASC", [$shipment['id']]);

        View::render('customer.shipment_detail', [
            'title'    => "Shipment {$shipment['reference_number']} — RC Courier UAE",
            'shipment' => $shipment,
            'events'   => $events
        ], 'customer');
    }

    public function invoices(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;

        $invoices = Database::fetchAll("SELECT * FROM invoices WHERE customer_id = ? ORDER BY created_at DESC", [$customerId]);

        View::render('customer.invoices', [
            'title'    => 'My Invoices — RC Courier UAE',
            'invoices' => $invoices
        ], 'customer');
    }

    public function invoiceDetail(Request $request, string $id): void
    {
        $user = Session::get('user');
        $invoice = Database::fetchOne("SELECT i.*, c.contact_name, c.company_name, c.email, c.phone FROM invoices i JOIN customers c ON i.customer_id = c.id WHERE i.id = ?", [$id]);

        if (!$invoice || !InvoicePolicy::canView($user, $invoice)) {
            Session::setFlash('error', 'Invoice not found or access denied.');
            Response::redirect('/customer/invoices');
        }

        $items = Database::fetchAll("SELECT * FROM invoice_items WHERE invoice_id = ?", [$invoice['id']]);
        $payments = Database::fetchAll("SELECT * FROM payments WHERE invoice_id = ? ORDER BY paid_at DESC", [$invoice['id']]);

        View::render('customer.invoice_detail', [
            'title'    => "Invoice {$invoice['invoice_number']} — RC Courier UAE",
            'invoice'  => $invoice,
            'items'    => $items,
            'payments' => $payments
        ], 'customer');
    }

    public function quotes(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;

        $quotes = Database::fetchAll("SELECT * FROM quotes WHERE customer_id = ? OR contact_email = ? ORDER BY created_at DESC", [$customerId, $user['email']]);

        View::render('customer.quotes', [
            'title'  => 'My Quotations — RC Courier UAE',
            'quotes' => $quotes
        ], 'customer');
    }

    public function profile(Request $request): void
    {
        $user = Session::get('user');
        $customer = Database::fetchOne("SELECT * FROM customers WHERE id = ?", [$user['customer_id'] ?? 0]);

        View::render('customer.profile', [
            'title'    => 'Profile & Settings — RC Courier UAE',
            'user'     => $user,
            'customer' => $customer
        ], 'customer');
    }

    public function updateProfile(Request $request): void
    {
        $user = Session::get('user');
        $name  = trim($request->input('name', ''));
        $phone = trim($request->input('phone', ''));

        if (!empty($name)) {
            Database::execute("UPDATE users SET name = ?, phone = ?, updated_at = ? WHERE id = ?", [$name, $phone, date('Y-m-d H:i:s'), $user['id']]);
            $user['name'] = $name;
            $user['phone'] = $phone;
            Session::set('user', $user);
            Session::setFlash('success', 'Profile updated successfully.');
        }

        Response::redirect('/customer/profile');
    }

    public function apiKeys(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;

        $keys = Database::fetchAll(
            "SELECT * FROM api_keys WHERE customer_id = ? ORDER BY created_at DESC",
            [$customerId]
        );

        $webhooks = Database::fetchAll(
            "SELECT * FROM api_webhooks WHERE customer_id = ? ORDER BY created_at DESC",
            [$customerId]
        );

        $logs = Database::fetchAll(
            "SELECT * FROM api_audit_logs WHERE customer_id = ? ORDER BY created_at DESC LIMIT 50",
            [$customerId]
        );

        $deliveries = Database::fetchAll(
            "SELECT d.*, wh.url as webhook_url 
             FROM api_webhook_deliveries d 
             JOIN api_webhooks wh ON d.webhook_id = wh.id 
             WHERE wh.customer_id = ? 
             ORDER BY d.created_at DESC LIMIT 30",
            [$customerId]
        );

        $totalReq   = Database::fetchOne("SELECT COUNT(*) as cnt FROM api_audit_logs WHERE customer_id = ?", [$customerId])['cnt'] ?? 0;
        $successReq = Database::fetchOne("SELECT COUNT(*) as cnt FROM api_audit_logs WHERE customer_id = ? AND response_code < 400", [$customerId])['cnt'] ?? 0;
        $errorReq   = Database::fetchOne("SELECT COUNT(*) as cnt FROM api_audit_logs WHERE customer_id = ? AND response_code >= 400", [$customerId])['cnt'] ?? 0;
        $activeKeys = Database::fetchOne("SELECT COUNT(*) as cnt FROM api_keys WHERE customer_id = ? AND status = 'active'", [$customerId])['cnt'] ?? 0;

        $stats = [
            'total_requests'   => (int)$totalReq,
            'success_requests' => (int)$successReq,
            'error_requests'   => (int)$errorReq,
            'active_keys'      => (int)$activeKeys
        ];

        View::render('customer.api_keys', [
            'title'      => 'API Credentials & Webhooks — RC Courier UAE',
            'user'       => $user,
            'keys'       => $keys,
            'webhooks'   => $webhooks,
            'logs'       => $logs,
            'deliveries' => $deliveries,
            'stats'      => $stats
        ], 'customer');
    }

    public function createApiKey(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;
        $name = trim($request->input('name', 'Main API Credential'));
        $env  = trim($request->input('environment', 'live'));
        $perms = $request->input('permissions', []);

        $validScopes = [
            'shipments:create', 'shipments:read', 'shipments:cancel',
            'quotes:create', 'tracking:read', 'invoices:read',
            'labels:read', 'webhooks:manage'
        ];

        if (!is_array($perms) || empty($perms)) {
            $perms = $validScopes;
        } else {
            $perms = array_intersect($perms, $validScopes);
            if (empty($perms)) {
                $perms = $validScopes;
            }
        }

        if ($customerId > 0) {
            $created = \App\Services\ApiService::createApiKey($customerId, $name, $env, $perms);
            Session::setFlash('new_api_credential', $created);
            Session::setFlash('success', "API Key '{$name}' generated successfully! Store the secret safely.");
        } else {
            Session::setFlash('error', 'Customer profile required to create API credentials.');
        }

        Response::redirect('/customer/api-keys');
    }

    public function rotateApiKey(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;
        $keyId = (int)$request->input('key_id', 0);

        if ($customerId > 0 && $keyId > 0) {
            $oldKey = Database::fetchOne(
                "SELECT * FROM api_keys WHERE id = ? AND customer_id = ?",
                [$keyId, $customerId]
            );

            if ($oldKey) {
                // Revoke old key
                Database::execute(
                    "UPDATE api_keys SET status = 'revoked', revoked_at = ? WHERE id = ?",
                    [date('Y-m-d H:i:s'), $oldKey['id']]
                );

                $perms = !empty($oldKey['permissions']) ? json_decode($oldKey['permissions'], true) : [];
                $newKey = \App\Services\ApiService::createApiKey(
                    $customerId,
                    $oldKey['name'] . ' (Rotated)',
                    $oldKey['environment'],
                    $perms,
                    (int)($oldKey['rate_limit_rpm'] ?? 60)
                );

                Session::setFlash('new_api_credential', $newKey);
                Session::setFlash('success', "API Key '{$oldKey['name']}' rotated successfully! New key generated.");
            }
        }

        Response::redirect('/customer/api-keys');
    }

    public function revokeApiKey(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;
        $keyId = (int)$request->input('key_id', 0);

        if ($customerId > 0 && $keyId > 0) {
            Database::execute(
                "UPDATE api_keys SET status = 'revoked', revoked_at = ? WHERE id = ? AND customer_id = ?",
                [date('Y-m-d H:i:s'), $keyId, $customerId]
            );
            Session::setFlash('success', 'API credential revoked successfully.');
        }

        Response::redirect('/customer/api-keys');
    }

    public function createWebhook(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;

        $url = trim($request->input('url', ''));
        $events = $request->input('events', ['shipment.created', 'shipment.delivered', 'shipment.cancelled']);

        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            Session::setFlash('error', 'Please provide a valid HTTP or HTTPS Webhook URL.');
            Response::redirect('/customer/api-keys');
        }

        if (!is_array($events) || empty($events)) {
            $events = ['*'];
        }

        $secret = 'whsec_' . bin2hex(random_bytes(24));

        Database::execute(
            "INSERT INTO api_webhooks (customer_id, url, secret, events, status) VALUES (?, ?, ?, ?, 'active')",
            [$customerId, $url, $secret, json_encode($events)]
        );

        Session::setFlash('success', 'Webhook endpoint registered successfully.');
        Response::redirect('/customer/api-keys');
    }

    public function deleteWebhook(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;
        $whId = (int)$request->input('webhook_id', 0);

        if ($customerId > 0 && $whId > 0) {
            Database::execute("DELETE FROM api_webhooks WHERE id = ? AND customer_id = ?", [$whId, $customerId]);
            Session::setFlash('success', 'Webhook endpoint deleted.');
        }

        Response::redirect('/customer/api-keys');
    }

    public function toggleWebhook(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;
        $whId = (int)$request->input('webhook_id', 0);

        if ($customerId > 0 && $whId > 0) {
            $wh = Database::fetchOne("SELECT status FROM api_webhooks WHERE id = ? AND customer_id = ?", [$whId, $customerId]);
            if ($wh) {
                $newStatus = ($wh['status'] === 'active') ? 'disabled' : 'active';
                Database::execute("UPDATE api_webhooks SET status = ?, updated_at = ? WHERE id = ?", [$newStatus, date('Y-m-d H:i:s'), $whId]);
                Session::setFlash('success', "Webhook status updated to {$newStatus}.");
            }
        }

        Response::redirect('/customer/api-keys');
    }

    public function testWebhook(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;
        $whId = (int)$request->input('webhook_id', 0);

        if ($customerId > 0 && $whId > 0) {
            $wh = Database::fetchOne("SELECT * FROM api_webhooks WHERE id = ? AND customer_id = ?", [$whId, $customerId]);
            if ($wh) {
                $result = \App\Services\WebhookService::testDispatch($wh);
                if ($result['status'] === 'success') {
                    Session::setFlash('success', "Test Webhook dispatched successfully (HTTP {$result['response_code']}).");
                } else {
                    Session::setFlash('error', "Test Webhook failed (HTTP {$result['response_code']}). Error: " . ($result['response_body'] ?: 'Connection failed'));
                }
            }
        }

        Response::redirect('/customer/api-keys');
    }

    public function retryWebhookDelivery(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;
        $deliveryId = (int)$request->input('delivery_id', 0);

        if ($customerId > 0 && $deliveryId > 0) {
            $delivery = Database::fetchOne(
                "SELECT d.*, wh.customer_id, wh.url, wh.secret 
                 FROM api_webhook_deliveries d 
                 JOIN api_webhooks wh ON d.webhook_id = wh.id 
                 WHERE d.id = ? AND wh.customer_id = ?",
                [$deliveryId, $customerId]
            );

            if ($delivery) {
                $webhook = [
                    'id'     => $delivery['webhook_id'],
                    'url'    => $delivery['url'],
                    'secret' => $delivery['secret']
                ];

                $result = \App\Services\WebhookService::retryDelivery($delivery, $webhook);
                if ($result['status'] === 'success') {
                    Session::setFlash('success', "Webhook retry delivery succeeded (HTTP {$result['response_code']}).");
                } else {
                    Session::setFlash('error', "Webhook retry attempt failed (HTTP {$result['response_code']}).");
                }
            }
        }

        Response::redirect('/customer/api-keys');
    }
}
