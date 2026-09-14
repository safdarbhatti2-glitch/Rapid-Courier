<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Services\ShipmentService;
use App\Services\ApiService;
use App\Services\WebhookService;
use Exception;

class ApiShipmentController
{
    public function create(Request $request): void
    {
        $keyRecord      = $request->getApiKeyRecord();
        $customerId     = (int)$keyRecord['customer_id'];
        $idempotencyKey = $request->getHeader('Idempotency-Key');
        $endpoint       = '/api/v1/shipments';

        // 1. Idempotency Check
        if ($idempotencyKey) {
            $cached = ApiService::getIdempotentResponse($customerId, $idempotencyKey, $endpoint);
            if ($cached) {
                Response::apiSuccess($cached['body']['data'] ?? [], ['idempotent_replay' => true], $cached['code']);
            }
        }

        // 2. Extract Data & Validate
        $serviceCode    = trim($request->input('service', 'express_same_day'));
        $sender         = $request->input('sender', []);
        $receiver       = $request->input('receiver', []);
        $package        = $request->input('package', []);

        $originEmirate  = trim($request->input('origin_emirate', $sender['address']['emirate'] ?? 'Dubai'));
        $destEmirate    = trim($request->input('destination_emirate', $receiver['address']['emirate'] ?? 'Dubai'));

        $fields = [];
        if (empty($receiver['name']) && empty($request->input('receiver_name'))) {
            $fields['receiver.name'] = 'Receiver name is required.';
        }
        if (empty($receiver['phone']) && empty($request->input('receiver_phone'))) {
            $fields['receiver.phone'] = 'Receiver phone number is required.';
        }

        if (!empty($fields)) {
            Response::apiError('VALIDATION_ERROR', 'Invalid shipment parameters.', $fields, 422);
        }

        $service = Database::fetchOne("SELECT id FROM services WHERE code = ? OR id = ? OR active = 1 ORDER BY active DESC, id ASC", [$serviceCode, (int)$serviceCode]);
        $serviceId = $service['id'] ?? 1;

        $weightKg      = (float)($package['weight_kg'] ?? $request->input('weight_kg', 1.0));
        $lengthCm      = (float)($package['length_cm'] ?? $request->input('length_cm', 10.0));
        $widthCm       = (float)($package['width_cm'] ?? $request->input('width_cm', 10.0));
        $heightCm      = (float)($package['height_cm'] ?? $request->input('height_cm', 10.0));
        $declaredValue = (float)($package['declared_value'] ?? $request->input('declared_value', 0.0));

        $senderAddressData = [
            'label'         => 'Sender Pickup',
            'contact_name'  => $sender['name'] ?? $keyRecord['contact_name'],
            'company_name'  => $sender['company'] ?? $keyRecord['company_name'],
            'phone'         => $sender['phone'] ?? '+971 4 800 2684',
            'address_line1' => $sender['address']['line1'] ?? $request->input('origin_address', 'Business Bay'),
            'address_line2' => $sender['address']['line2'] ?? null,
            'area'          => $sender['address']['area'] ?? 'Business Bay',
            'emirate'       => $originEmirate,
            'city'          => $sender['address']['city'] ?? $originEmirate
        ];

        $receiverAddressData = [
            'label'         => 'Receiver Address',
            'contact_name'  => $receiver['name'] ?? $request->input('receiver_name', 'Valued Customer'),
            'company_name'  => $receiver['company'] ?? null,
            'phone'         => $receiver['phone'] ?? $request->input('receiver_phone', '+971 50 123 4567'),
            'address_line1' => $receiver['address']['line1'] ?? $request->input('destination_address', 'Al Wasl'),
            'address_line2' => $receiver['address']['line2'] ?? null,
            'area'          => $receiver['address']['area'] ?? 'Al Wasl',
            'emirate'       => $destEmirate,
            'city'          => $receiver['address']['city'] ?? $destEmirate
        ];

        try {
            $shipmentResult = ShipmentService::createShipment([
                'customer_id'         => $customerId,
                'service_id'          => $serviceId,
                'origin_emirate'      => $originEmirate,
                'destination_emirate' => $destEmirate,
                'sender_address'      => $senderAddressData,
                'receiver_address'    => $receiverAddressData,
                'weight_kg'           => $weightKg,
                'length_cm'           => $lengthCm,
                'width_cm'            => $widthCm,
                'height_cm'           => $heightCm,
                'declared_value'      => $declaredValue,
                'item_description'    => $package['description'] ?? $request->input('item_description', 'Parcel Cargo'),
                'quantity'            => (int)($package['pieces'] ?? $request->input('quantity', 1)),
                'created_by'          => null
            ]);

            $shipmentId = $shipmentResult['id'];

            $responsePayload = [
                'shipment_id'       => $shipmentId,
                'reference_number'  => $shipmentResult['reference_number'],
                'tracking_number'   => $shipmentResult['tracking_number'],
                'status'            => 'BOOKED',
                'service_id'        => $serviceId,
                'weight_kg'         => $weightKg,
                'pricing'           => $shipmentResult['pricing'],
                'invoice_url'       => "/api/v1/shipments/{$shipmentId}/invoice",
                'thermal_label_url' => "/api/v1/shipments/{$shipmentId}/label/thermal"
            ];

            // Save Idempotency
            if ($idempotencyKey) {
                $reqHash = md5(json_encode($request->all()));
                ApiService::saveIdempotentResponse($customerId, $idempotencyKey, $endpoint, $reqHash, 201, ['data' => $responsePayload]);
            }

            // Trigger Webhooks
            WebhookService::triggerEvent('shipment.created', $customerId, $responsePayload);
            WebhookService::triggerEvent('shipment.booked', $customerId, $responsePayload);

            Response::apiSuccess($responsePayload, [], 201);

        } catch (Exception $e) {
            Response::apiError('SHIPMENT_CREATION_FAILED', $e->getMessage(), [], 400);
        }
    }

