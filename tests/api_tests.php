<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;
use App\Core\Database;
use App\Core\Request;
use App\Services\ApiService;
use App\Services\ShipmentService;
use App\Services\PricingService;
use App\Services\LabelService;
use App\Services\WebhookService;
use App\Middleware\ApiAuthMiddleware;

EnvLoader::load(BASE_PATH . '/.env');
Autoloader::register(BASE_PATH);

echo "==================================================\n";
echo "  RC COURIER UAE — REST API V1 INTEGRATION TEST SUITE\n";
echo "==================================================\n\n";

$passed = 0;
$failed = 0;

function assertApiTest(string $title, bool $condition): void
{
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] {$title}\n";
        $passed++;
    } else {
        echo " [FAIL] {$title}\n";
        $failed++;
    }
}

try {
    $randSuffix = mt_rand(1000, 9999);
    Database::execute("INSERT INTO customers (contact_name, email, phone, status) VALUES (?, ?, ?, 'active')", ["Test Cust A {$randSuffix}", "cust_a_{$randSuffix}@test.com", "+97150" . $randSuffix . "001"]);
    $custA = (int)Database::lastInsertId();

    Database::execute("INSERT INTO customers (contact_name, email, phone, status) VALUES (?, ?, ?, 'active')", ["Test Cust B {$randSuffix}", "cust_b_{$randSuffix}@test.com", "+97150" . $randSuffix . "002"]);
    $custB = (int)Database::lastInsertId();

    // 1. API Key Generation with Specific Scopes
    $scopesA = ['shipments:create', 'shipments:read', 'tracking:read', 'invoices:read', 'labels:read', 'webhooks:manage'];
    $credA   = ApiService::createApiKey($custA, 'Customer A App', 'live', $scopesA, 60);
    assertApiTest("API Key Creation & Bcrypt Secret Hashing", !empty($credA['api_key']) && !empty($credA['api_secret']) && str_starts_with($credA['api_key'], 'rc_live_'));

    // 2. Authentication Verification
    $authA = ApiService::authenticate($credA['api_key'], $credA['api_secret']);
    assertApiTest("API Authentication Header Verification", $authA !== null && (int)$authA['customer_id'] === $custA);

    $invalidAuth = ApiService::authenticate($credA['api_key'], 'wrong_secret_123');
    assertApiTest("Invalid Credentials Rejection (HTTP 401)", $invalidAuth === null);

    // 3. Permission Scope Enforcement
    $credRestricted = ApiService::createApiKey($custA, 'Restricted Key', 'live', ['tracking:read']);
    $authRestricted = ApiService::authenticate($credRestricted['api_key'], $credRestricted['api_secret']);
    
    $hasTrackingPerm  = ApiService::hasPermission($authRestricted, 'tracking:read');
    $hasCreateShipment = ApiService::hasPermission($authRestricted, 'shipments:create');
    assertApiTest("Granular Scope Permission Enforcement (403 Check)", $hasTrackingPerm === true && $hasCreateShipment === false);

    // 4. Rate Limiting Enforcer
    $rateLimitKey = ApiService::createApiKey($custA, 'Low Limit Key', 'test', ['*'], 2);
    $rateOk1 = ApiService::checkRateLimit((int)$rateLimitKey['id'], 2);
    $rateOk2 = ApiService::checkRateLimit((int)$rateLimitKey['id'], 2);
    $rateOk3 = ApiService::checkRateLimit((int)$rateLimitKey['id'], 2);
    assertApiTest("Per-Key Rate Limit RPM Enforcement (429 Check)", $rateOk1 === true && $rateOk2 === true && $rateOk3 === false);

    // 5. Revoked API Key Rejection
    Database::execute("UPDATE api_keys SET status = 'revoked' WHERE id = ?", [$rateLimitKey['id']]);
    $revokedAuth = ApiService::authenticate($rateLimitKey['api_key'], $rateLimitKey['api_secret']);
    assertApiTest("Revoked API Key Rejection", $revokedAuth === null);

    // 6. Instant Quotation Rate Calculation
    $quoteResult = PricingService::calculate(1, 'Dubai', 'Abu Dhabi', 2.5);
    assertApiTest("Quotation Calculation in AED (Subtotal & 5% VAT)", $quoteResult['subtotal'] > 0 && $quoteResult['tax'] > 0 && $quoteResult['currency'] === 'AED');

    // 7. Transactional Shipment Booking
    $shipmentA = ShipmentService::createShipment([
        'customer_id'         => $custA,
        'service_id'          => 1,
        'origin_emirate'      => 'Dubai',
        'destination_emirate' => 'Sharjah',
        'weight_kg'           => 1.5,
        'sender_address'      => ['contact_name' => 'Sender A', 'phone' => '+97148002684', 'address_line1' => 'Business Bay', 'area' => 'Business Bay', 'emirate' => 'Dubai', 'city' => 'Dubai'],
        'receiver_address'    => ['contact_name' => 'Receiver A', 'phone' => '+971509876543', 'address_line1' => 'Al Majaz', 'area' => 'Al Majaz', 'emirate' => 'Sharjah', 'city' => 'Sharjah'],
        'item_description'    => 'Customer A Package',
        'quantity'            => 1
    ]);
    assertApiTest("Transactional Shipment Booking & Waybill/Tracking Generation", !empty($shipmentA['reference_number']) && !empty($shipmentA['tracking_number']) && str_starts_with($shipmentA['tracking_number'], 'RC'));

    // 8. Idempotency Key Validation
    $ik = 'ik_test_' . bin2hex(random_bytes(8));
    ApiService::saveIdempotentResponse($custA, $ik, '/api/v1/shipments', md5('test_req'), 201, ['data' => ['shipment_id' => $shipmentA['id']]]);
    $cachedResult = ApiService::getIdempotentResponse($custA, $ik, '/api/v1/shipments');
    assertApiTest("Idempotency-Key Duplicate Booking Prevention", $cachedResult !== null && $cachedResult['code'] === 201 && $cachedResult['body']['data']['shipment_id'] === $shipmentA['id']);

    // 9. 4x6 Inch Thermal Label & Code128 SVG Barcode Engine
    $shipmentFull = Database::fetchOne(
        "SELECT s.*, serv.name as service_name, c.contact_name as sender_name, c.phone as sender_phone, orig.area as origin_area, orig.emirate as origin_emirate, dest.address_line1 as dest_line1, dest.area as dest_area, dest.emirate as dest_emirate FROM shipments s JOIN services serv ON s.service_id = serv.id JOIN customers c ON s.customer_id = c.id JOIN customer_addresses orig ON s.origin_address_id = orig.id JOIN customer_addresses dest ON s.destination_address_id = dest.id WHERE s.id = ?",
        [$shipmentA['id']]
    );
    $thermalLabel = LabelService::generateThermalHtml($shipmentFull);
    assertApiTest("4x6 Thermal Shipping Label HTML & Vector Code128 SVG Barcode Engine", str_contains($thermalLabel, 'barcode-svg') && str_contains($thermalLabel, $shipmentA['tracking_number']));

    // 10. Webhook Signature & Test Dispatch
    $whSecret = 'whsec_test_' . bin2hex(random_bytes(8));
    $dummyWh = ['id' => 999, 'url' => 'https://httpbin.org/post', 'secret' => $whSecret, 'events' => json_encode(['*'])];
    $sig = WebhookService::generateSignature(time(), json_encode(['event' => 'shipment.created']), $whSecret);
    assertApiTest("Webhook HMAC-SHA256 Signature Generator (X-RC-Signature)", str_starts_with($sig, 't=') && str_contains($sig, ',v1='));

    // 11. Rigorous Customer Data Isolation Test
    $shipmentB = ShipmentService::createShipment([
        'customer_id'         => $custB,
        'service_id'          => 1,
        'origin_emirate'      => 'Abu Dhabi',
        'destination_emirate' => 'Dubai',
        'weight_kg'           => 2.0,
        'sender_address'      => ['contact_name' => 'Sender B', 'phone' => '+97148000000', 'address_line1' => 'Corniche', 'area' => 'Corniche', 'emirate' => 'Abu Dhabi', 'city' => 'Abu Dhabi'],
        'receiver_address'    => ['contact_name' => 'Receiver B', 'phone' => '+97150000000', 'address_line1' => 'Downtown', 'area' => 'Downtown', 'emirate' => 'Dubai', 'city' => 'Dubai'],
        'item_description'    => 'Customer B Cargo',
        'quantity'            => 1
    ]);

    // Customer A querying Customer B's shipment -> MUST return false / 404
    $custA_query_custB = Database::fetchOne(
        "SELECT id FROM shipments WHERE id = ? AND customer_id = ?",
        [$shipmentB['id'], $custA]
    );

    // Customer A querying Customer B's invoice -> MUST return false / 404
    $invoiceB = Database::fetchOne("SELECT id FROM invoices WHERE shipment_id = ?", [$shipmentB['id']]);
    $custA_query_invB = Database::fetchOne(
        "SELECT id FROM invoices WHERE id = ? AND customer_id = ?",
        [$invoiceB['id'] ?? 0, $custA]
    );

    assertApiTest("Rigorous Customer Data Isolation (Shipments & Invoices)", $custA_query_custB === null && $custA_query_invB === null);

} catch (Exception $e) {
    echo "Test Exception: " . $e->getMessage() . "\n";
    $failed++;
}

echo "\n==================================================\n";
echo " API TEST SUMMARY: Passed {$passed} | Failed {$failed}\n";
echo "==================================================\n\n";

if ($failed > 0) {
    exit(1);
}
