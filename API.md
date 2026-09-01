# Internal API Contract

All JSON endpoints return:

{
  "success": true,
  "message": "Human readable message",
  "data": {},
  "errors": []
}

Failure:

{
  "success": false,
  "message": "Unable to complete request",
  "data": null,
  "errors": [
    {"field": "email", "message": "Invalid email"}
  ]
}

## Tracking
GET /api/tracking/{trackingNumber}

Return only:
tracking number
status
service
origin
destination
estimated delivery
safe timeline

## Invoice
POST /api/invoices

Server calculates all totals.

POST /api/invoices/{id}/issue

Requires finance/admin permission.

POST /api/invoices/{id}/payments

Requires finance permission.

## Shipment
POST /api/shipments
POST /api/shipments/{id}/status

Status transition must be validated against allowed domain transitions.

## Security
- authenticate private endpoints
- CSRF for cookie-authenticated browser requests
- rate limit public endpoints
- validate Content-Type
- limit request size
- do not expose stack traces
