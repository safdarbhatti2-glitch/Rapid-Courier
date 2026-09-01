<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? 'RC Courier Admin — Shipments') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root{--nav:#091225;--bg:#f4f7fa;--ink:#102039;--muted:#708096;--line:#e3e9f0;--gold:#e0ad48;--gold2:#f6d27a;--blue:#168fd2}
*{box-sizing:border-box}body{margin:0;font:14.5px/1.5 Inter,Arial,sans-serif;background:var(--bg);color:var(--ink)}
.app{min-height:100vh}.side{position:fixed;inset:0 auto 0 0;width:245px;background:linear-gradient(180deg,#081021,#0d172b);color:#9fb0c7;padding:22px 14px;z-index:5}
.logo{padding:0 8px 28px;font-weight:800;font-size:17.5px;color:#fff}.logo b{color:var(--gold2)}.mini{display:inline-grid;place-items:center;width:34px;height:34px;border:1px solid #b78b35;border-radius:10px;color:#f5cf77;margin-right:8px;font-size:12px}
.label{font-size:9.5px;text-transform:uppercase;letter-spacing:.15em;color:#50627b;padding:13px 9px 7px}.nav a{display:block;padding:11px 10px;margin:3px 0;border-radius:9px;color:#9fb0c7;text-decoration:none;font-size:12px;font-weight:600}.nav a:hover,.nav .active{background:rgba(224,173,72,.12);color:#f5cf77}.bottom{position:absolute;bottom:20px;left:14px;right:14px;border-top:1px solid #233047;padding-top:15px}.user{display:flex;gap:9px;align-items:center;margin-bottom:12px}.avatar{width:32px;height:32px;border-radius:50%;background:#18263d;color:#f5cf77;display:grid;place-items:center;font-weight:800;font-size:10px}.user small{display:block;color:#63758d}.logout{width:100%;padding:10px;border:1px solid #34435a;border-radius:9px;background:transparent;color:#fff;cursor:pointer}
.main{margin-left:245px}.top{height:72px;background:#fff;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 30px;position:sticky;top:0;z-index:4}.top h2{font-size:16.5px;margin:0}.top span{font-size:11px;color:var(--muted)}.content{padding:29px;max-width:1550px}.heading{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:20px}.heading h1{font-size:28.5px;margin:0 0 4px}.heading p{margin:0;color:var(--muted);font-size:11.5px}.btn{border:0;border-radius:9px;background:linear-gradient(135deg,var(--gold2),var(--gold));padding:12px 18px;font-weight:800;font-size:11.5px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;color:#102039}
.metrics{display:grid;grid-template-columns:repeat(5,1fr);gap:11px;margin-bottom:15px}.metric{background:#fff;border:1px solid var(--line);border-radius:13px;padding:15px}.metric small{color:#8492a4;font-size:9.5px}.metric strong{display:block;font-size:23px;margin:7px 0 2px}.trend{font-size:9.5px;color:#159367}
.toolbar{background:linear-gradient(135deg,#101c2b,#172638);padding:14px;border-radius:16px;margin-bottom:12px}.filters{display:grid;grid-template-columns:1.7fr 1fr 1fr 1fr auto;gap:8px}.input,.select{height:40px;border:0;border-radius:9px;padding:0 12px;font-size:11.5px;background:#fff;color:#25364c;outline:0;width:100%}.filter{border:0;border-radius:9px;padding:0 18px;background:linear-gradient(135deg,var(--gold2),var(--gold));font-size:11.5px;font-weight:800;cursor:pointer}.advanced{display:flex;justify-content:space-between;color:#8796aa;font-size:9.5px;margin-top:9px}.advanced b{color:#e1bd68}
.card{background:#fff;border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:0 18px 50px rgba(16,31,51,.08)}.cardhead{padding:15px 17px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between}.cardhead strong{font-size:12.5px}.cardhead span{font-size:9.5px;color:var(--muted)}.scroll{overflow:auto}table{width:100%;border-collapse:collapse;min-width:1000px}th{padding:12px;text-align:left;background:#f8fafc;color:#74849a;font-size:9.5px;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid var(--line)}td{padding:12px;border-bottom:1px solid #edf1f5;font-size:10.5px;white-space:nowrap}tr:hover td{background:#fbfcfd}.ref{font-weight:800}.track{font-family:monospace;color:#62748a;font-size:9.5px}.status{display:inline-block;padding:5px 8px;border-radius:20px;font-size:8.5px;font-weight:800}
.booked{color:#1383bf;background:#eaf6fd;border:1px solid #cceafa}
.transit{color:#a17008;background:#fff6dc;border:1px solid #f2dfaa}
.delivered{color:#159367;background:#eefbf5;border:1px solid #c3f1de}
.cancelled{color:#d93838;background:#fdf2f2;border:1px solid #f8d7d7}
.action{border:1px solid #e0e6ed;background:#fff;border-radius:7px;padding:6px 8px;font-size:9.5px;font-weight:700;text-decoration:none;color:#102039;display:inline-block}
.labelbtn{border:0;background:transparent;color:#a97917;cursor:pointer;font-size:9.5px}
.pagination{padding:14px 17px;display:flex;justify-content:space-between;color:#7a899b;font-size:9.5px}.pages button{border:1px solid var(--line);background:#fff;border-radius:7px;min-width:27px;height:27px;font-size:10px}.pages .active{background:#101c2d;color:#fff}
@media(max-width:1100px){.metrics{grid-template-columns:repeat(3,1fr)}.filters{grid-template-columns:1.5fr 1fr 1fr auto}.filters select:nth-child(4){display:none}}
@media(max-width:800px){.side{display:none}.main{margin:0}.top{padding:0 15px}.content{padding:20px 15px}.metrics{grid-template-columns:1fr 1fr}.filters{grid-template-columns:1fr 1fr}.filters .search{grid-column:1/-1}}
@media(max-width:520px){.metrics{grid-template-columns:1fr}.heading{align-items:start;gap:15px;flex-direction:column}.filters{grid-template-columns:1fr}}
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
    <h2>Shipments Operations — Admin Portal</h2>
    <span>Signed in as <b><?= e($adminUser['name'] ?? 'Admin User') ?></b> (<?= e(strtoupper($adminUser['role_name'] ?? 'ADMIN')) ?>)</span>
  </header>
  <section class="content">
    <div class="heading">
      <div>
        <h1>Shipment Operations</h1>
        <p>Monitor, filter and manage every shipment moving through the RC Courier network.</p>
      </div>
      <a href="<?= \App\Core\View::url('/admin/shipments/create') ?>" class="btn">＋ Create Shipment</a>
    </div>

    <div class="metrics">
      <div class="metric">Total Shipments<strong><?= e(number_format($metrics['total'] ?? count($shipments))) ?> <i class="trend">+8.4%</i></strong><small>vs. previous 30 days</small></div>
      <div class="metric">Booked<strong><?= e(number_format($metrics['booked'] ?? 0)) ?></strong><small>Active bookings</small></div>
      <div class="metric">In Transit<strong><?= e(number_format($metrics['in_transit'] ?? 0)) ?></strong><small>Currently moving</small></div>
      <div class="metric">Delivered<strong><?= e(number_format($metrics['delivered'] ?? 0)) ?> <i class="trend">+12.1%</i></strong><small>Successful deliveries</small></div>
      <div class="metric">Revenue<strong><?= e(number_format($metrics['revenue'] ?? 0, 2)) ?> AED</strong><small>Current period</small></div>
    </div>

    <form class="toolbar" action="<?= \App\Core\View::url('/admin/shipments') ?>" method="GET">
      <div class="filters">
        <input id="search" name="q" class="input search" value="<?= e($search) ?>" placeholder="Search reference, tracking #, customer...">
        <select id="status" name="status" class="select">
          <option value="">All Statuses</option>
          <option value="BOOKED" <?= $status === 'BOOKED' ? 'selected' : '' ?>>Booked</option>
          <option value="IN_TRANSIT" <?= $status === 'IN_TRANSIT' ? 'selected' : '' ?>>In Transit</option>
          <option value="DELIVERED" <?= $status === 'DELIVERED' ? 'selected' : '' ?>>Delivered</option>
          <option value="CANCELLED" <?= $status === 'CANCELLED' ? 'selected' : '' ?>>Cancelled</option>
        </select>
        <select class="select">
          <option>All Services</option>
          <option>Same-Day Express UAE</option>
          <option>Next-Day Delivery</option>
          <option>GCC Overland</option>
        </select>
        <select class="select">
          <option>All Dates</option>
          <option>Today</option>
          <option>Last 7 Days</option>
          <option>Last 30 Days</option>
        </select>
        <button class="filter" type="submit">Filter</button>
      </div>
      <div class="advanced">＋ Live MySQL Database <b>Table View</b></div>
    </form>

    <div class="card">
      <div class="cardhead">
        <strong>All Shipments</strong>
        <span id="count">Showing <?= count($shipments) ?> of <?= e($metrics['total'] ?? count($shipments)) ?> shipments</span>
      </div>
      <div class="scroll">
        <table>
          <thead>
            <tr>
              <th>Reference</th>
              <th>Tracking Number</th>
              <th>Customer</th>
              <th>Service</th>
              <th>Status</th>
              <th>Total (AED)</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($shipments as $s): ?>
              <?php 
                $stClass = strtolower(str_replace('_', '', $s['status']));
                if ($stClass === 'intransit') $stClass = 'transit';
              ?>
              <tr>
                <td class="ref"><a href="<?= \App\Core\View::url('/admin/shipments/' . $s['id']) ?>" style="color:inherit;text-decoration:none;"><?= e($s['reference_number']) ?></a></td>
                <td class="track"><?= e($s['tracking_number']) ?></td>
                <td><?= e($s['company_name'] ?: $s['contact_name']) ?></td>
                <td><?= e($s['service_name']) ?></td>
                <td><span class="status <?= $stClass ?>"><?= e($s['status']) ?></span></td>
                <td><b><?= e(number_format($s['total'], 2)) ?> AED</b></td>
                <td><?= e(date('M d, Y', strtotime($s['created_at']))) ?></td>
                <td>
                  <a href="<?= \App\Core\View::url('/admin/shipments/' . $s['id'] . '/edit') ?>" class="action" style="color:#b78b35;font-weight:800;">Edit</a>
                  <a href="<?= \App\Core\View::url('/admin/shipments/' . $s['id']) ?>" class="action">Manage</a>
                  <a href="<?= \App\Core\View::url('/shipments/' . $s['id'] . '/label') ?>" target="_blank" class="action labelbtn">Label</a>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($shipments)): ?>
              <tr><td colspan="8" style="text-align:center; color:#94a3b8; padding:2rem;">No shipments matching search criteria.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="pagination">
        <span>Rows per page: <b><?= count($shipments) ?></b></span>
        <div class="pages">
          <button type="button">‹</button>
          <button type="button" class="active">1</button>
          <button type="button">›</button>
        </div>
        <span>Showing <?= count($shipments) ?> records</span>
      </div>
    </div>
  </section>
</main>
</body>
</html>
