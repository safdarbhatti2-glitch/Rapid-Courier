<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Database;
use App\Core\View;
use App\Services\ShipmentService;
use App\Services\InvoiceService;
use App\Services\QuoteService;

class AdminController
{
    public function dashboard(Request $request): void
    {
        $user = Session::get('user');

        $today = date('Y-m-d');

        $metrics = [
            'shipments_today' => Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments WHERE DATE(created_at) = ?", [$today])['cnt'] ?? 0,
            'in_transit'      => Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments WHERE status = 'IN_TRANSIT'")['cnt'] ?? 0,
            'delivered_today' => Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments WHERE status = 'DELIVERED' AND DATE(updated_at) = ?", [$today])['cnt'] ?? 0,
            'revenue_today'   => Database::fetchOne("SELECT COALESCE(SUM(amount), 0) as tot FROM payments WHERE DATE(paid_at) = ?", [$today])['tot'] ?? 0.00,
            'unpaid_invoices' => Database::fetchOne("SELECT COUNT(*) as cnt FROM invoices WHERE status IN ('ISSUED', 'PARTIALLY_PAID', 'OVERDUE')")['cnt'] ?? 0,
            'pending_quotes'  => Database::fetchOne("SELECT COUNT(*) as cnt FROM quotes WHERE status IN ('DRAFT', 'SENT')")['cnt'] ?? 0,
        ];

        $totalActive = max(1, ((int)$metrics['delivered_today'] + (int)$metrics['in_transit']));
        $deliveredPct = round(((int)$metrics['delivered_today'] / $totalActive) * 100);
        $transitPct = 100 - $deliveredPct;

        $recentShipments = Database::fetchAll("SELECT s.*, c.contact_name, c.company_name, serv.name as service_name FROM shipments s JOIN customers c ON s.customer_id = c.id JOIN services serv ON s.service_id = serv.id ORDER BY s.created_at DESC LIMIT 6");

        View::render('admin.dashboard', [
            'title'           => 'Admin Dashboard — RC Courier UAE',
            'user'            => $user,
            'metrics'         => $metrics,
            'deliveredPct'    => $deliveredPct,
            'transitPct'      => $transitPct,
            'recentShipments' => $recentShipments
        ], null);
    }

    public function shipments(Request $request): void
    {
        $status = trim($request->input('status', ''));
        $search = trim($request->input('q', ''));

        $metrics = [
            'total'      => Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments")['cnt'] ?? 0,
            'booked'     => Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments WHERE status = 'BOOKED'")['cnt'] ?? 0,
            'in_transit' => Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments WHERE status = 'IN_TRANSIT'")['cnt'] ?? 0,
            'delivered'  => Database::fetchOne("SELECT COUNT(*) as cnt FROM shipments WHERE status = 'DELIVERED'")['cnt'] ?? 0,
            'revenue'    => Database::fetchOne("SELECT COALESCE(SUM(total), 0) as tot FROM shipments")['tot'] ?? 0.00,
        ];

        $sql = "SELECT s.*, c.contact_name, c.company_name, serv.name as service_name FROM shipments s JOIN customers c ON s.customer_id = c.id JOIN services serv ON s.service_id = serv.id WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $sql .= " AND s.status = ?";
            $params[] = $status;
        }

        if (!empty($search)) {
            $sql .= " AND (s.reference_number LIKE ? OR s.tracking_number LIKE ? OR c.contact_name LIKE ? OR c.company_name LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }

        $sql .= " ORDER BY s.created_at DESC LIMIT 50";
        $shipments = Database::fetchAll($sql, $params);

        View::render('admin.shipments', [
            'title'     => 'Shipments Operations — Admin Portal',
            'shipments' => $shipments,
            'metrics'   => $metrics,
            'status'    => $status,
            'search'    => $search
        ], null);
    }

