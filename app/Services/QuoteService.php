<?php

namespace App\Services;

use App\Core\Database;
use Exception;
use RuntimeException;

class QuoteService
{
    public static function createQuote(array $data): array
    {
        Database::beginTransaction();

        try {
            $year = date('Y');
            $countRow = Database::fetchOne("SELECT COUNT(*) as cnt FROM quotes");
            $nextSeq = str_pad((string)($countRow['cnt'] + 1), 6, '0', STR_PAD_LEFT);
            $quoteNumber = "QT-{$year}-{$nextSeq}";

            $pricing = PricingService::calculate(
                (int)($data['service_id'] ?? 1),
                $data['origin_emirate'] ?? 'Dubai',
                $data['destination_emirate'] ?? 'Abu Dhabi',
                (float)($data['weight_kg'] ?? 1.0)
            );

            $validUntil = date('Y-m-d', strtotime('+14 days'));

            Database::execute("INSERT INTO quotes (quote_number, customer_id, contact_name, contact_email, contact_phone, status, valid_until, subtotal, discount, tax, total, currency, notes) VALUES (?, ?, ?, ?, ?, 'SENT', ?, ?, 0.00, ?, ?, 'AED', ?)", [
                $quoteNumber,
                $data['customer_id'] ?? null,
                $data['name'],
                $data['email'],
                $data['phone'],
                $validUntil,
                $pricing['subtotal'],
                $pricing['tax'],
                $pricing['total'],
                $data['notes'] ?? 'UAE Express Courier Quotation'
            ]);

            $quoteId = Database::lastInsertId();

            Database::execute("INSERT INTO quote_items (quote_id, description, quantity, unit_price, discount, tax_rate, line_total) VALUES (?, ?, 1, ?, 0.00, 5.00, ?)", [
                $quoteId,
                "Express Logistics Shipping ({$data['origin_emirate']} to {$data['destination_emirate']})",
                $pricing['subtotal'],
                $pricing['total']
            ]);

            AuditService::log('quote_create', 'quote', $quoteId, null, ['quote_number' => $quoteNumber, 'total' => $pricing['total']]);

            Database::commit();

            return [
                'id'           => $quoteId,
                'quote_number' => $quoteNumber,
                'valid_until'  => $validUntil,
                'pricing'      => $pricing
            ];

        } catch (Exception $e) {
            Database::rollBack();
            throw new RuntimeException("Quote creation failed: " . $e->getMessage());
        }
    }

    public static function convertToShipment(int $quoteId, int $customerId): array
    {
        $quote = Database::fetchOne("SELECT * FROM quotes WHERE id = ?", [$quoteId]);
        if (!$quote) {
            throw new RuntimeException("Quote #{$quoteId} not found");
        }

        if ($quote['status'] === 'CONVERTED') {
            throw new RuntimeException("Quote #{$quote['quote_number']} has already been converted.");
        }

        // Create Shipment from quote parameters
        $shipmentData = [
            'customer_id'         => $customerId,
            'service_id'          => 1,
            'origin_emirate'      => 'Dubai',
            'destination_emirate' => 'Abu Dhabi',
            'weight_kg'           => 1.0,
            'sender_address'      => ['label' => 'Pickup HQ', 'line1' => 'Logistics City', 'area' => 'Business Bay'],
            'receiver_address'    => ['label' => 'Delivery Address', 'line1' => 'Corniche Plaza', 'area' => 'Al Khalidiya'],
            'item_description'    => "Converted from Quote {$quote['quote_number']}"
        ];

        $shipment = ShipmentService::createShipment($shipmentData);

        // Update quote status
        Database::execute("UPDATE quotes SET status = 'CONVERTED', updated_at = ? WHERE id = ?", [date('Y-m-d H:i:s'), $quoteId]);

        AuditService::log('quote_convert_shipment', 'quote', $quoteId, ['status' => $quote['status']], ['status' => 'CONVERTED', 'shipment_id' => $shipment['id']]);

        return $shipment;
    }
}
