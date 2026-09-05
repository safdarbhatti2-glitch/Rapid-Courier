<?php

use App\Controllers\Public\HomeController;
use App\Controllers\Auth\AuthController;
use App\Controllers\Customer\CustomerController;
use App\Controllers\Admin\AdminController;
use App\Controllers\Document\DocumentController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\RoleMiddleware;
use App\Middleware\CsrfMiddleware;

// Public Marketing Routes
$router->get('/seed_rc', function() {
    if (($_GET['key'] ?? '') !== 'rc_deploy_2026') {
        http_response_code(403);
        exit('Forbidden');
    }
    header('Content-Type: text/plain');
    echo "Running database seeder & password reset...\n";
    try {
        $hash = password_hash('Admin@123456', PASSWORD_DEFAULT);
        // Ensure roles exist
        $adminRole = \App\Core\Database::fetchOne("SELECT id FROM roles WHERE name IN ('admin', 'super_admin') ORDER BY id ASC");
        if (!$adminRole) {
            \App\Core\Database::execute("INSERT INTO roles (name) VALUES ('admin')");
            $adminRoleId = \App\Core\Database::lastInsertId();
        } else {
            $adminRoleId = $adminRole['id'];
        }

        // Upsert admin@antigravityexpress.ae
        $user1 = \App\Core\Database::fetchOne("SELECT id FROM users WHERE email = 'admin@antigravityexpress.ae'");
        if ($user1) {
            \App\Core\Database::execute("UPDATE users SET password_hash = ?, status = 'active', role_id = ? WHERE id = ?", [$hash, $adminRoleId, $user1['id']]);
        } else {
            \App\Core\Database::execute("INSERT INTO users (role_id, name, email, phone, password_hash, status) VALUES (?, 'System Admin', 'admin@antigravityexpress.ae', '+971 4 800 2684', ?, 'active')", [$adminRoleId, $hash]);
        }

        // Upsert admin@rccourier.ae
        $user2 = \App\Core\Database::fetchOne("SELECT id FROM users WHERE email = 'admin@rccourier.ae'");
        if ($user2) {
            \App\Core\Database::execute("UPDATE users SET password_hash = ?, status = 'active', role_id = ? WHERE id = ?", [$hash, $adminRoleId, $user2['id']]);
        } else {
            \App\Core\Database::execute("INSERT INTO users (role_id, name, email, phone, password_hash, status) VALUES (?, 'RC Admin', 'admin@rccourier.ae', '+971 4 800 2684', ?, 'active')", [$adminRoleId, $hash]);
        }

        // Upsert demo customer
        $custRole = \App\Core\Database::fetchOne("SELECT id FROM roles WHERE name = 'customer'");
        $custRoleId = $custRole ? $custRole['id'] : 7;
        $custHash = password_hash('Customer@123456', PASSWORD_DEFAULT);
        $user3 = \App\Core\Database::fetchOne("SELECT id FROM users WHERE email = 'demo.customer@example.ae'");
        if ($user3) {
            \App\Core\Database::execute("UPDATE users SET password_hash = ?, status = 'active' WHERE id = ?", [$custHash, $user3['id']]);
        } else {
            \App\Core\Database::execute("INSERT INTO users (role_id, name, email, phone, password_hash, status) VALUES (?, 'Omar Al-Zaabi', 'demo.customer@example.ae', '+971 55 987 6543', ?, 'active')", [$custRoleId, $custHash]);
        }

        echo "Admin and customer passwords successfully updated on live database!\n";
        echo "admin@antigravityexpress.ae -> Admin@123456\n";
        echo "admin@rccourier.ae -> Admin@123456\n";
        echo "demo.customer@example.ae -> Customer@123456\n";
    } catch (\Throwable $e) {
        echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
    }
    exit;
});

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/services', [HomeController::class, 'services']);
$router->get('/services/{slug}', [HomeController::class, 'serviceDetail']);
$router->get('/locations', [HomeController::class, 'locations']);
$router->get('/track', [HomeController::class, 'track']);
$router->get('/quote', [HomeController::class, 'showQuote']);
$router->post('/quote', [HomeController::class, 'submitQuote'], [CsrfMiddleware::class]);
$router->get('/book', [HomeController::class, 'showBook']);
$router->post('/book', [HomeController::class, 'submitBook'], [CsrfMiddleware::class]);
$router->get('/contact', [HomeController::class, 'contact']);
$router->post('/contact', [HomeController::class, 'submitContact'], [CsrfMiddleware::class]);

