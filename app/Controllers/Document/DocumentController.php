<?php

namespace App\Controllers\Document;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Database;
use App\Core\View;
use App\Policies\InvoicePolicy;
use App\Policies\ShipmentPolicy;
use App\Policies\QuotePolicy;

class DocumentController
{
    public function printInvoice(Request $request, string $id): void
    {
        $user = Session::get('user');
        $invoice = Database::fetchOne("
            SELECT i.*, c.contact_name, c.company_name, c.email, c.phone, c.trn as customer_trn,
                   s.tracking_number, s.reference_number, s.status as shipment_status, s.weight_kg, s.declared_value, s.created_at as shipment_date,
                   si.description as item_description,
                   serv.name as service_name,
                   oa.label as sender_name, oa.address_line1 as sender_address, oa.area as sender_area, oa.emirate as sender_emirate,
                   da.label as receiver_name, da.address_line1 as receiver_address, da.area as receiver_area, da.emirate as receiver_emirate
            FROM invoices i 
            JOIN customers c ON i.customer_id = c.id 
            LEFT JOIN shipments s ON i.shipment_id = s.id
            LEFT JOIN shipment_items si ON s.id = si.shipment_id
            LEFT JOIN services serv ON s.service_id = serv.id
            LEFT JOIN customer_addresses oa ON s.origin_address_id = oa.id
            LEFT JOIN customer_addresses da ON s.destination_address_id = da.id
            WHERE i.id = ?
        ", [$id]);

        if (!$invoice || ($user && !InvoicePolicy::canView($user, $invoice))) {
            Session::setFlash('error', 'Invoice not found or access denied.');
            Response::redirect('/login');
        }

        $items = Database::fetchAll("SELECT * FROM invoice_items WHERE invoice_id = ?", [$invoice['id']]);
        $taxes = Database::fetchAll("SELECT * FROM invoice_taxes WHERE invoice_id = ?", [$invoice['id']]);

        $rawSettings = Database::fetchAll("SELECT * FROM settings");
        $company = [];
        foreach ($rawSettings as $st) {
            $company[$st['setting_key']] = $st['setting_value'];
        }

        $payments = Database::fetchAll("SELECT * FROM payments WHERE invoice_id = ? ORDER BY id DESC", [$invoice['id']]);

        View::render('documents.invoice', [
            'title'    => "TAX INVOICE {$invoice['invoice_number']} — RC Courier UAE",
            'invoice'  => $invoice,
            'items'    => $items,
            'taxes'    => $taxes,
            'payments' => $payments,
            'company'  => $company
        ], null);
    }

    public function printQuote(Request $request, string $id): void
    {
        $user = Session::get('user');
        $quote = Database::fetchOne("SELECT q.*, c.company_name FROM quotes q LEFT JOIN customers c ON q.customer_id = c.id WHERE q.id = ?", [$id]);

        if (!$quote || ($user && !QuotePolicy::canView($user, $quote))) {
            Session::setFlash('error', 'Quote not found or access denied.');
            Response::redirect('/login');
        }

        $items = Database::fetchAll("SELECT * FROM quote_items WHERE quote_id = ?", [$quote['id']]);

        View::render('documents.quote', [
            'title' => "QUOTATION {$quote['quote_number']} — Antigravity Express UAE",
            'quote' => $quote,
            'items' => $items
        ], null);
    }

    public function waybillLabel(Request $request, string $id): void
    {
        $user = Session::get('user');
        $shipment = Database::fetchOne("SELECT s.*, c.contact_name, c.company_name, c.phone as sender_phone, serv.name as service_name, oa.address_line1 as origin_line1, oa.area as origin_area, oa.emirate as origin_emirate, da.address_line1 as dest_line1, da.area as dest_area, da.emirate as dest_emirate FROM shipments s JOIN customers c ON s.customer_id = c.id JOIN services serv ON s.service_id = serv.id JOIN customer_addresses oa ON s.origin_address_id = oa.id JOIN customer_addresses da ON s.destination_address_id = da.id WHERE s.id = ?", [$id]);

        if (!$shipment || ($user && !ShipmentPolicy::canView($user, $shipment))) {
            Session::setFlash('error', 'Shipment not found or access denied.');
            Response::redirect('/login');
        }

        View::render('documents.waybill', [
            'title'    => "WAYBILL {$shipment['tracking_number']} — Antigravity Express UAE",
            'shipment' => $shipment
        ], null);
    }

    public function verifyInvoice(Request $request, string $invoice_number): void
    {
        $invoice_number = trim($invoice_number);

        $invoice = Database::fetchOne("
            SELECT i.id, i.invoice_number, i.status as invoice_status, i.issue_date, i.currency, i.subtotal, i.tax, i.total, i.amount_paid, i.balance_due, i.created_at,
                   c.contact_name, c.company_name,
                   s.tracking_number, s.reference_number, s.status as shipment_status, s.weight_kg,
                   serv.name as service_name,
                   oa.label as sender_name,
                   oa.emirate as origin_emirate,
                   da.emirate as dest_emirate
            FROM invoices i
            JOIN customers c ON i.customer_id = c.id
            LEFT JOIN shipments s ON i.shipment_id = s.id
            LEFT JOIN services serv ON s.service_id = serv.id
            LEFT JOIN customer_addresses oa ON s.origin_address_id = oa.id
            LEFT JOIN customer_addresses da ON s.destination_address_id = da.id
            WHERE i.invoice_number = ?
        ", [$invoice_number]);

        if (!$invoice) {
            View::render('documents.verify_invalid', [
                'title'          => 'Invalid Invoice — RC Courier UAE Verification',
                'invoice_number' => $invoice_number
            ], null);
            return;
        }

        View::render('documents.verify', [
            'title'   => "Verify Tax Invoice {$invoice['invoice_number']} — RC Courier UAE",
            'invoice' => $invoice
        ], null);
    }

    public function thermalReceipt(Request $request, string $id): void
    {
        $user = Session::get('user');
        $invoice = Database::fetchOne("
            SELECT i.*, c.contact_name, c.company_name, c.email, c.phone, c.trn as customer_trn,
                   s.tracking_number, s.reference_number, s.status as shipment_status, s.weight_kg, s.declared_value, s.created_at as shipment_date,
                   si.description as item_description,
                   serv.name as service_name,
                   oa.label as sender_name, oa.address_line1 as sender_address, oa.area as sender_area, oa.emirate as sender_emirate,
                   da.label as receiver_name, da.address_line1 as receiver_address, da.area as receiver_area, da.emirate as receiver_emirate
            FROM invoices i 
            JOIN customers c ON i.customer_id = c.id 
            LEFT JOIN shipments s ON i.shipment_id = s.id
            LEFT JOIN shipment_items si ON s.id = si.shipment_id
            LEFT JOIN services serv ON s.service_id = serv.id
            LEFT JOIN customer_addresses oa ON s.origin_address_id = oa.id
            LEFT JOIN customer_addresses da ON s.destination_address_id = da.id
            WHERE i.id = ?
        ", [$id]);

        if (!$invoice || ($user && !InvoicePolicy::canView($user, $invoice))) {
            Session::setFlash('error', 'Invoice not found or access denied.');
            Response::redirect('/login');
        }

        $items = Database::fetchAll("SELECT * FROM invoice_items WHERE invoice_id = ?", [$invoice['id']]);
        $payments = Database::fetchAll("SELECT * FROM payments WHERE invoice_id = ? ORDER BY id DESC", [$invoice['id']]);

        $rawSettings = Database::fetchAll("SELECT * FROM settings");
        $company = [];
        foreach ($rawSettings as $st) {
            $company[$st['setting_key']] = $st['setting_value'];
        }

        View::render('documents.thermal_receipt', [
            'title'    => "THERMAL RECEIPT {$invoice['invoice_number']} — RC Courier UAE",
            'invoice'  => $invoice,
            'items'    => $items,
            'payments' => $payments,
            'company'  => $company
        ], null);
    }
}
