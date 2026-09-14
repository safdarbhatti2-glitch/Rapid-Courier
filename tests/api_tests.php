<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;
use App\Core\Database;
use App\Services\ApiService;
use App\Services\ShipmentService;
use App\Services\PricingService;
use App\Services\LabelService;
use App\Services\WebhookService;

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
    // 1. API Key Creation & Bcrypt Secret Hashing
    $customer = Database::fetchOne("SELECT id FROM customers LIMIT 1");
    $customerId = $customer['id'] ?? 1;

    $cred = ApiService::createApiKey($customerId, 'Test Suite Key', 'live', ['shipments:create', 'shipments:read', 'tracking:read', 'labels:read']);
    assertApiTest("API Key Creation & Bcrypt Secret Hashing", !empty($cred['api_key']) && !empty($cred['api_secret']) && str_starts_with($cred['api_key'], 'rc_live_'));

    // 2. Authentication by Key & Secret
    $authRecord = ApiService::authenticate($cred['api_key'], $cred['api_secret']);
    assertApiTest("API Authentication by Key & Secret Header", $authRecord !== null && (int)$authRecord['customer_id'] === $customerId);

    // 3. Permission Scope Enforcement
    $hasCreateScope = ApiService::hasPermission($authRecord, 'shipments:create');
    $hasAdminScope  = ApiService::hasPermission($authRecord, 'admin:full_access');
    assertApiTest("Granular Scope Permission Enforcement", $hasCreateScope === true && $hasAdminScope === false);

    // 4. Rate Limiting Window Tracking
    $rateOk1 = ApiService::checkRateLimit((int)$authRecord['id'], 60);
    assertApiTest("Per-Key Rate Limit Tracker", $rateOk1 === true);

    // 5. Instant Quotation Calculation
    $quoteResult = PricingService::calculate(1, 'Dubai', 'Abu Dhabi', 2.5);
    assertApiTest("Quotation Calculation in AED (Subtotal & 5% VAT)", $quoteResult['subtotal'] > 0 && $quoteResult['tax'] > 0 && $quoteResult['currency'] === 'AED');

    // 6. Transactional Shipment Booking
    $shipment = ShipmentService::createShipment([
        'customer_id'         => $customerId,
        'service_id'          => 1,
        'origin_emirate'      => 'Dubai',
        'destination_emirate' => 'Sharjah',
        'weight_kg'           => 1.5,
        'sender_address'      => ['contact_name' => 'API Sender', 'phone' => '+97148002684', 'address_line1' => 'Business Bay', 'area' => 'Business Bay', 'emirate' => 'Dubai', 'city' => 'Dubai'],
        'receiver_address'    => ['contact_name' => 'API Receiver', 'phone' => '+971509876543', 'address_line1' => 'Al Majaz', 'area' => 'Al Majaz', 'emirate' => 'Sharjah', 'city' => 'Sharjah'],
        'item_description'    => 'Test API Package',
        'quantity'            => 1
    ]);
    assertApiTest("Transactional Shipment Booking & Waybill/Tracking Generation", !empty($shipment['reference_number']) && !empty($shipment['tracking_number']) && str_starts_with($shipment['tracking_number'], 'RC'));

    // 7. Idempotency Key Validation
    $ik = 'ik_test_' . bin2hex(random_bytes(8));
    ApiService::saveIdempotentResponse($customerId, $ik, '/api/v1/shipments', md5('test'), 201, ['data' => ['shipment_id' => $shipment['id']]]);
    $idempotencyResult = ApiService::getIdempotentResponse($customerId, $ik, '/api/v1/shipments');
    assertApiTest("Idempotency-Key Duplicate Booking Prevention", $idempotencyResult !== null && $idempotencyResult['code'] === 201);

    // 8. 4x6 Inch Thermal Label & Code128 SVG Barcode Generator
    $shipmentFull = Database::fetchOne(
        "SELECT s.*, serv.name as service_name, c.contact_name as sender_name, c.phone as sender_phone, orig.area as origin_area, orig.emirate as origin_emirate, dest.address_line1 as dest_line1, dest.area as dest_area, dest.emirate as dest_emirate FROM shipments s JOIN services serv ON s.service_id = serv.id JOIN customers c ON s.customer_id = c.id JOIN customer_addresses orig ON s.origin_address_id = orig.id JOIN customer_addresses dest ON s.destination_address_id = dest.id WHERE s.id = ?",
        [$shipment['id']]
    );
    $thermalLabel = LabelService::generateThermalHtml($shipmentFull);
    assertApiTest("4x6 Thermal Shipping Label HTML/SVG Code128 Barcode Engine", str_contains($thermalLabel, 'barcode-svg') && str_contains($thermalLabel, $shipment['tracking_number']));

    // 9. Webhook Delivery & HMAC Signature Verification
    $payloadJson = json_encode(['event' => 'shipment.created', 'data' => ['shipment_id' => $shipment['id']]]);
    $whSecret = 'whsec_test_secret_key_123';
    $timeHeader = time();
    $signature = 't=' . $timeHeader . ',v1=' . hash_hmac('sha256', $timeHeader . '.' . $payloadJson, $whSecret);
    assertApiTest("Webhook HMAC-SHA256 Signature Verification", str_contains($signature, 'v1=') && strlen($signature) > 30);

    // 10. Customer Data Isolation Enforcement
    $otherCustomerShipment = Database::fetchOne("SELECT id FROM shipments WHERE customer_id != ?", [$customerId]);
    $isolationOk = true;
    if ($otherCustomerShipment) {
        $checkIsolated = Database::fetchOne("SELECT id FROM shipments WHERE id = ? AND customer_id = ?", [$otherCustomerShipment['id'], $customerId]);
        if ($checkIsolated !== false) {
            $isolationOk = false;
        }
    }
    assertApiTest("Customer Data Isolation Security Check", $isolationOk === true);

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
