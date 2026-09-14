<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;

class ApiTrackingController
{
    /**
     * Public tracking endpoint (safe, customer-facing info only)
     */
    public function publicTrack(Request $request, string $trackingNumber): void
    {
        $trackingNumber = trim($trackingNumber);

        $shipment = Database::fetchOne(
            "SELECT s.reference_number, s.tracking_number, s.status, s.weight_kg, s.created_at, s.estimated_delivery_at, s.delivered_at,
                    serv.name as service_name, orig.emirate as origin_emirate, dest.emirate as dest_emirate
             FROM shipments s
             JOIN services serv ON s.service_id = serv.id
             JOIN customer_addresses orig ON s.origin_address_id = orig.id
             JOIN customer_addresses dest ON s.destination_address_id = dest.id
             WHERE s.tracking_number = ? OR s.reference_number = ?",
            [$trackingNumber, $trackingNumber]
        );

        if (!$shipment) {
            Response::apiError('TRACKING_NOT_FOUND', "Tracking number '{$trackingNumber}' not found.", [], 404);
        }

        $events = Database::fetchAll(
            "SELECT status, location_name as location, public_notes as description, event_time as timestamp 
             FROM shipment_status_events 
             WHERE shipment_id = (SELECT id FROM shipments WHERE tracking_number = ? OR reference_number = ?)
             ORDER BY event_time ASC",
            [$trackingNumber, $trackingNumber]
        );

        $response = [
            'tracking_number'       => $shipment['tracking_number'],
            'reference_number'      => $shipment['reference_number'],
            'status'                => $shipment['status'],
            'service_name'          => $shipment['service_name'],
            'origin'                => $shipment['origin_emirate'],
            'destination'           => $shipment['dest_emirate'],
            'booking_date'          => $shipment['created_at'],
            'estimated_delivery_at' => $shipment['estimated_delivery_at'],
            'delivered_at'          => $shipment['delivered_at'],
            'events'                => $events
        ];

        Response::apiSuccess($response, [], 200);
    }

    /**
     * Authenticated shipment tracking
     */
    public function getShipmentTracking(Request $request, string $id): void
    {
        $keyRecord = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $shipment = Database::fetchOne(
            "SELECT id, reference_number, tracking_number, status, pickup_at, estimated_delivery_at, delivered_at, created_at
             FROM shipments WHERE (id = ? OR tracking_number = ?) AND customer_id = ?",
            [(int)$id, $id, $customerId]
        );

        if (!$shipment) {
            Response::apiError('SHIPMENT_NOT_FOUND', "Shipment '{$id}' not found or access denied.", [], 404);
        }

        $events = Database::fetchAll(
            "SELECT id, status, location_name as location, public_notes as description, event_time as timestamp 
             FROM shipment_status_events WHERE shipment_id = ? ORDER BY event_time ASC",
            [$shipment['id']]
        );

        $shipment['events'] = $events;
        Response::apiSuccess($shipment, [], 200);
    }

    /**
     * List Immutable Shipment Status Events
     */
    public function getEvents(Request $request, string $id): void
    {
        $keyRecord = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $shipment = Database::fetchOne(
            "SELECT id FROM shipments WHERE (id = ? OR tracking_number = ?) AND customer_id = ?",
            [(int)$id, $id, $customerId]
        );

        if (!$shipment) {
            Response::apiError('SHIPMENT_NOT_FOUND', "Shipment '{$id}' not found or access denied.", [], 404);
        }

        $events = Database::fetchAll(
            "SELECT id, shipment_id, status, location_name as location, public_notes as description, event_time, created_at 
             FROM shipment_status_events WHERE shipment_id = ? ORDER BY event_time ASC",
            [$shipment['id']]
        );

        Response::apiSuccess($events, [], 200);
    }
}
