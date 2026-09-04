<?php

namespace App\Controllers\Public;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Database;
use App\Core\View;
use App\Services\ShipmentService;
use App\Services\QuoteService;
use App\Services\PricingService;

class HomeController
{
    public function index(Request $request): void
    {
        $services = Database::fetchAll("SELECT * FROM services WHERE active = 1 ORDER BY id ASC");
        $locations = Database::fetchAll("SELECT * FROM locations WHERE active = 1 LIMIT 3");

        View::render('public.home', [
            'title'     => 'Rapid Courier UAE — Premium Logistics & Express Courier',
            'services'  => $services,
            'locations' => $locations
        ], 'main');
    }

    public function about(Request $request): void
    {
        View::render('public.about', ['title' => 'About Us | RC Courier UAE'], null);
    }

    public function services(Request $request): void
    {
        $services = Database::fetchAll("SELECT * FROM services WHERE active = 1 ORDER BY id ASC");
        View::render('public.services', [
            'title'    => 'Services | RC Courier UAE',
            'services' => $services
        ], null);
    }

    public function serviceDetail(Request $request, string $slug): void
    {
        $service = Database::fetchOne("SELECT * FROM services WHERE LOWER(code) = ? OR LOWER(REPLACE(name, ' ', '-')) = ?", [strtolower($slug), strtolower($slug)]);
        if (!$service) {
            $service = Database::fetchOne("SELECT * FROM services LIMIT 1");
        }

        View::render('public.service_detail', [
            'title'   => "{$service['name']} — Antigravity Express UAE",
            'service' => $service
        ], 'main');
    }

    public function locations(Request $request): void
    {
        $locations = Database::fetchAll("SELECT * FROM locations WHERE active = 1 ORDER BY is_hub DESC");
        View::render('public.locations', [
            'title'     => 'Locations | RC Courier UAE',
            'locations' => $locations
        ], null);
    }

    public function track(Request $request): void
    {
        $number = trim($request->input('number', ''));
        $searched = !empty($number);
        $trackingInfo = null;

        if ($searched) {
            $trackingInfo = ShipmentService::getTrackingInfo($number);
        }

        View::render('public.track', [
            'title'        => 'Track Shipment | RC Courier UAE',
            'number'       => $number,
            'searched'     => $searched,
            'trackingInfo' => $trackingInfo
        ], null);
    }

    public function showQuote(Request $request): void
    {
        $services = Database::fetchAll("SELECT * FROM services WHERE active = 1");
        $emirates = ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah', 'Umm Al Quwain'];

        View::render('public.quote', [
            'title'    => 'Get a Quote | RC Courier UAE',
            'services' => $services,
            'emirates' => $emirates
        ], null);
    }

    public function submitQuote(Request $request): void
    {
        $name    = trim($request->input('name', ''));
        $email   = trim($request->input('email', ''));
        $phone   = trim($request->input('phone', ''));
        $origin  = trim($request->input('origin_emirate', 'Dubai'));
        $dest    = trim($request->input('destination_emirate', 'Abu Dhabi'));
        $weight  = (float)$request->input('weight_kg', 1.0);
        $service = (int)$request->input('service_id', 1);

        if (empty($name) || empty($email) || empty($phone)) {
            Session::setFlash('error', 'Please fill in all required contact fields.');
            Response::redirect('/quote');
        }

        $res = QuoteService::createQuote([
            'name'                => $name,
            'email'               => $email,
            'phone'               => $phone,
            'origin_emirate'      => $origin,
            'destination_emirate' => $dest,
            'weight_kg'           => $weight,
            'service_id'          => $service,
            'notes'               => $request->input('notes', '')
        ]);

        Session::setFlash('success', "Quote generated! Reference: {$res['quote_number']}. Estimated Total: {$res['pricing']['total']} AED.");
        Response::redirect("/quote?ref={$res['quote_number']}");
    }

    public function showBook(Request $request): void
    {
        $services = Database::fetchAll("SELECT * FROM services WHERE active = 1");
        $emirates = ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah', 'Umm Al Quwain'];

        View::render('public.book', [
            'title'    => 'Book Shipment | RC Courier UAE',
            'services' => $services,
            'emirates' => $emirates
        ], null);
    }

    public function submitBook(Request $request): void
    {
        $user = Session::get('user');
        $customerId = $user['customer_id'] ?? null;

        if (!$customerId) {
            // Find or create guest customer record
            $email = trim($request->input('sender_email', 'guest@example.ae'));
            $existing = Database::fetchOne("SELECT id FROM customers WHERE email = ?", [$email]);
            if ($existing) {
                $customerId = $existing['id'];
            } else {
                Database::execute("INSERT INTO customers (customer_type, contact_name, email, phone, status) VALUES ('individual', ?, ?, ?, 'active')", [
                    $request->input('sender_name', 'Valued Customer'),
                    $email,
                    $request->input('sender_phone', '+971 50 000 0000')
                ]);
                $customerId = Database::lastInsertId();
            }
        }

        $shipment = ShipmentService::createShipment([
            'customer_id'         => $customerId,
            'service_id'          => (int)$request->input('service_id', 1),
            'origin_emirate'      => $request->input('origin_emirate', 'Dubai'),
            'destination_emirate' => $request->input('destination_emirate', 'Abu Dhabi'),
            'weight_kg'           => (float)$request->input('weight_kg', 1.0),
            'length_cm'           => (float)$request->input('length_cm', 10),
            'width_cm'            => (float)$request->input('width_cm', 10),
            'height_cm'           => (float)$request->input('height_cm', 10),
            'declared_value'      => (float)$request->input('declared_value', 0),
            'item_description'    => $request->input('item_description', 'Parcel Cargo Package'),
            'sender_address'      => [
                'label' => 'Sender',
                'line1' => $request->input('sender_address_line', 'Street 1'),
                'area'  => $request->input('sender_area', 'Downtown')
            ],
            'receiver_address'    => [
                'label' => 'Receiver',
                'line1' => $request->input('receiver_address_line', 'Street 2'),
                'area'  => $request->input('receiver_area', 'Corniche')
            ],
            'created_by'          => $user['id'] ?? null
        ]);

        Session::setFlash('success', "Shipment booked! Reference: {$shipment['reference_number']}. Tracking: {$shipment['tracking_number']}. Total: {$shipment['pricing']['total']} AED.");
        Response::redirect("/track?number={$shipment['tracking_number']}");
    }

    public function contact(Request $request): void
    {
        View::render('public.contact', ['title' => 'Contact Us | RC Courier UAE'], null);
    }

    public function submitContact(Request $request): void
    {
        $name    = trim($request->input('name', ''));
        $email   = trim($request->input('email', ''));
        $phone   = trim($request->input('phone', ''));
        $subject = trim($request->input('subject', 'General Inquiry'));
        $message = trim($request->input('message', ''));

        if (empty($name) || empty($email) || empty($message)) {
            Session::setFlash('error', 'Please fill in name, email, and message.');
            Response::redirect('/contact');
        }

        Database::execute("INSERT INTO contact_messages (name, email, phone, subject, message, status) VALUES (?, ?, ?, ?, ?, 'new')", [
            $name, $email, $phone, $subject, $message
        ]);

        Session::setFlash('success', 'Thank you for contacting us! Our logistics team will respond within 24 hours.');
        Response::redirect('/contact');
    }
}
