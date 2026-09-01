# QA Test Plan

## Authentication
- [ ] valid login
- [ ] invalid login
- [ ] brute force protection
- [ ] logout
- [ ] password reset
- [ ] session timeout
- [ ] role restrictions

## Booking
- [ ] valid shipment
- [ ] invalid phone
- [ ] invalid email
- [ ] invalid weight
- [ ] missing receiver
- [ ] duplicate submission
- [ ] price recalculation

## Tracking
- [ ] valid tracking
- [ ] unknown tracking
- [ ] status event creation
- [ ] unauthorized status update
- [ ] public/private notes separation
- [ ] AJAX refresh

## Quotes
- [ ] create
- [ ] calculate
- [ ] send
- [ ] accept
- [ ] reject
- [ ] expire
- [ ] convert to shipment
- [ ] convert to invoice

## Invoices
- [ ] draft
- [ ] edit draft
- [ ] issue
- [ ] print
- [ ] PDF
- [ ] payment
- [ ] partial payment
- [ ] paid
- [ ] overdue
- [ ] void
- [ ] audit trail

## Security
- [ ] SQL injection tests
- [ ] XSS tests
- [ ] CSRF tests
- [ ] IDOR/object authorization tests
- [ ] upload abuse tests
- [ ] session fixation tests
- [ ] rate limiting tests

## Performance
- [ ] indexed tracking lookup
- [ ] paginated admin lists
- [ ] no N+1 queries
- [ ] optimized assets
- [ ] mobile page performance
