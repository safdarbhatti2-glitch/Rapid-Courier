# MASTER PROMPT FOR ANTIGRAVITY

Build a complete, production-ready UAE courier and logistics management website using PHP + MySQL, deployable on Hostinger Business shared hosting.

## 1. Product goal

Create an original UAE logistics platform with the polished information architecture of a modern courier website. The public experience should communicate:
- Fast UAE delivery
- Same-day and next-day services
- GCC and international shipping
- Freight/cargo
- Business/e-commerce logistics
- Shipment tracking
- Instant quotation
- Online booking

The internal system must go beyond the reference marketing site and provide:
- Customer accounts
- Shipment management
- Tracking/status history
- Quotation management
- Invoice creation and management
- Customer balances/payments
- Admin/user roles
- Pricing rules
- Service areas
- Branches/locations
- Reports
- Audit logs

Use the Gulf Express site only for feature/flow inspiration. Do not copy its branding, proprietary content, source code, exact graphics, or text.

## 2. Hosting constraints

The production deployment target is Hostinger Business shared hosting.

Design for:
- PHP
- MySQL
- Apache
- HTTPS
- .htaccess
- Cron jobs where scheduled tasks are needed
- PDO/pdo_mysql
- MySQL/InnoDB
- PHP sessions

Avoid infrastructure that normally requires VPS/root access. Hostinger documents that PHP PDO/pdo_mysql and MySQL are supported, and that Web/Cloud hosting does not provide MySQL Event Scheduler; use cron jobs for scheduled work.

Do not require:
- Node.js in production
- Docker
- Redis
- MongoDB
- PostgreSQL
- Supervisor
- long-running workers
- websocket servers

For live tracking, implement AJAX polling as the default shared-hosting-safe solution. If a future VPS version is required, design the tracking service so it can later be upgraded without rewriting the domain model.

## 3. Technology architecture

Preferred structure:

/app
  /Controllers
  /Models
  /Services
  /Repositories
  /Middleware
  /Validation
  /Helpers
  /Policies
  /Notifications
/config
/database
  /migrations
  /seeders
/public
  /assets
  /uploads-public
/routes
/storage
  /logs
  /private
  /cache
  /exports
/views
  /layouts
  /components
  /public
  /auth
  /customer
  /admin
/cron
/tests
/docs

Use a front controller where practical:
public/index.php

Use clean URLs:
/
 /about
 /services
 /services/same-day
 /services/next-day
 /services/gcc
 /services/international
 /services/freight
 /track
 /quote
 /book
 /contact
 /login
 /register
 /customer
 /admin

## 4. Public website frontend

Create a premium, trustworthy UAE logistics visual system.

Header:
- Logo
- Services
- Track Shipment
- Get a Quote
- Book Shipment
- Locations
- About
- Contact
- Login
- Mobile menu

Homepage sections:
1. Hero with strong UAE logistics proposition
2. CTA buttons: Book Shipment / Get Quote / Track
3. Service cards
4. Shipment tracking preview
5. UAE coverage / Emirates section
6. Why choose us
7. Process: Book → Pickup → Transit → Delivery
8. Business/e-commerce logistics section
9. Testimonials using fictional/demo data
10. CTA
11. Footer

Responsive requirements:
- Mobile-first
- Excellent 360px–430px phone layouts
- Tablet
- Desktop
- Accessible keyboard navigation
- Visible focus states
- Semantic HTML
- Good contrast
- No layout shift caused by images

Use original CSS variables and components. Do not reproduce the reference site's exact styling.

## 5. UAE-specific UX

Support:
- UAE Emirates: Dubai, Abu Dhabi, Sharjah, Ajman, Ras Al Khaimah, Fujairah, Umm Al Quwain
- UAE phone validation (+971)
- AED currency
- Asia/Dubai timezone
- Arabic/English-ready architecture
- RTL-ready CSS structure
- UAE address fields
- Google Maps integration must be optional/configurable, not hard-coded
- Optional WhatsApp contact CTA
- VAT-ready invoice fields

Do not claim regulatory/tax compliance unless explicitly configured and verified. Include configurable TRN/VAT fields and tax rates rather than hard-coding legal assumptions.

## 6. Authentication

Roles:
- super_admin
- admin
- operations_manager
- dispatcher
- finance
- sales
- customer

