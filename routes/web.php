<?php

use App\Controllers\Public\HomeController;
use App\Controllers\Auth\AuthController;
use App\Controllers\Customer\CustomerController;
use App\Controllers\Admin\AdminController;
use App\Controllers\Document\DocumentController;
use App\Controllers\Api\ApiQuoteController;
use App\Controllers\Api\ApiShipmentController;
use App\Controllers\Api\ApiTrackingController;
use App\Controllers\Api\ApiInvoiceController;
use App\Controllers\Api\ApiLabelController;
use App\Controllers\Api\ApiWebhookController;
use App\Controllers\Api\ApiAccountController;

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\RoleMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\ApiAuthMiddleware;

// Public Marketing Routes
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
$router->get('/customer/api-keys', [CustomerController::class, 'apiKeys'], [AuthMiddleware::class]);
$router->post('/customer/api-keys/create', [CustomerController::class, 'createApiKey'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/customer/api-keys/revoke', [CustomerController::class, 'revokeApiKey'], [AuthMiddleware::class, CsrfMiddleware::class]);

// Admin Portal Routes
$router->get('/admin', [AdminController::class, 'dashboard'], [AuthMiddleware::class, RoleMiddleware::class]);
$router->get('/admin/api-management', [AdminController::class, 'apiManagement'], [AuthMiddleware::class, RoleMiddleware::class]);
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

// RESTful API v1 Routes
// Public Tracking API
$router->get('/api/v1/tracking/{tracking_number}', [ApiTrackingController::class, 'publicTrack']);

// Quotes API
$router->post('/api/v1/quotes', [ApiQuoteController::class, 'calculate'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/quotes/{id}', [ApiQuoteController::class, 'get'], [ApiAuthMiddleware::class]);

// Shipments API
$router->post('/api/v1/shipments', [ApiShipmentController::class, 'create'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/shipments', [ApiShipmentController::class, 'list'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/shipments/{id}', [ApiShipmentController::class, 'get'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/shipments/{id}/tracking', [ApiTrackingController::class, 'getShipmentTracking'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/shipments/{id}/events', [ApiTrackingController::class, 'getEvents'], [ApiAuthMiddleware::class]);
$router->post('/api/v1/shipments/{id}/cancel', [ApiShipmentController::class, 'cancel'], [ApiAuthMiddleware::class]);

// Invoices API
$router->get('/api/v1/shipments/{id}/invoice', [ApiInvoiceController::class, 'getByShipment'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/invoices/{id}', [ApiInvoiceController::class, 'get'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/invoices/{id}/download', [ApiInvoiceController::class, 'download'], [ApiAuthMiddleware::class]);

// Thermal Labels API
$router->get('/api/v1/shipments/{id}/label', [ApiLabelController::class, 'getLabel'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/shipments/{id}/label/thermal', [ApiLabelController::class, 'getThermalLabel'], [ApiAuthMiddleware::class]);

// Webhooks API
$router->post('/api/v1/webhooks', [ApiWebhookController::class, 'register'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/webhooks', [ApiWebhookController::class, 'list'], [ApiAuthMiddleware::class]);
$router->delete('/api/v1/webhooks/{id}', [ApiWebhookController::class, 'delete'], [ApiAuthMiddleware::class]);

// Account API
$router->get('/api/v1/account', [ApiAccountController::class, 'getAccount'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/account/shipments', [ApiShipmentController::class, 'list'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/account/invoices', [ApiInvoiceController::class, 'get'], [ApiAuthMiddleware::class]);
$router->get('/api/v1/account/quotes', [ApiQuoteController::class, 'get'], [ApiAuthMiddleware::class]);
