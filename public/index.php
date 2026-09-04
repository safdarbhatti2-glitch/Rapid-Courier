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

try {
    // Dispatch Request
    $router->dispatch($request);
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<div style='font-family:sans-serif; padding:2rem; background:#fff1f2; border:1px solid #fecdd3; color:#9f1239; border-radius:8px; max-width:800px; margin:2rem auto;'>";
    echo "<h2 style='margin-top:0;'>RC Courier Error</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " on line " . $e->getLine() . "</p>";
    echo "<pre style='background:#fff; padding:1rem; border-radius:4px; overflow:auto; font-size:12px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}
