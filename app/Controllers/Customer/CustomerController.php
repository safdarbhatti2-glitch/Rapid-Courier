<?php

namespace App\Controllers\Customer;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Database;
use App\Core\View;
use App\Policies\ShipmentPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\QuotePolicy;

class CustomerController
{
    public function dashboard(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;

        $shipmentsCount = Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments WHERE customer_id = ?", [$customerId])['cnt'] ?? 0;
        $invoicesCount  = Database::fetchOne("SELECT COUNT(*) as cnt FROM invoices WHERE customer_id = ?", [$customerId])['cnt'] ?? 0;
        $quotesCount    = Database::fetchOne("SELECT COUNT(*) as cnt FROM quotes WHERE customer_id = ?", [$customerId])['cnt'] ?? 0;
        $activeCount    = Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments WHERE customer_id = ? AND status NOT IN ('DELIVERED', 'CANCELLED', 'RETURNED')", [$customerId])['cnt'] ?? 0;

        $recentShipments = Database::fetchAll("SELECT s.*, serv.name as service_name FROM shipments s JOIN services serv ON s.service_id = serv.id WHERE s.customer_id = ? ORDER BY s.created_at DESC LIMIT 5", [$customerId]);

        View::render('customer.dashboard', [
            'title'            => 'Customer Dashboard — RC Courier UAE',
            'user'             => $user,
            'shipmentsCount'   => $shipmentsCount,
            'invoicesCount'    => $invoicesCount,
            'quotesCount'      => $quotesCount,
            'activeCount'      => $activeCount,
            'recentShipments'  => $recentShipments
        ], 'customer');
    }

    public function shipments(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;

        $shipments = Database::fetchAll("SELECT s.*, serv.name as service_name, oa.emirate as origin_emirate, da.emirate as destination_emirate FROM shipments s JOIN services serv ON s.service_id = serv.id JOIN customer_addresses oa ON s.origin_address_id = oa.id JOIN customer_addresses da ON s.destination_address_id = da.id WHERE s.customer_id = ? ORDER BY s.created_at DESC", [$customerId]);

        View::render('customer.shipments', [
            'title'     => 'My Shipments — RC Courier UAE',
            'shipments' => $shipments
        ], 'customer');
    }

    public function shipmentDetail(Request $request, string $id): void
    {
        $user = Session::get('user');
        $shipment = Database::fetchOne("SELECT s.*, serv.name as service_name, oa.address_line1 as origin_addr, oa.emirate as origin_emirate, da.address_line1 as dest_addr, da.emirate as dest_emirate FROM shipments s JOIN services serv ON s.service_id = serv.id JOIN customer_addresses oa ON s.origin_address_id = oa.id JOIN customer_addresses da ON s.destination_address_id = da.id WHERE s.id = ?", [$id]);

        if (!$shipment || !ShipmentPolicy::canView($user, $shipment)) {
            Session::setFlash('error', 'Shipment not found or access denied.');
            Response::redirect('/customer/shipments');
        }

        $events = Database::fetchAll("SELECT * FROM shipment_status_events WHERE shipment_id = ? ORDER BY event_time ASC", [$shipment['id']]);

        View::render('customer.shipment_detail', [
            'title'    => "Shipment {$shipment['reference_number']} — RC Courier UAE",
            'shipment' => $shipment,
            'events'   => $events
        ], 'customer');
    }

    public function invoices(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;

        $invoices = Database::fetchAll("SELECT * FROM invoices WHERE customer_id = ? ORDER BY created_at DESC", [$customerId]);

        View::render('customer.invoices', [
            'title'    => 'My Invoices — RC Courier UAE',
            'invoices' => $invoices
        ], 'customer');
    }

    public function invoiceDetail(Request $request, string $id): void
    {
        $user = Session::get('user');
        $invoice = Database::fetchOne("SELECT i.*, c.contact_name, c.company_name, c.email, c.phone FROM invoices i JOIN customers c ON i.customer_id = c.id WHERE i.id = ?", [$id]);

        if (!$invoice || !InvoicePolicy::canView($user, $invoice)) {
            Session::setFlash('error', 'Invoice not found or access denied.');
            Response::redirect('/customer/invoices');
        }

        $items = Database::fetchAll("SELECT * FROM invoice_items WHERE invoice_id = ?", [$invoice['id']]);
        $payments = Database::fetchAll("SELECT * FROM payments WHERE invoice_id = ? ORDER BY paid_at DESC", [$invoice['id']]);

        View::render('customer.invoice_detail', [
            'title'    => "Invoice {$invoice['invoice_number']} — RC Courier UAE",
            'invoice'  => $invoice,
            'items'    => $items,
            'payments' => $payments
        ], 'customer');
    }

    public function quotes(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? 0;

        $quotes = Database::fetchAll("SELECT * FROM quotes WHERE customer_id = ? OR contact_email = ? ORDER BY created_at DESC", [$customerId, $user['email']]);

        View::render('customer.quotes', [
            'title'  => 'My Quotations — RC Courier UAE',
            'quotes' => $quotes
        ], 'customer');
    }

    public function profile(Request $request): void
    {
        $user = Session::get('user');
        $customer = Database::fetchOne("SELECT * FROM customers WHERE id = ?", [$user['customer_id'] ?? 0]);

        View::render('customer.profile', [
            'title'    => 'Profile & Settings — RC Courier UAE',
            'user'     => $user,
            'customer' => $customer
        ], 'customer');
    }

    public function updateProfile(Request $request): void
    {
        $user = Session::get('user');
        $name  = trim($request->input('name', ''));
        $phone = trim($request->input('phone', ''));

        if (!empty($name)) {
            Database::execute("UPDATE users SET name = ?, phone = ?, updated_at = ? WHERE id = ?", [$name, $phone, date('Y-m-d H:i:s'), $user['id']]);
            $user['name'] = $name;
            $user['phone'] = $phone;
            Session::set('user', $user);
            Session::setFlash('success', 'Profile updated successfully.');
        }

        Response::redirect('/customer/profile');
    }
}
