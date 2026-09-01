<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--ink:#16324a;--muted:#61778b;--line:#d8e4ec;--canvas:#edf3f7;--blue:#087fbd;--green:#087f55;--gold:#d69b20}
*{box-sizing:border-box}
body{margin:0;padding:40px 16px;background:var(--canvas);color:var(--ink);font-family:Inter,sans-serif;font-size:14px}
.container{max-width:640px;margin:0 auto;background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:0 20px 60px rgba(22,50,74,.10);overflow:hidden}
.topline{height:6px;background:linear-gradient(90deg,var(--green),var(--blue),var(--gold))}
.header{padding:28px 32px 20px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center}
.logo-row{display:flex;align-items:center;gap:12px}
.logo{width:44px;height:44px;border:1px solid var(--line);border-radius:12px;display:grid;place-items:center;font-weight:900;color:var(--gold);background:#f8fbfd}
.brand h1{font-size:18px;margin:0}.brand small{color:var(--blue);font-weight:800;font-size:10px}
.verified-badge{padding:8px 14px;border-radius:999px;background:#f0fdf4;border:1px solid #bbf7d0;color:var(--green);font-weight:900;font-size:11px;display:inline-flex;align-items:center;gap:6px}
.body{padding:32px}
.hero-status{background:#f8fcfe;border:1px solid #c6dce9;border-radius:15px;padding:20px;margin-bottom:24px;text-align:center}
.hero-status h2{margin:0 0 4px;font-size:22px;color:var(--ink)}
.hero-status p{margin:0;color:var(--muted);font-size:12.5px}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:24px}
.info-box{border:1px solid var(--line);border-radius:12px;padding:14px;background:#fcfdfe}
.info-box small{display:block;color:var(--muted);font-size:10px;text-transform:uppercase;letter-spacing:1px;font-weight:800;margin-bottom:4px}
.info-box b{font-size:14px;color:var(--ink);display:block;word-break:break-all}
.footer{padding:20px 32px;background:#f8fafc;border-top:1px solid var(--line);font-size:11px;color:var(--muted);text-align:center}
.footer strong{color:var(--ink)}
</style>
</head>
<body>

<main class="container">
  <div class="topline"></div>
  <header class="header">
    <div class="logo-row">
      <div class="logo" style="padding:0; border:none; background:transparent;"><img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:44px; width:44px; border-radius:10px; object-fit:cover;"></div>
      <div class="brand">
        <h1>RC COURIER</h1>
        <small>UAE • GCC LOGISTICS</small>
      </div>
    </div>
    <div class="verified-badge">✓ VERIFIED AUTHENTIC TAX INVOICE</div>
  </header>

  <section class="body">
    <div class="hero-status">
      <h2>Tax Invoice Validated</h2>
      <p>This invoice is an official registered tax document issued by <strong>RC Courier UAE LLC</strong> under Tax Registration Number <strong>100987654321003</strong>.</p>
    </div>

    <div class="grid">
      <div class="info-box">
        <small>INVOICE NUMBER</small>
        <b><?= e($invoice['invoice_number']) ?></b>
      </div>
      <div class="info-box">
        <small>TRACKING ID</small>
        <b style="color:var(--blue);"><?= e($invoice['tracking_number'] ?: ($invoice['reference_number'] ?: 'N/A')) ?></b>
      </div>
      <div class="info-box">
        <small>ISSUE DATE</small>
        <b><?= e(date('d M Y', strtotime($invoice['issue_date']))) ?></b>
      </div>
      <div class="info-box">
        <small>COURIER / TRN</small>
        <b>RC Courier UAE LLC (100987654321003)</b>
      </div>
      <div class="info-box">
        <small>CUSTOMER / RECIPIENT</small>
        <b><?= e($invoice['sender_name'] ?: ($invoice['contact_name'] ?: $invoice['company_name'])) ?></b>
      </div>
      <div class="info-box">
        <small>LOGISTICS ROUTE</small>
        <b><?= e($invoice['origin_emirate'] ?: 'Dubai') ?> → <?= e($invoice['dest_emirate'] ?: 'Abu Dhabi') ?></b>
      </div>
      <div class="info-box">
        <small>GRAND TOTAL (AED)</small>
        <b style="color:var(--gold);"><?= e(number_format($invoice['total'], 2)) ?> AED</b>
      </div>
      <div class="info-box">
        <small>PAYMENT STATUS</small>
        <b style="color:var(--green);"><?= e($invoice['invoice_status']) ?> (Paid: <?= e(number_format($invoice['amount_paid'], 2)) ?> AED)</b>
      </div>
    </div>
  </section>

  <footer class="footer">
    <strong>RC Courier UAE LLC</strong> · Public Invoice Verification Portal · Support: <strong>800-RC-COURIER</strong>
  </footer>
</main>

</body>
</html>
