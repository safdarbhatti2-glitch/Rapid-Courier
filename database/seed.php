<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;
use App\Core\Database;

EnvLoader::load(BASE_PATH . '/.env');
Autoloader::register(BASE_PATH);

echo "Starting UAE Logistics Demo Data Seeding...\n";

try {
    Database::beginTransaction();

    // 1. Roles
    echo " -> Seeding Roles...\n";
    $rolesMap = [];
    $rolesList = ['super_admin', 'admin', 'operations_manager', 'dispatcher', 'finance', 'sales', 'customer'];
    foreach ($rolesList as $roleName) {
        $existing = Database::fetchOne("SELECT id FROM roles WHERE name = ?", [$roleName]);
        if ($existing) {
            $rolesMap[$roleName] = $existing['id'];
        } else {
            Database::execute("INSERT INTO roles (name) VALUES (?)", [$roleName]);
            $rolesMap[$roleName] = Database::lastInsertId();
        }
    }

    // 2. Demo Users
    echo " -> Seeding Users...\n";
    $usersData = [
        [
            'role' => 'admin',
            'name' => 'Sara Al-Maktoum (System Admin)',
            'email' => 'admin@antigravityexpress.ae',
            'phone' => '+971 4 800 2684',
            'pass' => 'Admin@123456'
        ],
        [
            'role' => 'finance',
            'name' => 'Tariq Mansoor (Finance Manager)',
            'email' => 'finance@antigravityexpress.ae',
            'phone' => '+971 4 800 2685',
            'pass' => 'Finance@123456'
        ],
        [
            'role' => 'operations_manager',
            'name' => 'Zayed Al-Hassan (Operations Hub)',
            'email' => 'ops@antigravityexpress.ae',
            'phone' => '+971 4 800 2686',
            'pass' => 'Ops@123456'
        ],
        [
            'role' => 'dispatcher',
            'name' => 'Khalid Driver (Fleet Courier)',
            'email' => 'driver@antigravityexpress.ae',
            'phone' => '+971 50 123 4567',
            'pass' => 'Driver@123456'
        ],
        [
            'role' => 'customer',
            'name' => 'Omar Al-Zaabi',
            'email' => 'demo.customer@example.ae',
            'phone' => '+971 55 987 6543',
            'pass' => 'Customer@123456'
        ],
    ];

    $usersMap = [];
    foreach ($usersData as $u) {
        $existing = Database::fetchOne("SELECT id FROM users WHERE email = ?", [$u['email']]);
        if ($existing) {
            $usersMap[$u['email']] = $existing['id'];
        } else {
            $hash = password_hash($u['pass'], PASSWORD_DEFAULT);
            Database::execute("INSERT INTO users (role_id, name, email, phone, password_hash, status) VALUES (?, ?, ?, ?, ?, 'active')", [
                $rolesMap[$u['role']], $u['name'], $u['email'], $u['phone'], $hash
            ]);
            $usersMap[$u['email']] = Database::lastInsertId();
        }
    }

    // 3. Customers & Customer Addresses
    echo " -> Seeding Customers & Addresses...\n";
    $cust = Database::fetchOne("SELECT id FROM customers WHERE email = ?", ['demo.customer@example.ae']);
    if (!$cust) {
        Database::execute("INSERT INTO customers (user_id, customer_type, company_name, contact_name, email, phone, trn, status) VALUES (?, 'corporate', 'Dubai Trading & Logistics FZE', 'Omar Al-Zaabi', 'demo.customer@example.ae', '+971 55 987 6543', '100987654321003', 'active')", [
            $usersMap['demo.customer@example.ae']
        ]);
        $customerId = Database::lastInsertId();
    } else {
        $customerId = $cust['id'];
    }

    // Customer Addresses
    $addr1 = Database::fetchOne("SELECT id FROM customer_addresses WHERE customer_id = ? AND label = 'Dubai HQ'", [$customerId]);
    if (!$addr1) {
        Database::execute("INSERT INTO customer_addresses (customer_id, label, address_line1, address_line2, area, emirate, city, country, is_default) VALUES (?, 'Dubai HQ', 'Business Bay Tower, Floor 14', 'Marasi Drive', 'Business Bay', 'Dubai', 'Dubai', 'United Arab Emirates', 1)", [$customerId]);
        $originAddrId = Database::lastInsertId();
    } else {
        $originAddrId = $addr1['id'];
    }

    $addr2 = Database::fetchOne("SELECT id FROM customer_addresses WHERE customer_id = ? AND label = 'Abu Dhabi Branch'", [$customerId]);
    if (!$addr2) {
        Database::execute("INSERT INTO customer_addresses (customer_id, label, address_line1, address_line2, area, emirate, city, country, is_default) VALUES (?, 'Abu Dhabi Branch', 'Corniche Plaza, Suite 402', 'Corniche Street', 'Al Khalidiya', 'Abu Dhabi', 'Abu Dhabi', 'United Arab Emirates', 0)", [$customerId]);
        $destAddrId = Database::lastInsertId();
    } else {
        $destAddrId = $addr2['id'];
    }

    // 4. Logistics Services
    echo " -> Seeding Logistics Services...\n";
    $servicesList = [
        ['code' => 'SAME_DAY', 'name' => 'Same-Day Express UAE', 'desc' => 'Guaranteed delivery within 6 hours across Dubai, Abu Dhabi, and Sharjah.', 'type' => 'express_same_day'],
        ['code' => 'NEXT_DAY', 'name' => 'Next-Day Delivery', 'desc' => 'Cost-effective next business day delivery to all 7 Emirates.', 'type' => 'express_next_day'],
        ['code' => 'GCC_OVERLAND', 'name' => 'GCC Overland Cargo', 'desc' => 'Door-to-door road freight across KSA, Oman, Kuwait, Bahrain, and Qatar.', 'type' => 'gcc_overland'],
        ['code' => 'INTL_AIR', 'name' => 'International Air Priority', 'desc' => 'Global express parcel dispatch to 220+ worldwide destinations.', 'type' => 'international_air'],
        ['code' => 'FREIGHT', 'name' => 'Heavy Freight & Logistics', 'desc' => 'Full truckload (FTL) and warehousing logistics solutions.', 'type' => 'freight'],
    ];

    $servicesMap = [];
    foreach ($servicesList as $s) {
        $existing = Database::fetchOne("SELECT id FROM services WHERE code = ?", [$s['code']]);
        if ($existing) {
            $servicesMap[$s['code']] = $existing['id'];
        } else {
            Database::execute("INSERT INTO services (code, name, description, service_type, active) VALUES (?, ?, ?, ?, 1)", [
                $s['code'], $s['name'], $s['desc'], $s['type']
            ]);
            $servicesMap[$s['code']] = Database::lastInsertId();
        }
    }

    // 5. Service Zones (7 Emirates)
    echo " -> Seeding Service Zones...\n";
    $emiratesList = [
        ['name' => 'Dubai Metropolitan Zone', 'emirate' => 'Dubai', 'code' => 'ZONE_DXB'],
        ['name' => 'Abu Dhabi Capital Zone', 'emirate' => 'Abu Dhabi', 'code' => 'ZONE_AUH'],
        ['name' => 'Sharjah Central Zone', 'emirate' => 'Sharjah', 'code' => 'ZONE_SHJ'],
        ['name' => 'Ajman Urban Zone', 'emirate' => 'Ajman', 'code' => 'ZONE_AJM'],
        ['name' => 'Ras Al Khaimah Northern Zone', 'emirate' => 'Ras Al Khaimah', 'code' => 'ZONE_RAK'],
        ['name' => 'Fujairah East Coast Zone', 'emirate' => 'Fujairah', 'code' => 'ZONE_FUJ'],
        ['name' => 'Umm Al Quwain Zone', 'emirate' => 'Umm Al Quwain', 'code' => 'ZONE_UAQ'],
    ];

    $zonesMap = [];
    foreach ($emiratesList as $z) {
        $existing = Database::fetchOne("SELECT id FROM service_zones WHERE zone_code = ?", [$z['code']]);
        if ($existing) {
            $zonesMap[$z['code']] = $existing['id'];
        } else {
            Database::execute("INSERT INTO service_zones (name, emirate, country, zone_code, active) VALUES (?, ?, 'United Arab Emirates', ?, 1)", [
                $z['name'], $z['emirate'], $z['code']
            ]);
            $zonesMap[$z['code']] = Database::lastInsertId();
        }
    }

    // 6. Pricing Rules Matrix
    echo " -> Seeding Pricing Rules...\n";
    foreach ($zonesMap as $zCode1 => $zId1) {
        foreach ($zonesMap as $zCode2 => $zId2) {
            $existing = Database::fetchOne("SELECT id FROM pricing_rules WHERE service_id = ? AND origin_zone_id = ? AND destination_zone_id = ?", [
                $servicesMap['SAME_DAY'], $zId1, $zId2
            ]);
            if (!$existing) {
                $base = ($zId1 === $zId2) ? 35.00 : 50.00;
                $perKg = ($zId1 === $zId2) ? 5.00 : 8.00;
                Database::execute("INSERT INTO pricing_rules (service_id, origin_zone_id, destination_zone_id, weight_from, weight_to, base_price, per_kg_price, pickup_fee, surcharge, tax_rate, active) VALUES (?, ?, ?, 0.00, 50.00, ?, ?, 10.00, 0.00, 5.00, 1)", [
                    $servicesMap['SAME_DAY'], $zId1, $zId2, $base, $perKg
                ]);
            }
        }
    }

    // 7. Hub Locations
    echo " -> Seeding Logistics Hub Locations...\n";
    $hubs = [
        ['name' => 'Dubai Logistics City Central Hub', 'code' => 'HUB_DXB_01', 'address' => 'Building C3, Dubai South Logistics District', 'emirate' => 'Dubai', 'phone' => '+971 4 800 2684', 'email' => 'hub.dxb@antigravityexpress.ae', 'is_hub' => 1],
        ['name' => 'Abu Dhabi Musaffah Logistics Center', 'code' => 'HUB_AUH_01', 'address' => 'Sector M-37, Industrial Area, Musaffah', 'emirate' => 'Abu Dhabi', 'phone' => '+971 2 600 5544', 'email' => 'hub.auh@antigravityexpress.ae', 'is_hub' => 1],
        ['name' => 'Sharjah SAIF Zone Distribution Hub', 'code' => 'HUB_SHJ_01', 'address' => 'Warehouse A8-12, SAIF Zone', 'emirate' => 'Sharjah', 'phone' => '+971 6 557 8822', 'email' => 'hub.shj@antigravityexpress.ae', 'is_hub' => 1],
    ];

    foreach ($hubs as $h) {
        $existing = Database::fetchOne("SELECT id FROM locations WHERE code = ?", [$h['code']]);
        if (!$existing) {
            Database::execute("INSERT INTO locations (name, code, address, emirate, phone, email, is_hub, active) VALUES (?, ?, ?, ?, ?, ?, ?, 1)", [
                $h['name'], $h['code'], $h['address'], $h['emirate'], $h['phone'], $h['email'], $h['is_hub']
            ]);
        }
    }

    // 8. Vehicles & Drivers
    echo " -> Seeding Vehicles & Drivers...\n";
    $vExist = Database::fetchOne("SELECT id FROM vehicles WHERE plate_number = ?", ['DXB-89412']);
    if (!$vExist) {
        Database::execute("INSERT INTO vehicles (plate_number, type, capacity_kg, status) VALUES ('DXB-89412', 'van', 1500.00, 'active')");
        $vehicleId = Database::lastInsertId();
    } else {
        $vehicleId = $vExist['id'];
    }

    $dExist = Database::fetchOne("SELECT id FROM drivers WHERE user_id = ?", [$usersMap['driver@antigravityexpress.ae']]);
    if (!$dExist) {
        Database::execute("INSERT INTO drivers (user_id, license_number, vehicle_id, status) VALUES (?, 'UAE-DL-9874125', ?, 'available')", [
            $usersMap['driver@antigravityexpress.ae'], $vehicleId
        ]);
        $driverId = Database::lastInsertId();
    } else {
        $driverId = $dExist['id'];
    }

    // 9. Sample Shipments & Tracking Timeline
    echo " -> Seeding Sample Shipments & Status Events...\n";
    $shp1 = Database::fetchOne("SELECT id FROM shipments WHERE reference_number = ?", ['SHP-2026-000001']);
    if (!$shp1) {
        $now = date('Y-m-d H:i:s');
        $estDelivery = date('Y-m-d H:i:s', strtotime('+1 day'));
        Database::execute("INSERT INTO shipments (reference_number, tracking_number, customer_id, service_id, origin_address_id, destination_address_id, status, weight_kg, length_cm, width_cm, height_cm, declared_value, subtotal, discount, tax, total, currency, pickup_at, estimated_delivery_at) VALUES ('SHP-2026-000001', 'RC98412503', ?, ?, ?, ?, 'IN_TRANSIT', 3.50, 30.00, 20.00, 15.00, 500.00, 60.00, 0.00, 3.00, 63.00, 'AED', ?, ?)", [
            $customerId, $servicesMap['SAME_DAY'], $originAddrId, $destAddrId, $now, $estDelivery
        ]);
        $shipmentId = Database::lastInsertId();

        // Items
        Database::execute("INSERT INTO shipment_items (shipment_id, description, quantity, weight_kg, declared_value) VALUES (?, 'Corporate Documents & Sample Components', 1, 3.50, 500.00)", [$shipmentId]);

        // Status Events Timeline
        $events = [
            ['status' => 'BOOKED', 'loc' => 'Business Bay, Dubai', 'pub' => 'Shipment booking confirmed by customer.', 'int' => 'Created via web portal.'],
            ['status' => 'PICKUP_ASSIGNED', 'loc' => 'Dubai South Hub', 'pub' => 'Courier assigned for pickup.', 'int' => 'Assigned to driver Khalid.'],
            ['status' => 'PICKED_UP', 'loc' => 'Business Bay, Dubai', 'pub' => 'Shipment collected from sender.', 'int' => 'Verified weight 3.5kg.'],
            ['status' => 'AT_ORIGIN_HUB', 'loc' => 'Dubai Logistics City Central Hub', 'pub' => 'Arrived at Dubai processing hub.', 'int' => 'Sorted into Bay 4.'],
            ['status' => 'IN_TRANSIT', 'loc' => 'Sheikh Zayed Road (E11)', 'pub' => 'In transit to Abu Dhabi distribution center.', 'int' => 'Dispatched via Truck #89412.'],
        ];

        foreach ($events as $ev) {
            Database::execute("INSERT INTO shipment_status_events (shipment_id, status, location_name, public_notes, internal_notes, created_by) VALUES (?, ?, ?, ?, ?, ?)", [
                $shipmentId, $ev['status'], $ev['loc'], $ev['pub'], $ev['int'], $usersMap['ops@antigravityexpress.ae']
            ]);
        }
    }

    // 10. Sample Quotes
    echo " -> Seeding Sample Quotes...\n";
    $qExist = Database::fetchOne("SELECT id FROM quotes WHERE quote_number = ?", ['QT-2026-000001']);
    if (!$qExist) {
        $validUntil = date('Y-m-d', strtotime('+14 days'));
        Database::execute("INSERT INTO quotes (quote_number, customer_id, contact_name, contact_email, contact_phone, status, valid_until, subtotal, discount, tax, total, currency, notes) VALUES ('QT-2026-000001', ?, 'Omar Al-Zaabi', 'demo.customer@example.ae', '+971 55 987 6543', 'ACCEPTED', ?, 500.00, 25.00, 23.75, 498.75, 'AED', 'Monthly GCC Overland Logistics Freight Contract')", [
            $customerId, $validUntil
        ]);
        $quoteId = Database::lastInsertId();

        Database::execute("INSERT INTO quote_items (quote_id, description, quantity, unit_price, discount, tax_rate, line_total) VALUES (?, 'Monthly Overland Pallet Shipping (Dubai to Riyadh)', 2, 250.00, 25.00, 5.00, 498.75)", [$quoteId]);
    }

    // 11. Sample Invoices & Payments
    echo " -> Seeding Sample Invoices & Payments...\n";
    $invExist = Database::fetchOne("SELECT id FROM invoices WHERE invoice_number = ?", ['INV-2026-000001']);
    if (!$invExist) {
        $today = date('Y-m-d');
        $dueDate = date('Y-m-d', strtotime('+30 days'));
        $now = date('Y-m-d H:i:s');
        Database::execute("INSERT INTO invoices (invoice_number, customer_id, status, issue_date, due_date, currency, subtotal, discount, tax, total, amount_paid, balance_due, trn, notes, issued_at) VALUES ('INV-2026-000001', ?, 'PAID', ?, ?, 'AED', 60.00, 0.00, 3.00, 63.00, 63.00, 0.00, '100987654321003', 'Thank you for choosing RC Courier UAE.', ?)", [
            $customerId, $today, $dueDate, $now
        ]);
        $invoiceId = Database::lastInsertId();

        Database::execute("INSERT INTO invoice_items (invoice_id, description, reference, quantity, unit_price, discount, tax_rate, line_subtotal, line_tax, line_total) VALUES (?, 'Same-Day Courier Freight Delivery (SHP-2026-000001)', 'SHP-2026-000001', 1, 60.00, 0.00, 5.00, 60.00, 3.00, 63.00)", [$invoiceId]);
        Database::execute("INSERT INTO invoice_taxes (invoice_id, tax_name, rate, amount) VALUES (?, 'UAE Standard VAT', 5.00, 3.00)", [$invoiceId]);

        Database::execute("INSERT INTO payments (payment_number, invoice_id, customer_id, amount, currency, method, reference, status, paid_at) VALUES ('PAY-2026-000001', ?, ?, 63.00, 'AED', 'credit_card', '**** **** **** 9874', 'completed', ?)", [
            $invoiceId, $customerId, $now
        ]);
    }

    // 12. Settings
    echo " -> Seeding System Settings...\n";
    $settingsList = [
        ['company_name', 'RC Courier UAE LLC', 'general'],
        ['company_email', 'support@rapid-courier.com', 'general'],
        ['company_phone', '+971 4 800 2684', 'general'],
        ['company_trn', '100987654321003', 'finance'],
        ['default_currency', 'AED', 'finance'],
        ['tax_rate', '5.00', 'finance'],
        ['tracking_refresh_seconds', '15', 'tracking'],
    ];

    foreach ($settingsList as $st) {
        $existing = Database::fetchOne("SELECT id FROM settings WHERE setting_key = ?", [$st[0]]);
        if (!$existing) {
            Database::execute("INSERT INTO settings (setting_key, setting_value, `group`) VALUES (?, ?, ?)", [$st[0], $st[1], $st[2]]);
        }
    }

    Database::commit();
    echo "\nUAE Logistics Demo Seed Complete!\n";

} catch (Exception $e) {
    Database::rollBack();
    echo "\nSeeding Error: " . $e->getMessage() . "\n";
    exit(1);
}
