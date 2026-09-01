<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\ShipmentService;
use App\Services\InvoiceService;
use App\Services\QuoteService;

class ApiController
{
    public function trackingInfo(Request $request, string $trackingNumber): void
    {
        $info = ShipmentService::getTrackingInfo($trackingNumber);
        if (!$info) {
            Response::error("Tracking number '{$trackingNumber}' not found", [], 404);
        }

        Response::success($info, "Tracking info retrieved successfully");
    }

    public function createShipment(Request $request): void
    {
        $user = Session::get('user');
        if (!$user) {
            Response::error("Authentication required", [], 401);
        }

        try {
            $data = $request->all();
            $data['customer_id'] = $data['customer_id'] ?? ($user['customer_id'] ?? 1);
            $data['created_by']  = $user['id'];

            $res = ShipmentService::createShipment($data);
            Response::success($res, "Shipment booked successfully", 201);
        } catch (\Exception $e) {
            Response::error($e->getMessage(), [], 400);
        }
    }

    public function updateShipmentStatus(Request $request, string $id): void
    {
        $user = Session::get('user');
        if (!$user || !in_array($user['role_name'], ['super_admin', 'admin', 'operations_manager', 'dispatcher'])) {
            Response::error("Forbidden. Insufficient permissions.", [], 403);
        }

        $status        = trim($request->input('status', ''));
        $location      = trim($request->input('location_name', 'Dubai Central Hub'));
        $publicNotes   = trim($request->input('public_notes', ''));
        $internalNotes = trim($request->input('internal_notes', ''));

        try {
            ShipmentService::updateStatus((int)$id, $status, $location, $publicNotes, $internalNotes, $user['id']);
            Response::success(null, "Shipment status updated to {$status}");
        } catch (\Exception $e) {
            Response::error($e->getMessage(), [], 400);
        }
    }

    public function createInvoice(Request $request): void
    {
        $user = Session::get('user');
        if (!$user || !in_array($user['role_name'], ['super_admin', 'admin', 'finance'])) {
            Response::error("Forbidden. Insufficient permissions.", [], 403);
        }

        try {
            $res = InvoiceService::createInvoice($request->all());
            Response::success($res, "Invoice created successfully", 201);
        } catch (\Exception $e) {
            Response::error($e->getMessage(), [], 400);
        }
    }

    public function recordPayment(Request $request, string $id): void
    {
        $user = Session::get('user');
        if (!$user || !in_array($user['role_name'], ['super_admin', 'admin', 'finance'])) {
            Response::error("Forbidden. Insufficient permissions.", [], 403);
        }

        $amount    = (float)$request->input('amount', 0.0);
        $method    = trim($request->input('method', 'credit_card'));
        $reference = trim($request->input('reference', ''));

        try {
            $res = InvoiceService::recordPayment((int)$id, $amount, $method, $reference, $user['id']);
            Response::success($res, "Payment recorded successfully");
        } catch (\Exception $e) {
            Response::error($e->getMessage(), [], 400);
        }
    }
}
