<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title ?? 'RC Courier — Shipment Tracking') ?></title>
<meta name="description" content="RC Courier UAE shipment tracking and delivery status portal.">
<!-- Favicons -->
<link rel="icon" type="image/png" sizes="32x32" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">
<link rel="icon" type="image/png" sizes="192x192" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
<link rel="shortcut icon" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#f5f8fc;
  --surface:#ffffff;
  --surface-2:#f8fafc;
  --ink:#0b1830;
  --muted:#66758b;
  --line:#dce4ee;
  --gold:#dcae3f;
  --gold-2:#f3c85c;
  --blue:#138fd1;
  --green:#13a56b;
  --red:#e05252;
  --shadow:0 24px 70px rgba(15,32,54,.10);
  --radius:22px;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{
  margin:0;
  font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
  color:var(--ink);
  background:
    radial-gradient(circle at 50% 0%,rgba(220,174,63,.10),transparent 28%),
    linear-gradient(180deg,#f8fafc 0%,#eef3f8 100%);
  line-height:1.5;
}
a{text-decoration:none;color:inherit}
.container{width:min(1120px,calc(100% - 40px));margin:auto}
.topbar{
  height:76px;background:#07101f;color:#fff;border-bottom:1px solid #1d2b40;
  position:sticky;top:0;z-index:50;
}
.nav{height:100%;display:flex;align-items:center;justify-content:space-between;gap:24px}
.brand{display:flex;align-items:center;gap:11px;font-weight:900;letter-spacing:-.5px}
.logo{
  width:42px;height:42px;border:1px solid rgba(243,200,92,.65);border-radius:12px;
  display:grid;place-items:center;color:var(--gold-2);font-weight:900;background:#101b2c;
}
.brand small{display:block;color:#eabf52;font-size:8px;letter-spacing:1.2px;margin-top:-2px}
.navlinks{display:flex;gap:25px;color:#bdc9d9;font-size:13px;font-weight:600}
.navlinks a:hover{color:#fff}
.nav-actions{display:flex;gap:10px}
.btn{
  border:1px solid var(--line);border-radius:11px;padding:11px 17px;
  background:#fff;color:var(--ink);font-weight:800;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;
}
.btn-gold{background:linear-gradient(135deg,#f5cb68,#dcae3f);border-color:#e1b34b;color:#101827}
.btn-dark{background:#0b1830;border-color:#26364d;color:#fff}
.hero{padding:90px 0 45px;text-align:center}
.eyebrow{
  color:#b18221;text-transform:uppercase;letter-spacing:2.5px;font-size:11px;font-weight:900;
  display:inline-flex;align-items:center;gap:10px;
}
.eyebrow:before,.eyebrow:after{content:"";width:30px;height:1px;background:#d6aa45}
.hero h1{font-size:clamp(42px,6vw,74px);line-height:.98;letter-spacing:-4px;margin:20px auto 18px;max-width:850px}
.hero h1 span{color:#d7a52f}
.hero p{max-width:700px;margin:auto;color:var(--muted);font-size:15px}
.search{
  max-width:760px;margin:32px auto 0;padding:9px;background:#fff;border:1px solid var(--line);
  border-radius:17px;box-shadow:var(--shadow);display:flex;gap:9px;
}
.search input{
  flex:1;min-width:0;border:0;outline:0;background:#f5f7fa;border-radius:11px;
  padding:16px 18px;font-size:15px;color:var(--ink)
}
.search button{min-width:160px}
.examples{margin-top:12px;font-size:11px;color:#7b899c}
.examples b{color:#31415a}.examples a{color:inherit;text-decoration:underline}
.result{
  margin:35px auto 100px;max-width:900px;background:var(--surface);border:1px solid var(--line);
  border-radius:24px;box-shadow:var(--shadow);overflow:hidden;
}
.result-head{
  padding:25px 28px;background:#0b1830;color:#fff;display:flex;justify-content:space-between;align-items:center;gap:20px
}
.ref-label{text-transform:uppercase;font-size:9px;letter-spacing:1.7px;color:#91a3ba}
.reference{font-size:24px;font-weight:900;margin-top:4px}
.status{
  padding:8px 13px;border-radius:999px;background:rgba(19,165,107,.13);
  color:#57dda4;border:1px solid rgba(19,165,107,.45);font-size:10px;font-weight:900;letter-spacing:.7px
}
.parties{
  display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:24px 28px;background:#fbfcfe;border-bottom:1px solid var(--line)
}
.party{padding:18px;border:1px solid var(--line);border-radius:16px;background:#fff}
.party .label{font-size:10px;font-weight:900;color:#7b899c;text-transform:uppercase;letter-spacing:1px}
.party .name{font-size:18px;font-weight:900;margin:7px 0 3px}
.party .place{font-size:12px;color:var(--muted)}
.ship-date{
  margin-top:14px;padding-top:13px;border-top:1px solid var(--line);font-size:12px;
}
.ship-date b{color:var(--ink)}
.details{display:grid;grid-template-columns:1.4fr .8fr}
.journey{padding:28px}
.section-title{font-size:14px;font-weight:900;margin-bottom:23px}
.timeline{position:relative}
.timeline:before{
  content:"";position:absolute;left:12px;top:10px;bottom:12px;width:2px;background:#dce5ee
}
.event{position:relative;padding:0 0 25px 38px}
.event:last-child{padding-bottom:0}
.dot{
  position:absolute;left:5px;top:1px;width:16px;height:16px;border-radius:50%;
  background:#fff;border:2px solid #cbd6e3;z-index:1
}
.event.done .dot{background:#18a971;border-color:#18a971;box-shadow:0 0 0 4px rgba(24,169,113,.10)}
.event.current .dot{background:#dcae3f;border-color:#dcae3f;box-shadow:0 0 0 5px rgba(220,174,63,.13)}
.event-title{font-size:13px;font-weight:900}
.event-meta{font-size:11px;color:var(--muted);margin-top:3px}
.event-time{font-size:10px;color:#8291a4;margin-top:3px}
.side{
  border-left:1px solid var(--line);padding:28px;background:#fbfcfe
}
.info{padding:14px 0;border-bottom:1px solid var(--line)}
.info:first-of-type{padding-top:0}
.info:last-of-type{border-bottom:0}
.info label{display:block;text-transform:uppercase;letter-spacing:1px;font-size:8px;font-weight:900;color:#8b98aa}
.info strong{display:block;font-size:13px;margin-top:4px}
.side-actions{display:flex;gap:8px;margin-top:18px;flex-wrap:wrap}
.feature-section{padding:80px 0}
.section-heading{text-align:center;max-width:680px;margin:auto}
.section-heading h2{font-size:42px;line-height:1.05;letter-spacing:-2px;margin:15px 0 10px}
.section-heading p{color:var(--muted)}
.features{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:35px}
.feature{
  background:#fff;border:1px solid var(--line);border-radius:18px;padding:24px;box-shadow:0 12px 35px rgba(15,32,54,.06)
}
.icon{width:42px;height:42px;border-radius:12px;background:#fff7df;color:#ad7c16;display:grid;place-items:center;font-weight:900;border:1px solid #f0ddb0}
.feature h3{font-size:15px;margin:16px 0 6px}
.feature p{font-size:11px;color:var(--muted);margin:0}
.stats{
  margin:0 0 75px;border:1px solid var(--line);background:#fff;border-radius:18px;display:grid;grid-template-columns:repeat(4,1fr);overflow:hidden
}
.stat{text-align:center;padding:24px;border-right:1px solid var(--line)}
.stat:last-child{border-right:0}
.stat strong{display:block;color:#c08d20;font-size:25px}
.stat span{font-size:10px;color:var(--muted)}
.cta{
  background:linear-gradient(135deg,#d69e28,#f1c454);border-radius:20px;padding:27px 30px;
  display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:80px
}
.cta h3{margin:0;font-size:22px}.cta p{margin:4px 0 0;font-size:11px;color:#4f3a0d}
footer{background:#07101f;color:#9aaabd;padding:55px 0 25px}
.footer-grid{display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr;gap:35px}
footer h4{color:#fff;margin:0 0 15px;font-size:13px}
footer p,footer a{font-size:11px;color:#8fa0b4;display:block;margin:8px 0}
.footer-brand{color:#fff;font-weight:900;font-size:16px}
.footer-brand span{color:#e9bc4f}
.copyright{border-top:1px solid #1b293c;margin-top:35px;padding-top:20px;display:flex;justify-content:space-between;font-size:10px}
.toast{position:fixed;right:25px;bottom:25px;background:#10203a;color:#fff;border-radius:12px;padding:14px 17px;box-shadow:0 15px 40px rgba(0,0,0,.18);display:none;font-weight:700;z-index:99}
@media(max-width:850px){
  .navlinks{display:none}
  .container{width:min(100% - 24px,720px)}
  .hero{padding-top:60px}
  .hero h1{letter-spacing:-2px}
  .parties,.details{grid-template-columns:1fr}
  .side{border-left:0;border-top:1px solid var(--line)}
  .features{grid-template-columns:1fr 1fr}
  .footer-grid{grid-template-columns:1fr 1fr}
}
@media(max-width:600px){
  .search{flex-direction:column}
  .search button{width:100%}
  .result-head{align-items:flex-start;flex-direction:column}
  .parties,.journey,.side{padding:20px}
  .features,.stats{grid-template-columns:1fr}
  .stat{border-right:0;border-bottom:1px solid var(--line)}
  .stat:last-child{border-bottom:0}
  .cta{flex-direction:column;align-items:flex-start}
  .footer-grid{grid-template-columns:1fr}
  .copyright{flex-direction:column;gap:8px}
}
@media print{
  .topbar,.hero,.feature-section,.stats,.cta,footer{display:none!important}
  body{background:#fff}
  .result{box-shadow:none;border:1px solid #bbb;margin:0;max-width:none}
  .result-head{background:#fff;color:#000;border-bottom:1px solid #bbb}
  .status{color:#087a4e;border-color:#087a4e;background:#fff}
  .parties,.side{background:#fff}
}
</style>
</head>
<body>

<header class="topbar">
  <div class="container nav">
    <a class="brand" href="<?= \App\Core\View::url('/') ?>" style="display:flex; align-items:center; gap:12px; text-decoration:none;">
      <img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" srcset="<?= \App\Core\View::url('/assets/images/rc_logo_256.png') ?> 2x, <?= \App\Core\View::url('/assets/images/rc_logo_hd.png') ?> 3x" alt="RC Courier Logo" style="height: 48px; width: 48px; border-radius: 10px; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.3); border: 1.5px solid rgba(241,196,94,0.45); image-rendering: -webkit-optimize-contrast;">
      <div class="brand-text" style="display:flex; flex-direction:column; justify-content:center;">
        <span style="font-family:'Manrope','Inter',sans-serif; font-size: 20px; font-weight: 900; color: #ffffff; letter-spacing: -0.4px; line-height: 1.1;">RC COURIER</span>
        <small style="font-family:'Inter',sans-serif; font-size: 9px; font-weight: 700; color: #f1c45e; letter-spacing: 1px; text-transform: uppercase; margin-top: 2px;">UAE • GCC LOGISTICS</small>
      </div>
    </a>
    <nav class="navlinks">
      <a href="<?= \App\Core\View::url('/services') ?>">Services</a>
      <a href="<?= \App\Core\View::url('/track') ?>">Track Shipment</a>
      <a href="<?= \App\Core\View::url('/quote') ?>">Get Quote</a>
      <a href="<?= \App\Core\View::url('/book') ?>">Book Shipment</a>
      <a href="<?= \App\Core\View::url('/locations') ?>">Locations</a>
      <a href="<?= \App\Core\View::url('/about') ?>">About Us</a>
      <a href="<?= \App\Core\View::url('/contact') ?>">Contact</a>
    </nav>
    <div class="nav-actions">
      <?php $user = \App\Core\Session::get('user'); ?>
      <?php if ($user): ?>
          <?php $dashUrl = ($user['role_name'] === 'customer') ? '/customer' : '/admin'; ?>
          <a href="<?= \App\Core\View::url($dashUrl) ?>" class="btn btn-gold">Dashboard</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<main>
<section class="hero">
  <div class="container">
    <div class="eyebrow">RC Courier Shipment Intelligence</div>
    <h1>Track every shipment.<br><span>Know every milestone.</span></h1>
    <p>Enter your Waybill Reference or Tracking Number to view shipment status, movement history, estimated delivery and proof-of-delivery information.</p>

    <form class="search" action="<?= \App\Core\View::url('/track') ?>" method="GET" id="trackingForm">
      <input id="trackingInput" name="number" value="<?= e($number ?? '') ?>" placeholder="Enter tracking or waybill reference" aria-label="Tracking number" required>
      <button class="btn btn-gold" type="submit">Track Shipment</button>
    </form>
    <div class="examples">
      Try demo: <a href="<?= \App\Core\View::url('/track?number=RC98412503') ?>"><b>RC98412503</b></a> &nbsp;•&nbsp; Waybill: <a href="<?= \App\Core\View::url('/track?number=SHP-2026-000001') ?>"><b>SHP-2026-000001</b></a>
    </div>
  </div>
</section>

<section class="container">
  <?php if (!empty($searched)): ?>
    <?php if (!empty($trackingInfo)): ?>
      <article class="result" id="trackingResult">
        <div class="result-head">
          <div>
            <div class="ref-label">Shipment Reference</div>
            <div class="reference" id="ref"><?= e($trackingInfo['tracking_number'] ?? $trackingInfo['reference_number']) ?></div>
          </div>
          <div class="status" id="status">● <?= e(str_replace('_', ' ', strtoupper($trackingInfo['status']))) ?></div>
        </div>

        <div class="parties">
          <div class="party">
            <div class="label">Sender / Shipper</div>
            <div class="name" id="sender"><?= e($trackingInfo['sender'] ?? 'Shipper') ?></div>
            <div class="ship-date">Shipment Date: <b id="shipDate"><?= date('d M Y', strtotime($trackingInfo['created_at'] ?? 'now')) ?></b></div>
          </div>
          <div class="party">
            <div class="label">Receiver / Consignee</div>
            <div class="name" id="receiver"><?= e($trackingInfo['receiver'] ?? 'Valued Consignee') ?></div>
          </div>
        </div>

        <div class="details">
          <div class="journey">
            <div class="section-title">Shipment Journey</div>
            <div class="timeline">
              <?php 
              $timelineList = $trackingInfo['timeline'] ?? [];
              $totalEvents = count($timelineList);
              foreach ($timelineList as $idx => $ev): 
                $isDone = ($idx < $totalEvents - 1 || $trackingInfo['status'] === 'DELIVERED');
                $isCurrent = ($idx === $totalEvents - 1 && $trackingInfo['status'] !== 'DELIVERED');
                $cssClass = $isDone ? 'done' : ($isCurrent ? 'current' : '');
              ?>
                <div class="event <?= $cssClass ?>">
                  <span class="dot"></span>
                  <div class="event-title"><?= e(str_replace('_', ' ', strtoupper($ev['status']))) ?></div>
                  <div class="event-meta"><?= e($ev['location_name'] ?? 'Processing Station') ?> — <?= e($ev['public_notes'] ?? 'Milestone updated') ?></div>
                  <div class="event-time"><?= date('d M Y • h:i A', strtotime($ev['event_time'] ?? 'now')) ?></div>
                </div>
              <?php endforeach; ?>

              <?php if (empty($timelineList)): ?>
                <div class="event done">
                  <span class="dot"></span>
                  <div class="event-title"><?= e(str_replace('_', ' ', strtoupper($trackingInfo['status'] ?? 'BOOKED'))) ?></div>
                  <div class="event-meta"><?= e($trackingInfo['origin'] ?? 'Dubai') ?> Processing Center — Order processed</div>
                  <div class="event-time"><?= date('d M Y • h:i A', strtotime($trackingInfo['created_at'] ?? 'now')) ?></div>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <aside class="side">
            <div class="section-title">Shipment Details</div>
            <div class="info"><label>Service</label><strong id="service"><?= e($trackingInfo['service'] ?? 'Express Courier') ?></strong></div>
            <div class="info"><label>Origin</label><strong id="origin2"><?= e($trackingInfo['origin'] ?? 'Dubai') ?></strong></div>
            <div class="info"><label>Destination</label><strong id="destination2"><?= e($trackingInfo['destination'] ?? 'Abu Dhabi') ?></strong></div>
            <div class="info"><label>Package</label><strong>1 Parcel • <?= e($trackingInfo['weight_kg'] ?? '1.00') ?> KG</strong></div>
            <div class="info"><label>Last Updated</label><strong><?= date('d M Y • h:i A', strtotime($trackingInfo['updated_at'] ?? $trackingInfo['created_at'])) ?></strong></div>
            <div class="side-actions">
              <button class="btn btn-gold" onclick="window.print()">Print</button>
              <button class="btn btn-dark" onclick="showToast('Digital Proof of Delivery (POD) available in customer account.')">Proof of Delivery</button>
            </div>
          </aside>
        </div>
      </article>
    <?php else: ?>
      <article class="result" style="padding: 40px; text-align: center; border-color: #fecaca;">
        <h3 style="color: #c92e3a; font-size: 20px; margin-bottom: 10px;">Shipment Not Found</h3>
        <p style="color: #61778b; font-size: 14px;">No shipment record matched "<strong><?= e($number) ?></strong>". Please verify your Waybill or Tracking ID and try again.</p>
      </article>
    <?php endif; ?>
  <?php endif; ?>
</section>

<section class="feature-section">
  <div class="container">
    <div class="section-heading">
      <div class="eyebrow">Built for visibility</div>
      <h2>A better tracking experience for UAE customers.</h2>
      <p>Give customers a clean, trustworthy view of their shipment without exposing internal operational data.</p>
    </div>
    <div class="features">
      <div class="feature"><div class="icon">◉</div><h3>Real-Time Milestones</h3><p>Display booking, pickup, hub processing, dispatch, out-for-delivery and delivery events from your shipment database.</p></div>
      <div class="feature"><div class="icon">✦</div><h3>Smart ETA</h3><p>Show an estimated delivery window based on service, route and operational status.</p></div>
      <div class="feature"><div class="icon">✓</div><h3>Digital POD</h3><p>Make delivery confirmation, recipient details and proof-of-delivery information easy to access.</p></div>
      <div class="feature"><div class="icon">⌁</div><h3>UAE + GCC Coverage</h3><p>Present UAE and GCC routes with location-aware shipment milestones.</p></div>
      <div class="feature"><div class="icon">▣</div><h3>Secure Customer View</h3><p>Expose only the shipment information the customer is authorized to see.</p></div>
      <div class="feature"><div class="icon">↗</div><h3>Operations Ready</h3><p>Designed to connect cleanly to a PHP/MySQL shipment management system.</p></div>
    </div>
  </div>
</section>

<section class="container">
  <div class="stats">
    <div class="stat"><strong>7</strong><span>UAE Emirates</span></div>
    <div class="stat"><strong>6</strong><span>GCC Markets</span></div>
    <div class="stat"><strong>24/7</strong><span>Online Tracking</span></div>
    <div class="stat"><strong>220+</strong><span>Global Destinations</span></div>
  </div>

  <div class="cta">
    <div><h3>Can't find your shipment?</h3><p>Speak with the RC Courier support team and provide your reference number.</p></div>
    <div style="display:flex;gap:10px">
      <a class="btn btn-dark" href="<?= \App\Core\View::url('/contact') ?>">Contact Support</a>
      <button class="btn" onclick="document.getElementById('trackingInput').focus()">Track Another</button>
    </div>
  </div>
</section>
</main>

<footer>
  <div class="container">
    <div class="footer-grid">
      <div>
        <a class="brand" href="<?= \App\Core\View::url('/') ?>" style="display:flex; align-items:center; gap:10px; text-decoration:none; margin-bottom:12px;">
          <img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" srcset="<?= \App\Core\View::url('/assets/images/rc_logo_256.png') ?> 2x, <?= \App\Core\View::url('/assets/images/rc_logo_hd.png') ?> 3x" alt="RC Courier Logo" style="height: 40px; width: 40px; border-radius: 8px; object-fit: cover; border: 1px solid rgba(241,196,94,0.3); image-rendering: -webkit-optimize-contrast;">
          <div class="brand-text" style="display:flex; flex-direction:column; justify-content:center;">
            <span style="font-family:'Manrope','Inter',sans-serif; font-size: 18px; font-weight: 900; color: #ffffff; letter-spacing: -0.4px; line-height: 1.1;">RC COURIER</span>
            <small style="font-family:'Inter',sans-serif; font-size: 8.5px; font-weight: 700; color: #f1c45e; letter-spacing: 0.8px; text-transform: uppercase; margin-top: 2px;">UAE • GCC LOGISTICS</small>
          </div>
        </a>
        <p>Premium courier, express delivery and logistics solutions across all 7 UAE Emirates, GCC and international destinations.</p>
      </div>
      <div><h4>Services</h4><a href="<?= \App\Core\View::url('/services') ?>">Same-Day Delivery</a><a href="<?= \App\Core\View::url('/services') ?>">Next-Day Delivery</a><a href="<?= \App\Core\View::url('/services') ?>">GCC Road Freight</a><a href="<?= \App\Core\View::url('/services') ?>">International Air</a><a href="<?= \App\Core\View::url('/services') ?>">E-Commerce Logistics</a></div>
      <div><h4>Company</h4><a href="<?= \App\Core\View::url('/about') ?>">About RC Courier</a><a href="<?= \App\Core\View::url('/locations') ?>">Our Hubs & Coverage</a><a href="<?= \App\Core\View::url('/contact') ?>">Contact Us</a><a href="<?= \App\Core\View::url('/contact') ?>">Corporate Solutions</a></div>
      <div><h4>Quick Links</h4><a href="<?= \App\Core\View::url('/track') ?>">Track Shipment</a><a href="<?= \App\Core\View::url('/quote') ?>">Get a Quote</a><a href="<?= \App\Core\View::url('/book') ?>">Book Shipment</a><p style="margin-top:10px;font-size:11px;">Dubai, United Arab Emirates</p><p style="font-size:11px;">support@rccourier.ae</p></div>
    </div>
    <div class="copyright"><span>© <?= date('Y') ?> RC Courier UAE LLC. All rights reserved.</span><span>UAE • GCC • Worldwide</span></div>
  </div>
</footer>

<div class="toast" id="toast"></div>

<script>
function showToast(msg){
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.display = 'block';
  setTimeout(() => { t.style.display = 'none'; }, 3200);
}
</script>
</body>
</html>
