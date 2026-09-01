<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? 'Edit Shipment — RC Courier Admin') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root{--nav:#091225;--bg:#f4f7fa;--ink:#102039;--muted:#708096;--line:#e3e9f0;--gold:#e0ad48;--gold2:#f6d27a;--blue:#168fd2;--red:#ef6a6a;--green:#38c88b}
*{box-sizing:border-box}body{margin:0;font:13px Inter,Arial,sans-serif;background:var(--bg);color:var(--ink)}
.side{position:fixed;inset:0 auto 0 0;width:245px;background:linear-gradient(180deg,#081021,#0d172b);color:#9fb0c7;padding:22px 14px;z-index:5}
.logo{padding:0 8px 28px;font-weight:800;font-size:16px;color:#fff}.logo b{color:var(--gold2)}.mini{display:inline-grid;place-items:center;width:34px;height:34px;border:1px solid #b78b35;border-radius:10px;color:#f5cf77;margin-right:8px;font-size:11px}
.label{font-size:8px;text-transform:uppercase;letter-spacing:.15em;color:#50627b;padding:13px 9px 7px}.nav a{display:block;padding:11px 10px;margin:3px 0;border-radius:9px;color:#9fb0c7;text-decoration:none;font-size:11px;font-weight:600}.nav a:hover,.nav .active{background:rgba(224,173,72,.12);color:#f5cf77}.bottom{position:absolute;bottom:20px;left:14px;right:14px;border-top:1px solid #233047;padding-top:15px}.user{display:flex;gap:9px;align-items:center;margin-bottom:12px}.avatar{width:32px;height:32px;border-radius:50%;background:#18263d;color:#f5cf77;display:grid;place-items:center;font-weight:800;font-size:9px}.user small{display:block;color:#63758d}.logout{width:100%;padding:10px;border:1px solid #34435a;border-radius:9px;background:transparent;color:#fff;cursor:pointer}
.main{margin-left:245px}.top{height:72px;background:#fff;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 30px;position:sticky;top:0;z-index:4}.top h2{font-size:15px;margin:0}.top span{font-size:10px;color:var(--muted)}.content{padding:29px;max-width:1200px}.heading{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:20px}.heading h1{font-size:24px;margin:0 0 4px}.heading p{margin:0;color:var(--muted);font-size:10px}.btn{border:0;border-radius:9px;background:linear-gradient(135deg,var(--gold2),var(--gold));padding:11px 18px;font-weight:800;font-size:10px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;color:#102039}.btn-dark{background:#101c2b;color:#fff}
.card{background:#fff;border:1px solid var(--line);border-radius:18px;padding:25px;box-shadow:0 18px 50px rgba(16,31,51,.06);margin-bottom:20px}
.card h2{font-size:14px;margin:0 0 15px;color:#102039;display:flex;align-items:center;gap:8px}.card h2 span{color:var(--gold);font-size:12px}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px}.grid-4{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px}.field{display:flex;flex-direction:column;gap:5px}.full{grid-column:1/-1}
label{font-size:9px;color:#64748b;font-weight:700;text-transform:uppercase;letter-spacing:0.5px}input,select,textarea{width:100%;border:1px solid var(--line);background:#f8fafc;color:#1e293b;border-radius:9px;padding:11px 12px;outline:0;font-size:11px;transition:.2s}input:focus,select:focus,textarea:focus{border-color:var(--gold);background:#fff;box-shadow:0 0 0 3px rgba(224,173,72,.12)}input[readonly]{background:#e2e8f0;color:#64748b;cursor:not-allowed}
.alert{padding:12px 15px;border-radius:11px;font-size:10px;margin-bottom:15px;font-weight:600}.alert-error{background:#fdf2f2;color:#d93838;border:1px solid #f8d7d7}
.badge-tag{font-size:9px;font-weight:800;padding:4px 8px;border-radius:6px;background:rgba(224,173,72,0.15);color:#b78b35;display:inline-block;margin-left:6px;}
@media(max-width:950px){.side{display:none}.main{margin:0}.grid,.grid-3,.grid-4{grid-template-columns:1fr}}
</style>
</head>
<body>

<aside class="side">
  <div class="logo" style="display:flex; align-items:center; gap:8px;"><img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:36px; width:36px; border-radius:8px; object-fit:cover;"><b>RC</b> COURIER</div>
  <div class="label">Operations</div>
  <nav class="nav">
    <a href="<?= \App\Core\View::url('/admin') ?>">▦ &nbsp; Dashboard</a>
    <a class="active" href="<?= \App\Core\View::url('/admin/shipments') ?>">▣ &nbsp; Shipments</a>
    <a href="<?= \App\Core\View::url('/admin/tracking') ?>">⌁ &nbsp; Tracking Dispatcher</a>
    <a href="<?= \App\Core\View::url('/admin/quotes') ?>">◇ &nbsp; Quotations</a>
  </nav>
  <div class="label">Finance & Operations</div>
  <nav class="nav">
    <a href="<?= \App\Core\View::url('/admin/invoices') ?>">▤ &nbsp; Invoices & Accounting</a>
  </nav>
  <div class="label">System</div>
  <nav class="nav">
    <a href="<?= \App\Core\View::url('/admin/settings') ?>">⚙ &nbsp; System Settings</a>
  </nav>
  <div class="bottom">
    <?php $adminUser = \App\Core\Session::get('user'); ?>
    <div class="user">
      <div class="avatar"><?= e(strtoupper(substr($adminUser['name'] ?? 'Admin', 0, 2))) ?></div>
      <div>
        <b style="font-size:9px;color:#fff"><?= e($adminUser['name'] ?? 'Admin User') ?></b>
        <small><?= e(strtoupper($adminUser['role_name'] ?? 'Administrator')) ?></small>
      </div>
    </div>
    <form action="<?= \App\Core\View::url('/logout') ?>" method="POST">
      <input type="hidden" name="_token" value="<?= \App\Core\Session::getCsrfToken() ?>">
      <button class="logout" type="submit">Sign Out</button>
    </form>
  </div>
</aside>

<main class="main">
  <header class="top">
    <h2>Edit Full Shipment Details — <?= e($shipment['reference_number']) ?></h2>
    <span>Signed in as <b><?= e($adminUser['name'] ?? 'Admin User') ?></b> (<?= e(strtoupper($adminUser['role_name'] ?? 'ADMIN')) ?>)</span>
  </header>

  <section class="content">
    <div class="heading">
      <div>
        <h1>Edit Shipment Details</h1>
        <p>Update core identification, operational status, sender origin, and receiver delivery address for <strong><?= e($shipment['reference_number']) ?></strong></p>
      </div>
      <a href="<?= \App\Core\View::url('/admin/shipments/' . $shipment['id']) ?>" class="btn btn-dark">← Back to Shipment Detail</a>
    </div>

    <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
      <div class="alert alert-error"><?= e($msg) ?></div>
    <?php endif; ?>

    <form action="<?= \App\Core\View::url('/admin/shipments/' . $shipment['id'] . '/edit') ?>" method="POST" id="shipmentEditForm">
      <input type="hidden" name="_token" value="<?= \App\Core\Session::getCsrfToken() ?>">

      <!-- 01 CORE SHIPMENT IDENTIFICATION & STATUS -->
      <div class="card">
        <h2><span>01</span> Core Identification & Operational Status</h2>
        <div class="grid">
          <div class="field">
            <label for="refNum">Reference Number</label>
            <input id="refNum" value="<?= e($shipment['reference_number']) ?>" readonly>
          </div>
          <div class="field">
            <label for="trkNum">Tracking ID (AWB Number)</label>
            <input id="trkNum" name="tracking_number" value="<?= e($shipment['tracking_number']) ?>" required style="font-weight:700; color:var(--blue);">
          </div>
          <div class="field">
            <label for="statusSelect">Operational Status</label>
            <select id="statusSelect" name="status" required style="font-weight:700; color:#102039;">
              <?php foreach ($statuses as $code => $lbl): ?>
                <option value="<?= $code ?>" <?= $shipment['status'] === $code ? 'selected' : '' ?>><?= $lbl ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="serviceSelect">Service Type</label>
            <select id="serviceSelect" name="service_id" required>
              <?php foreach ($services as $srv): ?>
                <option value="<?= $srv['id'] ?>" <?= (int)$shipment['service_id'] === (int)$srv['id'] ? 'selected' : '' ?>><?= e($srv['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- 02 SENDER DETAILS & PICKUP ADDRESS -->
      <div class="card">
        <h2><span>02</span> Sender (Pickup Origin) Details</h2>
        <div class="grid">
          <div class="field full">
            <label for="sName">Sender Contact Name / Business Name</label>
            <input id="sName" name="sender_name" value="<?= e($shipment['sender_name']) ?>" required>
          </div>
          <div class="field">
            <label for="sEmirate">Pickup Emirate</label>
            <select id="sEmirate" name="sender_emirate" required>
              <?php foreach ($emirates as $e): ?>
                <option value="<?= $e ?>" <?= $shipment['sender_emirate'] === $e ? 'selected' : '' ?>><?= $e ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="sArea">Pickup Area / Community</label>
            <input id="sArea" name="sender_area" value="<?= e($shipment['sender_area']) ?>" required>
          </div>
          <div class="field full">
            <label for="sAddr">Pickup Street / Building Address</label>
            <input id="sAddr" name="sender_address" value="<?= e($shipment['sender_address']) ?>" required>
          </div>
        </div>
      </div>

      <!-- 03 RECEIVER DETAILS & DELIVERY ADDRESS -->
      <div class="card">
        <h2><span>03</span> Receiver (Delivery Consignee) Details</h2>
        <div class="grid">
          <div class="field full">
            <label for="rName">Receiver Contact Name / Business Name</label>
            <input id="rName" name="receiver_name" value="<?= e($shipment['receiver_name']) ?>" required>
          </div>
          <div class="field">
            <label for="rEmirate">Delivery Destination Emirate / Country</label>
            <select id="rEmirate" name="receiver_emirate" required>
              <?php foreach ($emirates as $e): ?>
                <option value="<?= $e ?>" <?= $shipment['receiver_emirate'] === $e ? 'selected' : '' ?>><?= $e ?></option>
              <?php endforeach; ?>
              <option value="Saudi Arabia" <?= $shipment['receiver_emirate'] === 'Saudi Arabia' ? 'selected' : '' ?>>Saudi Arabia</option>
              <option value="Oman" <?= $shipment['receiver_emirate'] === 'Oman' ? 'selected' : '' ?>>Oman</option>
              <option value="Qatar" <?= $shipment['receiver_emirate'] === 'Qatar' ? 'selected' : '' ?>>Qatar</option>
              <option value="Bahrain" <?= $shipment['receiver_emirate'] === 'Bahrain' ? 'selected' : '' ?>>Bahrain</option>
              <option value="Kuwait" <?= $shipment['receiver_emirate'] === 'Kuwait' ? 'selected' : '' ?>>Kuwait</option>
              <option value="International" <?= $shipment['receiver_emirate'] === 'International' ? 'selected' : '' ?>>International</option>
            </select>
          </div>
          <div class="field">
            <label for="rArea">Delivery Area / Community</label>
            <input id="rArea" name="receiver_area" value="<?= e($shipment['receiver_area']) ?>" required>
          </div>
          <div class="field full">
            <label for="rAddr">Delivery Street / Building Address</label>
            <input id="rAddr" name="receiver_address" value="<?= e($shipment['receiver_address']) ?>" required>
          </div>
        </div>
      </div>

      <div style="display:flex;gap:12px;margin-top:10px;">
        <button type="submit" class="btn" style="padding:14px 28px;font-size:11px;">Save Updated Shipment Details →</button>
        <a href="<?= \App\Core\View::url('/admin/shipments/' . $shipment['id']) ?>" class="btn btn-dark" style="padding:14px 22px;font-size:11px;text-decoration:none;">Cancel</a>
      </div>
    </form>
  </section>
</main>
</body>
</html>
