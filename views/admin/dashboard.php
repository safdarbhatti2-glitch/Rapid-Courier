<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= e($title ?? 'Admin Dashboard — RC Courier UAE') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{
  --nav:#ffffff;--nav2:#f7f9fc;--bg:#f3f6fa;--card:#ffffff;
  --ink:#102238;--muted:#64778d;--line:#dce5ee;
  --gold:#d89e22;--gold-soft:#fff5dc;
  --blue:#078fcf;--blue-soft:#e8f7fd;
  --green:#10a773;--green-soft:#e8f8f2;
  --red:#e44852;
}
*{box-sizing:border-box}
html,body{margin:0;min-height:100%;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--ink)}
body{font-size:15px}
button,input,select{font:inherit}
button{cursor:pointer}
.app{display:flex;min-height:100vh}
.sidebar{
  width:235px;flex:0 0 235px;background:linear-gradient(180deg,#fff,#f5f8fb);
  border-right:1px solid #dfe7ef;padding:22px 13px;position:sticky;top:0;height:100vh;
  box-shadow:5px 0 25px rgba(18,43,68,.05)
}
.brand{display:flex;align-items:center;gap:9px;padding:0 8px 25px}
.logo{
 width:35px;height:35px;border:1px solid #d8a32c;border-radius:10px;display:grid;place-items:center;
 color:#b47a0d;font-weight:900;background:#fff9e9;box-shadow:0 5px 14px rgba(216,158,34,.14)
}
.brand strong{font-size:16px;letter-spacing:-.4px}.brand em{font-style:normal;color:#e33c46}
.nav{display:grid;gap:5px}.nav a{
 text-decoration:none;color:#486077;padding:13px 10px;border-radius:9px;font-weight:600;font-size:14px;
 border-left:3px solid transparent;transition:.2s
}
.nav a:hover,.nav a.active{background:#fff7e2;color:#986b12;border-left-color:var(--gold)}
.sidebarBottom{position:absolute;left:13px;right:13px;bottom:20px;border-top:1px solid #dfe7ef;padding-top:13px}
.signout{width:100%;height:42px;border:1px solid #ccd8e4;background:#fff;border-radius:9px;color:var(--red);font-weight:800;cursor:pointer}
.main{flex:1;min-width:0;padding:25px 28px 40px}
.topbar{
 background:#fff;border:1px solid var(--line);border-radius:10px;padding:15px 20px;
 display:flex;justify-content:space-between;align-items:center;box-shadow:0 5px 20px rgba(20,45,70,.04);margin-bottom:26px
}
.topbar h1{font-size:17px;margin:0;font-weight:800}.topbar .user{color:#60758c;font-size:13px}.user b{color:var(--ink)}
.welcome{display:flex;justify-content:space-between;align-items:end;margin:0 0 18px}
.welcome h2{margin:0;font-size:30px;letter-spacing:-1px}.welcome p{margin:7px 0 0;color:var(--muted);font-size:14px}
.dateBadge{background:#fff;border:1px solid var(--line);padding:10px 14px;border-radius:9px;color:#587087;font-size:13px;font-weight:700}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:28px}
.stat{
 background:#fff;border:1px solid var(--line);border-radius:15px;padding:22px 22px 20px;
 box-shadow:0 12px 30px rgba(20,45,70,.08);position:relative;overflow:hidden
}
.stat:before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:var(--gold)}
.stat.blue:before{background:var(--blue)}.stat.green:before{background:var(--green)}.stat.red:before{background:var(--red)}
.statTop{display:flex;justify-content:space-between;align-items:center;margin-bottom:15px}
.statLabel{font-size:12px;font-weight:800;color:#687c91;text-transform:uppercase;letter-spacing:.5px}
.icon{
 width:35px;height:35px;border-radius:10px;display:grid;place-items:center;background:var(--gold-soft);color:#ae7710;font-weight:900
}
.blue .icon{background:var(--blue-soft);color:#087dad}.green .icon{background:var(--green-soft);color:#087e58}.red .icon{background:#fff0f1;color:#c63540}
.statValue{font-size:31px;font-weight:900;letter-spacing:-1px;color:#102238}
.statMeta{margin-top:8px;font-size:12px;color:#708398}.up{color:var(--green);font-weight:800}
.contentGrid{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:20px}
.card{
 background:#fff;border:1px solid var(--line);border-radius:15px;box-shadow:0 12px 30px rgba(20,45,70,.07);overflow:hidden
}
.cardHead{padding:19px 20px;border-bottom:1px solid #e6edf3;display:flex;justify-content:space-between;align-items:center}
.cardHead h3{font-size:17px;margin:0}.cardHead p{font-size:12px;color:var(--muted);margin:5px 0 0}
.link{border:0;background:transparent;color:#087fb8;font-weight:800;font-size:12px;text-decoration:none;cursor:pointer}
.tableWrap{overflow:auto}.table{width:100%;border-collapse:collapse;min-width:800px}
.table th{text-align:left;padding:13px 15px;background:#f7f9fb;color:#73869a;font-size:11px;text-transform:uppercase;letter-spacing:.45px}
.table td{padding:14px 15px;border-top:1px solid #edf1f5;font-size:13px;color:#344a60;white-space:nowrap}
.table td:first-child{font-weight:800;color:#132840}.tracking{font-family:ui-monospace,SFMono-Regular,monospace;font-size:11px;color:#087fb8;font-weight:700}
.status{display:inline-flex;align-items:center;gap:6px;padding:6px 9px;border-radius:999px;font-size:10px;font-weight:900;letter-spacing:.3px}
.status:before{content:"";width:6px;height:6px;border-radius:50%;background:currentColor}
.booked{background:#eaf5fc;color:#087bad;border:1px solid #c7e5f4}.delivered{background:#e9f8f2;color:#0a8b60;border:1px solid #c6eadc}.transit{background:#fff5df;color:#aa7610;border:1px solid #f1ddb1}
.manage{border:1px solid #d6e0e9;background:#fff;border-radius:7px;padding:6px 12px;color:#31506a;font-weight:800;font-size:11px;text-decoration:none;display:inline-block}
.rightStack{display:grid;gap:20px;align-content:start}
.quick{display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:18px}
.quick a{border:1px solid #d8e2eb;background:#fff;border-radius:10px;padding:14px 9px;color:#26445e;font-weight:800;font-size:12px;text-decoration:none;text-align:center;transition:.2s}
.quick a:hover{border-color:#d6a02a;background:#fff9eb;transform:translateY(-1px)}
.progress{padding:19px}.progressRow{display:flex;justify-content:space-between;font-size:12px;font-weight:800;margin-bottom:9px}
.bar{height:9px;background:#edf2f6;border-radius:99px;overflow:hidden;margin-bottom:15px}.fill{height:100%;border-radius:99px;background:linear-gradient(90deg,#d99f27,#f0c45b)}
.activity{padding:0 19px 19px}.activityItem{display:flex;gap:11px;padding:13px 0;border-bottom:1px solid #edf1f5}
.activityItem:last-child{border-bottom:0}.dot{width:9px;height:9px;border-radius:50%;background:var(--blue);margin-top:5px;box-shadow:0 0 0 4px #e8f7fd}
.activityItem strong{font-size:12px}.activityItem small{display:block;color:var(--muted);font-size:11px;margin-top:3px}
.alert{padding:12px 15px;border-radius:11px;font-size:12px;margin-bottom:15px;font-weight:600}
.alert-success{background:#eefbf5;color:#159367;border:1px solid #c3f1de}
.alert-error{background:#fdf2f2;color:#d93838;border:1px solid #f8d7d7}
@media(max-width:1100px){.stats{grid-template-columns:repeat(2,1fr)}.contentGrid{grid-template-columns:1fr}.rightStack{grid-template-columns:1fr 1fr}}
@media(max-width:800px){.sidebar{width:76px;flex-basis:76px;padding:18px 9px}.brand strong,.nav a span,.sidebarBottom{display:none}.brand{justify-content:center;padding-bottom:20px}.nav a{font-size:0;text-align:center;padding:14px 5px}.main{padding:18px 14px}.topbar{gap:10px}.topbar .user{display:none}.welcome{align-items:start;gap:12px}.welcome h2{font-size:24px}.dateBadge{display:none}.rightStack{grid-template-columns:1fr}}
@media(max-width:560px){.stats{grid-template-columns:1fr}.topbar h1{font-size:14px}.welcome h2{font-size:22px}.statValue{font-size:28px}}
</style>
</head>
<body>
<div class="app">
<aside class="sidebar">
  <div class="brand"><img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:36px; width:36px; border-radius:8px; object-fit:cover; margin-right:6px;"><strong><em>RC</em> COURIER</strong></div>
  <nav class="nav">
    <a class="active" href="<?= \App\Core\View::url('/admin') ?>"><span>Dashboard</span></a>
    <a href="<?= \App\Core\View::url('/admin/shipments') ?>"><span>Shipments</span></a>
    <a href="<?= \App\Core\View::url('/admin/tracking') ?>"><span>Tracking Dispatcher</span></a>
    <a href="<?= \App\Core\View::url('/admin/quotes') ?>"><span>Quotations</span></a>
    <a href="<?= \App\Core\View::url('/admin/invoices') ?>"><span>Invoices &amp; Accounting</span></a>
    <a href="<?= \App\Core\View::url('/admin/settings') ?>"><span>System Settings</span></a>
  </nav>
  <div class="sidebarBottom">
    <form action="<?= \App\Core\View::url('/logout') ?>" method="POST">
      <input type="hidden" name="_token" value="<?= \App\Core\Session::getCsrfToken() ?>">
      <button class="signout" type="submit">Sign Out</button>
    </form>
  </div>
</aside>

<main class="main">
  <header class="topbar">
    <h1>Admin Dashboard — RC Courier UAE</h1>
    <?php $adminUser = \App\Core\Session::get('user'); ?>
    <div class="user">Signed in as <b><?= e($adminUser['name'] ?? 'Admin User') ?></b> &nbsp;(<?= e(strtoupper($adminUser['role_name'] ?? 'ADMIN')) ?>)</div>
  </header>

  <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
    <div class="alert alert-success"><?= e($msg) ?></div>
  <?php endif; ?>
  <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
    <div class="alert alert-error"><?= e($msg) ?></div>
  <?php endif; ?>

  <section class="welcome">
    <div>
      <h2>Operations Overview</h2>
      <p>Monitor shipments, delivery performance and today's courier activity.</p>
    </div>
    <div class="dateBadge"><?= e(date('l, d F Y')) ?></div>
  </section>

  <!-- 4 Stat Cards -->
  <section class="stats">
    <article class="stat">
      <div class="statTop"><div class="statLabel">Shipments Today</div><div class="icon">S</div></div>
      <div class="statValue"><?= e($metrics['shipments_today'] ?? 0) ?></div>
      <div class="statMeta"><span class="up">+12%</span> vs yesterday</div>
    </article>
    <article class="stat blue">
      <div class="statTop"><div class="statLabel">In Transit</div><div class="icon">→</div></div>
      <div class="statValue"><?= e($metrics['in_transit'] ?? 0) ?></div>
      <div class="statMeta">Active deliveries across UAE</div>
    </article>
    <article class="stat green">
      <div class="statTop"><div class="statLabel">Delivered Today</div><div class="icon">✓</div></div>
      <div class="statValue"><?= e($metrics['delivered_today'] ?? 0) ?></div>
      <div class="statMeta"><span class="up"><?= e($deliveredPct ?? 94) ?>%</span> delivery success rate</div>
    </article>
    <article class="stat">
      <div class="statTop"><div class="statLabel">Revenue Today (AED)</div><div class="icon">د</div></div>
      <div class="statValue"><?= e(number_format($metrics['revenue_today'] ?? 0, 2)) ?></div>
      <div class="statMeta"><span class="up">+8.4%</span> from completed orders</div>
    </article>
  </section>

  <section class="contentGrid">
    <!-- Live Operations Table -->
    <div class="card">
      <div class="cardHead">
        <div><h3>Live Operations Stream</h3><p>Latest shipment activity across RC Courier UAE</p></div>
        <a href="<?= \App\Core\View::url('/admin/shipments') ?>" class="link">View All Shipments →</a>
      </div>
      <div class="tableWrap">
        <table class="table">
          <thead><tr><th>Reference</th><th>Tracking #</th><th>Customer</th><th>Service</th><th>Status</th><th>Total</th><th>Created</th><th>Action</th></tr></thead>
          <tbody>
            <?php foreach ($recentShipments as $s): ?>
              <?php 
                $stClass = strtolower(str_replace('_', '', $s['status']));
                if ($stClass === 'intransit') $stClass = 'transit';
              ?>
              <tr>
                <td><?= e($s['reference_number']) ?></td>
                <td class="tracking"><?= e($s['tracking_number']) ?></td>
                <td><?= e($s['company_name'] ?: $s['contact_name']) ?></td>
                <td><?= e($s['service_name']) ?></td>
                <td><span class="status <?= $stClass ?>"><?= e($s['status']) ?></span></td>
                <td><?= e(number_format($s['total'], 2)) ?> AED</td>
                <td><?= e(date('M d, H:i', strtotime($s['created_at']))) ?></td>
                <td><a href="<?= \App\Core\View::url('/admin/shipments/' . $s['id']) ?>" class="manage">Manage</a></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($recentShipments)): ?>
              <tr><td colspan="8" style="text-align:center; color:#94a3b8; padding:2rem;">No recent shipments logged today.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <aside class="rightStack">
      <!-- Quick Actions Card -->
      <div class="card">
        <div class="cardHead"><div><h3>Quick Actions</h3><p>Common operations</p></div></div>
        <div class="quick">
          <a href="<?= \App\Core\View::url('/admin/shipments/create') ?>">＋ New Shipment</a>
          <a href="<?= \App\Core\View::url('/admin/quotes') ?>">AED Get Quote</a>
          <a href="<?= \App\Core\View::url('/admin/tracking') ?>">⌁ Track Shipment</a>
          <a href="<?= \App\Core\View::url('/admin/invoices') ?>">▣ Invoices</a>
        </div>
      </div>

      <!-- Today's Delivery Mix Card -->
      <div class="card">
        <div class="cardHead"><div><h3>Today's Delivery Mix</h3><p>Current operational progress</p></div></div>
        <div class="progress">
          <div class="progressRow"><span>Delivered</span><span><?= e($deliveredPct ?? 56) ?>%</span></div>
          <div class="bar"><div class="fill" style="width:<?= e($deliveredPct ?? 56) ?>%"></div></div>
          <div class="progressRow"><span>In Transit</span><span><?= e($transitPct ?? 44) ?>%</span></div>
          <div class="bar"><div class="fill" style="width:<?= e($transitPct ?? 44) ?>%;background:linear-gradient(90deg,#078fcf,#5bc6ef)"></div></div>
        </div>
      </div>

      <!-- Recent Activity Feed -->
      <div class="card">
        <div class="cardHead"><div><h3>Recent Activity</h3><p>System events</p></div></div>
        <div class="activity">
          <?php foreach (array_slice($recentShipments, 0, 3) as $s): ?>
            <div class="activityItem">
              <i class="dot" style="<?= $s['status'] === 'DELIVERED' ? 'background:#10a773;box-shadow:0 0 0 4px #e8f8f2;' : '' ?>"></i>
              <div>
                <strong><?= e($s['reference_number']) ?> — <?= e($s['status']) ?></strong>
                <small><?= e($s['service_name']) ?> • <?= e(date('H:i', strtotime($s['created_at']))) ?></small>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </aside>
  </section>
</main>
</div>
</body>
</html>
