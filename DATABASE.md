# Database Specification

## General
- MySQL/MariaDB compatible
- InnoDB
- utf8mb4
- UTC timestamps internally where practical
- DECIMAL(12,2) for money
- BIGINT UNSIGNED for major entity IDs
- Foreign keys
- Index every high-frequency lookup

## Core entities

### users
id, role_id, name, email, phone, password_hash, status, last_login_at, created_at, updated_at

### roles
id, name, created_at

### permissions
id, name

### role_permissions
role_id, permission_id

### customers
id, customer_type, company_name, contact_name, email, phone, trn, billing_address_id, status, created_at, updated_at

### customer_addresses
id, customer_id, label, address_line1, address_line2, area, emirate, city, country, latitude, longitude, is_default, created_at, updated_at

### services
id, code, name, description, service_type, active, created_at, updated_at

### service_zones
id, name, emirate, country, zone_code, active

### pricing_rules
id, service_id, origin_zone_id, destination_zone_id, weight_from, weight_to, base_price, per_kg_price, pickup_fee, surcharge, tax_rate, active

### shipments
id, reference_number, tracking_number, customer_id, service_id, origin_address_id, destination_address_id, status, weight_kg, length_cm, width_cm, height_cm, declared_value, subtotal, discount, tax, total, currency, pickup_at, estimated_delivery_at, delivered_at, created_at, updated_at

### shipment_items
id, shipment_id, description, quantity, weight_kg, length_cm, width_cm, height_cm, declared_value

### shipment_status_events
id, shipment_id, status, location_name, public_notes, internal_notes, latitude, longitude, event_time, created_by, created_at

### quotes
id, quote_number, customer_id, status, valid_until, subtotal, discount, tax, total, currency, notes, created_at, updated_at

### quote_items
id, quote_id, description, quantity, unit_price, discount, tax_rate, line_total

### invoices
id, invoice_number, customer_id, shipment_id nullable, status, issue_date, due_date, currency, subtotal, discount, tax, total, amount_paid, balance_due, trn, notes, issued_at, voided_at, created_at, updated_at

### invoice_items
id, invoice_id, description, reference, quantity, unit_price, discount, tax_rate, line_subtotal, line_tax, line_total

### payments
id, payment_number, invoice_id, customer_id, amount, currency, method, reference, status, paid_at, created_by, created_at

### audit_logs
id, actor_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent, created_at

## Important constraints
- unique users.email
- unique shipments.reference_number
- unique shipments.tracking_number
- unique invoices.invoice_number
- unique quotes.quote_number
- unique payments.payment_number
- status values should be controlled by application/domain rules
- prevent negative financial values unless explicitly supported by credit-note logic