Features:
- Login
- Logout
- Registration
- Forgot password
- Password reset tokens
- Change password
- Account lock/rate limiting
- Session timeout
- Role-based permissions

Customer portal:
- Dashboard
- Profile
- Addresses
- Shipments
- Track shipment
- Quotes
- Invoices
- Payment status
- Download documents
- Support/contact

## 7. Shipment booking system

Booking form:
Sender:
- name
- company
- phone
- email
- address
- emirate
- area
- building
- landmark

Receiver:
- name
- company
- phone
- email
- address
- country
- emirate/city
- postal/ZIP if applicable

Shipment:
- service
- package type
- quantity
- weight
- dimensions
- declared value
- contents description
- pickup date/time
- delivery speed
- pickup required
- special instructions

Pricing:
- base rate
- weight charge
- dimensional-weight charge
- distance/zone charge
- service surcharge
- pickup fee
- insurance/optional fee
- discount
- VAT/tax
- grand total

After booking:
- Generate shipment number such as SHP-2026-000001
- Create initial status BOOKED
- Generate tracking timeline
- Send confirmation
- Optionally create invoice automatically based on configuration

## 8. Tracking management

Tracking page:
- tracking number search
- current status
- origin
- destination
- estimated delivery
- service
- shipment date
- package summary
- timeline

Statuses:
BOOKED
CONFIRMED
PICKUP_ASSIGNED
PICKED_UP
AT_ORIGIN_HUB
IN_TRANSIT
AT_DESTINATION_HUB
OUT_FOR_DELIVERY
DELIVERY_ATTEMPTED
DELIVERED
CANCELLED
ON_HOLD
RETURNED

Each status event stores:
- shipment_id
- status
- location
- notes
- public_notes
- event_time
- created_by
- optional latitude/longitude
- proof/document reference if applicable

Public tracking should expose only safe fields. Never expose internal notes, employee data or sensitive customer information.

Implement:
- public tracking
- customer tracking
- admin tracking
- status update form
- bulk status update where safe
- tracking event audit log
- estimated delivery calculation
- AJAX refresh every configurable interval

## 9. Invoice creation system — FRONTEND

Admin invoice screen:
- New Invoice
- Select customer
- Select shipment(s)
- Invoice date
- Due date
- Currency
- VAT/Tax mode
- Payment terms
- Notes
- Line items
- Discount
- Tax
- Total

Line item columns:
- description
- SKU/reference
- quantity
- unit
- unit price
- discount
- tax rate
- line subtotal
- line tax
- line total

Actions:
- Save Draft
- Preview
- Issue Invoice
- Mark Sent
- Mark Partially Paid
- Mark Paid
- Void
- Duplicate as Draft
- Download PDF
- Print
- Email

Invoice statuses:
DRAFT
ISSUED
SENT
PARTIALLY_PAID
PAID
OVERDUE
VOID

Never allow a normal user to edit financial totals after an invoice is issued. Use credit-note/adjustment workflows for corrections if implemented.

Invoice number format:
INV-2026-000001

Credit note:
CN-2026-000001

Payment:
PAY-2026-000001

## 10. Invoice backend

Tables:
customers
customer_addresses
invoices
invoice_items
invoice_taxes
payments
credit_notes
credit_note_items
invoice_audit_logs

Business rules:
- subtotal = sum(line totals before tax)
- discount handled explicitly
- taxable subtotal calculated server-side
- tax calculated server-side
- total = subtotal - discounts + taxes + fees
- never trust browser-calculated totals
- use DECIMAL(12,2)
- use database transactions for issuing invoices and recording payments
- maintain immutable issued invoice snapshots
- store PDF path/hash if PDFs are generated
- record who issued/voided/paid the invoice

PDF:
Use a PHP-compatible PDF library such as Dompdf or TCPDF only if compatible with the deployment. Keep a print-friendly HTML invoice fallback.

Invoice design:
- Company logo
- Company name/contact
- TRN field if configured
- Invoice number/date
- Customer billing details
- Shipment references
- Line items
- subtotal
- discount
- VAT/tax
- total AED
- payment status
- notes/terms
- footer

## 11. Quotation system — FRONTEND

Public quote form:
- name
- company
- email
- phone
- origin
- destination
- shipment type
- weight
- dimensions
- service speed
- pickup required
- notes

