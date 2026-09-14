<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;

class ApiAccountController
{
    public function getAccount(Request $request): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $customer = Database::fetchOne(
            "SELECT id, user_id, customer_type, company_name, contact_name, email, phone, trn, status, created_at FROM customers WHERE id = ?",
            [$customerId]
        );

        if (!$customer) {
            Response::apiError('CUSTOMER_NOT_FOUND', 'Customer account record not found.', [], 404);
        }

        $shipmentsCount = Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments WHERE customer_id = ?", [$customerId]);
        $invoicesCount  = Database::fetchOne("SELECT COUNT(*) as cnt FROM invoices WHERE customer_id = ?", [$customerId]);
        $quotesCount    = Database::fetchOne("SELECT COUNT(*) as cnt FROM quotes WHERE customer_id = ?", [$customerId]);
        $keysCount      = Database::fetchOne("SELECT COUNT(*) as cnt FROM api_keys WHERE customer_id = ? AND status = 'active'", [$customerId]);

        $accountSummary = [
            'customer'  => $customer,
            'api_key'   => [
                'name'           => $keyRecord['name'],
                'environment'    => $keyRecord['environment'],
                'rate_limit_rpm' => (int)$keyRecord['rate_limit_rpm'],
                'permissions'    => $keyRecord['permissions_list']
            ],
            'statistics' => [
                'total_shipments' => (int)($shipmentsCount['cnt'] ?? 0),
                'total_invoices'  => (int)($invoicesCount['cnt'] ?? 0),
                'total_quotes'    => (int)($quotesCount['cnt'] ?? 0),
                'active_api_keys' => (int)($keysCount['cnt'] ?? 0)
            ]
        ];

        Response::apiSuccess($accountSummary, [], 200);
    }
}
