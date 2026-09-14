<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;

class ApiInvoiceController
{
    public function get(Request $request, string $id): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $invoice = Database::fetchOne(
            "SELECT i.*, c.contact_name, c.company_name, c.email, c.phone, c.trn as customer_trn
             FROM invoices i JOIN customers c ON i.customer_id = c.id
             WHERE (i.id = ? OR i.invoice_number = ?) AND i.customer_id = ?",
            [(int)$id, $id, $customerId]
        );

        if (!$invoice) {
            Response::apiError('INVOICE_NOT_FOUND', "Invoice '{$id}' not found or access denied.", [], 404);
        }

        $items = Database::fetchAll("SELECT * FROM invoice_items WHERE invoice_id = ?", [$invoice['id']]);
        $taxes = Database::fetchAll("SELECT * FROM invoice_taxes WHERE invoice_id = ?", [$invoice['id']]);

        $invoice['items'] = $items;
        $invoice['taxes'] = $taxes;

        Response::apiSuccess($invoice, [], 200);
    }

    public function getByShipment(Request $request, string $shipmentId): void
    {
        $keyRecord  = $request->getApiKeyRecord();
        $customerId = (int)$keyRecord['customer_id'];

        $invoice = Database::fetchOne(
            "SELECT i.*, c.contact_name, c.company_name, c.email, c.phone
             FROM invoices i JOIN customers c ON i.customer_id = c.id
             JOIN shipments s ON i.shipment_id = s.id
             WHERE (s.id = ? OR s.tracking_number = ? OR s.reference_number = ?) AND i.customer_id = ?",
            [(int)$shipmentId, $shipmentId, $shipmentId, $customerId]
        );

        if (!$invoice) {
            Response::apiError('INVOICE_NOT_FOUND', "Invoice for shipment '{$shipmentId}' not found.", [], 404);
        }

        $items = Database::fetchAll("SELECT * FROM invoice_items WHERE invoice_id = ?", [$invoice['id']]);
        $invoice['items'] = $items;

        Response::apiSuccess($invoice, [], 200);
    }

    public function download(Request $request, string $id): void
    {
        $docController = new \App\Controllers\Document\DocumentController();
        $docController->printInvoice($request, $id);
    }
}
