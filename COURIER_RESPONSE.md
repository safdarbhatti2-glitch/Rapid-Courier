# RC Courier UAE — Technical Integration Guide & Credentials Onboarding

**Company Name:** RC Courier UAE LLC  
**API Version:** RESTful API v1.0  
**Target Partner:** Step Inside Solution HQ / Systems Integrators  
**Technical Support Contact:** api-support@rccourier.ae / dev@rapid-courier.com  
**Document Date:** September 14, 2026  

---

## 1. Executive Implementation Guide & Prerequisites

Welcome to the **RC Courier UAE REST API v1** integration platform. This document provides complete technical specifications for B2B machine-to-machine (M2M) automated shipment booking, rate calculation, milestone tracking, tax invoice retrieval, 4x6 thermal shipping label generation, and HMAC webhooks.

### Quick Start Checklist:
1. **Account Registration**: Create a business customer account at [https://rapid-courier.com/register](https://rapid-courier.com/register).
2. **Generate API Credentials**: Log into your Customer Portal at `/customer/api-keys` to generate your **API Key** (`rc_live_...` or `rc_test_...`) and **API Secret** (`sec_...`).
3. **Select Scope Permissions**: Enable necessary scopes (`shipments:create`, `shipments:read`, `shipments:cancel`, `quotes:create`, `tracking:read`, `invoices:read`, `labels:read`, `webhooks:manage`).
4. **Integration Testing**: Perform sandbox testing against our Live/Test API endpoints using the cURL samples below.

---

## 2. Environment URLs

- **Sandbox / Test Base URL:** `https://rapid-courier.com/api/v1` *(Use test API key prefixed with `rc_test_`)*
- **Production / Live Base URL:** `https://rapid-courier.com/api/v1` *(Use live API key prefixed with `rc_live_`)*
- **Developer Portal & OpenAPI Specs:** `https://rapid-courier.com/docs/api/`
- **OpenAPI 3.0 Specification:** `https://rapid-courier.com/docs/api/openapi.yaml`

---

## 3. Authentication & Security Process

RC Courier REST API v1 supports two standard header authentication schemes. All requests must be transmitted over HTTPS (TLS 1.2+).

### Option A: Standard Dual Headers (Recommended)
```http
X-API-Key: rc_live_7f8a9b0c1d2e3f4a5b6c7d8e
X-API-Secret: sec_a1b2c3d4e5f6789012345678
Content-Type: application/json
```

### Option B: HTTP Bearer Token
Format as `Bearer <API_KEY>:<API_SECRET>`:
```http
Authorization: Bearer rc_live_7f8a9b0c1d2e3f4a5b6c7d8e:sec_a1b2c3d4e5f6789012345678
Content-Type: application/json
```

### Key Management & Rotation:
- **Generation**: Created via Customer Portal at `https://rapid-courier.com/customer/api-keys`. Secrets are hashed using Bcrypt and shown only once upon generation.
- **Rotation**: Supported via 1-click rotation in Customer Portal (`/customer/api-keys/rotate`), which revokes the previous credential and issues a new key pair with identical scopes.
- **Rate Limits**: 60 requests/minute for Live keys, 120 requests/minute for Test keys. Rate limit headers are returned on all requests. Exceeding limits returns HTTP `429 RATE_LIMIT_EXCEEDED`.

---

## 4. Endpoints Mapping & Standard Contracts

### 4.1. Calculate Shipping Quotation
- **Method:** `POST`
- **Endpoint:** `/api/v1/quotes`
- **Required Scope:** `quotes:create`

**Request Payload:**
```json
{
  "service": "express_same_day",
  "origin_emirate": "Dubai",
  "destination_emirate": "Abu Dhabi",
  "weight_kg": 2.5,
  "length_cm": 25.0,
  "width_cm": 15.0,
  "height_cm": 10.0,
  "declared_value": 249.00
}
```

**Response (`200 OK`):**
```json
{
  "success": true,
  "data": {
    "quote_id": 1042,
    "quote_number": "QT-2026-004812",
    "service_id": 1,
    "actual_weight": 2.5,
    "volumetric_weight": 0.75,
    "chargeable_weight": 2.5,
    "currency": "AED",
    "subtotal": 35.00,
    "tax": 1.75,
    "total": 36.75,
    "valid_until": "2026-09-28"
  },
  "meta": {
    "request_id": "RCREQ-20260914-98A1B2C3",
    "timestamp": "2026-09-14T17:45:00Z",
    "version": "v1"
  }
}
```

---

### 4.2. Create Shipment / Book Delivery (Supports Idempotency)
- **Method:** `POST`
- **Endpoint:** `/api/v1/shipments`
- **Required Scope:** `shipments:create`
- **Optional Header:** `Idempotency-Key: ik_ord_98124_abc` *(Prevents duplicate charges/bookings)*

**Request Payload:**
```json
{
  "service": "express_same_day",
  "sender": {
    "name": "Customer Return Service",
    "company": "Step Inside Solution",
    "phone": "+971 50 123 4567",
    "email": "customer@example.com",
    "address": {
      "line1": "Al Barsha 1, Street 14, Building 5, Apt 204",
      "area": "Al Barsha 1",
      "emirate": "Dubai",
      "city": "Dubai"
    }
  },
  "receiver": {
    "name": "Inbound Receiving Dept",
    "company": "Central Logistics Warehouse",
    "phone": "+971 4 800 1234",
    "email": "warehouse-inbound@stepinsidesolutions.com",
    "address": {
      "line1": "Logistics District, Warehouse Unit WH-12",
      "area": "Dubai South",
      "emirate": "Dubai",
      "city": "Dubai South"
    }
  },
  "package": {
    "pieces": 1,
    "weight_kg": 1.25,
    "length_cm": 25.0,
    "width_cm": 15.0,
    "height_cm": 10.0,
    "declared_value": 249.00,
    "description": "Electronics Accessories - Return"
  }
}
```

**Response (`201 Created`):**
```json
{
  "success": true,
  "data": {
    "shipment_id": 4812,
    "reference_number": "SHP-2026-04812",
    "tracking_number": "RC98124503",
    "status": "BOOKED",
    "service_id": 1,
    "weight_kg": 1.25,
    "pricing": {
      "subtotal": 35.00,
      "tax": 1.75,
      "total": 36.75,
      "currency": "AED"
    },
    "invoice_url": "/api/v1/shipments/4812/invoice",
    "thermal_label_url": "/api/v1/shipments/4812/label/thermal"
  },
  "meta": {
    "request_id": "RCREQ-20260914-A1B2C3D4",
    "timestamp": "2026-09-14T17:45:05Z",
    "version": "v1"
  }
}
```

---

### 4.3. Track Shipment Status & Timeline
- **Public Endpoint (No Key Required):** `GET /api/v1/tracking/{tracking_number}`
- **Authenticated Endpoint:** `GET /api/v1/shipments/{id}/tracking` *(Requires scope `tracking:read`)*

**Response (`200 OK`):**
```json
{
  "success": true,
  "data": {
    "tracking_number": "RC98124503",
    "reference_number": "SHP-2026-04812",
    "status": "OUT_FOR_DELIVERY",
    "service_name": "Same-Day Express UAE",
    "origin": "Dubai",
    "destination": "Dubai",
    "booking_date": "2026-09-14 17:45:05",
    "estimated_delivery_at": "2026-09-15 18:00:00",
    "delivered_at": null,
    "events": [
      {
        "status": "BOOKED",
        "location": "Dubai Hub",
        "description": "Shipment booking created successfully.",
        "timestamp": "2026-09-14 17:45:05"
      },
      {
        "status": "PICKED_UP",
        "location": "Al Barsha Hub",
        "description": "Parcel collected from sender.",
        "timestamp": "2026-09-15 09:15:00"
      },
      {
        "status": "OUT_FOR_DELIVERY",
        "location": "Dubai South Hub",
        "description": "Assigned to courier driver for final delivery.",
        "timestamp": "2026-09-15 11:30:00"
      }
    ]
  }
}
```

---

### 4.4. Download 4x6 Thermal Shipping Label
- **Method:** `GET`
- **Endpoint:** `/api/v1/shipments/{id}/label/thermal`
- **Required Scope:** `labels:read`
- **Output:** Print-ready `text/html` formatted for standard 4x6 inch thermal barcode printers (Zebra, TSC, Xprinter). Features a machine-readable vector Code128 SVG barcode and QR tracking link.

---

### 4.5. Download UAE Tax Invoice (5% VAT)
- **Method:** `GET`
- **Endpoint:** `/api/v1/invoices/{id}/download`
- **Required Scope:** `invoices:read`
- **Output:** Printable UAE Tax Invoice HTML featuring breakdown in AED, customer TRN, and 5% VAT calculation.

---

### 4.6. Cancel Shipment
- **Method:** `POST`
- **Endpoint:** `/api/v1/shipments/{id}/cancel`
- **Required Scope:** `shipments:cancel`

---

## 5. Milestone Status Codes Mapping

| RC Courier Status Code | Description | Standard Logistics Milestone |
| :--- | :--- | :--- |
| `BOOKED` | Shipment created and manifest issued | `SHIPMENT_CREATED` / `ORDER_CREATED` |
| `CONFIRMED` | Dispatch confirmed by ops | `BOOKING_CONFIRMED` |
| `PICKUP_ASSIGNED` | Driver assigned for parcel collection | `DRIVER_ASSIGNED` |
| `PICKED_UP` | Parcel collected from sender | `PICKED_UP` |
| `AT_ORIGIN_HUB` | Received at origin sorting facility | `HUB_RECEIVED` |
| `IN_TRANSIT` | In transit between logistics hubs | `IN_TRANSIT` |
| `AT_DESTINATION_HUB` | Arrived at destination distribution hub | `DESTINATION_ARRIVED` |
| `OUT_FOR_DELIVERY` | Out with courier driver for final delivery | `OUT_FOR_DELIVERY` |
| `DELIVERY_ATTEMPTED` | Delivery attempted (consignee unavailable) | `ATTEMPTED` |
| `DELIVERED` | Consignee received parcel | `DELIVERED` |
| `CANCELLED` | Shipment cancelled prior to transit | `CANCELLED` |
| `ON_HOLD` | Customs or address verification hold | `EXCEPTION` |
| `RETURNED` | Returned back to origin sender | `RETURNED` |

---

## 6. Real-Time Webhooks (Push Notifications)

To eliminate polling, register your webhook callback URL in the Customer Portal (`/customer/api-keys`) or via API (`POST /api/v1/webhooks`).

- **Supported Events:** `shipment.created`, `shipment.status_updated`, `shipment.delivered`, `shipment.cancelled`, `*`.
- **HMAC Signature Header:** `X-RC-Signature: t={timestamp},v1={hash_hmac('sha256', timestamp . '.' . payload, secret)}`
- **Auto-Disable Protection:** Webhooks with 5 consecutive delivery failures are automatically paused (`status = 'disabled'`).
- **Delivery Retries:** Failed webhook deliveries can be inspected and manually retried via API (`POST /api/v1/webhooks/deliveries/{id}/retry`) or Customer Portal UI.

---

## 7. Working cURL Samples

### cURL: Book Shipment with Idempotency Key
```bash
curl -X POST "https://rapid-courier.com/api/v1/shipments" \
  -H "X-API-Key: rc_live_7f8a9b0c1d2e3f4a5b6c7d8e" \
  -H "X-API-Secret: sec_a1b2c3d4e5f6789012345678" \
  -H "Idempotency-Key: ik_ord_98124_abc" \
  -H "Content-Type: application/json" \
  -d '{
    "service": "express_same_day",
    "sender": {
      "name": "Customer Return Service",
      "phone": "+971501234567",
      "address": {
        "line1": "Al Barsha 1",
        "emirate": "Dubai"
      }
    },
    "receiver": {
      "name": "Central Logistics Warehouse",
      "phone": "+97148001234",
      "address": {
        "line1": "Logistics District WH-12",
        "emirate": "Dubai"
      }
    },
    "package": {
      "weight_kg": 1.25,
      "declared_value": 249.00,
      "description": "Electronics Accessories - Return"
    }
  }'
```

### cURL: Track Shipment Status
```bash
curl -X GET "https://rapid-courier.com/api/v1/tracking/RC98124503"
```

### cURL: Download 4x6 Thermal Label
```bash
curl -X GET "https://rapid-courier.com/api/v1/shipments/4812/label/thermal" \
  -H "Authorization: Bearer rc_live_7f8a9b0c1d2e3f4a5b6c7d8e:sec_a1b2c3d4e5f6789012345678"
```

---

## 8. Summary Response Matrix

| Requirement | RC Courier Technical Detail |
| :--- | :--- |
| **Sandbox Base URL** | `https://rapid-courier.com/api/v1` *(with test key `rc_test_...`)* |
| **Production Base URL** | `https://rapid-courier.com/api/v1` *(with live key `rc_live_...`)* |
| **Auth Headers** | `X-API-Key` & `X-API-Secret` or `Authorization: Bearer <key>:<secret>` |
| **Booking Endpoint** | `POST /api/v1/shipments` |
| **Tracking Endpoint** | `GET /api/v1/tracking/{tracking_number}` |
| **Cancel Endpoint** | `POST /api/v1/shipments/{id}/cancel` |
| **Label Endpoint** | `GET /api/v1/shipments/{id}/label/thermal` (4x6 thermal HTML + Code128 SVG) |
| **Invoice Endpoint** | `GET /api/v1/invoices/{id}/download` (5% UAE VAT PDF/HTML) |
| **Webhook Signature** | `X-RC-Signature: t={timestamp},v1={hmac_sha256}` |
| **Default Currency** | AED (United Arab Emirates Dirham) |
