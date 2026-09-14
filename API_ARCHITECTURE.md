# RC Courier UAE — RESTful API Architecture & Specification (v1)

## Executive Summary & System Overview

This document defines the production-ready RESTful API v1 platform for **RC Courier UAE**. The API enables authorized external systems, e-commerce platforms (Shopify, WooCommerce, custom ERPs), and corporate logistics clients to integrate directly with RC Courier's logistics network across all 7 UAE Emirates and GCC.

### Technical & Hosting Stack Compatibility
- **Language & Runtime**: PHP 8.x+ (Strict types, PDO prepared statements, native JSON).
- **Database**: MySQL / MariaDB (fully backward-compatible with SQLite fallback for local testing).
- **Server Environment**: Hostinger Business Shared/Cloud Hosting (Apache with `.htaccess` mod_rewrite, HTTPS enforcement).
- **Dependencies**: 100% lightweight, standalone PHP (Zero Node.js dependency required in production).
- **Security Protocols**: HMAC-SHA256 signatures, API Key/Secret hashed validation, granular permission scopes, customer data isolation, per-key rate limiting, idempotency keys.

---

## 1. Existing Database Reuse & Schema Extensions

### Reused Existing Tables
The API directly operates on existing business tables without duplicating logic:
- `users`, `customers`, `customer_addresses`
- `services`, `service_zones`, `pricing_rules`, `pricing_rule_versions`
- `shipments`, `shipment_items`, `shipment_status_events`, `shipment_assignments`
- `quotes`, `quote_items`, `invoices`, `invoice_items`, `invoice_taxes`, `payments`
- `documents`, `audit_logs`, `settings`

### New API Tables (Phase 2 Database Migration)
To support API keys, webhooks, rate limiting, audit logging, and idempotency, 6 new indexed tables are added:

```sql
-- 1. API Keys & Credentials
CREATE TABLE IF NOT EXISTS `api_keys` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `api_key` VARCHAR(64) NOT NULL UNIQUE,
    `api_secret_hash` VARCHAR(255) NOT NULL,
    `environment` ENUM('live', 'test') NOT NULL DEFAULT 'live',
    `permissions` JSON NULL,
    `status` ENUM('active', 'revoked', 'expired') NOT NULL DEFAULT 'active',
    `rate_limit_rpm` INT UNSIGNED NOT NULL DEFAULT 60,
    `expires_at` DATETIME NULL,
    `revoked_at` DATETIME NULL,
    `last_used_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
    INDEX `idx_api_keys_key` (`api_key`),
    INDEX `idx_api_keys_customer` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. API Idempotency Keys
CREATE TABLE IF NOT EXISTS `api_idempotency` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `idempotency_key` VARCHAR(100) NOT NULL,
    `endpoint` VARCHAR(255) NOT NULL,
    `request_hash` VARCHAR(64) NOT NULL,
    `response_code` INT NOT NULL,
    `response_body` JSON NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `uniq_idempotency` (`customer_id`, `idempotency_key`),
    INDEX `idx_idempotency_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Webhook Subscriptions
CREATE TABLE IF NOT EXISTS `api_webhooks` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `secret` VARCHAR(64) NOT NULL,
    `events` JSON NOT NULL,
    `status` ENUM('active', 'disabled', 'failed') NOT NULL DEFAULT 'active',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
    INDEX `idx_webhooks_customer` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Webhook Deliveries Log
CREATE TABLE IF NOT EXISTS `api_webhook_deliveries` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `webhook_id` BIGINT UNSIGNED NOT NULL,
    `event_type` VARCHAR(50) NOT NULL,
    `event_id` VARCHAR(100) NOT NULL,
    `payload` JSON NOT NULL,
    `response_code` INT NULL,
    `response_body` TEXT NULL,
    `execution_time_ms` INT UNSIGNED NULL,
    `attempt` INT UNSIGNED NOT NULL DEFAULT 1,
    `status` ENUM('success', 'failed', 'retrying') NOT NULL DEFAULT 'success',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`webhook_id`) REFERENCES `api_webhooks` (`id`) ON DELETE CASCADE,
    INDEX `idx_webhook_deliv_webhook` (`webhook_id`),
    INDEX `idx_webhook_deliv_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Per-Key Rate Limiting Tracker
