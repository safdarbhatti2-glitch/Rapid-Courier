# Security Checklist

## Authentication
- [ ] password_hash/password_verify
- [ ] session_regenerate_id after login
- [ ] secure session cookie
- [ ] login rate limiting
- [ ] password reset token expiry
- [ ] generic login failure messages
- [ ] account deactivation

## Authorization
- [ ] role middleware
- [ ] object-level authorization
- [ ] never trust customer_id/user_id from POST
- [ ] protect admin routes
- [ ] protect document downloads

## Input
- [ ] PDO prepared statements
- [ ] server-side validation
- [ ] allowlists for status/method fields
- [ ] length limits
- [ ] numeric bounds
- [ ] upload MIME/size validation

## Output
- [ ] htmlspecialchars or equivalent contextual escaping
- [ ] safe JSON encoding
- [ ] CSP-compatible scripts where practical

## CSRF
All POST/PUT/PATCH/DELETE browser actions require CSRF protection.

## Headers
Configure where compatible:
- Strict-Transport-Security
- X-Content-Type-Options: nosniff
- Referrer-Policy
- Content-Security-Policy
- Permissions-Policy
- frame-ancestors via CSP

## Files
- [ ] private storage outside public directory
- [ ] random filenames
- [ ] block script execution in uploads
- [ ] authorize every download
- [ ] file size limits
- [ ] image re-encoding where required

## Financial integrity
- [ ] server-side calculations
- [ ] transaction boundaries
- [ ] immutable issued invoice data
- [ ] audit logs
- [ ] no direct client-side total trust
