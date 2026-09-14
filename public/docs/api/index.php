<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RC Courier REST API v1 — Developer Portal & Documentation</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
  :root { --bg: #050a12; --panel: #0c1624; --gold: #dca83f; --gold2: #f4cc70; --white: #f8fafc; --muted: #8998ac; --line: rgba(255,255,255,0.09); --blue: #38bdf8; --green: #34d399; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--white); line-height: 1.6; }
  a { color: var(--gold2); text-decoration: none; }
  a:hover { text-decoration: underline; }
  .header { background: rgba(5,10,18,0.9); backdrop-filter: blur(15px); border-bottom: 1px solid var(--line); position: sticky; top: 0; z-index: 50; padding: 1rem 0; }
  .container { width: min(1200px, 92%); margin: auto; }
  .flex-between { display: flex; justify-content: space-between; align-items: center; }
  .brand { font-family: 'Manrope', sans-serif; font-weight: 800; font-size: 1.2rem; display: flex; align-items: center; gap: 10px; }
  .brand span { color: var(--white); }
  .brand small { color: var(--gold); font-size: 0.6rem; letter-spacing: 1px; display: block; }
  .btn { padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; display: inline-block; }
  .btn-gold { background: linear-gradient(135deg, var(--gold2), var(--gold)); color: #10151c; }
  .main-grid { display: grid; grid-template-columns: 260px 1fr; gap: 2rem; padding: 2.5rem 0; }
  .sidebar { background: var(--panel); border: 1px solid var(--line); border-radius: 12px; padding: 1.25rem; position: sticky; top: 90px; height: fit-content; }
  .sidebar h4 { font-size: 0.8rem; text-transform: uppercase; color: var(--gold); letter-spacing: 1px; margin-bottom: 0.75rem; margin-top: 1rem; }
  .sidebar h4:first-child { margin-top: 0; }
  .sidebar a { display: block; color: var(--muted); font-size: 0.85rem; padding: 0.3rem 0; }
  .sidebar a:hover { color: var(--white); }
  .content-card { background: var(--panel); border: 1px solid var(--line); border-radius: 12px; padding: 2rem; margin-bottom: 2rem; }
  .content-card h2 { font-family: 'Manrope', sans-serif; font-size: 1.5rem; margin-bottom: 1rem; color: var(--white); }
  .method-tag { font-weight: 800; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-family: monospace; display: inline-block; margin-right: 8px; }
  .method-post { background: rgba(52, 211, 153, 0.15); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); }
  .method-get { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
  .method-delete { background: rgba(248, 113, 113, 0.15); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.3); }
  pre { background: #070d17; border: 1px solid var(--line); padding: 1rem; border-radius: 8px; overflow-x: auto; font-family: monospace; font-size: 0.85rem; color: var(--gold2); margin: 1rem 0; }
  code { font-family: monospace; background: rgba(255,255,255,0.06); padding: 2px 6px; border-radius: 4px; color: var(--gold2); }
</style>
</head>
<body>

<header class="header">
  <div class="container flex-between">
    <div class="brand">
      <div>
        <span>RC COURIER UAE</span>
        <small>DEVELOPER PLATFORM & REST API V1</small>
      </div>
    </div>
    <div>
      <a href="/docs/api/rc_courier_v1.postman_collection.json" download class="btn btn-gold">Download Postman Collection</a>
      <a href="/docs/api/openapi.yaml" download class="btn" style="background: rgba(255,255,255,0.1); color: #fff; margin-left: 8px;">OpenAPI YAML</a>
    </div>
  </div>
</header>

<div class="container main-grid">
  <aside class="sidebar">
    <h4>Getting Started</h4>
    <a href="#overview">Overview & Base URL</a>
    <a href="#authentication">Authentication</a>
    <a href="#rate-limits">Rate Limits & Idempotency</a>
    <a href="#responses">Standard Response Format</a>

    <h4>Core APIs</h4>
    <a href="#quotes">Quotation API</a>
    <a href="#shipments">Shipments API</a>
    <a href="#tracking">Tracking API</a>
    <a href="#invoices">Invoices API</a>
    <a href="#labels">4x6 Thermal Labels API</a>
    <a href="#webhooks">Webhooks & HMAC Signatures</a>
    <a href="#account">Account API</a>
  </aside>

  <main>
    <div class="content-card" id="overview">
      <h2>API Overview & Base URL</h2>
      <p>The RC Courier REST API allows external websites, e-commerce systems, and business platforms to programmatically book shipments, calculate instant quotations in AED, download 4x6 thermal shipping labels, stream tax invoices, track packages, and receive HMAC-signed webhooks.</p>
      <pre>Base URL: https://rapid-courier.com/api/v1</pre>
    </div>

    <div class="content-card" id="authentication">
      <h2>Authentication</h2>
      <p>Pass your API Key and Secret in request headers. Generate credentials from your <a href="/customer/api-keys">Customer Portal</a>.</p>
      <pre>X-API-Key: rc_live_7f8a9b0c1d2e3f4a5b6c7d8e9f0a1b2c
X-API-Secret: sec_a1b2c3d4e5f678901234567890abcdef
Content-Type: application/json</pre>
    </div>

    <div class="content-card" id="quotes">
      <h2><span class="method-tag method-post">POST</span> /api/v1/quotes</h2>
      <p>Calculate shipping charges, volumetric weight, 5% UAE VAT, and total in AED.</p>
      <pre>{
  "service": "express_same_day",
  "origin_emirate": "Dubai",
  "destination_emirate": "Abu Dhabi",
  "weight_kg": 2.5,
  "declared_value": 150.00
}</pre>
    </div>

    <div class="content-card" id="shipments">
      <h2><span class="method-tag method-post">POST</span> /api/v1/shipments</h2>
      <p>Book a shipment delivery. Pass <code>Idempotency-Key</code> header to prevent duplicate bookings during network retries.</p>
      <pre>Idempotency-Key: ik_98412503_abc</pre>
      <pre>{
  "service": "express_same_day",
  "sender": { "name": "ABC Trading", "phone": "+97148002684" },
  "receiver": { "name": "Fatima Al-Nuaimi", "phone": "+971509876543" },
  "package": { "description": "Electronics", "weight_kg": 1.5, "pieces": 1 }
}</pre>
    </div>

    <div class="content-card" id="labels">
      <h2><span class="method-tag method-get">GET</span> /api/v1/shipments/{id}/label/thermal</h2>
      <p>Stream print-ready 4x6 inch thermal shipping label HTML with high-contrast Code128 vector barcode and QR verification link.</p>
    </div>

    <div class="content-card" id="webhooks">
      <h2>Webhooks & HMAC Signatures</h2>
      <p>Webhooks trigger on status changes (`shipment.created`, `shipment.delivered`, etc.). Every HTTP POST delivery includes an HMAC signature header:</p>
      <pre>X-RC-Signature: t=1788627000,v1=a5b6c7d8...</pre>
    </div>
  </main>
</div>

</body>
</html>
