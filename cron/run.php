<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Core/EnvLoader.php';
require_once BASE_PATH . '/app/Core/Autoloader.php';

use App\Core\EnvLoader;
use App\Core\Autoloader;
use App\Core\Database;

EnvLoader::load(BASE_PATH . '/.env');
Autoloader::register(BASE_PATH);

echo "[" . date('Y-m-d H:i:s') . "] Starting Antigravity Express Scheduled Maintenance Jobs...\n";

try {
    $now = date('Y-m-d H:i:s');
    $today = date('Y-m-d');

    // 1. Expire outdated quotes
    $expiredQuotes = Database::execute("UPDATE quotes SET status = 'EXPIRED', updated_at = ? WHERE status IN ('DRAFT', 'SENT', 'VIEWED') AND valid_until < ?", [$now, $today]);
    echo " -> Expired quotes processed.\n";

    // 2. Mark overdue invoices
    $overdueInvoices = Database::execute("UPDATE invoices SET status = 'OVERDUE', updated_at = ? WHERE status IN ('ISSUED', 'SENT', 'PARTIALLY_PAID') AND due_date < ?", [$now, $today]);
    echo " -> Overdue invoices updated.\n";

    echo "[" . date('Y-m-d H:i:s') . "] Scheduled Jobs Completed Successfully!\n";

} catch (Exception $e) {
    echo "[" . date('Y-m-d H:i:s') . "] Scheduled Jobs Error: " . $e->getMessage() . "\n";
    exit(1);
}
