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
:root{--ink:#16324a;--muted:#61778b;--line:#d8e4ec;--canvas:#edf3f7;--red:#c92e3a;--gold:#d69b20}
*{box-sizing:border-box}
body{margin:0;padding:40px 16px;background:var(--canvas);color:var(--ink);font-family:Inter,sans-serif;font-size:14px}
.container{max-width:580px;margin:0 auto;background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:0 20px 60px rgba(22,50,74,.10);overflow:hidden}
.topline{height:6px;background:var(--red)}
.header{padding:28px 32px 20px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center}
.logo-row{display:flex;align-items:center;gap:12px}
.logo{width:44px;height:44px;border:1px solid var(--line);border-radius:12px;display:grid;place-items:center;font-weight:900;color:var(--gold);background:#f8fbfd}
.brand h1{font-size:18px;margin:0}.brand small{color:var(--red);font-weight:800;font-size:10px}
.invalid-badge{padding:8px 14px;border-radius:999px;background:#fef2f2;border:1px solid #fecaca;color:var(--red);font-weight:900;font-size:11px;display:inline-flex;align-items:center;gap:6px}
.body{padding:40px 32px;text-align:center}
.icon-warn{width:64px;height:64px;background:#fef2f2;border-radius:50%;display:grid;place-items:center;margin:0 auto 20px;color:var(--red);font-size:32px;font-weight:900}
.body h2{margin:0 0 10px;font-size:24px;color:var(--ink)}
.body p{margin:0 0 20px;color:var(--muted);font-size:13.5px;line-height:1.6}
.code-box{background:#f8fafc;border:1px solid var(--line);border-radius:10px;padding:12px;font-family:monospace;font-size:13px;color:var(--red);display:inline-block;margin-bottom:24px}
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
    <div class="invalid-badge">⚠ INVOICE NOT FOUND / INVALID</div>
  </header>

  <section class="body">
    <div class="icon-warn">✕</div>
    <h2>Invalid Tax Document</h2>
    <p>The requested invoice number does not exist in the official RC Courier database or has failed verification requirements.</p>

    <div class="code-box">Searched Invoice #: <?= e($invoice_number) ?></div>

    <p style="font-size:12px;color:var(--muted);">If you believe this is an error, please contact RC Courier support at <strong>support@rccourier.ae</strong> or call <strong>+971 4 800 2684</strong>.</p>
  </section>

  <footer class="footer">
    <strong>RC Courier UAE LLC</strong> · Public Invoice Verification Portal · Support: <strong>800-RC-COURIER</strong>
  </footer>
</main>

</body>
</html>