CREATE TABLE IF NOT EXISTS `api_rate_limits` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `api_key_id` BIGINT UNSIGNED NOT NULL,
    `window_time` INT UNSIGNED NOT NULL, -- Unix timestamp minute window
    `request_count` INT UNSIGNED NOT NULL DEFAULT 1,
    FOREIGN KEY (`api_key_id`) REFERENCES `api_keys` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `uniq_rate_window` (`api_key_id`, `window_time`),
    INDEX `idx_rate_limits_window` (`window_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. API Audit Request Log
CREATE TABLE IF NOT EXISTS `api_audit_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `request_id` VARCHAR(50) NOT NULL UNIQUE,
    `api_key_id` BIGINT UNSIGNED NULL,
    `customer_id` BIGINT UNSIGNED NULL,
    `method` VARCHAR(10) NOT NULL,
    `endpoint` VARCHAR(255) NOT NULL,
    `response_code` INT NOT NULL,
    `execution_time_ms` INT UNSIGNED NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_api_audit_req` (`request_id`),
    INDEX `idx_api_audit_key` (`api_key_id`),
    INDEX `idx_api_audit_cust` (`customer_id`),
    INDEX `idx_api_audit_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 2. API Authentication & Authorization Model

### Credentials & Security Standards
1. **API Key (`X-API-Key`)**: 64-character public key identifier (e.g. `rc_live_7f8a9b0c1d2e3f4a5b6c7d8e9f0a1b2c`).
2. **API Secret (`X-API-Secret`)**: Secret token passed in header or validated via Bearer token (`Authorization: Bearer <secret>`).
3. **Secret Protection**: Secrets are shown to the customer ONCE upon generation and stored in the database exclusively as `password_hash()` (Bcrypt).
4. **Environment Isolation**:
   - `live`: Real bookings, real invoice generation.
   - `test`: Sandbox mode prefix `rc_test_...`.

### Permission Scopes
API Keys enforce least-privilege scope verification:
- `shipments:create` — Create bookings & shipments.
- `shipments:read` — View customer shipments & status.
- `shipments:update` — Modify allowed fields before dispatch.
- `shipments:cancel` — Cancel pending/booked shipments.
- `tracking:read` — Access tracking timeline and status events.
- `quotes:create` — Request instant AED rate calculations.
- `invoices:read` — Download tax invoices & breakdown.
- `labels:read` — Render 4x6 thermal shipping labels.
- `webhooks:manage` — Register/manage webhook endpoints.

---

## 3. Standardized API Request & Response Envelope

Every request gets a unique Request ID returned in header `X-Request-ID: RCREQ-YYYYMMDD-XXXXXXXX` and JSON metadata.

### Success Response Format (HTTP 200 / 201 / 204)
```json
{
  "success": true,
  "data": {
    "shipment_id": 105,
    "reference_number": "SHP-2026-984125",
    "tracking_number": "RC41105043",
    "status": "BOOKED",
    "total_amount": 36.75,
    "currency": "AED"
  },
  "meta": {
    "request_id": "RCREQ-20260914-98A1B2C3",
    "timestamp": "2026-09-14T14:25:38Z",
    "version": "v1"
  }
}
```

### Error Response Format (HTTP 400 / 401 / 403 / 404 / 422 / 429 / 500)
```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Invalid shipment parameter values.",
    "fields": {
      "receiver.phone": "Invalid UAE phone number format (+971... required).",
      "weight_kg": "Weight must be a positive number."
    }
  },
  "meta": {
    "request_id": "RCREQ-20260914-98A1B2C3",
    "timestamp": "2026-09-14T14:25:38Z",
    "version": "v1"
  }
}
```

---

## 4. API Endpoints Specification (v1)

### Authentication & Token
- `POST /api/v1/auth/token` — Exchange Key/Secret for short-lived Session Access Token (Optional Bearer Token support).

### Quotation API
- `POST /api/v1/quotes` — Calculate instant pricing for origin/destination, weight, dimensions, declared value in AED.
- `GET /api/v1/quotes/{id}` — Retrieve quotation details.

### Shipments API
- `POST /api/v1/shipments` — Book a shipment (Supports `Idempotency-Key` header). Auto-generates Waybill, Tracking Number, Status Event, Tax Invoice, and Thermal Label.
- `GET /api/v1/shipments` — List customer shipments with pagination (`page`, `limit`), filters (`status`, `date_from`, `date_to`, `search`).
- `GET /api/v1/shipments/{id}` — Retrieve full shipment details.
- `GET /api/v1/shipments/{id}/tracking` — Retrieve public/customer tracking data.
- `GET /api/v1/shipments/{id}/events` — List immutable timeline events.
- `POST /api/v1/shipments/{id}/cancel` — Cancel shipment if not yet picked up.

### Public Tracking API
- `GET /api/v1/tracking/{tracking_number}` — Public tracking endpoint (safe, customer-facing info only).

### Invoices API
- `GET /api/v1/invoices/{id}` — View tax invoice breakdown.
- `GET /api/v1/invoices/{id}/download` — Stream PDF / print-friendly HTML tax invoice.
- `GET /api/v1/shipments/{id}/invoice` — Retrieve linked invoice for a specific shipment.

### Thermal Labels API
- `GET /api/v1/shipments/{id}/label` — View label metadata.
- `GET /api/v1/shipments/{id}/label/thermal` — Stream 4x6 inch thermal shipping label (HTML/SVG with Code128 machine-readable barcode and QR code verification link).

### Webhooks API
- `POST /api/v1/webhooks` — Register webhook URL with event subscriptions.
- `GET /api/v1/webhooks` — List registered webhooks.
- `DELETE /api/v1/webhooks/{id}` — Remove webhook subscription.

### Account API
- `GET /api/v1/account` — View authenticated account summary & plan.
- `GET /api/v1/account/shipments` — Shortcut to account shipments.
- `GET /api/v1/account/invoices` — Shortcut to account invoices.
- `GET /api/v1/account/quotes` — Shortcut to account quotes.

---

## 5. Webhook System Architecture & HMAC Signatures

When a shipment status changes (e.g. `BOOKED`, `PICKED_UP`, `IN_TRANSIT`, `OUT_FOR_DELIVERY`, `DELIVERED`, `CANCELLED`), registered webhooks are triggered.

### Delivery Payload
```json
{
  "event": "shipment.delivered",
  "event_id": "evt_98412503",
  "created_at": "2026-09-14T14:25:38Z",
  "data": {
    "shipment_id": 105,
    "reference_number": "SHP-2026-984125",
    "tracking_number": "RC41105043",
    "status": "DELIVERED",
    "location": "Dubai Hub",
    "delivered_at": "2026-09-14 14:25:00"
  }
}
```

### Signature Verification Header
Every webhook delivery includes header:
`X-RC-Signature: t=1788627000,v1=a5b6c7d8...`
Where `v1` is `hash_hmac('sha256', "1788627000." . payload, webhook_secret)`.

---

## 6. Thermal Label Specification (4x6 Inch)

Format: 4x6 inches (100mm x 150mm), 300 DPI layout.
Contains:
- **Header**: RC Courier UAE Logo & Service Badge (`EXPRESS SAME-DAY` / `GCC FREIGHT`).
- **Barcodes**: High-contrast Code128 SVG barcode of `tracking_number` (e.g. `RC41105043`).
- **QR Code**: Verification & Live Tracking Link (`https://rapid-courier.com/track?number=RC41105043`).
- **Routing**: Origin Emirate -> Destination Emirate & Hub Routing Code (e.g., `DXB -> AUH`).
- **Sender/Receiver Details**: Address, contact person, phone number.
- **Package Details**: Weight, dimensions, COD amount (AED).

---

## 7. Phase-by-Phase Development Roadmap

- **PHASE 1**: Project Audit & Architecture Spec (`API_ARCHITECTURE.md` & `implementation_plan.md`).
- **PHASE 2**: Database Schema Migration (`api_keys`, `api_webhooks`, `api_webhook_deliveries`, `api_idempotency`, `api_rate_limits`, `api_audit_logs`).
- **PHASE 3**: Service Layer Refactoring (`ApiService`, `AuthService`, `LabelService`, `WebhookService`).
- **PHASE 4**: API Routing, Middleware (`ApiAuthMiddleware`, `RateLimitMiddleware`, `IdempotencyMiddleware`, `ApiAuditMiddleware`) & Response Envelope.
- **PHASE 5**: Quotation API (`POST /api/v1/quotes`, `GET /api/v1/quotes/{id}`).
- **PHASE 6**: Shipment API (`POST`, `GET`, `CANCEL`).
- **PHASE 7**: Tracking & Events API (`GET /api/v1/tracking/{tracking_number}`).
- **PHASE 8**: Invoice API (`GET /api/v1/invoices/{id}`, `GET /api/v1/invoices/{id}/download`).
- **PHASE 9**: Thermal Label Generator (4x6 Thermal Label HTML/SVG Barcode Engine).
- **PHASE 10**: Webhook Dispatcher Engine & Delivery Logger.
- **PHASE 11**: Customer Portal API Management UI (Key Generator, Usage Charts, Webhook Config, Test Sandbox).
- **PHASE 12**: Admin Panel API Management UI (Client list, Rate limits, Revocation, Global Audit Logs).
- **PHASE 13**: OpenAPI 3.0 Spec & Developer Portal UI (`/docs/api/`) + Postman Collection JSON.
- **PHASE 14**: Security Testing, Customer Data Isolation Verification & Rate Limit Tests.
- **PHASE 15**: Hostinger Production Deployment & Verification.