    public function list(Request $request): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $page   = max(1, (int)$request->input('page', 1));
        $limit  = min(100, max(1, (int)$request->input('limit', 20)));
        $offset = ($page - 1) * $limit;

        $status = trim($request->input('status', ''));
        $search = trim($request->input('search', ''));

        $where = ["s.customer_id = ?"];
        $params = [$customerId];

        if (!empty($status)) {
            $where[] = "s.status = ?";
            $params[] = strtoupper($status);
        }

        if (!empty($search)) {
            $where[] = "(s.reference_number LIKE ? OR s.tracking_number LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $whereSql = implode(' AND ', $where);

        $countRow = Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments s WHERE {$whereSql}", $params);
        $totalRecords = (int)($countRow['cnt'] ?? 0);

        $sql = "SELECT s.*, serv.name as service_name, serv.code as service_code FROM shipments s JOIN services serv ON s.service_id = serv.id WHERE {$whereSql} ORDER BY s.created_at DESC LIMIT {$limit} OFFSET {$offset}";
        $shipments = Database::fetchAll($sql, $params);

        $meta = [
            'page'          => $page,
            'limit'         => $limit,
            'total_records' => $totalRecords,
            'total_pages'   => ceil($totalRecords / $limit)
        ];

        Response::apiSuccess($shipments, $meta, 200);
    }

    public function get(Request $request, string $id): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $shipment = Database::fetchOne(
            "SELECT s.*, serv.name as service_name, serv.code as service_code, 
                    c.contact_name as sender_name, c.phone as sender_phone, orig.address_line1 as origin_line1, orig.area as origin_area, orig.emirate as origin_emirate,
                    dest.address_line1 as dest_line1, dest.area as dest_area, dest.emirate as dest_emirate
             FROM shipments s 
             JOIN services serv ON s.service_id = serv.id
             JOIN customers c ON s.customer_id = c.id
             JOIN customer_addresses orig ON s.origin_address_id = orig.id
             JOIN customer_addresses dest ON s.destination_address_id = dest.id
             WHERE (s.id = ? OR s.reference_number = ? OR s.tracking_number = ?) AND s.customer_id = ?",
            [(int)$id, $id, $id, $customerId]
        );

        if (!$shipment) {
            Response::apiError('SHIPMENT_NOT_FOUND', "Shipment '{$id}' not found or access denied.", [], 404);
        }

        $items = Database::fetchAll("SELECT * FROM shipment_items WHERE shipment_id = ?", [$shipment['id']]);
        $events = Database::fetchAll("SELECT id, status, location_name, public_notes, event_time FROM shipment_status_events WHERE shipment_id = ? ORDER BY event_time ASC", [$shipment['id']]);

        $shipment['items']  = $items;
        $shipment['events'] = $events;

        Response::apiSuccess($shipment, [], 200);
    }

    public function cancel(Request $request, string $id): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $shipment = Database::fetchOne(
            "SELECT * FROM shipments WHERE (id = ? OR reference_number = ?) AND customer_id = ?",
            [(int)$id, $id, $customerId]
        );

        if (!$shipment) {
            Response::apiError('SHIPMENT_NOT_FOUND', "Shipment '{$id}' not found or access denied.", [], 404);
        }

        if (in_array($shipment['status'], ['DELIVERED', 'IN_TRANSIT', 'OUT_FOR_DELIVERY', 'CANCELLED'])) {
            Response::apiError('CANNOT_CANCEL_SHIPMENT', "Shipment status '{$shipment['status']}' cannot be cancelled.", [], 400);
        }

        ShipmentService::updateStatus((int)$shipment['id'], 'CANCELLED', 'Customer Portal', 'Cancelled via API request');

        WebhookService::triggerEvent('shipment.cancelled', $customerId, [
            'shipment_id'      => $shipment['id'],
            'tracking_number'  => $shipment['tracking_number'],
            'reference_number' => $shipment['reference_number'],
            'status'           => 'CANCELLED'
        ]);

        Response::apiSuccess(['shipment_id' => $shipment['id'], 'status' => 'CANCELLED'], [], 200);
    }
}