// Authentication Routes
$router->get('/login', [AuthController::class, 'showLogin'], [GuestMiddleware::class]);
$router->post('/login', [AuthController::class, 'login'], [CsrfMiddleware::class]);
$router->get('/register', [AuthController::class, 'showRegister'], [GuestMiddleware::class]);
$router->post('/register', [AuthController::class, 'register'], [CsrfMiddleware::class]);
$router->post('/logout', [AuthController::class, 'logout'], [CsrfMiddleware::class]);

// Customer Portal Routes
$router->get('/customer', [CustomerController::class, 'dashboard'], [AuthMiddleware::class]);
$router->get('/customer/shipments', [CustomerController::class, 'shipments'], [AuthMiddleware::class]);
$router->get('/customer/shipments/{id}', [CustomerController::class, 'shipmentDetail'], [AuthMiddleware::class]);
$router->get('/customer/invoices', [CustomerController::class, 'invoices'], [AuthMiddleware::class]);
$router->get('/customer/invoices/{id}', [CustomerController::class, 'invoiceDetail'], [AuthMiddleware::class]);
$router->get('/customer/quotes', [CustomerController::class, 'quotes'], [AuthMiddleware::class]);
$router->get('/customer/profile', [CustomerController::class, 'profile'], [AuthMiddleware::class]);
$router->post('/customer/profile', [CustomerController::class, 'updateProfile'], [AuthMiddleware::class, CsrfMiddleware::class]);

// Admin Portal Routes
$router->get('/admin', [AdminController::class, 'dashboard'], [AuthMiddleware::class, RoleMiddleware::class]);
$router->get('/admin/shipments', [AdminController::class, 'shipments'], [AuthMiddleware::class, RoleMiddleware::class]);
$router->get('/admin/shipments/create', [AdminController::class, 'createShipment'], [AuthMiddleware::class, RoleMiddleware::class]);
$router->post('/admin/shipments/create', [AdminController::class, 'storeShipment'], [AuthMiddleware::class, RoleMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/shipments/{id}', [AdminController::class, 'shipmentDetail'], [AuthMiddleware::class, RoleMiddleware::class]);
$router->get('/admin/shipments/{id}/edit', [AdminController::class, 'editShipment'], [AuthMiddleware::class, RoleMiddleware::class]);
$router->post('/admin/shipments/{id}/edit', [AdminController::class, 'updateShipmentDetails'], [AuthMiddleware::class, RoleMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/shipments/{id}/status', [AdminController::class, 'updateShipmentStatus'], [AuthMiddleware::class, RoleMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/shipments/{id}/auto-generate-events', [AdminController::class, 'autoGenerateEvents'], [AuthMiddleware::class, RoleMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/tracking', [AdminController::class, 'tracking'], [AuthMiddleware::class, RoleMiddleware::class]);
$router->get('/admin/quotes', [AdminController::class, 'quotes'], [AuthMiddleware::class, RoleMiddleware::class]);
$router->post('/admin/quotes/{id}/convert', [AdminController::class, 'convertQuote'], [AuthMiddleware::class, RoleMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/invoices', [AdminController::class, 'invoices'], [AuthMiddleware::class, RoleMiddleware::class]);
$router->get('/admin/invoices/{id}', [AdminController::class, 'invoiceDetail'], [AuthMiddleware::class, RoleMiddleware::class]);
$router->post('/admin/invoices/{id}/payments', [AdminController::class, 'recordPayment'], [AuthMiddleware::class, RoleMiddleware::class, CsrfMiddleware::class]);
$router->post('/admin/invoices/{id}/void', [AdminController::class, 'voidInvoice'], [AuthMiddleware::class, RoleMiddleware::class, CsrfMiddleware::class]);
$router->get('/admin/settings', [AdminController::class, 'settings'], [AuthMiddleware::class, RoleMiddleware::class]);
$router->post('/admin/settings', [AdminController::class, 'updateSettings'], [AuthMiddleware::class, RoleMiddleware::class, CsrfMiddleware::class]);

// Printable Document Routes
$router->get('/invoices/{id}/pdf', [DocumentController::class, 'printInvoice']);
$router->get('/invoices/{id}/thermal', [DocumentController::class, 'thermalReceipt']);
$router->get('/quotes/{id}/pdf', [DocumentController::class, 'printQuote']);
$router->get('/shipments/{id}/label', [DocumentController::class, 'waybillLabel']);
$router->get('/verify/invoice/{invoice_number}', [DocumentController::class, 'verifyInvoice']);
