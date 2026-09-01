<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;
use App\Core\Session;
use App\Core\View;
use App\Core\Request;
use App\Core\Router;

// Load environment variables
EnvLoader::load(BASE_PATH . '/.env');

// Register PSR-4 Autoloader
Autoloader::register(BASE_PATH);

// Initialize Session
Session::start();

// Set View Directory
View::setViewsDir(BASE_PATH . '/views');

// Global helper for HTML escaping
if (!function_exists('e')) {
    function e(?string $value): string {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// Instantiate Request and Router
$request = new Request();
$router = new Router();

// Load Routes
require_once BASE_PATH . '/routes/web.php';
require_once BASE_PATH . '/routes/api.php';

// Dispatch Request
$router->dispatch($request);
