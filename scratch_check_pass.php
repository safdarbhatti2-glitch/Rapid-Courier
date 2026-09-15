<?php

define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;
use App\Core\Database;

EnvLoader::load(BASE_PATH . '/.env');
Autoloader::register(BASE_PATH);

$users = Database::fetchAll("SELECT id, name, email, password_hash, status FROM users");
foreach ($users as $u) {
    echo $u['email'] . PHP_EOL;
    echo "  Admin@123456: " . (password_verify('Admin@123456', $u['password_hash']) ? 'MATCH' : 'NO') . PHP_EOL;
    echo "  admin123:     " . (password_verify('admin123', $u['password_hash']) ? 'MATCH' : 'NO') . PHP_EOL;
    echo "  vpOa|NgR5#:   " . (password_verify('vpOa|NgR5#', $u['password_hash']) ? 'MATCH' : 'NO') . PHP_EOL;
}