After submission:
- Generate quote reference QT-2026-000001
- Calculate estimated price
- Save lead/customer information
- Show quote summary
- Email quote if configured

Admin quotation:
- Create
- Edit draft
- Add customer
- Add shipment assumptions
- Add line items
- Apply discounts
- Apply tax
- Add terms
- Set validity date
- Send
- Approve
- Reject
- Expire
- Convert to shipment
- Convert to invoice

Quote statuses:
DRAFT
SENT
VIEWED
ACCEPTED
REJECTED
EXPIRED
CONVERTED

Do not let a public user manipulate pricing. Server-side pricing rules determine the amount.

## 12. Pricing engine

Create configurable pricing tables.

Pricing inputs:
- origin zone
- destination zone
- service
- weight bracket
- dimensional weight
- package type
- customer type
- pickup fee
- fuel surcharge
- remote area surcharge
- insurance
- discount
- VAT

Allow customer-specific pricing overrides.

Store pricing versions with effective_from/effective_to so historical shipments remain explainable.

## 13. Admin dashboard

Dashboard cards:
- Shipments today
- Pending pickup
- In transit
- Out for delivery
- Delivered today
- Failed attempts
- Revenue today
- Outstanding invoices
- Quotes awaiting action

Charts:
- shipments by status
- revenue trend
- service mix
- Emirates distribution
- payment aging

Admin menus:
Dashboard
Shipments
Tracking
Customers
Quotes
Invoices
Payments
Services
Pricing
Locations
Users
Reports
Audit Logs
Settings

## 14. Shipment management backend

Admin shipment list:
- reference
- tracking number
- customer
- origin
- destination
- service
- status
- assigned driver
- pickup date
- estimated delivery
- amount
- payment status

Filters:
- status
- date
- customer
- emirate
- service
- payment status

Actions:
- view
- edit where permitted
- assign driver
- update status
- add tracking event
- print label
- create invoice
- cancel
- return

Use server-side pagination and indexed filters.

## 15. Drivers / operations

Optional first-class driver module:
- driver profile
- phone
- vehicle
- status
- assigned shipments
- pickup queue
- delivery queue

Driver-facing page can be a lightweight responsive web interface.

Driver actions:
- accept assignment
- pickup
- mark in transit
- out for delivery
- delivery attempt
- delivered
- upload proof of delivery

Proof of delivery:
- recipient name
- signature image
- photo
- timestamp
- optional GPS
- notes

Store uploads privately and authorize every download.

## 16. Database design

Use InnoDB and utf8mb4.

Minimum tables:
users
roles
permissions
role_permissions
customers
customer_addresses
services
service_zones
locations
pricing_rules
pricing_rule_versions
shipments
shipment_items
shipment_status_events
shipment_assignments
drivers
vehicles
quotes
quote_items
invoices
invoice_items
payments
credit_notes
credit_note_items
documents
notifications
audit_logs
settings
contact_messages

Add indexes for:
- users.email
- customers.email
- shipments.tracking_number
- shipments.reference_number
- shipments.customer_id
- shipments.status
- shipments.created_at
- shipment_status_events.shipment_id
- invoices.invoice_number
- invoices.customer_id
- invoices.status
- invoices.due_date
- quotes.quote_number
- quotes.customer_id

Use foreign keys where safe and useful.

## 17. Security

Implement:
- prepared statements
- CSRF
- XSS prevention
- authorization policies
- rate limiting for login/tracking/contact endpoints
- secure session cookies
- SameSite=Lax or stricter where compatible
- HttpOnly
- Secure under HTTPS
- password_hash/password_verify
- password reset expiry
- upload MIME/type/size validation
- randomized private filenames
- block script execution in upload directories
- security headers
- HTTPS redirect
- no directory listing
- no stack traces in production
- generic public errors
- detailed private server logs
- audit logs for privileged actions
- login attempt logging
- brute-force protection

Never put secrets in source control.

## 18. Performance

Optimize for shared hosting:
- server-side pagination
- indexed queries
- avoid N+1 queries
- select only required columns
- cache stable settings
- minify production CSS/JS where practical
- lazy-load images
- WebP/AVIF assets where supported
- browser caching
- gzip/brotli if available
- OPcache-friendly PHP
- avoid huge framework overhead

Do not run heavy reports on every page load.

## 19. Email

