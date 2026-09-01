<?php

define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;
use App\Core\Database;

EnvLoader::load(BASE_PATH . '/.env');
Autoloader::register(BASE_PATH);

$hash123 = password_hash('admin123', PASSWORD_BCRYPT);
$hash123456 = password_hash('Admin@123456', PASSWORD_BCRYPT);

// Update ID 1 (Admin) -> email: admin@rccourier.ae, pass accepts admin123
Database::execute("UPDATE users SET email = 'admin@rccourier.ae', password_hash = ? WHERE id = 1", [$hash123]);

// Ensure admin@antigravityexpress.ae exists for integration tests
$existingAg = Database::fetchOne("SELECT id FROM users WHERE email = 'admin@antigravityexpress.ae'");
if (!$existingAg) {
    Database::execute("INSERT INTO users (role_id, name, email, phone, password_hash, status) VALUES (1, 'Sara Al-Maktoum (Admin)', 'admin@antigravityexpress.ae', '+971 50 111 2233', ?, 'active')", [$hash123456]);
} else {
    Database::execute("UPDATE users SET password_hash = ? WHERE id = ?", [$hash123456, $existingAg['id']]);
}

echo "Admin accounts configured successfully!" . PHP_EOL;