    public function createShipment(Request $request): void
    {
        $customers = Database::fetchAll("SELECT * FROM customers WHERE status = 'active' ORDER BY contact_name ASC");
        if (empty($customers)) {
            $customers = Database::fetchAll("SELECT * FROM customers ORDER BY contact_name ASC");
        }
        $addresses = Database::fetchAll("SELECT * FROM customer_addresses ORDER BY created_at DESC LIMIT 30");
        $emirates = ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah', 'Umm Al Quwain'];

        View::render('admin.shipment_create', [
            'title'     => 'Create New Shipment — Admin Portal',
            'customers' => $customers,
            'addresses' => $addresses,
            'emirates'  => $emirates
        ], null);
    }

    public function storeShipment(Request $request): void
    {
        $user = Session::get('user');
        $customerId = (int)$request->input('customer_id', 1);

        $serviceId   = (int)$request->input('service_id', 1);
        $origin      = trim($request->input('origin_emirate', 'Dubai'));
        $dest        = trim($request->input('destination_emirate', 'Abu Dhabi'));
        $weight      = (float)$request->input('weight_kg', 1.0);
        $pieces      = (int)$request->input('quantity', 1);
        $description = trim($request->input('item_description', 'Goods'));

        try {
            // Check if user requested saving receiver address for future
            if (!empty($request->input('save_receiver_address'))) {
                $label = trim($request->input('receiver_address_label', 'Receiver Address'));
                Database::execute(
                    "INSERT INTO customer_addresses (customer_id, label, address_line1, area, emirate, city, country) VALUES (?, ?, ?, ?, ?, ?, ?)",
                    [
                        $customerId,
                        $label,
                        trim($request->input('receiver_address', 'Address')),
                        trim($request->input('delivery_area', 'Central')),
                        $dest,
                        $dest,
                        'United Arab Emirates'
                    ]
                );
            }

            $shipment = ShipmentService::createShipment([
                'customer_id'         => $customerId,
                'service_id'          => $serviceId,
                'origin_emirate'      => $origin,
                'destination_emirate' => $dest,
                'weight_kg'           => $weight,
                'quantity'            => $pieces,
                'item_description'    => $description,
                'pickup_at'           => trim($request->input('pickup_at', '')),
                'payment_method'         => trim($request->input('payment_method', 'cash')),
                'card_number'            => trim($request->input('card_number', '')),
                'custom_shipping_charge' => $request->input('custom_shipping_charge', null),
                'sender_address'      => [
                    'label' => trim($request->input('sender_name', 'Sender')),
                    'line1' => trim($request->input('sender_address', 'Address')),
                    'area'  => trim($request->input('pickup_area', 'Central')),
                    'city'  => $origin
                ],
                'receiver_address'    => [
                    'label' => trim($request->input('receiver_name', 'Receiver')),
                    'line1' => trim($request->input('receiver_address', 'Address')),
                    'area'  => trim($request->input('delivery_area', 'Central')),
                    'city'  => $dest
                ]
            ]);

            Session::setFlash('success', "New shipment created! Reference: {$shipment['reference_number']} | Tracking: {$shipment['tracking_number']}.");
            Response::redirect("/admin/shipments/{$shipment['id']}");
        } catch (\Exception $e) {
            Session::setFlash('error', "Failed to create shipment: " . $e->getMessage());
            Response::redirect("/admin/shipments/create");
        }
    }

    public function shipmentDetail(Request $request, string $id): void
    {
        $shipment = Database::fetchOne("SELECT s.*, c.contact_name, c.company_name, c.email, c.phone, serv.name as service_name, oa.address_line1 as origin_addr, oa.emirate as origin_emirate, da.address_line1 as dest_addr, da.emirate as dest_emirate FROM shipments s JOIN customers c ON s.customer_id = c.id JOIN services serv ON s.service_id = serv.id JOIN customer_addresses oa ON s.origin_address_id = oa.id JOIN customer_addresses da ON s.destination_address_id = da.id WHERE s.id = ?", [$id]);

        if (!$shipment) {
            Session::setFlash('error', 'Shipment not found.');
            Response::redirect('/admin/shipments');
        }

        $events = Database::fetchAll("SELECT * FROM shipment_status_events WHERE shipment_id = ? ORDER BY event_time DESC", [$shipment['id']]);

        View::render('admin.shipment_detail', [
            'title'    => "Manage Shipment {$shipment['reference_number']} — Admin",
            'shipment' => $shipment,
            'events'   => $events
        ], null);
    }

