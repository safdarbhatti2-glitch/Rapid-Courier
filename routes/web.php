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
$router->get('/update_rc', function() {
    if (($_GET['key'] ?? '') !== 'rc_deploy_2026') {
        http_response_code(403);
        exit('Forbidden');
    }
    header('Content-Type: text/plain');
    echo "Pulling latest git changes...\n";
    $output = [];
    $return_var = 0;
    exec('git pull origin main 2>&1', $output, $return_var);
    echo implode("\n", $output) . "\n";
    echo "Exit code: " . $return_var;
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
