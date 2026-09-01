<?php

define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;
use App\Core\Database;

EnvLoader::load(BASE_PATH . '/.env');
Autoloader::register(BASE_PATH);

$hash = password_hash('admin123', PASSWORD_BCRYPT);

// Update all admin, finance, dispatcher, operations_manager to password_hash 'admin123'
Database::execute("UPDATE users SET password_hash = ?", [$hash]);

// Ensure admin email is admin@rccourier.ae
Database::execute("UPDATE users SET email = 'admin@rccourier.ae' WHERE id = 1");

$users = Database::fetchAll("SELECT u.id, u.name, u.email, u.status, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id");

echo "=== VERIFIED LOGINS (Password for all is: admin123) ===" . PHP_EOL;
foreach ($users as $u) {
    echo "Role: {$u['role_name']} | Email: {$u['email']} | Status: {$u['status']}" . PHP_EOL;
}
