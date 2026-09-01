<?php

define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;
use App\Core\Database;

EnvLoader::load(BASE_PATH . '/.env');
Autoloader::register(BASE_PATH);

$newHash = password_hash('admin123', PASSWORD_BCRYPT);

// Update ID 1 (Admin) to use admin@rccourier.ae and admin123 hash
Database::execute("UPDATE users SET email = 'admin@rccourier.ae', password_hash = ? WHERE id = 1", [$newHash]);

// Also update demo customer (ID 5) password hash to admin123 or customer123
Database::execute("UPDATE users SET password_hash = ? WHERE id = 5", [$newHash]);

// Verify the update
$updated = Database::fetchOne("SELECT id, name, email, password_hash FROM users WHERE id = 1");

echo "UPDATED ADMIN USER:" . PHP_EOL;
echo "ID: " . $updated['id'] . PHP_EOL;
echo "Email: " . $updated['email'] . PHP_EOL;
echo "Password Verify (admin123): " . (password_verify('admin123', $updated['password_hash']) ? 'SUCCESS' : 'FAILED') . PHP_EOL;