    public function updateShipmentStatus(Request $request, string $id): void
    {
        $user = Session::get('user');
        $status    = trim($request->input('status', ''));
        $eventTime = trim($request->input('event_time', ''));

        if (empty($status)) {
            Session::setFlash('error', 'Please select a valid status.');
            Response::redirect("/admin/shipments/{$id}");
        }

        $shipment = Database::fetchOne("SELECT s.*, oa.emirate as origin_emirate, da.emirate as dest_emirate FROM shipments s JOIN customer_addresses oa ON s.origin_address_id = oa.id JOIN customer_addresses da ON s.destination_address_id = da.id WHERE s.id = ?", [(int)$id]);
        if (in_array($status, ['DELIVERED', 'AT_DESTINATION_HUB', 'OUT_FOR_DELIVERY'])) {
            $location = ($shipment['dest_emirate'] ?? 'Destination') . ' Hub';
        } else {
            $location = ($shipment['origin_emirate'] ?? 'Dubai') . ' Logistics Center';
        }

        try {
            ShipmentService::updateStatus((int)$id, $status, $location, null, null, $user['id'] ?? null, $eventTime);
            Session::setFlash('success', "Shipment status updated to {$status}.");
        } catch (\Exception $e) {
            Session::setFlash('error', "Status update failed: " . $e->getMessage());
        }

        Response::redirect("/admin/shipments/{$id}");
    }

    public function autoGenerateEvents(Request $request, string $id): void
    {
        $user = Session::get('user');
        $shipmentId = (int)$id;

        $startInput = trim($request->input('start_time', ''));
        $endInput   = trim($request->input('end_time', ''));
        $numEvents  = (int)$request->input('num_events', 4);

        if (empty($startInput) || empty($endInput)) {
            Session::setFlash('error', 'Please specify both start date and end date.');
            Response::redirect("/admin/shipments/{$shipmentId}");
        }

        $startTime = strtotime($startInput);
        $endTime   = strtotime($endInput);

        if ($endTime <= $startTime) {
            Session::setFlash('error', 'End date/time must be after start date/time.');
            Response::redirect("/admin/shipments/{$shipmentId}");
        }

        $shipment = Database::fetchOne("SELECT s.*, oa.emirate as origin_emirate, da.emirate as dest_emirate FROM shipments s JOIN customer_addresses oa ON s.origin_address_id = oa.id JOIN customer_addresses da ON s.destination_address_id = da.id WHERE s.id = ?", [$shipmentId]);
        if (!$shipment) {
            Session::setFlash('error', 'Shipment not found.');
            Response::redirect('/admin/shipments');
        }

        $origin = $shipment['origin_emirate'] ?: 'Dubai';
        $dest   = $shipment['dest_emirate'] ?: 'Abu Dhabi';

        if ($numEvents === 5) {
            $pipeline = [
                ['status' => 'BOOKED',            'location' => "{$origin} Dispatch Center"],
                ['status' => 'PICKED_UP',         'location' => "{$origin} Local Station"],
                ['status' => 'AT_ORIGIN_HUB',     'location' => "{$origin} Central Sorting Hub"],
                ['status' => 'IN_TRANSIT',        'location' => "E11 Sheikh Zayed Highway"],
                ['status' => 'DELIVERED',         'location' => "{$dest} Destination Address"],
            ];
        } else { // Default 4 Events
            $pipeline = [
                ['status' => 'BOOKED',            'location' => "{$origin} Dispatch Center"],
                ['status' => 'PICKED_UP',         'location' => "{$origin} Local Station"],
                ['status' => 'IN_TRANSIT',        'location' => "E11 Highway in Transit to {$dest}"],
                ['status' => 'DELIVERED',         'location' => "{$dest} Customer Address"],
            ];
        }

        $count = count($pipeline);
        $totalSeconds = $endTime - $startTime;
        $stepSeconds = $totalSeconds / ($count - 1);

        try {
            Database::beginTransaction();

            // Clear existing timeline events for fresh auto generation
            Database::execute("DELETE FROM shipment_status_events WHERE shipment_id = ?", [$shipmentId]);

            $lastStatus = 'BOOKED';
            for ($i = 0; $i < $count; $i++) {
                $eventTs = $startTime + ($i * $stepSeconds);
                $eventFormatted = date('Y-m-d H:i:s', (int)$eventTs);
                $step = $pipeline[$i];
                $lastStatus = $step['status'];

                Database::execute(
                    "INSERT INTO shipment_status_events (shipment_id, status, location_name, event_time, created_by) VALUES (?, ?, ?, ?, ?)",
                    [$shipmentId, $step['status'], $step['location'], $eventFormatted, $user['id'] ?? null]
                );
            }

            $now = date('Y-m-d H:i:s');
            // Update overall shipment status to final generated status
            Database::execute("UPDATE shipments SET status = ?, updated_at = ? WHERE id = ?", [$lastStatus, $now, $shipmentId]);

            Database::commit();

            Session::setFlash('success', "Auto-generated {$count} realistic events from " . date('M d, H:i', $startTime) . " to " . date('M d, H:i', $endTime) . "!");
        } catch (\Exception $e) {
            Database::rollBack();
            Session::setFlash('error', "Failed to auto-generate events: " . $e->getMessage());
        }

        Response::redirect("/admin/shipments/{$shipmentId}");
    }

