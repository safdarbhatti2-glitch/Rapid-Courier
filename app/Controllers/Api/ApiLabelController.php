<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Services\LabelService;

class ApiLabelController
{
    public function getLabel(Request $request, string $id): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $shipment = Database::fetchOne(
            "SELECT id, reference_number, tracking_number, status, created_at FROM shipments WHERE (id = ? OR tracking_number = ?) AND customer_id = ?",
            [(int)$id, $id, $customerId]
        );

        if (!$shipment) {
            Response::apiError('SHIPMENT_NOT_FOUND', "Shipment '{$id}' not found or access denied.", [], 404);
        }

        $response = [
            'shipment_id'       => $shipment['id'],
            'reference_number'  => $shipment['reference_number'],
            'tracking_number'   => $shipment['tracking_number'],
            'label_format'      => '4x6 inch thermal',
            'thermal_label_url' => "/api/v1/shipments/{$shipment['id']}/label/thermal"
        ];

        Response::apiSuccess($response, [], 200);
    }

    public function getThermalLabel(Request $request, string $id): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $shipment = Database::fetchOne(
            "SELECT s.*, serv.name as service_name,
                    c.contact_name as sender_name, c.phone as sender_phone, orig.area as origin_area, orig.emirate as origin_emirate,
                    dest.address_line1 as dest_line1, dest.address_line2 as dest_line2, dest.area as dest_area, dest.emirate as dest_emirate
             FROM shipments s
             JOIN services serv ON s.service_id = serv.id
             JOIN customers c ON s.customer_id = c.id
             JOIN customer_addresses orig ON s.origin_address_id = orig.id
             JOIN customer_addresses dest ON s.destination_address_id = dest.id
             WHERE (s.id = ? OR s.tracking_number = ? OR s.reference_number = ?) AND s.customer_id = ?",
            [(int)$id, $id, $id, $customerId]
        );

        if (!$shipment) {
            Response::apiError('SHIPMENT_NOT_FOUND', "Shipment '{$id}' not found or access denied.", [], 404);
        }

        $thermalHtml = LabelService::generateThermalHtml($shipment);

        header('Content-Type: text/html; charset=utf-8');
        echo $thermalHtml;
        exit;
    }
}
