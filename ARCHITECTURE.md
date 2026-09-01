# Architecture

## Recommended pattern

Use a lightweight MVC/service architecture rather than a large framework unless the existing environment already supports one cleanly.

Request:
Browser → Apache → public/index.php → Router → Middleware → Controller → Service → Repository/Model → PDO/MySQL

Response:
Controller → View or JSON response

## Layers

### Controllers
HTTP concerns only:
- parse request
- authorize
- validate
- call service
- return response

### Services
Business logic:
- pricing
- shipment lifecycle
- invoice calculations
- quote conversion
- notifications

### Repositories
Database access:
- prepared SQL
- pagination
- transactions
- mapping rows

### Policies
Authorization:
- canViewShipment()
- canEditShipment()
- canIssueInvoice()
- canVoidInvoice()
- canManagePricing()

### Views
Presentation only. No SQL.

## Transactions

Use database transactions for:
- issuing invoice
- recording payment
- converting quote to shipment
- changing shipment state when multiple records are updated
- creating shipment + initial status event

Rollback on any exception.

## IDs

Use internal BIGINT UNSIGNED IDs.
Use unique public references:
SHP-YYYY-000001
QT-YYYY-000001
INV-YYYY-000001
PAY-YYYY-000001
CN-YYYY-000001

Never use sequential public IDs alone as an authorization mechanism.
