<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? 'RC Courier — Shipment Events') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--nav:#071020;--bg:#f3f6f9;--panel:#101d2d;--ink:#0b1728;--muted:#75869c;--line:#24354b;--gold:#e5b44f;--gold2:#f7d37a;--cyan:#27b8ff;--green:#22c58b}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font:13px Inter,system-ui,-apple-system,Segoe UI,Arial,sans-serif}button,input,select{font:inherit}button{cursor:pointer}
.sidebar{position:fixed;left:0;top:0;bottom:0;width:238px;background:linear-gradient(180deg,#ffffff 0%,#f5f8fb 100%) !important;color:#52677e !important;padding:22px 12px;z-index:20;border-right:1px solid #e0e7ef;box-shadow:4px 0 25px rgba(25,45,70,.04)}
.brand{display:flex;align-items:center;gap:9px;padding:0 8px 27px}
.brandmark{width:35px;height:35px;border:1px solid #a87922;border-radius:10px;display:grid;place-items:center;color:#f4c65e;font-weight:900;background:#101b2b}
.brandtext{font-size:21px !important;font-weight:900;color:#122238}.brandtext b{color:#e63c45}.brandtext small{display:block;color:#b07b13;font-size:6px;letter-spacing:.16em;margin-top:2px}
.group{margin:8px 0 5px;padding:10px 9px 4px;color:#8798aa !important;font-size:10px !important;letter-spacing:.16em;text-transform:uppercase}
.nav a{display:flex;align-items:center;gap:10px;color:#425870 !important;text-decoration:none;padding:11px 10px;border-radius:9px;margin:3px 0;font-size:15px !important;font-weight:650}
.nav a:hover,.nav a.active{background:#fff8e8 !important;color:#9a690c !important;border-left:3px solid #d9a12a !important}
.nav i{font-style:normal;width:18px;text-align:center;color:#8a9aae}
.sideBottom{position:absolute;left:12px;right:12px;bottom:18px;border-top:1px solid #e0e7ef;padding-top:14px}
.admin{display:flex;align-items:center;gap:9px;margin-bottom:12px}
.avatar{width:32px;height:32px;border-radius:50%;display:grid;place-items:center;background:#182a40;border:1px solid #30435c;color:#f2c55f;font-size:9px;font-weight:900}
.admin b{font-size:11px !important;color:#122238}.admin small{display:block;color:#637892;font-size:10px !important;margin-top:2px}
.signout{width:100%;padding:10px;border:1px solid #d5dee8;background:#ffffff;color:#31445b;border-radius:9px;font-size:9px;font-weight:700;cursor:pointer}
.main{margin-left:238px;min-height:100vh}
.topbar{height:68px;background:rgba(255,255,255,.96);backdrop-filter:blur(12px);border-bottom:1px solid #e0e7ee;display:flex;align-items:center;justify-content:space-between;padding:0 28px;position:sticky;top:0;z-index:10}
.topbar h2{font-size:16.8px;margin:0}.signed{color:#708198;font-size:9px}.signed b{color:#14243a}
.content{padding:27px 28px 45px;max-width:1500px;margin:auto}
.pageHead{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:21px}
.eyebrow{font-size:8px;color:#71839a;text-transform:uppercase;letter-spacing:.15em;font-weight:800;margin-bottom:6px}
.pageHead h1{font-size:30px !important;line-height:1.1;margin:0 0 6px;letter-spacing:-.02em}
.sub{margin:0;color:#4e637a !important;font-size:14px !important}
.sub b{color:#087fb8 !important;font-size:16px !important;font-weight:900 !important;background:#e8f7fd !important;border:1px solid #b8dfef !important;border-radius:7px !important;padding:3px 7px !important}
.headActions{display:flex;gap:8px}
.btn{border:1px solid #dfe6ee;background:#ffffff;color:#26374d;border-radius:9px;padding:10px 15px;font-size:11px;font-weight:800;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}
.btn.primary{border-color:#d9a638;background:linear-gradient(135deg,var(--gold2),var(--gold));color:#111d2d;box-shadow:0 8px 22px rgba(206,155,48,.18)}
.hero{background:linear-gradient(135deg,#ffffff 0%,#f8fbff 52%,#fff7df 100%) !important;color:#10233a !important;border:1px solid #cfdce8 !important;border-left:6px solid #d9a12a !important;border-radius:18px !important;padding:24px !important;margin-bottom:15px;position:relative;overflow:hidden;box-shadow:0 16px 38px rgba(24,48,75,.12) !important}
.heroTop{display:flex;justify-content:space-between;gap:20px;align-items:center;position:relative;z-index:2 !important}
.shipId{color:#10233a !important;font-size:26px !important;font-weight:900 !important;letter-spacing:-.01em !important}
.shipId span{color:#52677e !important;font-size:14px !important;font-weight:650 !important;display:block;margin-top:7px !important}
.live{border:2px solid #159bd4 !important;background:#e8f7fd !important;color:#066f9f !important;padding:10px 15px !important;border-radius:24px !important;font-size:13px !important;font-weight:900 !important;letter-spacing:.02em !important;display:inline-flex;align-items:center;gap:7px}
.live .dot{width:9px !important;height:9px !important;background:#12a86f !important;border-radius:50%;box-shadow:0 0 0 4px rgba(18,168,111,.13),0 0 12px rgba(18,168,111,.4) !important}
.grid{display:grid;grid-template-columns:1.2fr .8fr;gap:15px}
.panel{background:linear-gradient(145deg,#ffffff,#f8fbfd);color:#122238;border:1px solid #dce5ed;border-radius:17px;padding:19px;box-shadow:0 15px 38px rgba(24,48,75,.09);position:relative;overflow:hidden;margin-bottom:15px}
.panel:before{content:"";position:absolute;left:0;right:0;top:0;height:2px;background:linear-gradient(90deg,var(--gold),transparent)}
.panel h3{margin:0 0 4px;font-size:17px !important}.desc{margin:0 0 16px;color:#708198;font-size:11px !important;line-height:1.5}
.field{margin:11px 0}.field label{display:block;color:#33465d;font-size:10px !important;font-weight:800;margin-bottom:6px}
.input,.select{width:100%;height:44px !important;border:1px solid #dce4ec;border-radius:10px;background:#ffffff;color:#1b2b40;padding:0 11px;font-size:12px !important;outline:0}
.input:focus,.select:focus{border-color:#51b9ef;box-shadow:0 0 0 3px rgba(39,184,255,.11)}
.fullBtn{width:100%;height:44px !important;border:0;border-radius:10px;background:linear-gradient(135deg,var(--gold2),var(--gold));font-weight:900;color:#101d2c;font-size:11px !important;margin-top:7px;cursor:pointer}
.auto{margin-top:9px;background:#eef3f7;color:#1a2d43;border-radius:10px;height:40px !important;border:0;width:100%;font-size:10px !important;font-weight:850;cursor:pointer}
.timeline{margin-top:15px}.timelineHead{display:flex;justify-content:space-between;align-items:center;margin-bottom:15px}
.timelineHead h3{margin:0;font-size:17px !important}.timelineHead span{color:#708198;font-size:9px}
.events{position:relative;padding:5px 2px 3px 27px}
.events:before{content:"";position:absolute;left:9px;top:10px;bottom:10px;width:2px;background:linear-gradient(#d9a02c,#159bd4,#16ae76)}
.event{position:relative;margin:0 0 14px;padding:12px 13px;background:#f9fbfd;border:1px solid #dce5ed;border-radius:11px;display:flex;justify-content:space-between;gap:15px}
.event:before{content:"";position:absolute;left:-23px;top:16px;width:9px;height:9px;border-radius:50%;background:var(--gold);border:3px solid #ffffff}
.event.current:before{background:var(--cyan);box-shadow:0 0 14px rgba(7,143,209,.35)}
.event.done:before{background:var(--green)}
.event b{font-size:11px !important}.event p,.event time{font-size:10px !important}
.event time{color:#087fb8;font-size:10px !important;font-weight:800;white-space:nowrap}
.details{display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-top:15px}
.infoList{display:grid;grid-template-columns:1fr 1fr;gap:9px}
.info{background:#f9fbfd;border:1px solid #dce5ed;border-radius:10px;padding:10px}
.info small{display:block;color:#687c95;font-size:9px !important;margin-bottom:4px}
.info b{font-size:11px !important}
.price{display:grid;gap:8px}
.priceRow{display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #e2e8f0;color:#52677e;font-size:11px !important}
.priceRow strong{color:#122238}
.total{display:flex;justify-content:space-between;margin-top:3px;font-size:14.4px}
.total b{color:#b78b35}
.notice{margin-top:15px;padding:12px 14px;border:1px solid rgba(39,184,255,.18);background:rgba(39,184,255,.05);border-radius:11px;color:#4a627d;font-size:10px !important}
.alert{padding:12px 15px;border-radius:11px;font-size:11px;margin-bottom:15px;font-weight:600}
.alert-success{background:#eefbf5;color:#159367;border:1px solid #c3f1de}
.alert-error{background:#fdf2f2;color:#d93838;border:1px solid #f8d7d7}
@media(max-width:1050px){.grid{grid-template-columns:1fr}.details{grid-template-columns:1fr}.sidebar{width:205px}.main{margin-left:205px}}
@media(max-width:760px){.sidebar{display:none}.main{margin:0}.topbar{padding:0 15px}.content{padding:20px 14px}.pageHead{align-items:flex-start;flex-direction:column;gap:13px}.heroTop{align-items:flex-start;flex-direction:column}.infoList{grid-template-columns:1fr}.signed{display:none}}
</style>
</head>
<body>

<aside class="sidebar">
  <div class="brand"><img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:36px; width:36px; border-radius:8px; object-fit:cover; margin-right:6px;"><div class="brandtext"><b>RC</b> COURIER<small>UAE • GCC LOGISTICS</small></div></div>
  <div class="group">Operations</div>
  <nav class="nav">
    <a href="<?= \App\Core\View::url('/admin') ?>"><i>▦</i>Dashboard</a>
    <a class="active" href="<?= \App\Core\View::url('/admin/shipments') ?>"><i>▣</i>Shipments</a>
    <a href="<?= \App\Core\View::url('/admin/tracking') ?>"><i>⌁</i>Tracking Dispatcher</a>
    <a href="<?= \App\Core\View::url('/admin/quotes') ?>"><i>◇</i>Quotations</a>
  </nav>
  <div class="group">Finance</div>
  <nav class="nav">
    <a href="<?= \App\Core\View::url('/admin/invoices') ?>"><i>▤</i>Invoices & Accounting</a>
  </nav>
  <div class="group">System</div>
  <nav class="nav">
    <a href="<?= \App\Core\View::url('/admin/settings') ?>"><i>⚙</i>System Settings</a>
  </nav>
  <div class="sideBottom">
    <?php $adminUser = \App\Core\Session::get('user'); ?>
    <div class="admin">
      <div class="avatar"><?= e(strtoupper(substr($adminUser['name'] ?? 'Admin', 0, 2))) ?></div>
      <div>
        <b><?= e($adminUser['name'] ?? 'Admin User') ?></b>
        <small><?= e(strtoupper($adminUser['role_name'] ?? 'Administrator')) ?></small>
      </div>
    </div>
    <form action="<?= \App\Core\View::url('/logout') ?>" method="POST">
      <input type="hidden" name="_token" value="<?= \App\Core\Session::getCsrfToken() ?>">
      <button class="signout" type="submit">Sign Out</button>
    </form>
  </div>
</aside>

<main class="main">
  <header class="topbar">
    <h2>Shipment Control Center — Admin</h2>
    <div class="signed">Signed in as <b><?= e($adminUser['name'] ?? 'Admin User') ?></b> (<?= e(strtoupper($adminUser['role_name'] ?? 'ADMIN')) ?>)</div>
  </header>

  <section class="content">
    
    <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
      <div class="alert alert-success"><?= e($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
      <div class="alert alert-error"><?= e($msg) ?></div>
    <?php endif; ?>

    <div class="pageHead">
      <div>
        <div class="eyebrow">Shipment intelligence / event control</div>
        <h1>Shipment <?= e($shipment['reference_number']) ?></h1>
        <p class="sub">Tracking Number: <b><?= e($shipment['tracking_number']) ?></b> · Live operational event management</p>
      </div>
      <div class="headActions">
        <a href="<?= \App\Core\View::url('/admin/shipments/' . $shipment['id'] . '/edit') ?>" class="btn">Edit Shipment</a>
        <a href="<?= \App\Core\View::url('/shipments/' . $shipment['id'] . '/label') ?>" target="_blank" class="btn primary">Generate Label</a>
      </div>
    </div>

    <!-- Ultra Visible Hero Banner -->
    <div class="hero">
      <div class="heroTop">
        <div class="shipId">
          <?= e($shipment['tracking_number']) ?>
          <span><?= e($shipment['reference_number']) ?> · <?= e($shipment['service_name']) ?></span>
        </div>
        <div class="live"><span class="dot"></span> LIVE TRACKING</div>
      </div>
    </div>

    <!-- 2 Column Forms Grid -->
    <div class="grid">
      <!-- Update Status Panel -->
      <section class="panel">
        <h3>Update Shipment Status</h3>
        <p class="desc">Publish a verified status event to the customer's tracking timeline.</p>

        <form action="<?= \App\Core\View::url('/admin/shipments/' . $shipment['id'] . '/status') ?>" method="POST">
          <input type="hidden" name="_token" value="<?= \App\Core\Session::getCsrfToken() ?>">

          <div class="field">
            <label>NEW STATUS</label>
            <select name="status" class="select" required>
              <?php 
              $statuses = ['BOOKED', 'CONFIRMED', 'PICKUP_ASSIGNED', 'PICKED_UP', 'AT_ORIGIN_HUB', 'IN_TRANSIT', 'AT_DESTINATION_HUB', 'OUT_FOR_DELIVERY', 'DELIVERY_ATTEMPTED', 'DELIVERED', 'CANCELLED', 'ON_HOLD', 'RETURNED'];
              foreach ($statuses as $st):
              ?>
                <option value="<?= $st ?>" <?= $shipment['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="field">
            <label>EVENT DATE & TIME</label>
            <input name="event_time" class="input" type="datetime-local" required value="<?= date('Y-m-d\TH:i') ?>">
          </div>

          <button type="submit" class="fullBtn">Publish Status Event</button>
        </form>
      </section>

      <!-- Auto Generate Events Panel -->
      <section class="panel">
        <h3>⚡ Auto-Generate Events</h3>
        <p class="desc">Create a realistic chronological operational timeline between the selected dates.</p>

        <form action="<?= \App\Core\View::url('/admin/shipments/' . $shipment['id'] . '/auto-generate-events') ?>" method="POST">
          <input type="hidden" name="_token" value="<?= \App\Core\Session::getCsrfToken() ?>">

          <div class="field">
            <label>START DATE & TIME</label>
            <input name="start_time" class="input" type="datetime-local" required value="<?= date('Y-m-d\TH:i', strtotime('-24 hours')) ?>">
          </div>

          <div class="field">
            <label>END DATE & TIME</label>
            <input name="end_time" class="input" type="datetime-local" required value="<?= date('Y-m-d\TH:i') ?>">
          </div>

          <div class="field">
            <label>NUMBER OF EVENTS</label>
            <select name="num_events" class="select" required>
              <option value="5" selected>5 Events · Booked → Picked Up → At Origin Hub → In Transit → Delivered</option>
              <option value="4">4 Events · Booked → Picked Up → In Transit → Delivered</option>
            </select>
          </div>

          <button type="submit" class="auto">⚡ Auto-Generate Chronological Timeline</button>
        </form>
      </section>
    </div>

    <!-- Timeline Panel -->
    <section class="panel timeline">
      <div class="timelineHead">
        <h3>Full Audit Tracking Timeline</h3>
        <span><?= count($events) ?> published event(s)</span>
      </div>
      <div class="events">
        <?php foreach ($events as $idx => $ev): ?>
          <?php 
            $isLatest = ($idx === 0);
            $isDelivered = ($ev['status'] === 'DELIVERED');
            $evClass = $isDelivered ? 'done' : ($isLatest ? 'current' : '');
          ?>
          <article class="event <?= $evClass ?>">
            <div>
              <b><?= e($ev['status']) ?> — <?= e($ev['location_name']) ?></b>
              <p>Operational event recorded into RC Courier network.</p>
            </div>
            <time><?= e(date('M d, Y · H:i', strtotime($ev['event_time']))) ?></time>
          </article>
        <?php endforeach; ?>
        <?php if (empty($events)): ?>
          <article class="event current">
            <div>
              <b>BOOKED — <?= e($shipment['origin_emirate'] ?: 'Dubai') ?> Hub</b>
              <p>Shipment successfully booked and accepted into the RC Courier network.</p>
            </div>
            <time><?= e(date('M d, Y · H:i', strtotime($shipment['created_at']))) ?></time>
          </article>
        <?php endif; ?>
      </div>
    </section>

    <!-- Details Panels -->
    <div class="details">
      <section class="panel">
        <h3>Customer Details</h3>
        <p class="desc">Verified customer and billing contact information.</p>
        <div class="infoList">
          <div class="info"><small>NAME</small><b><?= e($shipment['contact_name']) ?></b></div>
          <div class="info"><small>COMPANY</small><b><?= e($shipment['company_name'] ?: 'Individual') ?></b></div>
          <div class="info"><small>EMAIL</small><b><?= e($shipment['email']) ?></b></div>
          <div class="info"><small>PHONE</small><b><?= e($shipment['phone']) ?></b></div>
        </div>
      </section>

      <section class="panel">
        <h3>Pricing Breakdown</h3>
        <p class="desc">Shipment charges calculated in UAE Dirhams.</p>
        <div class="price">
          <div class="priceRow"><span>Subtotal</span><strong><?= e(number_format($shipment['subtotal'], 2)) ?> AED</strong></div>
          <div class="priceRow"><span>VAT (5%)</span><strong><?= e(number_format($shipment['tax'], 2)) ?> AED</strong></div>
          <div class="total"><b>Total</b><b><?= e(number_format($shipment['total'], 2)) ?> AED</b></div>
        </div>
      </section>
    </div>

    <div class="notice">
      🔒 <b>Audit-ready workflow:</b> Store each status publication server-side with administrator ID, timestamp, previous status, new status and source IP when connected to PHP/MySQL.
    </div>

  </section>
</main>
</body>
</html>
