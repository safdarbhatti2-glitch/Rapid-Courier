# Route Map

## Public
GET /
GET /about
GET /services
GET /services/{slug}
GET /locations
GET /track
GET /quote
POST /quote
GET /book
POST /book
GET /contact
POST /contact

## Auth
GET /login
POST /login
POST /logout
GET /register
POST /register
GET /forgot-password
POST /forgot-password
GET /reset-password
POST /reset-password

## Customer
GET /customer
GET /customer/shipments
GET /customer/shipments/{id}
GET /customer/quotes
GET /customer/quotes/{id}
GET /customer/invoices
GET /customer/invoices/{id}
GET /customer/profile
POST /customer/profile

## Admin
GET /admin
GET /admin/shipments
GET /admin/shipments/{id}
POST /admin/shipments
POST /admin/shipments/{id}/status
GET /admin/tracking
GET /admin/customers
GET /admin/customers/{id}
GET /admin/quotes
GET /admin/quotes/{id}
POST /admin/quotes
POST /admin/quotes/{id}/send
POST /admin/quotes/{id}/convert
GET /admin/invoices
GET /admin/invoices/{id}
POST /admin/invoices
POST /admin/invoices/{id}/issue
POST /admin/invoices/{id}/void
POST /admin/invoices/{id}/payments
GET /admin/reports
GET /admin/settings
GET /admin/audit-logs

## Documents
GET /documents/{id}/download
GET /invoices/{id}/pdf
GET /quotes/{id}/pdf
GET /shipments/{id}/label
