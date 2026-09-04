<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="description" content="RC Courier UAE locations — hubs, service coverage and logistics network across the UAE and GCC.">
<title><?= e($title ?? 'Locations | RC Courier UAE') ?></title>
<!-- Favicons -->
<link rel="icon" type="image/png" sizes="32x32" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">
<link rel="icon" type="image/png" sizes="192x192" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
<link rel="shortcut icon" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root{--bg:#050a12;--bg2:#08111c;--panel:#0c1624;--panel2:#101d2c;--gold:#dca83f;--gold2:#f4cc70;--white:#f8fafc;--muted:#8998ac;--line:rgba(255,255,255,.085);--blue:#42b5ff;--green:#39c88b;--shadow:0 30px 90px rgba(0,0,0,.4)}
*{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}body{font-family:Inter,Arial,sans-serif;background:var(--bg);color:var(--white);line-height:1.5}a{text-decoration:none;color:inherit}.container{width:min(1120px,92%);margin:auto}
.topbar{height:74px;position:sticky;top:0;z-index:50;background:rgba(5,10,18,.91);backdrop-filter:blur(18px);border-bottom:1px solid var(--line)}.nav{height:100%;display:flex;align-items:center;justify-content:space-between;gap:22px}.brand{display:flex;align-items:center;gap:9px;font-family:Manrope;font-weight:800;white-space:nowrap}.logo{width:38px;height:38px;border-radius:11px;display:grid;place-items:center;color:var(--gold2);border:1px solid rgba(220,168,63,.5);background:linear-gradient(145deg,#172334,#080d15);font-size:13px}.brand span{font-size:15px;letter-spacing:-.045em}.brand small{display:block;color:var(--gold);font:700 6px Inter;letter-spacing:.12em}.links{display:flex;gap:21px;color:#aebaca;font-size:11px}.links a:hover{color:#fff}.actions{display:flex;gap:8px}.btn{border:0;border-radius:11px;padding:11px 17px;font-size:11px;font-weight:800;transition:.25s;display:inline-flex;align-items:center;justify-content:center;}.btn-gold{background:linear-gradient(135deg,var(--gold2),var(--gold));color:#10151c;box-shadow:0 10px 30px rgba(220,168,63,.14)}.btn-gold:hover{transform:translateY(-2px);box-shadow:0 15px 35px rgba(220,168,63,.28)}.btn-dark{background:#0d1724;color:#fff;border:1px solid var(--line)}.menu{display:none;background:none;border:0;color:#fff;font-size:24px}
.hero{position:relative;overflow:hidden;padding:70px 0 78px;text-align:center;background:radial-gradient(circle at 50% 0,rgba(220,168,63,.14),transparent 32%),radial-gradient(circle at 8% 65%,rgba(66,181,255,.09),transparent 25%),linear-gradient(180deg,#070d17,#050a12)}.hero:before{content:"";position:absolute;inset:0;opacity:.18;background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);background-size:70px 70px;mask-image:linear-gradient(#000,transparent)}.eyebrow{position:relative;display:inline-flex;align-items:center;gap:9px;color:var(--gold2);font-size:10px;font-weight:800;letter-spacing:.17em;text-transform:uppercase}.eyebrow:before,.eyebrow:after{content:"";height:1px;width:25px;background:var(--gold)}.hero h1{position:relative;font:800 clamp(40px,6vw,64px)/1.02 Manrope;letter-spacing:-.065em;margin:16px auto 14px}.hero h1 span{background:linear-gradient(90deg,#fff,var(--gold2));color:transparent;background-clip:text;-webkit-background-clip:text}.hero p{position:relative;max-width:700px;margin:auto;color:var(--muted);font-size:12px}.hero-stats{position:relative;display:flex;justify-content:center;gap:10px;margin-top:29px;flex-wrap:wrap}.stat{padding:10px 17px;border:1px solid var(--line);background:rgba(10,20,32,.68);border-radius:30px;font-size:9px;color:#91a0b3}.stat strong{color:var(--gold2);font-size:12px;margin-right:4px}
.network{padding:0 0 80px}.network-card{margin-top:-34px;position:relative;z-index:2;display:grid;grid-template-columns:1fr 1.05fr;gap:0;border:1px solid var(--line);border-radius:22px;overflow:hidden;background:linear-gradient(145deg,#0d1826,#09121e);box-shadow:var(--shadow)}.map{min-height:450px;position:relative;overflow:hidden;background:radial-gradient(circle at 48% 45%,rgba(220,168,63,.14),transparent 16%),radial-gradient(circle at 50% 50%,rgba(66,181,255,.08),transparent 42%),#07101a}.map:before{content:"";position:absolute;inset:0;background-image:linear-gradient(30deg,transparent 48%,rgba(255,255,255,.04) 49%,transparent 50%),linear-gradient(120deg,transparent 48%,rgba(255,255,255,.04) 49%,transparent 50%);background-size:52px 52px;transform:scale(1.3) rotate(-7deg);opacity:.45}.map:after{content:"UAE  •  GCC  •  INTERNATIONAL";position:absolute;left:26px;bottom:23px;color:#4d6076;font-size:8px;letter-spacing:.18em}.route{position:absolute;width:67%;height:48%;left:20%;top:23%;border:1px dashed rgba(220,168,63,.35);border-radius:48% 55% 42% 58%;transform:rotate(-14deg)}.route2{position:absolute;width:46%;height:26%;left:35%;top:39%;border-top:1px dashed rgba(66,181,255,.4);transform:rotate(18deg)}.pin{position:absolute;width:14px;height:14px;border-radius:50%;background:var(--gold);box-shadow:0 0 0 6px rgba(220,168,63,.1),0 0 24px rgba(220,168,63,.4);z-index:2}.pin:after{content:"";position:absolute;width:5px;height:5px;background:#fff;border-radius:50%;left:4.5px;top:4.5px}.pin.dubai{left:50%;top:43%}.pin.abudhabi{left:39%;top:57%}.pin.sharjah{left:52%;top:35%}.pin.ajman{left:53%;top:31%}.pin.rak{left:62%;top:23%}.pin.fujairah{left:68%;top:38%}.pin.uaq{left:57%;top:28%}.map-label{position:absolute;font-size:8px;font-weight:800;color:#dce5ef;z-index:3}.map-label.d{left:53%;top:45%}.map-label.a{left:33%;top:61%}.map-label.s{left:55%;top:31%}.map-label.r{left:65%;top:19%}.map-center{position:absolute;left:24px;top:22px;padding:9px 11px;border-radius:11px;background:rgba(6,13,22,.82);border:1px solid var(--line);font-size:8px;color:#8fa0b5;backdrop-filter:blur(10px)}.map-center strong{display:block;color:#fff;font-size:10px;margin-bottom:2px}.location-list{padding:29px}.section-head{display:flex;justify-content:space-between;align-items:flex-end;gap:15px;margin-bottom:18px}.section-head h2{font:800 22px Manrope;letter-spacing:-.04em}.section-head p{font-size:9px;color:#66778c;margin-top:4px}.live{color:#80dcb3;font-size:8px;font-weight:800;background:rgba(56,200,139,.07);border:1px solid rgba(56,200,139,.15);padding:7px 9px;border-radius:20px;white-space:nowrap}.locations{display:grid;gap:9px}.loc{display:grid;grid-template-columns:36px 1fr auto;align-items:center;gap:11px;padding:12px;border:1px solid var(--line);background:#08121f;border-radius:13px;transition:.2s}.loc:hover{border-color:rgba(220,168,63,.35);transform:translateX(2px)}.loc-icon{width:32px;height:32px;display:grid;place-items:center;border-radius:10px;color:var(--gold2);background:rgba(220,168,63,.08);font-size:10px}.loc h3{font-size:10px}.loc p{font-size:7px;color:#607087;margin-top:2px}.status{font-size:7px;font-weight:800;color:#72dca9;display:flex;align-items:center;gap:5px}.status:before{content:"";width:5px;height:5px;border-radius:50%;background:#39c88b;box-shadow:0 0 9px #39c88b}.coverage{margin-top:12px;padding:12px;border-radius:13px;background:linear-gradient(90deg,rgba(66,181,255,.06),rgba(220,168,63,.05));border:1px solid var(--line);font-size:8px;color:#6d7e92}.coverage strong{color:#dce5ef}
.services{padding:80px 0;background:#070e18;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}.title{text-align:center;margin-bottom:35px}.title .eyebrow{font-size:8px}.title h2{font:800 31px Manrope;letter-spacing:-.05em;margin:11px 0 5px}.title p{color:#68788d;font-size:10px}.service-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:11px}.service{padding:21px;border:1px solid var(--line);border-radius:16px;background:linear-gradient(145deg,#0d1826,#09121e);transition:.25s}.service:hover{transform:translateY(-5px);border-color:rgba(220,168,63,.28);box-shadow:0 20px 50px rgba(0,0,0,.25)}.service .icon{width:36px;height:36px;display:grid;place-items:center;border-radius:10px;color:var(--gold2);background:rgba(220,168,63,.08);border:1px solid rgba(220,168,63,.15);font-size:10px}.service h3{font-size:11px;margin:14px 0 5px}.service p{font-size:8px;color:#68788d;min-height:38px}.service a{display:inline-block;color:var(--gold2);font-size:8px;font-weight:800;margin-top:10px}
.gcc{padding:80px 0}.gcc-grid{display:grid;grid-template-columns:.9fr 1.4fr;gap:45px;align-items:center}.gcc-copy h2{font:800 32px Manrope;letter-spacing:-.055em;margin:10px 0}.gcc-copy p{font-size:10px;color:#718096;max-width:430px}.gcc-copy .btn{margin-top:20px}.countries{display:grid;grid-template-columns:repeat(3,1fr);gap:9px}.country{padding:17px;border:1px solid var(--line);border-radius:14px;background:#09131f}.country strong{display:block;font-size:10px}.country span{display:block;color:#607087;font-size:7px;margin-top:3px}.country em{display:inline-block;margin-top:9px;font-style:normal;color:#76dcae;font-size:7px;font-weight:800}
.cta{padding:65px 0;background:radial-gradient(circle at 50% 0,rgba(220,168,63,.12),transparent 50%),#07101a;text-align:center;border-top:1px solid var(--line)}.cta h2{font:800 30px Manrope;letter-spacing:-.05em}.cta p{color:#718096;font-size:10px;margin:6px auto 18px}.cta .btn{margin:0 4px}
footer{padding:58px 0 20px;background:#03070d;border-top:1px solid var(--line)}.footer-grid{display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr;gap:38px;padding-bottom:40px}.footer-grid h4{font-size:10px;margin-bottom:14px}.footer-grid p,.footer-grid a{display:block;color:#607087;font-size:9px;margin:8px 0}.footer-grid a:hover{color:var(--gold2)}.footer-bottom{border-top:1px solid var(--line);padding-top:17px;color:#47566a;font-size:8px;display:flex;justify-content:space-between}
.toast{position:fixed;right:22px;bottom:22px;z-index:100;padding:14px 16px;border-radius:13px;background:#0d1927;border:1px solid rgba(220,168,63,.3);box-shadow:var(--shadow);font-size:10px;color:#dce5ef;transform:translateY(30px);opacity:0;pointer-events:none;transition:.3s}.toast.show{transform:none;opacity:1}
@media(max-width:950px){.links{display:none}.menu{display:block}.network-card{grid-template-columns:1fr}.map{min-height:400px}.service-grid{grid-template-columns:1fr 1fr}.gcc-grid{grid-template-columns:1fr}.footer-grid{grid-template-columns:1fr 1fr}}
@media(max-width:650px){.topbar{height:66px}.hero{padding:54px 0 65px}.hero h1{font-size:42px}.map{min-height:330px}.location-list{padding:20px}.service-grid,.countries,.footer-grid{grid-template-columns:1fr}.footer-bottom{flex-direction:column;gap:7px}.actions .btn{display:none}.actions .menu{display:block}.hero-stats{gap:6px}.stat{padding:9px 12px}}
</style>
</head>
<body>

<header class="topbar">
 <div class="container nav">
  <a class="brand" href="<?= \App\Core\View::url('/') ?>">
    <div class="logo" style="padding:0; border:none; background:transparent;"><img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:42px; width:42px; border-radius:10px; object-fit:cover;"></div>
    <div><span>COURIER</span><small>UAE • GCC LOGISTICS</small></div>
  </a>
  <nav class="links">
    <a href="<?= \App\Core\View::url('/services') ?>">Services</a>
    <a href="<?= \App\Core\View::url('/track') ?>">Track Shipment</a>
    <a href="<?= \App\Core\View::url('/quote') ?>">Get Quote</a>
    <a href="<?= \App\Core\View::url('/book') ?>">Book Shipment</a>
    <a href="<?= \App\Core\View::url('/locations') ?>">Locations</a>
    <a href="<?= \App\Core\View::url('/about') ?>">About Us</a>
    <a href="<?= \App\Core\View::url('/contact') ?>">Contact</a>
  </nav>
  <div class="actions">
    <?php $user = \App\Core\Session::get('user'); ?>
    <?php if ($user): ?>
        <?php $dashUrl = ($user['role_name'] === 'customer') ? '/customer' : '/admin'; ?>
        <a href="<?= \App\Core\View::url($dashUrl) ?>" class="btn btn-gold">Dashboard</a>
    <?php else: ?>
        <a href="<?= \App\Core\View::url('/login') ?>" class="btn btn-gold">Login</a>
        <a href="<?= \App\Core\View::url('/register') ?>" class="btn btn-dark">Register</a>
    <?php endif; ?>
    <button class="menu">☰</button>
  </div>
 </div>
</header>

<section class="hero">
 <div class="container">
  <div class="eyebrow">Our Network</div>
  <h1>Closer to you.<br><span>Across the Gulf.</span></h1>
  <p>RC Courier connects businesses and individuals through a growing UAE delivery network, GCC road corridors and international logistics solutions.</p>
  <div class="hero-stats">
   <div class="stat"><strong>7</strong> Emirates</div>
   <div class="stat"><strong>6</strong> GCC Markets</div>
   <div class="stat"><strong>220+</strong> Global Destinations</div>
   <div class="stat"><strong>24/7</strong> Network Support</div>
  </div>
 </div>
</section>

<main class="network">
 <div class="container">
  <div class="network-card">
   <div class="map">
    <div class="map-center"><strong>RC COURIER NETWORK</strong>UAE operational coverage</div>
    <div class="route"></div><div class="route2"></div>
    <div class="pin dubai"></div><div class="pin abudhabi"></div><div class="pin sharjah"></div><div class="pin ajman"></div><div class="pin rak"></div><div class="pin fujairah"></div><div class="pin uaq"></div>
    <span class="map-label d">Dubai</span><span class="map-label a">Abu Dhabi</span><span class="map-label s">Sharjah</span><span class="map-label r">RAK</span>
   </div>
   <div class="location-list">
    <div class="section-head"><div><h2>UAE locations</h2><p>Pickup, delivery and logistics coverage.</p></div><div class="live">● NETWORK ACTIVE</div></div>
    <div class="locations">
      <div class="loc"><div class="loc-icon">DXB</div><div><h3>Dubai Hub</h3><p>Central operations • Same-day & next-day</p></div><div class="status">Active</div></div>
      <div class="loc"><div class="loc-icon">AUH</div><div><h3>Abu Dhabi Center</h3><p>Capital region • Express & freight</p></div><div class="status">Active</div></div>
      <div class="loc"><div class="loc-icon">SHJ</div><div><h3>Sharjah Hub</h3><p>Industrial & commercial coverage</p></div><div class="status">Active</div></div>
      <div class="loc"><div class="loc-icon">AJM</div><div><h3>Ajman Depot</h3><p>Local delivery & collection</p></div><div class="status">Active</div></div>
      <div class="loc"><div class="loc-icon">RAK</div><div><h3>Ras Al Khaimah</h3><p>North Emirates coverage</p></div><div class="status">Active</div></div>
      <div class="loc"><div class="loc-icon">FJR</div><div><h3>Fujairah East</h3><p>East coast & port connectivity</p></div><div class="status">Active</div></div>
      <div class="loc"><div class="loc-icon">UAQ</div><div><h3>Umm Al Quwain</h3><p>Local pickup & delivery</p></div><div class="status">Active</div></div>
    </div>
    <div class="coverage"><strong>UAE-wide delivery:</strong> service availability, cutoff times and pricing can vary by service, route and shipment profile.</div>
   </div>
  </div>
 </div>
</main>

<section class="services">
 <div class="container">
  <div class="title"><div class="eyebrow">Coverage by service</div><h2>One network. Multiple ways to move.</h2><p>Choose the logistics solution that fits your route and delivery promise.</p></div>
  <div class="service-grid">
   <article class="service"><div class="icon">⚡</div><h3>Same-Day Express</h3><p>Priority delivery across selected UAE routes for time-critical shipments.</p><a href="<?= \App\Core\View::url('/services') ?>">Explore service →</a></article>
   <article class="service"><div class="icon">24</div><h3>Next-Day Delivery</h3><p>Reliable business-day delivery across all seven Emirates.</p><a href="<?= \App\Core\View::url('/services') ?>">Explore service →</a></article>
   <article class="service"><div class="icon">GCC</div><h3>GCC Road Freight</h3><p>Door-to-door road logistics connecting the UAE with GCC markets.</p><a href="<?= \App\Core\View::url('/services') ?>">Explore service →</a></article>
   <article class="service"><div class="icon">AIR</div><h3>International Air</h3><p>Priority international dispatch to 220+ destinations worldwide.</p><a href="<?= \App\Core\View::url('/services') ?>">Explore service →</a></article>
  </div>
 </div>
</section>

<section class="gcc">
 <div class="container gcc-grid">
  <div class="gcc-copy">
   <div class="eyebrow">Beyond the UAE</div>
   <h2>Built for Gulf commerce.</h2>
   <p>From UAE fulfillment and retail deliveries to cross-border GCC road freight, RC Courier is designed around the routes businesses use every day.</p>
   <a href="<?= \App\Core\View::url('/quote') ?>" class="btn btn-gold">Plan a GCC shipment →</a>
  </div>
  <div class="countries">
   <div class="country"><strong>🇸🇦 Saudi Arabia</strong><span>Road & cross-border logistics</span><em>GCC COVERAGE</em></div>
   <div class="country"><strong>🇴🇲 Oman</strong><span>Road delivery & freight</span><em>GCC COVERAGE</em></div>
   <div class="country"><strong>🇶🇦 Qatar</strong><span>Cross-border logistics</span><em>GCC COVERAGE</em></div>
   <div class="country"><strong>🇧🇭 Bahrain</strong><span>Road freight connectivity</span><em>GCC COVERAGE</em></div>
   <div class="country"><strong>🇰🇼 Kuwait</strong><span>GCC cargo routes</span><em>GCC COVERAGE</em></div>
   <div class="country"><strong>🌐 Worldwide</strong><span>International air priority</span><em>220+ DESTINATIONS</em></div>
  </div>
 </div>
</section>

<section class="cta">
 <div class="container">
  <h2>Ready to move something?</h2>
  <p>Get a fast AED quote or schedule a pickup from your nearest RC Courier service area.</p>
  <a class="btn btn-gold" href="<?= \App\Core\View::url('/quote') ?>">Get a Quote</a>
  <a class="btn btn-dark" href="<?= \App\Core\View::url('/book') ?>">Book Shipment</a>
 </div>
</section>

<footer>
 <div class="container">
  <div class="footer-grid">
   <div>
    <a class="brand" href="<?= \App\Core\View::url('/') ?>">
      <div class="logo" style="padding:0; border:none; background:transparent;"><img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:36px; width:36px; border-radius:8px; object-fit:cover;"></div>
      <div><span>COURIER</span><small>UAE • GCC LOGISTICS</small></div>
    </a>
    <p style="max-width:300px;margin-top:14px">Premium courier, express delivery and logistics solutions across the UAE, GCC and international destinations.</p>
   </div>
   <div>
    <h4>Services</h4>
    <a href="<?= \App\Core\View::url('/services') ?>">Same-Day Delivery</a>
    <a href="<?= \App\Core\View::url('/services') ?>">Next-Day Delivery</a>
    <a href="<?= \App\Core\View::url('/services') ?>">GCC Road Freight</a>
    <a href="<?= \App\Core\View::url('/services') ?>">International Air</a>
    <a href="<?= \App\Core\View::url('/services') ?>">E-Commerce Logistics</a>
   </div>
   <div>
    <h4>Company</h4>
    <a href="<?= \App\Core\View::url('/about') ?>">About RC Courier</a>
    <a href="<?= \App\Core\View::url('/locations') ?>">Our Hubs & Coverage</a>
    <a href="<?= \App\Core\View::url('/contact') ?>">Contact Us</a>
    <a href="<?= \App\Core\View::url('/contact') ?>">Corporate Solutions</a>
   </div>
   <div>
    <h4>Quick Links</h4>
    <a href="<?= \App\Core\View::url('/track') ?>">Track Shipment</a>
    <a href="<?= \App\Core\View::url('/quote') ?>">Get a Quote</a>
    <a href="<?= \App\Core\View::url('/book') ?>">Book Shipment</a>
    <p style="margin-top:10px;">Dubai, United Arab Emirates</p>
    <p>Phone: +971 4 800 2684</p>
    <p>support@rccourier.ae</p>
   </div>
  </div>
  <div class="footer-bottom">
    <span>© <?= date('Y') ?> RC Courier UAE. All rights reserved. TRN: 100987654321003.</span>
    <span>UAE • GCC • Worldwide</span>
  </div>
 </div>
</footer>
<div class="toast" id="toast"></div>
<script>
function showToast(msg){const t=document.getElementById('toast');t.textContent=msg;t.classList.add('show');clearTimeout(window.toastTimer);window.toastTimer=setTimeout(()=>t.classList.remove('show'),3500)}
</script>
</body>
</html>
