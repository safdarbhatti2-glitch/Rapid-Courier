# Hostinger Deployment

## 1. Create database
In Hostinger hPanel:
- create MySQL database/user
- record DB host, database name, username and password
- import schema/migrations through phpMyAdmin or the project migration mechanism

Hostinger supports MySQL, PDO and pdo_mysql on Web/Cloud hosting.

## 2. PHP
Use a currently supported PHP version compatible with the project. Verify extensions required by the chosen PDF/mail libraries.

## 3. Files
Recommended:
public web root → /public

If the account requires public_html:
- place public-facing files in public_html
- keep /app, /config, /storage and secrets outside the web-accessible directory where possible

## 4. Configuration
Create secure environment configuration:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.ae

DB_HOST=
DB_NAME=
DB_USER=
DB_PASS=

MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM=
MAIL_FROM_NAME=

COMPANY_NAME=
COMPANY_EMAIL=
COMPANY_PHONE=
COMPANY_TRN=
DEFAULT_CURRENCY=AED
TIMEZONE=Asia/Dubai

Never commit production secrets.

## 5. HTTPS
Enable SSL and force HTTPS.

## 6. Cron
Use Hostinger Cron Jobs for scheduled tasks such as:
- quote expiry
- overdue invoice marking
- notification queue cleanup
- daily reports
- database maintenance

Do not depend on MySQL Event Scheduler on shared hosting.

## 7. Backups
Maintain:
- database backups
- uploaded documents backups
- configuration backup without secrets
- tested restore procedure

## 8. Production checklist
- [ ] APP_DEBUG=false
- [ ] HTTPS active
- [ ] secure cookies
- [ ] database credentials protected
- [ ] upload directory protected
- [ ] error pages configured
- [ ] cron jobs tested
- [ ] backup tested
- [ ] admin account secured
- [ ] demo data removed
