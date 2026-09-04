<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;

EnvLoader::load(BASE_PATH . '/.env');
Autoloader::register(BASE_PATH);

$host = EnvLoader::get('DB_HOST', '127.0.0.1');
$port = EnvLoader::get('DB_PORT', '3306');
$dbName = EnvLoader::get('DB_NAME', 'rc_courier');
$user = EnvLoader::get('DB_USER', 'root');
$pass = EnvLoader::get('DB_PASS', '');

try {
    $pdo = \App\Core\Database::getConnection();
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    echo "Connected using {$driver} database engine...\n";

    echo "Executing schema migration...\n";

    if ($driver === 'mysql') {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    }

    $tables = [
        "roles" => "CREATE TABLE IF NOT EXISTS `roles` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(50) NOT NULL UNIQUE,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "permissions" => "CREATE TABLE IF NOT EXISTS `permissions` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL UNIQUE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "role_permissions" => "CREATE TABLE IF NOT EXISTS `role_permissions` (
            `role_id` BIGINT UNSIGNED NOT NULL,
            `permission_id` BIGINT UNSIGNED NOT NULL,
            PRIMARY KEY (`role_id`, `permission_id`),
            FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
            FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "users" => "CREATE TABLE IF NOT EXISTS `users` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `role_id` BIGINT UNSIGNED NOT NULL,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL UNIQUE,
            `phone` VARCHAR(30) NULL,
            `password_hash` VARCHAR(255) NOT NULL,
            `status` ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
            `last_login_at` DATETIME NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
            INDEX `idx_users_email` (`email`),
            INDEX `idx_users_role` (`role_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "password_resets" => "CREATE TABLE IF NOT EXISTS `password_resets` (
            `email` VARCHAR(150) NOT NULL,
            `token` VARCHAR(255) NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_password_resets_email` (`email`),
            INDEX `idx_password_resets_token` (`token`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "customers" => "CREATE TABLE IF NOT EXISTS `customers` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` BIGINT UNSIGNED NULL,
            `customer_type` ENUM('individual', 'corporate') NOT NULL DEFAULT 'individual',
            `company_name` VARCHAR(150) NULL,
            `contact_name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `phone` VARCHAR(30) NOT NULL,
            `trn` VARCHAR(50) NULL,
            `billing_address_id` BIGINT UNSIGNED NULL,
            `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
            INDEX `idx_customers_email` (`email`),
            INDEX `idx_customers_phone` (`phone`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "customer_addresses" => "CREATE TABLE IF NOT EXISTS `customer_addresses` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `customer_id` BIGINT UNSIGNED NOT NULL,
            `label` VARCHAR(50) NOT NULL DEFAULT 'Home',
            `address_line1` VARCHAR(255) NOT NULL,
            `address_line2` VARCHAR(255) NULL,
            `area` VARCHAR(100) NOT NULL,
            `emirate` VARCHAR(50) NOT NULL,
            `city` VARCHAR(50) NOT NULL DEFAULT 'Dubai',
            `country` VARCHAR(50) NOT NULL DEFAULT 'United Arab Emirates',
            `latitude` DECIMAL(10,8) NULL,
            `longitude` DECIMAL(11,8) NULL,
            `is_default` TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "services" => "CREATE TABLE IF NOT EXISTS `services` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `code` VARCHAR(50) NOT NULL UNIQUE,
            `name` VARCHAR(100) NOT NULL,
            `description` TEXT NULL,
            `service_type` ENUM('express_same_day', 'express_next_day', 'gcc_overland', 'international_air', 'freight') NOT NULL,
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "service_zones" => "CREATE TABLE IF NOT EXISTS `service_zones` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `emirate` VARCHAR(50) NOT NULL,
            `country` VARCHAR(50) NOT NULL DEFAULT 'United Arab Emirates',
            `zone_code` VARCHAR(20) NOT NULL UNIQUE,
            `active` TINYINT(1) NOT NULL DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "pricing_rules" => "CREATE TABLE IF NOT EXISTS `pricing_rules` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `service_id` BIGINT UNSIGNED NOT NULL,
            `origin_zone_id` BIGINT UNSIGNED NOT NULL,
            `destination_zone_id` BIGINT UNSIGNED NOT NULL,
            `weight_from` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            `weight_to` DECIMAL(10,2) NOT NULL DEFAULT 9999.00,
            `base_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `per_kg_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `pickup_fee` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `surcharge` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `tax_rate` DECIMAL(5,2) NOT NULL DEFAULT 5.00,
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
            FOREIGN KEY (`origin_zone_id`) REFERENCES `service_zones` (`id`),
            FOREIGN KEY (`destination_zone_id`) REFERENCES `service_zones` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "pricing_rule_versions" => "CREATE TABLE IF NOT EXISTS `pricing_rule_versions` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `pricing_rule_id` BIGINT UNSIGNED NOT NULL,
            `version` INT UNSIGNED NOT NULL DEFAULT 1,
            `effective_from` DATETIME NOT NULL,
            `effective_to` DATETIME NULL,
            `changes_json` JSON NULL,
            `created_by` BIGINT UNSIGNED NULL,
            FOREIGN KEY (`pricing_rule_id`) REFERENCES `pricing_rules` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "locations" => "CREATE TABLE IF NOT EXISTS `locations` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `code` VARCHAR(30) NOT NULL UNIQUE,
            `address` TEXT NOT NULL,
            `emirate` VARCHAR(50) NOT NULL,
            `phone` VARCHAR(30) NULL,
            `email` VARCHAR(100) NULL,
            `is_hub` TINYINT(1) NOT NULL DEFAULT 0,
            `active` TINYINT(1) NOT NULL DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "vehicles" => "CREATE TABLE IF NOT EXISTS `vehicles` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `plate_number` VARCHAR(30) NOT NULL UNIQUE,
            `type` ENUM('van', 'motorcycle', 'truck') NOT NULL DEFAULT 'van',
            `capacity_kg` DECIMAL(10,2) NOT NULL DEFAULT 1000.00,
            `status` ENUM('active', 'maintenance', 'inactive') NOT NULL DEFAULT 'active'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "drivers" => "CREATE TABLE IF NOT EXISTS `drivers` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` BIGINT UNSIGNED NOT NULL UNIQUE,
            `license_number` VARCHAR(50) NOT NULL,
            `vehicle_id` BIGINT UNSIGNED NULL,
            `status` ENUM('available', 'on_delivery', 'off_duty') NOT NULL DEFAULT 'available',
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
            FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "shipments" => "CREATE TABLE IF NOT EXISTS `shipments` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `reference_number` VARCHAR(50) NOT NULL UNIQUE,
            `tracking_number` VARCHAR(50) NOT NULL UNIQUE,
            `customer_id` BIGINT UNSIGNED NOT NULL,
            `service_id` BIGINT UNSIGNED NOT NULL,
            `origin_address_id` BIGINT UNSIGNED NOT NULL,
            `destination_address_id` BIGINT UNSIGNED NOT NULL,
            `status` ENUM('BOOKED', 'CONFIRMED', 'PICKUP_ASSIGNED', 'PICKED_UP', 'AT_ORIGIN_HUB', 'IN_TRANSIT', 'AT_DESTINATION_HUB', 'OUT_FOR_DELIVERY', 'DELIVERY_ATTEMPTED', 'DELIVERED', 'CANCELLED', 'ON_HOLD', 'RETURNED') NOT NULL DEFAULT 'BOOKED',
            `weight_kg` DECIMAL(10,2) NOT NULL DEFAULT 1.00,
            `length_cm` DECIMAL(10,2) NOT NULL DEFAULT 10.00,
            `width_cm` DECIMAL(10,2) NOT NULL DEFAULT 10.00,
            `height_cm` DECIMAL(10,2) NOT NULL DEFAULT 10.00,
            `declared_value` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `discount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `tax` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `currency` VARCHAR(10) NOT NULL DEFAULT 'AED',
            `pickup_at` DATETIME NULL,
            `estimated_delivery_at` DATETIME NULL,
            `delivered_at` DATETIME NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
            FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
            FOREIGN KEY (`origin_address_id`) REFERENCES `customer_addresses` (`id`),
            FOREIGN KEY (`destination_address_id`) REFERENCES `customer_addresses` (`id`),
            INDEX `idx_shipments_reference` (`reference_number`),
            INDEX `idx_shipments_tracking` (`tracking_number`),
            INDEX `idx_shipments_customer` (`customer_id`),
            INDEX `idx_shipments_status` (`status`),
            INDEX `idx_shipments_created` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "shipment_items" => "CREATE TABLE IF NOT EXISTS `shipment_items` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `shipment_id` BIGINT UNSIGNED NOT NULL,
            `description` VARCHAR(255) NOT NULL,
            `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
            `weight_kg` DECIMAL(10,2) NOT NULL DEFAULT 1.00,
            `length_cm` DECIMAL(10,2) NULL,
            `width_cm` DECIMAL(10,2) NULL,
            `height_cm` DECIMAL(10,2) NULL,
            `declared_value` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "shipment_status_events" => "CREATE TABLE IF NOT EXISTS `shipment_status_events` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `shipment_id` BIGINT UNSIGNED NOT NULL,
            `status` VARCHAR(50) NOT NULL,
            `location_name` VARCHAR(150) NOT NULL DEFAULT 'Dubai Hub',
            `public_notes` TEXT NULL,
            `internal_notes` TEXT NULL,
            `latitude` DECIMAL(10,8) NULL,
            `longitude` DECIMAL(11,8) NULL,
            `event_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `created_by` BIGINT UNSIGNED NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE,
            INDEX `idx_events_shipment` (`shipment_id`),
            INDEX `idx_events_time` (`event_time`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "shipment_assignments" => "CREATE TABLE IF NOT EXISTS `shipment_assignments` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `shipment_id` BIGINT UNSIGNED NOT NULL,
            `driver_id` BIGINT UNSIGNED NOT NULL,
            `vehicle_id` BIGINT UNSIGNED NULL,
            `assigned_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `status` ENUM('assigned', 'accepted', 'completed', 'cancelled') NOT NULL DEFAULT 'assigned',
            FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE,
            FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
            FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "quotes" => "CREATE TABLE IF NOT EXISTS `quotes` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `quote_number` VARCHAR(50) NOT NULL UNIQUE,
            `customer_id` BIGINT UNSIGNED NULL,
            `contact_name` VARCHAR(100) NOT NULL,
            `contact_email` VARCHAR(150) NOT NULL,
            `contact_phone` VARCHAR(30) NOT NULL,
            `status` ENUM('DRAFT', 'SENT', 'VIEWED', 'ACCEPTED', 'REJECTED', 'EXPIRED', 'CONVERTED') NOT NULL DEFAULT 'DRAFT',
            `valid_until` DATE NOT NULL,
            `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `discount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `tax` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `currency` VARCHAR(10) NOT NULL DEFAULT 'AED',
            `notes` TEXT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
            INDEX `idx_quotes_number` (`quote_number`),
            INDEX `idx_quotes_customer` (`customer_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "quote_items" => "CREATE TABLE IF NOT EXISTS `quote_items` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `quote_id` BIGINT UNSIGNED NOT NULL,
            `description` VARCHAR(255) NOT NULL,
            `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
            `unit_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `discount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `tax_rate` DECIMAL(5,2) NOT NULL DEFAULT 5.00,
            `line_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "invoices" => "CREATE TABLE IF NOT EXISTS `invoices` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
            `customer_id` BIGINT UNSIGNED NOT NULL,
            `shipment_id` BIGINT UNSIGNED NULL,
            `status` ENUM('DRAFT', 'ISSUED', 'SENT', 'PARTIALLY_PAID', 'PAID', 'OVERDUE', 'VOID') NOT NULL DEFAULT 'DRAFT',
            `issue_date` DATE NOT NULL,
            `due_date` DATE NOT NULL,
            `currency` VARCHAR(10) NOT NULL DEFAULT 'AED',
            `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `discount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `tax` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `amount_paid` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `balance_due` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `trn` VARCHAR(50) NULL,
            `notes` TEXT NULL,
            `issued_at` DATETIME NULL,
            `voided_at` DATETIME NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
            FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE SET NULL,
            INDEX `idx_invoices_number` (`invoice_number`),
            INDEX `idx_invoices_customer` (`customer_id`),
            INDEX `idx_invoices_status` (`status`),
            INDEX `idx_invoices_due` (`due_date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "invoice_items" => "CREATE TABLE IF NOT EXISTS `invoice_items` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `invoice_id` BIGINT UNSIGNED NOT NULL,
            `description` VARCHAR(255) NOT NULL,
            `reference` VARCHAR(100) NULL,
            `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
            `unit_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `discount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `tax_rate` DECIMAL(5,2) NOT NULL DEFAULT 5.00,
            `line_subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `line_tax` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `line_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "invoice_taxes" => "CREATE TABLE IF NOT EXISTS `invoice_taxes` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `invoice_id` BIGINT UNSIGNED NOT NULL,
            `tax_name` VARCHAR(50) NOT NULL DEFAULT 'UAE VAT',
            `rate` DECIMAL(5,2) NOT NULL DEFAULT 5.00,
            `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "payments" => "CREATE TABLE IF NOT EXISTS `payments` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `payment_number` VARCHAR(50) NOT NULL UNIQUE,
            `invoice_id` BIGINT UNSIGNED NOT NULL,
            `customer_id` BIGINT UNSIGNED NOT NULL,
            `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `currency` VARCHAR(10) NOT NULL DEFAULT 'AED',
            `method` ENUM('cash', 'credit_card', 'bank_transfer', 'cheque') NOT NULL DEFAULT 'credit_card',
            `reference` VARCHAR(100) NULL,
            `status` ENUM('completed', 'pending', 'failed', 'refunded') NOT NULL DEFAULT 'completed',
            `paid_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `created_by` BIGINT UNSIGNED NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
            FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
            INDEX `idx_payments_number` (`payment_number`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "credit_notes" => "CREATE TABLE IF NOT EXISTS `credit_notes` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `credit_note_number` VARCHAR(50) NOT NULL UNIQUE,
            `invoice_id` BIGINT UNSIGNED NOT NULL,
            `customer_id` BIGINT UNSIGNED NOT NULL,
            `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            `reason` TEXT NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
            FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "credit_note_items" => "CREATE TABLE IF NOT EXISTS `credit_note_items` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `credit_note_id` BIGINT UNSIGNED NOT NULL,
            `description` VARCHAR(255) NOT NULL,
            `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            FOREIGN KEY (`credit_note_id`) REFERENCES `credit_notes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "documents" => "CREATE TABLE IF NOT EXISTS `documents` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `entity_type` VARCHAR(50) NOT NULL,
            `entity_id` BIGINT UNSIGNED NOT NULL,
            `file_name` VARCHAR(255) NOT NULL,
            `file_path` VARCHAR(255) NOT NULL,
            `mime_type` VARCHAR(100) NOT NULL,
            `file_size` INT UNSIGNED NOT NULL,
            `is_private` TINYINT(1) NOT NULL DEFAULT 1,
            `uploaded_by` BIGINT UNSIGNED NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "notifications" => "CREATE TABLE IF NOT EXISTS `notifications` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` BIGINT UNSIGNED NOT NULL,
            `title` VARCHAR(150) NOT NULL,
            `message` TEXT NOT NULL,
            `type` VARCHAR(50) NOT NULL DEFAULT 'info',
            `is_read` TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "audit_logs" => "CREATE TABLE IF NOT EXISTS `audit_logs` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `actor_id` BIGINT UNSIGNED NULL,
            `action` VARCHAR(100) NOT NULL,
            `entity_type` VARCHAR(50) NOT NULL,
            `entity_id` BIGINT UNSIGNED NULL,
            `old_values` JSON NULL,
            `new_values` JSON NULL,
            `ip_address` VARCHAR(45) NULL,
            `user_agent` TEXT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_audit_actor` (`actor_id`),
            INDEX `idx_audit_entity` (`entity_type`, `entity_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "settings" => "CREATE TABLE IF NOT EXISTS `settings` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `setting_key` VARCHAR(100) NOT NULL UNIQUE,
            `setting_value` TEXT NULL,
            `group` VARCHAR(50) NOT NULL DEFAULT 'general',
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "contact_messages" => "CREATE TABLE IF NOT EXISTS `contact_messages` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `phone` VARCHAR(30) NOT NULL,
            `subject` VARCHAR(200) NOT NULL,
            `message` TEXT NOT NULL,
            `status` ENUM('new', 'read', 'replied') NOT NULL DEFAULT 'new',
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    ];

    foreach ($tables as $name => $sql) {
        if ($driver === 'sqlite') {
            $sql = preg_replace('/ENGINE\s*=\s*InnoDB.*$/i', '', $sql);
            $sql = str_replace('BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY', 'INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
            $sql = str_replace('INT UNSIGNED AUTO_INCREMENT PRIMARY KEY', 'INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
            $sql = preg_replace('/ENUM\([^)]+\)/i', 'TEXT', $sql);
            $sql = str_replace('ON UPDATE CURRENT_TIMESTAMP', '', $sql);
            $sql = preg_replace('/,\s*INDEX\s+`[^`]+`\s*\([^)]+\)/i', '', $sql);
            $sql = preg_replace('/INDEX\s+`[^`]+`\s*\([^)]+\),?/i', '', $sql);
        }
        $pdo->exec($sql);
        echo " -> Table `{$name}` verified/created.\n";
    }

    if ($driver === 'mysql') {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    }
    echo "\nAll database migrations executed successfully!\n";

} catch (Exception $e) {
    echo "\nMigration Error: " . $e->getMessage() . "\n";
    exit(1);
}
