<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Services\PricingService;

class ApiQuoteController
{
    public function calculate(Request $request): void
    {
        $keyRecord = $request->getApiKeyRecord();

        $serviceCode    = trim($request->input('service', 'express_same_day'));
        $originEmirate  = trim($request->input('origin_emirate', $request->input('origin', 'Dubai')));
        $destEmirate    = trim($request->input('destination_emirate', $request->input('destination', 'Dubai')));
        $weightKg       = (float)$request->input('weight_kg', $request->input('weight', 1.0));
        $lengthCm       = (float)$request->input('length_cm', 10.0);
        $widthCm        = (float)$request->input('width_cm', 10.0);
        $heightCm       = (float)$request->input('height_cm', 10.0);
        $declaredValue  = (float)$request->input('declared_value', 0.0);

        if ($weightKg <= 0) {
            Response::apiError('VALIDATION_ERROR', 'Weight must be greater than 0 kg.', ['weight_kg' => 'Must be > 0']);
        }

        $service = Database::fetchOne("SELECT id, code, name FROM services WHERE code = ? OR id = ? OR active = 1 ORDER BY active DESC, id ASC", [$serviceCode, (int)$serviceCode]);
        $serviceId = $service['id'] ?? 1;

        $pricing = PricingService::calculate($serviceId, $originEmirate, $destEmirate, $weightKg, $lengthCm, $widthCm, $heightCm, $declaredValue);

        // Store Quotation in quotes table
        $quoteNumber = 'QT-' . date('Y') . '-' . str_pad((string)mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $validUntil  = date('Y-m-d', strtotime('+14 days'));

        Database::execute(
            "INSERT INTO quotes (quote_number, customer_id, contact_name, contact_email, contact_phone, status, valid_until, subtotal, discount, tax, total, currency, notes) VALUES (?, ?, ?, ?, ?, 'SENT', ?, ?, 0.00, ?, ?, 'AED', ?)",
            [
                $quoteNumber,
                $keyRecord['customer_id'] ?? null,
                $keyRecord['contact_name'] ?? 'API Client',
                $keyRecord['customer_email'] ?? 'client@api.com',
                '+971 4 800 2684',
                $validUntil,
                $pricing['subtotal'],
                $pricing['tax'],
                $pricing['total'],
                "API Quote: {$originEmirate} -> {$destEmirate}, {$weightKg}kg."
            ]
        );

        $quoteId = Database::lastInsertId();

        $pricing['quote_id']     = (int)$quoteId;
        $pricing['quote_number'] = $quoteNumber;
        $pricing['valid_until']  = $validUntil;

        Response::apiSuccess($pricing, [], 200);
    }

    public function get(Request $request, string $id): void
    {
        $keyRecord = $request->getApiKeyRecord();
        $quote = Database::fetchOne(
            "SELECT * FROM quotes WHERE (id = ? OR quote_number = ?) AND (customer_id = ? OR customer_id IS NULL)",
            [(int)$id, $id, $keyRecord['customer_id']]
        );

        if (!$quote) {
            Response::apiError('QUOTE_NOT_FOUND', "Quotation '{$id}' not found.", [], 404);
        }

        Response::apiSuccess($quote, [], 200);
    }
}