Build an email abstraction:
sendPasswordReset()
sendQuote()
sendInvoice()
sendShipmentBooked()
sendShipmentStatusUpdate()
sendContactAcknowledgement()

Prefer SMTP when configured.

Configuration:
MAIL_HOST
MAIL_PORT
MAIL_USERNAME
MAIL_PASSWORD
MAIL_FROM
MAIL_FROM_NAME

Do not rely on arbitrary server mail() behavior for critical business communication.

## 20. PDF and documents

Support:
- invoice PDF
- quote PDF
- shipment label
- proof of delivery
- export CSV

Private documents must not be directly accessible by guessing URLs.

Use controlled download endpoints:
GET /documents/{id}/download

Authorize every download.

## 21. API-style endpoints

Create internal JSON endpoints for AJAX:
POST /api/auth/login
POST /api/quotes
GET /api/quotes/{id}
POST /api/shipments
GET /api/shipments/{id}
POST /api/shipments/{id}/status
GET /api/tracking/{trackingNumber}
POST /api/invoices
GET /api/invoices/{id}
POST /api/invoices/{id}/issue
POST /api/invoices/{id}/payments
GET /api/dashboard/summary

Use consistent JSON:
{
  "success": true,
  "message": "...",
  "data": {},
  "errors": []
}

Never expose internal exception details.

## 22. Reports

Reports:
- shipment report
- revenue report
- invoice aging
- payments
- quotes
- customer activity
- service performance
- delivery performance
- Emirates performance

Filters:
- date from/to
- customer
- service
- status
- Emirates
- payment status

Export CSV with safe CSV escaping. Avoid spreadsheet formula injection by prefixing dangerous leading characters where appropriate.

## 23. Audit trail

Audit:
- login/logout
- user creation/update/deactivation
- shipment creation/update/status
- quote creation/status
- invoice creation/issue/void
- payment creation/refund
- pricing changes
- settings changes
- document access where sensitive

Store:
actor_id
action
entity_type
entity_id
old_values JSON
new_values JSON
IP
user_agent
created_at

## 24. UI quality

Use:
- reusable cards
- tables with mobile responsive behavior
- modal confirmations
- toast notifications
- validation messages
- empty states
- skeleton/loading states where useful
- confirmation before destructive actions
- clear status badges
- accessible forms
- consistent spacing and typography

Admin UI should feel like a professional logistics SaaS.

## 25. SEO

Public pages:
- unique title
- meta description
- canonical
- Open Graph
- Twitter/X card
- semantic headings
- sitemap.xml
- robots.txt
- structured data where appropriate

Target UAE logistics keywords naturally. Do not keyword stuff.

## 26. Deployment

Provide:
- database creation instructions
- config setup
- .env or secure config outside public webroot where possible
- PHP version requirement
- required PHP extensions
- writable directories
- cron configuration
- HTTPS configuration
- backups
- migration instructions
- rollback procedure

Hostinger Business supports PHP/MySQL and cron/SSH-related capabilities depending on plan/configuration. Verify exact account settings before deployment.

## 27. Testing

Create test checklist for:
Authentication
Authorization
CSRF
Shipment lifecycle
Tracking
Quote calculation
Quote conversion
Invoice calculation
Invoice issuing
Payments
PDF/print
Uploads
Document permissions
Reports
Mobile UI
SQL injection
XSS
Session security
Rate limiting
Error handling

Test edge cases:
- zero/negative quantities
- excessive weights
- invalid phone numbers
- invalid emails
- expired quotes
- void invoices
- duplicate submissions
- simultaneous invoice/payment operations
- unauthorized IDs
- direct URL access
- malformed JSON
- oversized uploads

## 28. Seed data

Create clearly fictional UAE demo data:
- demo admin
- demo finance user
- demo operations user
- demo customer
- sample shipments
- sample tracking events
- sample quote
- sample invoice

Never use real personal information.

## 29. Final deliverables

Antigravity must leave the repository with:
- working source code
- database migrations/schema
- seed data
- README
- deployment guide
- security checklist
- test checklist
- environment example
- cron instructions
- API documentation
- database documentation
- backup/restore guide
- changelog

At the end, provide:
1. files created
2. database tables
3. routes
4. admin credentials for local/demo only
5. deployment steps
6. known limitations
7. next recommended improvements

Do not claim features are complete if they are only mocked.
