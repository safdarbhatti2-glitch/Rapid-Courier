<?php

define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;
use App\Core\Database;

EnvLoader::load(BASE_PATH . '/.env');
Autoloader::register(BASE_PATH);

$users = Database::fetchAll("SELECT u.id, u.name, u.email, u.password_hash, r.name as role FROM users u JOIN roles r ON u.role_id = r.id");

echo "=== ALL USERS IN DATABASE ===" . PHP_EOL;
foreach ($users as $u) {
    $verify = password_verify('admin123', $u['password_hash']) ? 'VALID (admin123)' : 'INVALID';
    echo "ID: {$u['id']} | Name: {$u['name']} | Email: {$u['email']} | Role: {$u['role']} | Pass Check: {$verify}" . PHP_EOL;
}
