<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title ?? 'RC Courier — Company Details & System Settings') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{
  --navy:#0b1630;
  --navy-2:#111f3d;
  --gold:#dcae3d;
  --gold-2:#f3ca67;
  --blue:#168bd1;
  --ink:#10203a;
  --muted:#63738b;
  --line:#dce5ef;
  --surface:#ffffff;
  --soft:#f4f8fc;
  --success:#159a67;
  --danger:#d83c45;
  --shadow:0 18px 55px rgba(16,32,58,.12);
}
*{box-sizing:border-box}
body{
  margin:0;
  font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
  background:linear-gradient(135deg,#eef4fa 0%,#f9fbfd 52%,#edf3f9 100%);
  color:var(--ink);
  font-size:16px;
}
button,input,textarea,select{font:inherit}
.app{display:flex;min-height:100vh}
.sidebar{
  width:250px;flex:0 0 250px;background:var(--navy);color:#fff;
  padding:26px 18px;position:sticky;top:0;height:100vh;
  box-shadow:10px 0 35px rgba(5,15,35,.12)
}
.brand{display:flex;align-items:center;gap:8px;font-weight:900;font-size:19px;letter-spacing:-.4px;margin:0 2px 38px}
.brand b{color:#ef3d46}.brand span{color:#fff}
.brand-mark{
  width:34px;height:34px;border:1px solid rgba(220,174,61,.7);border-radius:10px;
  display:grid;place-items:center;color:var(--gold-2);font-size:13px;margin-right:3px
}
.nav{display:grid;gap:7px}
.nav a{
  color:#b9c9df;text-decoration:none;padding:13px 12px;border-radius:10px;
  transition:.2s;font-weight:600
}
.nav a:hover,.nav a.active{background:rgba(255,255,255,.08);color:#fff}
.nav a.active{box-shadow:inset 3px 0 var(--gold-2)}
.sidebar-bottom{position:absolute;left:18px;right:18px;bottom:24px;border-top:1px solid rgba(255,255,255,.12);padding-top:18px}
.signout{width:100%;background:transparent;color:#ff858a;border:1px solid #46546e;border-radius:10px;padding:12px;font-weight:800;cursor:pointer}

main{flex:1;min-width:0;padding:28px 34px 45px}
.topbar{
  background:rgba(255,255,255,.92);border:1px solid var(--line);border-radius:12px;
  min-height:62px;padding:15px 20px;display:flex;align-items:center;justify-content:space-between;
  box-shadow:0 8px 30px rgba(22,48,78,.05);margin-bottom:30px
}
.top-title{font-size:17px;font-weight:850}
.user{color:var(--muted);font-size:14px}.user strong{color:var(--ink)}
.page-head{display:flex;justify-content:space-between;align-items:end;gap:20px;margin-bottom:20px}
h1{margin:0;font-size:30px;letter-spacing:-.8px}
.subtitle{margin:7px 0 0;color:var(--muted);font-size:15px}
.status{
  display:inline-flex;align-items:center;gap:8px;background:#ecfbf5;color:#08774d;
  border:1px solid #c8efdf;border-radius:999px;padding:9px 13px;font-weight:800;font-size:13px
}
.dot{width:8px;height:8px;border-radius:50%;background:#18a36d;box-shadow:0 0 0 4px #d8f5e9}

.card{
  background:var(--surface);border:1px solid var(--line);border-radius:18px;
  box-shadow:var(--shadow);overflow:hidden;margin-bottom:22px
}
.card-head{
  padding:22px 24px;border-bottom:1px solid #e8eef5;display:flex;justify-content:space-between;align-items:center
}
.card-title{font-size:18px;font-weight:850;display:flex;gap:10px;align-items:center}
.icon{
  width:36px;height:36px;border-radius:10px;display:grid;place-items:center;
  background:#fff7df;color:#9a6c06;border:1px solid #f1dfaa
}
.card-body{padding:26px 24px}
.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:22px}
.field{display:grid;gap:8px}
.field.full{grid-column:1/-1}
label{font-weight:750;font-size:13px;color:#40536d}
.required{color:#d13e45}
input,textarea,select{
  width:100%;border:1px solid #cfdbe8;background:#fff;color:var(--ink);
  border-radius:12px;padding:14px 15px;outline:none;transition:.2s;
  box-shadow:0 2px 5px rgba(19,43,71,.025)
}
input:focus,textarea:focus,select:focus{border-color:#d3a53c;box-shadow:0 0 0 4px rgba(220,174,61,.14)}
textarea{min-height:110px;resize:vertical}
.help{font-size:12px;color:#7a899e}
.input-prefix{position:relative}.input-prefix span{
  position:absolute;left:15px;top:50%;transform:translateY(-50%);font-weight:800;color:#77869a
}.input-prefix input{padding-left:53px}

.divider{height:1px;background:#e7edf4;margin:30px 0}
.section-label{
  font-size:13px;text-transform:uppercase;letter-spacing:1.1px;font-weight:900;color:#718199;
  margin:0 0 16px
}
.financial-grid{display:grid;grid-template-columns:1fr 1fr;gap:22px}
.savebar{
  display:flex;align-items:center;justify-content:space-between;gap:15px;
  padding:18px 24px;background:#f7faff;border-top:1px solid #e5edf5
}
.live-note{
  background:linear-gradient(135deg,#fff 0%,#f8fbff 100%);
  border:1px solid #dbe6f1;border-left:4px solid var(--gold);padding:22px 24px;border-radius:18px;
  box-shadow:0 12px 35px rgba(16,32,58,.08)
}
.live-note h3{margin:0 0 8px;font-size:16px}.live-note p{margin:0 0 10px;color:var(--muted)}
.live-note ul{margin:0;padding-left:19px;color:#334965;line-height:1.9}
.actions{display:flex;gap:10px}
.btn{
  border:0;border-radius:11px;padding:13px 18px;font-weight:850;cursor:pointer;
  transition:transform .15s,box-shadow .15s
}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:linear-gradient(135deg,#f1c45e,#dcae3d);color:#111b2b;box-shadow:0 9px 22px rgba(220,174,61,.23)}
.btn-secondary{background:#fff;border:1px solid #ccd8e6;color:#263a56}
.alert{padding:12px 15px;border-radius:11px;font-size:13px;margin-bottom:20px;font-weight:600}
.alert-success{background:#eefbf5;color:#159367;border:1px solid #c3f1de}
.alert-error{background:#fdf2f2;color:#d93838;border:1px solid #f8d7d7}
@media(max-width:900px){
  .sidebar{width:210px;flex-basis:210px}.grid,.financial-grid{grid-template-columns:1fr}
  main{padding:20px}.page-head{align-items:flex-start;flex-direction:column}
}
@media(max-width:700px){
  .app{display:block}.sidebar{position:relative;width:100%;height:auto}.nav{grid-template-columns:repeat(2,1fr)}
  .sidebar-bottom{position:static;margin-top:20px}.topbar{align-items:flex-start;gap:10px;flex-direction:column}
  h1{font-size:25px}
}
</style>
</head>
<body>
<div class="app">
  <aside class="sidebar">
    <div class="brand"><img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:36px; width:36px; border-radius:8px; object-fit:cover; margin-right:6px;"><b>RC</b><span>COURIER</span></div>
    <nav class="nav">
      <a href="<?= \App\Core\View::url('/admin') ?>">Dashboard</a>
      <a href="<?= \App\Core\View::url('/admin/shipments') ?>">Shipments</a>
      <a href="<?= \App\Core\View::url('/admin/tracking') ?>">Tracking Dispatcher</a>
      <a href="<?= \App\Core\View::url('/admin/quotes') ?>">Quotations</a>
      <a href="<?= \App\Core\View::url('/admin/invoices') ?>">Invoices &amp; Accounting</a>
      <a class="active" href="<?= \App\Core\View::url('/admin/settings') ?>">System Settings</a>
    </nav>
    <div class="sidebar-bottom">
      <?php $adminUser = \App\Core\Session::get('user'); ?>
      <form action="<?= \App\Core\View::url('/logout') ?>" method="POST">
        <input type="hidden" name="_token" value="<?= \App\Core\Session::getCsrfToken() ?>">
        <button class="signout" type="submit">Sign Out</button>
      </form>
    </div>
  </aside>

  <main>
    <div class="topbar">
      <div class="top-title">Company Details &amp; System Settings — Admin</div>
      <div class="user">Signed in as <strong><?= e($adminUser['name'] ?? 'Admin User') ?></strong> (<?= e(strtoupper($adminUser['role_name'] ?? 'ADMIN')) ?>)</div>
    </div>

    <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
      <div class="alert alert-success"><?= e($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
      <div class="alert alert-error"><?= e($msg) ?></div>
    <?php endif; ?>

    <section class="page-head">
      <div>
        <h1>Company Details &amp; System Settings</h1>
        <p class="subtitle">Control the official company identity, tax configuration and financial defaults used throughout RC Courier.</p>
      </div>
      <div class="status"><i class="dot"></i> System Configuration Active</div>
    </section>

    <form action="<?= \App\Core\View::url('/admin/settings') ?>" method="POST" id="settingsForm" class="card">
      <input type="hidden" name="_token" value="<?= \App\Core\Session::getCsrfToken() ?>">
      
      <div class="card-head">
        <div class="card-title"><span class="icon">▣</span> Official Company Profile</div>
        <span class="help">Used across invoices, quotations &amp; shipping documents</span>
      </div>
      <div class="card-body">
        <div class="grid">
          <div class="field full">
            <label for="company">Legal Company Name <span class="required">*</span></label>
            <input id="company" name="company_name" value="<?= e($settings['company_name'] ?? 'RC Courier UAE LLC') ?>" required>
          </div>
          <div class="field">
            <label for="trn">TRN / Federal Tax Registration Number <span class="required">*</span></label>
            <input id="trn" name="company_trn" value="<?= e($settings['company_trn'] ?? '100987654321003') ?>" inputmode="numeric" required>
            <span class="help">Enter the official UAE Federal Tax Authority registration number.</span>
          </div>
          <div class="field">
            <label for="phone">Company Phone Number</label>
            <input id="phone" name="company_phone" value="<?= e($settings['company_phone'] ?? '+971 4 800 2684') ?>">
          </div>
          <div class="field">
            <label for="email">Company Email Address</label>
            <input id="email" name="company_email" type="email" value="<?= e($settings['company_email'] ?? 'support@rccourier.ae') ?>">
          </div>
          <div class="field full">
            <label for="address">Registered / Full Company Address</label>
            <textarea id="address" name="company_address"><?= e($settings['company_address'] ?? 'Dubai Logistics City Central Hub, Dubai, United Arab Emirates') ?></textarea>
          </div>
        </div>

        <div class="divider"></div>
        <p class="section-label">Tax &amp; Financial Defaults</p>
        <div class="financial-grid">
          <div class="field">
            <label for="vat">UAE VAT Rate (%)</label>
            <div class="input-prefix"><span>%</span><input id="vat" name="tax_rate" type="number" min="0" max="100" step="0.01" value="<?= e($settings['tax_rate'] ?? '5.00') ?>"></div>
            <span class="help">Applied by default to taxable services; allow transaction-level overrides where required.</span>
          </div>
          <div class="field">
            <label for="currency">Default Currency</label>
            <select id="currency" name="default_currency">
              <option value="AED" <?= ($settings['default_currency'] ?? 'AED') === 'AED' ? 'selected' : '' ?>>AED — UAE Dirham</option>
              <option value="USD" <?= ($settings['default_currency'] ?? '') === 'USD' ? 'selected' : '' ?>>USD — US Dollar</option>
              <option value="SAR" <?= ($settings['default_currency'] ?? '') === 'SAR' ? 'selected' : '' ?>>SAR — Saudi Riyal</option>
              <option value="QAR" <?= ($settings['default_currency'] ?? '') === 'QAR' ? 'selected' : '' ?>>QAR — Qatari Riyal</option>
              <option value="KWD" <?= ($settings['default_currency'] ?? '') === 'KWD' ? 'selected' : '' ?>>KWD — Kuwaiti Dinar</option>
              <option value="BHD" <?= ($settings['default_currency'] ?? '') === 'BHD' ? 'selected' : '' ?>>BHD — Bahraini Dinar</option>
              <option value="OMR" <?= ($settings['default_currency'] ?? '') === 'OMR' ? 'selected' : '' ?>>OMR — Omani Rial</option>
            </select>
            <span class="help">AED is recommended for UAE domestic billing.</span>
          </div>
        </div>
      </div>
      <div class="savebar">
        <span class="help">Changes should be audited and reflected only in newly generated documents unless explicitly configured otherwise.</span>
        <div class="actions">
          <button type="reset" class="btn btn-secondary">Reset</button>
          <button type="submit" class="btn btn-primary">Save Company Settings →</button>
        </div>
      </div>
    </form>

    <section class="live-note">
      <h3>⚡ Live System Integration</h3>
      <p>These settings are intended to act as the single source of truth for official RC Courier documents.</p>
      <ul>
        <li><strong>Tax Invoices</strong> — company name, TRN, address, phone and tax defaults</li>
        <li><strong>Shipping Labels / Waybills</strong> — sender identity and operational contact details</li>
        <li><strong>Quotations &amp; Documents</strong> — company branding and financial defaults</li>
        <li><strong>Public Verification Portal</strong> — official identity used for document verification</li>
      </ul>
    </section>
  </main>
</div>
</body>
</html>
