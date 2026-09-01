<?php

use App\Controllers\Api\ApiController;
use App\Middleware\CsrfMiddleware;

// Public Tracking API
$router->get('/api/tracking/{trackingNumber}', [ApiController::class, 'trackingInfo']);

// Protected API endpoints
$router->post('/api/shipments', [ApiController::class, 'createShipment']);
$router->post('/api/shipments/{id}/status', [ApiController::class, 'updateShipmentStatus']);
$router->post('/api/invoices', [ApiController::class, 'createInvoice']);
$router->post('/api/invoices/{id}/payments', [ApiController::class, 'recordPayment']);
