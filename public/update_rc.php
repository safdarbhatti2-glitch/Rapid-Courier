<?php
// Web update script
$token = $_GET['key'] ?? '';
if ($token !== 'rc_secure_deploy_2026') {
    http_response_code(403);
    die('Forbidden');
}

header('Content-Type: text/plain');

$base = dirname(__DIR__);

echo "=== RC COURIER WEB DEPLOYMENT ===\n\n";

echo "1. Fetching latest git code:\n";
$gitOut = shell_exec("cd " . escapeshellarg($base) . " && git fetch origin main 2>&1 && git reset --hard origin/main 2>&1");
echo $gitOut ? $gitOut : "No shell output or shell_exec disabled\n";

echo "\n2. Running Database Migration:\n";
include_once $base . '/app/Core/EnvLoader.php';
include_once $base . '/app/Core/Autoloader.php';
\App\Core\EnvLoader::load($base . '/.env');
\App\Core\Autoloader::register($base);

try {
    include_once $base . '/database/migrate.php';
    echo "Migration completed.\n";
} catch (\Throwable $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}

try {
    include_once $base . '/database/seed.php';
    echo "Seeding completed.\n";
} catch (\Throwable $e) {
    echo "Seeding Error: " . $e->getMessage() . "\n";
}

echo "\n=== DEPLOYMENT COMPLETED ===\n";
