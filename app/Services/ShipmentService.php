<?php

namespace App\Services;

use App\Core\Database;
use Exception;
use RuntimeException;

class ShipmentService
{
    public static function createShipment(array $data): array
    {
        Database::beginTransaction();

        try {
            // Generate Reference & Tracking Numbers (SHP-YYYY-XXRRRR with last 4 digits random)
            $year = date('Y');
            $countRow = Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments");
            $seqPrefix = str_pad((string)(($countRow['cnt'] + 1) % 100), 2, '0', STR_PAD_LEFT);
            
            do {
                $rand4 = str_pad((string)mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                $refNumber = "SHP-{$year}-{$seqPrefix}{$rand4}";
                $exists = Database::fetchOne("SELECT id FROM shipments WHERE reference_number = ?", [$refNumber]);
            } while ($exists);

            $randDigits = str_pad((string)mt_rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            $trkNumber  = "RC{$randDigits}";

            // Calculate pricing
            $pricing = PricingService::calculate(
                (int)$data['service_id'],
                $data['origin_emirate'],
                $data['destination_emirate'],
                (float)$data['weight_kg'],
                (float)($data['length_cm'] ?? 10),
                (float)($data['width_cm'] ?? 10),
                (float)($data['height_cm'] ?? 10),
                (float)($data['declared_value'] ?? 0)
            );

            if (isset($data['custom_shipping_charge']) && is_numeric($data['custom_shipping_charge']) && (float)$data['custom_shipping_charge'] >= 0 && trim((string)$data['custom_shipping_charge']) !== '') {
                $customSub = round((float)$data['custom_shipping_charge'], 2);
                $customTax = round($customSub * 0.05, 2);
                $customTot = $customSub + $customTax;

                $pricing['subtotal'] = $customSub;
                $pricing['tax']      = $customTax;
                $pricing['total']    = $customTot;
            }

            // Create Sender / Receiver Addresses if passed as raw array
            $originAddressId = self::ensureAddress($data['customer_id'], $data['sender_address'], $data['origin_emirate']);
            $destAddressId   = self::ensureAddress($data['customer_id'], $data['receiver_address'], $data['destination_emirate']);

            $pickupAt = !empty($data['pickup_at']) ? date('Y-m-d H:i:s', strtotime($data['pickup_at'])) : date('Y-m-d H:i:s');

            // Insert Shipment
            Database::execute("INSERT INTO shipments (reference_number, tracking_number, customer_id, service_id, origin_address_id, destination_address_id, status, weight_kg, length_cm, width_cm, height_cm, declared_value, subtotal, discount, tax, total, currency, pickup_at, estimated_delivery_at) VALUES (?, ?, ?, ?, ?, ?, 'BOOKED', ?, ?, ?, ?, ?, ?, 0.00, ?, ?, 'AED', ?, DATE_ADD(?, INTERVAL 1 DAY))", [
                $refNumber,
                $trkNumber,
                $data['customer_id'],
                $data['service_id'],
                $originAddressId,
                $destAddressId,
                $pricing['chargeable_weight'],
                $data['length_cm'] ?? 10,
                $data['width_cm'] ?? 10,
                $data['height_cm'] ?? 10,
                $data['declared_value'] ?? 0,
                $pricing['subtotal'],
                $pricing['tax'],
                $pricing['total'],
                $pickupAt,
                $pickupAt
            ]);

            $shipmentId = Database::lastInsertId();

            // Insert Item Package
            Database::execute("INSERT INTO shipment_items (shipment_id, description, quantity, weight_kg, length_cm, width_cm, height_cm, declared_value) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
                $shipmentId,
                $data['item_description'] ?? 'General Cargo Package',
                $data['quantity'] ?? 1,
                $pricing['chargeable_weight'],
                $data['length_cm'] ?? 10,
                $data['width_cm'] ?? 10,
                $data['height_cm'] ?? 10,
                $data['declared_value'] ?? 0
            ]);

            // Initial Status Event
            Database::execute("INSERT INTO shipment_status_events (shipment_id, status, location_name, public_notes, internal_notes, created_by) VALUES (?, 'BOOKED', ?, 'Shipment booking created successfully.', 'Web booking dispatch.', ?)", [
                $shipmentId,
                $data['origin_emirate'] . ' Hub',
                $data['created_by'] ?? null
            ]);

            // Auto-Generate Linked Tax Invoice for New Shipment
            $invNum = 'INV-' . date('Y') . '-' . str_pad((string)mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            Database::execute("
                INSERT INTO invoices (invoice_number, customer_id, shipment_id, status, issue_date, due_date, currency, subtotal, discount, tax, total, amount_paid, balance_due)
                VALUES (?, ?, ?, 'PAID', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), 'AED', ?, 0.00, ?, ?, ?, 0.00)
            ", [
                $invNum,
                $data['customer_id'],
                $shipmentId,
                $pricing['subtotal'],
                $pricing['tax'],
                $pricing['total'],
                $pricing['total']
            ]);
            $invoiceId = Database::lastInsertId();

            Database::execute("
                INSERT INTO invoice_items (invoice_id, description, reference, quantity, unit_price, discount, tax_rate, line_subtotal, line_tax, line_total)
                VALUES (?, ?, ?, 1, ?, 0.00, 5.00, ?, ?, ?)
            ", [
                $invoiceId,
                "Doorstep Logistics Courier Service - {$trkNumber}",
                $trkNumber,
                $pricing['subtotal'],
                $pricing['subtotal'],
                $pricing['tax'],
                $pricing['total']
            ]);

            $payMethod = ($data['payment_method'] ?? 'cash') === 'credit_card' ? 'credit_card' : 'cash';
            $payRef    = !empty($data['card_number']) ? $data['card_number'] : ($payMethod === 'credit_card' ? '**** **** **** ' . mt_rand(1000, 9999) : 'Cash Settlement');
            $payNum    = 'PAY-' . date('Y') . '-' . str_pad((string)mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

            Database::execute("
                INSERT INTO payments (payment_number, invoice_id, customer_id, amount, currency, method, reference, status, paid_at, created_by)
                VALUES (?, ?, ?, ?, 'AED', ?, ?, 'completed', NOW(), ?)
            ", [
                $payNum,
                $invoiceId,
                $data['customer_id'],
                $pricing['total'],
                $payMethod,
                $payRef,
                $data['created_by'] ?? null
            ]);

            AuditService::log('shipment_create', 'shipment', $shipmentId, null, ['reference_number' => $refNumber, 'total' => $pricing['total'], 'invoice_number' => $invNum, 'payment_method' => $payMethod]);

            Database::commit();

            return [
                'id'               => $shipmentId,
                'reference_number' => $refNumber,
                'tracking_number'  => $trkNumber,
                'pricing'          => $pricing
            ];

        } catch (Exception $e) {
            Database::rollBack();
            throw new RuntimeException("Failed to create shipment: " . $e->getMessage());
        }
    }

    public static function updateStatus(int $shipmentId, string $status, string $location, ?string $publicNotes = null, ?string $internalNotes = null, ?int $createdBy = null, ?string $eventTime = null): bool
    {
        $allowed = [
            'BOOKED', 'CONFIRMED', 'PICKUP_ASSIGNED', 'PICKED_UP',
            'AT_ORIGIN_HUB', 'IN_TRANSIT', 'AT_DESTINATION_HUB',
            'OUT_FOR_DELIVERY', 'DELIVERY_ATTEMPTED', 'DELIVERED',
            'CANCELLED', 'ON_HOLD', 'RETURNED'
        ];

        if (!in_array($status, $allowed)) {
            throw new RuntimeException("Invalid status transition: {$status}");
        }

        Database::beginTransaction();

        try {
            $shipment = Database::fetchOne("SELECT * FROM shipments WHERE id = ?", [$shipmentId]);
            if (!$shipment) {
                throw new RuntimeException("Shipment #{$shipmentId} not found");
            }

            // Update shipment
            $deliveredAtSql = ($status === 'DELIVERED') ? ", delivered_at = NOW()" : "";
            Database::execute("UPDATE shipments SET status = ? {$deliveredAtSql}, updated_at = NOW() WHERE id = ?", [$status, $shipmentId]);

            $time = !empty($eventTime) ? date('Y-m-d H:i:s', strtotime($eventTime)) : date('Y-m-d H:i:s');

            // Create Event with event_time
            Database::execute("INSERT INTO shipment_status_events (shipment_id, status, location_name, event_time, public_notes, internal_notes, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)", [
                $shipmentId, $status, $location, $time, $publicNotes, $internalNotes, $createdBy
            ]);

            AuditService::log('shipment_status_update', 'shipment', $shipmentId, ['status' => $shipment['status']], ['status' => $status]);

            Database::commit();
            return true;

        } catch (Exception $e) {
            Database::rollBack();
            throw new RuntimeException("Status update failed: " . $e->getMessage());
        }
    }

    public static function getTrackingInfo(string $trackingNumber): ?array
    {
        $shipment = Database::fetchOne("
            SELECT s.*, c.contact_name, c.company_name, serv.name as service_name, 
                   oa.label as sender_name, oa.address_line1 as origin_line1, oa.area as origin_area, oa.emirate as origin_emirate, 
                   da.label as receiver_name, da.address_line1 as dest_line1, da.area as dest_area, da.emirate as destination_emirate 
            FROM shipments s 
            JOIN customers c ON s.customer_id = c.id 
            JOIN services serv ON s.service_id = serv.id 
            JOIN customer_addresses oa ON s.origin_address_id = oa.id 
            JOIN customer_addresses da ON s.destination_address_id = da.id 
            WHERE s.tracking_number = ? OR s.reference_number = ?
        ", [$trackingNumber, $trackingNumber]);

        if (!$shipment) {
            return null;
        }

        // Fetch events (safe public fields only)
        $events = Database::fetchAll("SELECT status, location_name, public_notes, event_time FROM shipment_status_events WHERE shipment_id = ? ORDER BY event_time ASC", [$shipment['id']]);

        return [
            'shipment_id'           => $shipment['id'],
            'tracking_number'       => $shipment['tracking_number'],
            'reference_number'      => $shipment['reference_number'],
            'status'                => $shipment['status'],
            'service'               => $shipment['service_name'],
            'sender'                => $shipment['sender_name'] ?: ($shipment['company_name'] ?: $shipment['contact_name']),
            'receiver'              => $shipment['receiver_name'] ?: 'Valued Consignee',
            'origin'                => $shipment['origin_emirate'],
            'origin_full'           => ($shipment['origin_area'] ? $shipment['origin_area'] . ', ' : '') . $shipment['origin_emirate'] . ', United Arab Emirates',
            'destination'           => $shipment['destination_emirate'],
            'destination_full'      => ($shipment['dest_area'] ? $shipment['dest_area'] . ', ' : '') . $shipment['destination_emirate'] . ', United Arab Emirates',
            'weight_kg'             => $shipment['weight_kg'],
            'estimated_delivery_at' => $shipment['estimated_delivery_at'],
            'delivered_at'          => $shipment['delivered_at'],
            'updated_at'            => $shipment['updated_at'],
            'created_at'            => $shipment['created_at'],
            'timeline'              => $events
        ];
    }

    private static function ensureAddress(int $customerId, mixed $addrData, string $emirate): int
    {
        if (is_numeric($addrData)) {
            return (int)$addrData;
        }

        if (is_array($addrData)) {
            Database::execute("INSERT INTO customer_addresses (customer_id, label, address_line1, address_line2, area, emirate, city, country) VALUES (?, ?, ?, ?, ?, ?, 'Dubai', 'United Arab Emirates')", [
                $customerId,
                $addrData['label'] ?? 'Shipping Address',
                $addrData['line1'] ?? 'Building / Street',
                $addrData['line2'] ?? '',
                $addrData['area'] ?? 'Area',
                $emirate
            ]);
            return (int)Database::lastInsertId();
        }

        // Default address fallback
        $existing = Database::fetchOne("SELECT id FROM customer_addresses WHERE customer_id = ? LIMIT 1", [$customerId]);
        return $existing ? (int)$existing['id'] : 1;
    }
}