    public function editShipment(Request $request, string $id): void
    {
        $shipment = Database::fetchOne("
            SELECT s.*, 
                   c.contact_name as customer_name, c.company_name, c.email as customer_email, c.phone as customer_phone,
                   oa.label as sender_name, oa.address_line1 as sender_address, oa.area as sender_area, oa.emirate as sender_emirate,
                   da.label as receiver_name, da.address_line1 as receiver_address, da.area as receiver_area, da.emirate as receiver_emirate,
                   serv.name as service_name
            FROM shipments s
            JOIN customers c ON s.customer_id = c.id
            JOIN customer_addresses oa ON s.origin_address_id = oa.id
            JOIN customer_addresses da ON s.destination_address_id = da.id
            LEFT JOIN services serv ON s.service_id = serv.id
            WHERE s.id = ?
        ", [$id]);

        if (!$shipment) {
            Session::setFlash('error', 'Shipment record not found.');
            Response::redirect('/admin/shipments');
        }

        $item     = Database::fetchOne("SELECT * FROM shipment_items WHERE shipment_id = ? ORDER BY id ASC LIMIT 1", [$id]);
        $services = Database::fetchAll("SELECT * FROM services ORDER BY id ASC");
        $emirates = ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah', 'Umm Al Quwain'];
        $statuses = [
            'BOOKED'             => 'BOOKED — Order Created',
            'PICKUP_ASSIGNED'    => 'PICKUP ASSIGNED — Courier Dispatched',
            'PICKED_UP'          => 'PICKED UP — Collected from Sender',
            'AT_ORIGIN_HUB'      => 'AT ORIGIN HUB — Sorting Center',
            'IN_TRANSIT'         => 'IN TRANSIT — On Highway / Linehaul',
            'AT_DESTINATION_HUB' => 'AT DESTINATION HUB — Destination Facility',
            'OUT_FOR_DELIVERY'   => 'OUT FOR DELIVERY — Driver Dispatched',
            'DELIVERED'          => 'DELIVERED — Successfully Delivered',
            'RETURNED'           => 'RETURNED — Returned to Origin',
            'CANCELLED'          => 'CANCELLED — Shipment Voided'
        ];

        View::render('admin.shipment_edit', [
            'title'    => "Edit Shipment {$shipment['reference_number']} — Admin",
            'shipment' => $shipment,
            'item'     => $item,
            'services' => $services,
            'emirates' => $emirates,
            'statuses' => $statuses
        ], null);
    }

    public function updateShipmentDetails(Request $request, string $id): void
    {
        $shipment = Database::fetchOne("SELECT * FROM shipments WHERE id = ?", [$id]);

        if (!$shipment) {
            Session::setFlash('error', 'Shipment record not found.');
            Response::redirect('/admin/shipments');
        }

        // Core Shipment Info & Status
        $status      = trim($request->input('status', $shipment['status']));
        $serviceId   = (int)$request->input('service_id', $shipment['service_id']);
        $trackingNum = trim($request->input('tracking_number', $shipment['tracking_number']));

        // Sender Details
        $senderName    = trim($request->input('sender_name', 'Sender'));
        $senderAddress = trim($request->input('sender_address', ''));
        $senderArea    = trim($request->input('sender_area', ''));
        $senderEmirate = trim($request->input('sender_emirate', 'Dubai'));

        // Receiver Details
        $receiverName    = trim($request->input('receiver_name', 'Receiver'));
        $receiverAddress = trim($request->input('receiver_address', ''));
        $receiverArea    = trim($request->input('receiver_area', ''));
        $receiverEmirate = trim($request->input('receiver_emirate', 'Abu Dhabi'));

        // Package & Volumetric Specs
        $description   = trim($request->input('item_description', 'General Cargo Package'));
        $quantity      = max(1, (int)$request->input('quantity', 1));
        $weightKg      = max(0.1, (float)$request->input('weight_kg', 1.0));
        $lengthCm      = max(1.0, (float)$request->input('length_cm', 10.0));
        $widthCm       = max(1.0, (float)$request->input('width_cm', 10.0));
        $heightCm      = max(1.0, (float)$request->input('height_cm', 10.0));
        $declaredValue = max(0.0, (float)$request->input('declared_value', 0.0));

        // Re-calculate pricing defaults or custom overrides
        $pricing = PricingService::calculate(
            $serviceId,
            $senderEmirate,
            $receiverEmirate,
            $weightKg,
            $lengthCm,
            $widthCm,
            $heightCm,
            $declaredValue
        );

        $subtotal = (float)$request->input('subtotal', $pricing['subtotal']);
        $discount = (float)$request->input('discount', 0.0);
        $tax      = (float)$request->input('tax', $pricing['tax']);
        $total    = max(0.0, ($subtotal - $discount) + $tax);

        try {
            Database::beginTransaction();
            $now = date('Y-m-d H:i:s');

            // 1. Update Origin Address
            Database::execute("
                UPDATE customer_addresses 
                SET label = ?, address_line1 = ?, area = ?, emirate = ?, city = ?, updated_at = ? 
                WHERE id = ?
            ", [$senderName, $senderAddress, $senderArea, $senderEmirate, $senderEmirate, $now, $shipment['origin_address_id']]);

            // 2. Update Destination Address
            Database::execute("
                UPDATE customer_addresses 
                SET label = ?, address_line1 = ?, area = ?, emirate = ?, city = ?, updated_at = ? 
                WHERE id = ?
            ", [$receiverName, $receiverAddress, $receiverArea, $receiverEmirate, $receiverEmirate, $now, $shipment['destination_address_id']]);

            // 3. Update Shipment Record
            Database::execute("
                UPDATE shipments 
                SET tracking_number = ?, status = ?, service_id = ?, weight_kg = ?, 
                    length_cm = ?, width_cm = ?, height_cm = ?, declared_value = ?, 
                    subtotal = ?, discount = ?, tax = ?, total = ?, updated_at = ? 
                WHERE id = ?
            ", [
                $trackingNum, $status, $serviceId, $pricing['chargeable_weight'],
                $lengthCm, $widthCm, $heightCm, $declaredValue,
                $subtotal, $discount, $tax, $total, $now, (int)$id
            ]);

            // 4. Update Shipment Items
            $itemExists = Database::fetchOne("SELECT id FROM shipment_items WHERE shipment_id = ?", [(int)$id]);
            if ($itemExists) {
                Database::execute("
                    UPDATE shipment_items 
                    SET description = ?, quantity = ?, weight_kg = ?, length_cm = ?, width_cm = ?, height_cm = ?, declared_value = ? 
                    WHERE shipment_id = ?
                ", [$description, $quantity, $pricing['chargeable_weight'], $lengthCm, $widthCm, $heightCm, $declaredValue, (int)$id]);
            } else {
                Database::execute("
                    INSERT INTO shipment_items (shipment_id, description, quantity, weight_kg, length_cm, width_cm, height_cm, declared_value) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ", [(int)$id, $description, $quantity, $pricing['chargeable_weight'], $lengthCm, $widthCm, $heightCm, $declaredValue]);
            }

            // 5. Sync linked invoice if present
            $invoice = Database::fetchOne("SELECT id FROM invoices WHERE shipment_id = ?", [(int)$id]);
            if ($invoice) {
                Database::execute("
                    UPDATE invoices 
                    SET subtotal = ?, discount = ?, tax = ?, total = ?, balance_due = (total - amount_paid), updated_at = ? 
                    WHERE id = ?
                ", [$subtotal, $discount, $tax, $total, $now, $invoice['id']]);

                Database::execute("
                    UPDATE invoice_items 
                    SET description = ?, quantity = ?, unit_price = ?, line_total = ? 
                    WHERE invoice_id = ?
                ", ["{$description} - {$trackingNum}", $quantity, $subtotal, $subtotal, $invoice['id']]);
            }

            AuditService::log('shipment_edit', 'shipment', (int)$id, null, [
                'sender'   => $senderName,
                'receiver' => $receiverName,
                'status'   => $status,
                'total'    => $total
            ]);

            Database::commit();

            Session::setFlash('success', "Shipment {$shipment['reference_number']} full details updated successfully!");
            Response::redirect("/admin/shipments/{$id}");
        } catch (\Exception $e) {
            Database::rollBack();
            Session::setFlash('error', "Failed to update shipment details: " . $e->getMessage());
            Response::redirect("/admin/shipments/{$id}/edit");
        }
    }

    public function tracking(Request $request): void
    {
        $shipments = Database::fetchAll("SELECT s.*, c.contact_name, serv.name as service_name FROM shipments s JOIN customers c ON s.customer_id = c.id JOIN services serv ON s.service_id = serv.id ORDER BY s.updated_at DESC LIMIT 30");

        View::render('admin.tracking', [
            'title'     => 'Real-Time Tracking Dispatcher — Admin',
            'shipments' => $shipments
        ], 'admin');
    }

    public function customers(Request $request): void
    {
        $customers = Database::fetchAll("SELECT c.*, COUNT(s.id) as total_shipments FROM customers c LEFT JOIN shipments s ON c.id = s.customer_id GROUP BY c.id ORDER BY c.created_at DESC");

        View::render('admin.customers', [
            'title'     => 'Customer Management (CRM) — Admin',
            'customers' => $customers
        ], 'admin');
    }

    public function customerDetail(Request $request, string $id): void
    {
        $customer = Database::fetchOne("SELECT * FROM customers WHERE id = ?", [$id]);
        if (!$customer) {
            Session::setFlash('error', 'Customer record not found.');
            Response::redirect('/admin/customers');
        }

        $shipments = Database::fetchAll("SELECT * FROM shipments WHERE customer_id = ? ORDER BY created_at DESC", [$id]);
        $invoices  = Database::fetchAll("SELECT * FROM invoices WHERE customer_id = ? ORDER BY created_at DESC", [$id]);

        View::render('admin.customer_detail', [
            'title'     => "Customer {$customer['contact_name']} — Admin",
            'customer'  => $customer,
            'shipments' => $shipments,
            'invoices'  => $invoices
        ], 'admin');
    }

    public function quotes(Request $request): void
    {
        $quotes = Database::fetchAll("SELECT q.*, c.company_name FROM quotes q LEFT JOIN customers c ON q.customer_id = c.id ORDER BY q.created_at DESC");

        View::render('admin.quotes', [
            'title'  => 'Quotation Management — Admin',
            'quotes' => $quotes
        ], 'admin');
    }

    public function convertQuote(Request $request, string $id): void
    {
        $user = Session::get('user');
        try {
            $quote = Database::fetchOne("SELECT * FROM quotes WHERE id = ?", [$id]);
            $customerId = $quote['customer_id'] ?? 1;

            $shipment = QuoteService::convertToShipment((int)$id, $customerId);
            Session::setFlash('success', "Quote converted to Shipment {$shipment['reference_number']}.");
            Response::redirect("/admin/shipments/{$shipment['id']}");
        } catch (\Exception $e) {
            Session::setFlash('error', "Conversion failed: " . $e->getMessage());
            Response::redirect("/admin/quotes");
        }
    }

    public function invoices(Request $request): void
    {
        $search = trim($request->input('q', ''));
        $status = trim($request->input('status', ''));

        $sql = "SELECT DISTINCT i.*, c.contact_name, c.company_name, s.tracking_number as shipment_tracking 
                FROM invoices i 
                JOIN customers c ON i.customer_id = c.id 
                LEFT JOIN shipments s ON i.shipment_id = s.id 
                LEFT JOIN invoice_items ii ON i.id = ii.invoice_id 
                WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $sql .= " AND i.status = ?";
            $params[] = $status;
        }

        if (!empty($search)) {
            $sql .= " AND (i.invoice_number LIKE ? OR s.tracking_number LIKE ? OR s.reference_number LIKE ? OR ii.reference LIKE ? OR c.contact_name LIKE ? OR c.company_name LIKE ?)";
            $term = "%{$search}%";
            $params = array_merge($params, [$term, $term, $term, $term, $term, $term]);
        }

        $sql .= " ORDER BY i.created_at DESC";
        $invoices = Database::fetchAll($sql, $params);

        View::render('admin.invoices', [
            'title'    => 'Financial Invoices — Admin',
            'invoices' => $invoices,
            'search'   => $search,
            'status'   => $status
        ], 'admin');
    }

    public function invoiceDetail(Request $request, string $id): void
    {
        $invoice = Database::fetchOne("SELECT i.*, c.contact_name, c.company_name, c.email, c.phone FROM invoices i JOIN customers c ON i.customer_id = c.id WHERE i.id = ?", [$id]);

        if (!$invoice) {
            Session::setFlash('error', 'Invoice record not found.');
            Response::redirect('/admin/invoices');
        }

        $items = Database::fetchAll("SELECT * FROM invoice_items WHERE invoice_id = ?", [$invoice['id']]);
        $payments = Database::fetchAll("SELECT * FROM payments WHERE invoice_id = ? ORDER BY paid_at DESC", [$invoice['id']]);

        View::render('admin.invoice_detail', [
            'title'    => "Invoice {$invoice['invoice_number']} — Admin",
            'invoice'  => $invoice,
            'items'    => $items,
            'payments' => $payments
        ], 'admin');
    }

    public function recordPayment(Request $request, string $id): void
    {
        $user = Session::get('user');
        $amount    = (float)$request->input('amount', 0.0);
        $method    = trim($request->input('method', 'credit_card'));
        $reference = trim($request->input('reference', ''));

        if ($amount <= 0) {
            Session::setFlash('error', 'Payment amount must be greater than zero.');
            Response::redirect("/admin/invoices/{$id}");
        }

        try {
            $res = InvoiceService::recordPayment((int)$id, $amount, $method, $reference, $user['id'] ?? null);
            Session::setFlash('success', "Payment recorded! Reference: {$res['payment_number']}. New Status: {$res['status']}.");
        } catch (\Exception $e) {
            Session::setFlash('error', "Payment failed: " . $e->getMessage());
        }

        Response::redirect("/admin/invoices/{$id}");
    }

    public function voidInvoice(Request $request, string $id): void
    {
        try {
            InvoiceService::voidInvoice((int)$id);
            Session::setFlash('success', "Invoice marked as VOID.");
        } catch (\Exception $e) {
            Session::setFlash('error', "Void failed: " . $e->getMessage());
        }

        Response::redirect("/admin/invoices/{$id}");
    }

    public function auditLogs(Request $request): void
    {
        $logs = Database::fetchAll("SELECT a.*, u.name as actor_name, u.email as actor_email FROM audit_logs a LEFT JOIN users u ON a.actor_id = u.id ORDER BY a.created_at DESC LIMIT 100");

        View::render('admin.audit_logs', [
            'title' => 'Security Audit Logs — Admin',
            'logs'  => $logs
        ], 'admin');
    }

    public function reports(Request $request): void
    {
        $revenue = Database::fetchOne("SELECT COALESCE(SUM(total),0) as total_rev, COALESCE(SUM(amount_paid),0) as paid_rev, COALESCE(SUM(balance_due),0) as unpaid_rev FROM invoices WHERE status != 'VOID'");
        $shipmentsByStatus = Database::fetchAll("SELECT status, COUNT(*) as cnt FROM shipments GROUP BY status");

        View::render('admin.reports', [
            'title'             => 'Financial & Operations Reports — Admin',
            'revenue'           => $revenue,
            'shipmentsByStatus' => $shipmentsByStatus
        ], 'admin');
    }

    public function settings(Request $request): void
    {
        $rawSettings = Database::fetchAll("SELECT * FROM settings");
        $settings = [];
        foreach ($rawSettings as $st) {
            $settings[$st['setting_key']] = $st['setting_value'];
        }

        View::render('admin.settings', [
            'title'    => 'Company Details & System Settings — Admin',
            'settings' => $settings
        ], null);
    }

    public function updateSettings(Request $request): void
    {
        $fields = [
            'company_name'    => ['val' => trim($request->input('company_name', 'RC Courier UAE LLC')), 'group' => 'general'],
            'company_address' => ['val' => trim($request->input('company_address', 'Dubai Logistics City Central Hub, Dubai, UAE')), 'group' => 'general'],
            'company_phone'   => ['val' => trim($request->input('company_phone', '+971 4 800 2684')), 'group' => 'general'],
            'company_email'   => ['val' => trim($request->input('company_email', 'support@rccourier.ae')), 'group' => 'general'],
            'company_trn'     => ['val' => trim($request->input('company_trn', '100987654321003')), 'group' => 'finance'],
            'tax_rate'        => ['val' => trim($request->input('tax_rate', '5.00')), 'group' => 'finance'],
            'default_currency'=> ['val' => trim($request->input('default_currency', 'AED')), 'group' => 'finance'],
        ];

        try {
            Database::beginTransaction();
            $now = date('Y-m-d H:i:s');
            foreach ($fields as $key => $item) {
                $exists = Database::fetchOne("SELECT id FROM settings WHERE setting_key = ?", [$key]);
                if ($exists) {
                    Database::execute("UPDATE settings SET setting_value = ?, updated_at = ? WHERE setting_key = ?", [$item['val'], $now, $key]);
                } else {
                    Database::execute("INSERT INTO settings (setting_key, setting_value, `group`, updated_at) VALUES (?, ?, ?, ?)", [$key, $item['val'], $item['group'], $now]);
                }
            }

            AuditService::log('settings_update', 'settings', null, null, ['company_name' => $fields['company_name']['val']]);
            Database::commit();

            Session::setFlash('success', 'Company details and system settings updated successfully!');
        } catch (\Exception $e) {
            Database::rollBack();
            Session::setFlash('error', 'Failed to update settings: ' . $e->getMessage());
        }

        Response::redirect('/admin/settings');
    }
}
