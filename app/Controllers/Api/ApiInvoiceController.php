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

        $invNum = $invoice['invoice_number'];
        $subtotal = number_format((float)$invoice['subtotal'], 2);
        $tax = number_format((float)$invoice['tax'], 2);
        $total = number_format((float)$invoice['total'], 2);

        $itemsHtml = '';
        foreach ($items as $item) {
            $itemsHtml .= "<tr><td>" . htmlspecialchars($item['description']) . "</td><td>1</td><td>AED " . number_format((float)$item['unit_price'], 2) . "</td><td>5%</td><td>AED " . number_format((float)$item['line_total'], 2) . "</td></tr>";
        }

        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>TAX INVOICE — {$invNum}</title>
<style>
  body { font-family: Arial, sans-serif; padding: 40px; color: #1e293b; line-height: 1.5; }
  .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0b1830; padding-bottom: 20px; }
  .title { font-size: 24px; font-weight: 800; color: #0b1830; }
  .table { width: 100%; border-collapse: collapse; margin-top: 30px; }
  .table th, .table td { padding: 10px; border: 1px solid #cbd5e1; text-align: left; }
  .table th { background: #f1f5f9; }
  .totals { float: right; margin-top: 20px; width: 300px; }
  .totals td { padding: 5px 10px; }
</style>
</head>
<body onload="window.print()">
<div class="header">
  <div>
    <div class="title">RC COURIER UAE LLC</div>
    <div>Dubai Logistics City, UAE</div>
    <div>TRN: 100987654321003</div>
  </div>
  <div style="text-align: right;">
    <div style="font-size: 20px; font-weight: 800;">TAX INVOICE</div>
    <div><b>Invoice #:</b> {$invNum}</div>
    <div><b>Date:</b> {$invoice['issue_date']}</div>
    <div><b>Status:</b> {$invoice['status']}</div>
  </div>
</div>

<div style="margin-top: 20px;">
  <b>BILLED TO:</b><br>
  {$invoice['contact_name']}<br>
  {$invoice['company_name']}<br>
  Email: {$invoice['email']} | Phone: {$invoice['phone']}
</div>

<table class="table">
  <thead>
    <tr><th>Description</th><th>Qty</th><th>Unit Price</th><th>VAT Rate</th><th>Total</th></tr>
  </thead>
  <tbody>
    {$itemsHtml}
  </tbody>
</table>

<table class="totals">
  <tr><td><b>Subtotal:</b></td><td>AED {$subtotal}</td></tr>
  <tr><td><b>UAE VAT (5%):</b></td><td>AED {$tax}</td></tr>
  <tr><td><b>Grand Total:</b></td><td><b>AED {$total}</b></td></tr>
</table>
</body>
</html>
HTML;

        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }
}
