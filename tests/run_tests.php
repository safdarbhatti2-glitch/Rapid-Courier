<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;
use App\Core\Database;
use App\Services\PricingService;
use App\Services\ShipmentService;
use App\Services\QuoteService;
use App\Services\InvoiceService;

EnvLoader::load(BASE_PATH . '/.env');
Autoloader::register(BASE_PATH);

echo "==================================================\n";
echo "  ANTIGRAVITY EXPRESS UAE — INTEGRATION TEST SUITE\n";
echo "==================================================\n\n";

$passed = 0;
$failed = 0;

function assertTest(string $name, bool $condition, string $failureDetails = ''): void {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] {$name}\n";
        $passed++;
    } else {
        echo " [FAIL] {$name} - {$failureDetails}\n";
        $failed++;
    }
}

try {
    // 1. Database Connection & Table Audit
    $driver = Database::getDriverName();
    if ($driver === 'sqlite') {
        $tablesCount = Database::fetchOne("SELECT COUNT(*) as cnt FROM sqlite_master WHERE type='table'")['cnt'] ?? 0;
    } else {
        $tablesCount = Database::fetchOne("SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema = ?", [EnvLoader::get('DB_NAME')])['cnt'] ?? 0;
    }
    assertTest("Database 27 Tables Migration Audit", $tablesCount >= 27, "Expected >=27 tables, found {$tablesCount}");

    // 2. Pricing Engine Unit Tests
    $priceRes = PricingService::calculate(1, 'Dubai', 'Abu Dhabi', 2.0, 30, 20, 15);
    assertTest("Pricing Calculation (Subtotal & VAT)", $priceRes['subtotal'] > 0 && $priceRes['tax'] > 0 && $priceRes['total'] == ($priceRes['subtotal'] + $priceRes['tax']));
    assertTest("Volumetric Weight Computation", $priceRes['volumetric_weight'] == 1.8);

    // 3. User Hashing & Auth Verification
    $admin = Database::fetchOne("SELECT * FROM users WHERE email = ?", ['admin@antigravityexpress.ae']);
    assertTest("Admin User Seeding Check", !empty($admin));
    assertTest("Secure Password Hash Verification", password_verify('Admin@123456', $admin['password_hash']));

    // 4. Shipment Booking Lifecycle Test
    $shipment = ShipmentService::createShipment([
        'customer_id'         => 1,
        'service_id'          => 1,
        'origin_emirate'      => 'Dubai',
        'destination_emirate' => 'Sharjah',
        'weight_kg'           => 1.5,
        'item_description'    => 'Test Integration Package',
        'sender_address'      => ['label' => 'Test Sender', 'line1' => 'Street 1', 'area' => 'Business Bay'],
        'receiver_address'    => ['label' => 'Test Receiver', 'line1' => 'Street 2', 'area' => 'Industrial Area']
    ]);

    assertTest("Shipment Creation & Reference Number (SHP-YYYY-XXXXXX)", str_starts_with($shipment['reference_number'], 'SHP-2026-'));
    assertTest("Tracking Number Format (RC + 8 Random Digits)", (bool)preg_match('/^RC\d{8}$/', $shipment['tracking_number']));

    // 5. Tracking API Lookup Test
    $trackingInfo = ShipmentService::getTrackingInfo($shipment['tracking_number']);
    assertTest("Public Tracking Lookup API", !empty($trackingInfo) && $trackingInfo['status'] === 'BOOKED' && count($trackingInfo['timeline']) >= 1);

    // 6. Quotation Engine Test
    $quote = QuoteService::createQuote([
        'name'                => 'Integration Tester',
        'email'               => 'test@example.ae',
        'phone'               => '+971 50 111 2222',
        'origin_emirate'      => 'Dubai',
        'destination_emirate' => 'Fujairah',
        'weight_kg'           => 4.0
    ]);
    assertTest("Quotation Generation (QT-YYYY-XXXXXX)", str_starts_with($quote['quote_number'], 'QT-2026-'));

    // 7. Invoice Engine & Payment Immutability Test
    $invoice = InvoiceService::createInvoice([
        'customer_id' => 1,
        'items'       => [
            ['description' => 'Test Express Courier Delivery', 'quantity' => 1, 'unit_price' => 100.00, 'discount' => 0.00, 'tax_rate' => 5.00]
        ]
    ]);
    assertTest("Invoice Generation (INV-YYYY-XXXXXX)", str_starts_with($invoice['invoice_number'], 'INV-2026-') && $invoice['total'] == 105.00);

    $payRes = InvoiceService::recordPayment($invoice['id'], 105.00, 'credit_card', 'TXN-TEST-100', 1);
    assertTest("Payment Recording & Full Balance Clearance", $payRes['status'] === 'PAID' && $payRes['balance_due'] == 0.00);

    echo "\n==================================================\n";
    echo " TEST SUMMARY: Passed {$passed} | Failed {$failed}\n";
    echo "==================================================\n";

    if ($failed > 0) {
        exit(1);
    }

} catch (Exception $e) {
    echo "\n[ERROR] Test Execution Failed: " . $e->getMessage() . "\n";
    exit(1);
}
