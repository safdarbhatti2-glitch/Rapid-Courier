<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="About RC Courier — premium UAE courier, express delivery and GCC logistics solutions.">
<title><?= e($title ?? 'About Us | RC Courier UAE') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root{
 --bg:#050a12;--bg2:#08111c;--panel:#0b1522;--panel2:#0f1b2a;
 --gold:#dca83f;--gold2:#f5ce73;--white:#f8fafc;--muted:#8797aa;
 --line:rgba(255,255,255,.085);--blue:#42b5ff;--green:#42d39a;
 --shadow:0 30px 90px rgba(0,0,0,.42)
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:Inter,Arial,sans-serif;background:var(--bg);color:var(--white);line-height:1.55}
a{text-decoration:none;color:inherit}.container{width:min(1120px,92%);margin:auto}
.topbar{height:80px;position:sticky;top:0;z-index:50;background:rgba(5,10,18,.91);backdrop-filter:blur(18px);border-bottom:1px solid var(--line)}
.nav{height:100%;display:flex;align-items:center;justify-content:space-between;gap:22px}
.brand{display:flex;align-items:center;gap:9px;font-family:Manrope;font-weight:800;white-space:nowrap}
.logo{width:38px;height:38px;border-radius:11px;display:grid;place-items:center;color:var(--gold2);border:1px solid rgba(220,168,63,.5);background:linear-gradient(145deg,#172334,#080d15);font-size:13px}
.brand span{font-size:15px;letter-spacing:-.045em}.brand small{display:block;color:var(--gold);font:700 6px Inter;letter-spacing:.12em}
.links{display:flex;gap:21px;color:#aebaca;font-size:11px}.links a:hover{color:#fff}
.actions{display:flex;gap:8px}.btn{border:0;border-radius:11px;padding:11px 17px;font-size:11px;font-weight:800;transition:.25s;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;}
.btn-gold{background:linear-gradient(135deg,var(--gold2),var(--gold));color:#10151c;box-shadow:0 10px 30px rgba(220,168,63,.14)}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 15px 35px rgba(220,168,63,.28)}
.btn-dark{background:#0d1724;color:#fff;border:1px solid var(--line)}.menu{display:none;background:none;border:0;color:#fff;font-size:24px}

.hero{position:relative;overflow:hidden;padding:84px 0 94px;text-align:center;background:
 radial-gradient(circle at 50% 0,rgba(220,168,63,.14),transparent 31%),
 radial-gradient(circle at 8% 60%,rgba(66,181,255,.08),transparent 24%),
 linear-gradient(180deg,#070d17,#050a12)}
.hero:before{content:"";position:absolute;inset:0;opacity:.17;background-image:
 linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),
 linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);
 background-size:70px 70px;mask-image:linear-gradient(#000,transparent)}
.eyebrow{position:relative;display:inline-flex;align-items:center;gap:9px;color:var(--gold2);font-size:9px;font-weight:800;letter-spacing:.17em;text-transform:uppercase}
.eyebrow:before,.eyebrow:after{content:"";height:1px;width:25px;background:var(--gold)}
.hero h1{position:relative;font:800 clamp(43px,6vw,68px)/1.02 Manrope;letter-spacing:-.065em;margin:17px auto 16px}
.hero h1 span{background:linear-gradient(90deg,#fff,var(--gold2));color:transparent;background-clip:text;-webkit-background-clip:text}
.hero p{position:relative;max-width:710px;margin:auto;color:var(--muted);font-size:12px}
.hero-stats{position:relative;display:flex;justify-content:center;gap:8px;flex-wrap:wrap;margin-top:29px}
.stat{padding:10px 16px;border:1px solid var(--line);background:rgba(10,20,32,.68);border-radius:30px;color:#91a0b3;font-size:9px}
.stat strong{color:var(--gold2);font-size:12px;margin-right:4px}

.intro{padding:80px 0}.intro-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:70px;align-items:center}
.kicker{color:var(--gold2);font-size:9px;font-weight:800;letter-spacing:.16em;text-transform:uppercase}
.intro h2{font:800 36px/1.08 Manrope;letter-spacing:-.055em;margin:12px 0 18px}
.intro h2 span{color:var(--gold2)}.intro p{font-size:10px;color:#748499;max-width:540px;margin:0 0 13px}
.quote-card{position:relative;padding:32px;border:1px solid var(--line);border-radius:22px;background:
 radial-gradient(circle at 100% 0,rgba(220,168,63,.13),transparent 36%),linear-gradient(145deg,#0d1927,#08111c);box-shadow:var(--shadow);overflow:hidden}
.quote-card:before{content:"RC";position:absolute;right:-8px;bottom:-34px;font:800 130px Manrope;color:rgba(255,255,255,.025)}
.quote-card .mark{width:44px;height:44px;border-radius:13px;display:grid;place-items:center;color:var(--gold2);border:1px solid rgba(220,168,63,.25);background:rgba(220,168,63,.07);font-weight:800}
.quote-card h3{font:800 20px Manrope;margin:22px 0 8px;position:relative}.quote-card p{font-size:10px;color:#718197;position:relative}
.quote-line{height:1px;background:var(--line);margin:23px 0}.signature{font-size:9px;color:#9caabd}.signature strong{display:block;color:#fff;font-size:10px;margin-bottom:2px}

.values{padding:80px 0;background:#070e18;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.title{text-align:center;margin-bottom:36px}.title .eyebrow{font-size:8px}.title h2{font:800 31px Manrope;letter-spacing:-.05em;margin:10px 0 5px}.title p{font-size:10px;color:#68788d}
.value-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.value{padding:24px;border:1px solid var(--line);border-radius:17px;background:linear-gradient(145deg,#0d1826,#09121e);transition:.25s}
.value:hover{transform:translateY(-5px);border-color:rgba(220,168,63,.3);box-shadow:0 20px 50px rgba(0,0,0,.25)}
.value .num{font:800 11px Manrope;color:var(--gold2);letter-spacing:.08em}.value h3{font-size:12px;margin:14px 0 7px}.value p{font-size:8px;color:#68788d}

.story{padding:85px 0}.story-grid{display:grid;grid-template-columns:.82fr 1.18fr;gap:65px;align-items:start}
.story-title{position:sticky;top:110px}.story-title h2{font:800 34px Manrope;letter-spacing:-.055em;margin:10px 0}.story-title p{font-size:10px;color:#708096;max-width:330px}
.timeline{position:relative;padding-left:30px}.timeline:before{content:"";position:absolute;left:7px;top:8px;bottom:8px;width:1px;background:linear-gradient(var(--gold),rgba(220,168,63,.05))}
.step{position:relative;padding:0 0 34px 25px}.step:last-child{padding-bottom:0}.dot{position:absolute;left:-30px;top:3px;width:15px;height:15px;border-radius:50%;background:var(--gold);box-shadow:0 0 0 5px rgba(220,168,63,.09),0 0 20px rgba(220,168,63,.25)}
.step small{color:var(--gold2);font-size:8px;font-weight:800;letter-spacing:.12em}.step h3{font:800 17px Manrope;margin:7px 0}.step p{font-size:9px;color:#718096;max-width:600px}

.network{padding:80px 0;background:#070e18;border-top:1px solid var(--line)}
.network-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.network-card{padding:26px;border:1px solid var(--line);border-radius:18px;background:linear-gradient(145deg,#0d1826,#09121e)}
.network-card h3{font:800 18px Manrope;margin:12px 0 7px}.network-card p{font-size:9px;color:#6f8095}.pills{display:flex;gap:7px;flex-wrap:wrap;margin-top:18px}
.pill{padding:8px 10px;border-radius:9px;background:#08121e;border:1px solid var(--line);color:#a8b5c5;font-size:8px}.pill b{color:var(--gold2)}
.mini-map{height:190px;margin-top:20px;border-radius:14px;border:1px solid var(--line);position:relative;overflow:hidden;background:
 radial-gradient(circle at 53% 45%,rgba(220,168,63,.16),transparent 10%),#07101a}
.mini-map:before{content:"";position:absolute;inset:0;background-image:linear-gradient(30deg,transparent 48%,rgba(255,255,255,.035) 49%,transparent 50%),linear-gradient(120deg,transparent 48%,rgba(255,255,255,.035) 49%,transparent 50%);background-size:42px 42px;transform:rotate(-8deg) scale(1.3)}
.mini-map:after{content:"UAE • GCC • GLOBAL";position:absolute;left:15px;bottom:12px;color:#4e6076;font-size:7px;letter-spacing:.16em}
.orbit{position:absolute;width:63%;height:45%;left:20%;top:27%;border:1px dashed rgba(220,168,63,.32);border-radius:50%;transform:rotate(-12deg)}.m-dot{position:absolute;width:9px;height:9px;border-radius:50%;background:var(--gold2);box-shadow:0 0 0 5px rgba(220,168,63,.08)}.m1{left:51%;top:43%}.m2{left:41%;top:58%}.m3{left:64%;top:27%}.m4{left:70%;top:43%}

.promise{padding:80px 0}.promise-card{border:1px solid rgba(220,168,63,.2);border-radius:22px;padding:43px;text-align:center;background:
 radial-gradient(circle at 50% 0,rgba(220,168,63,.14),transparent 43%),linear-gradient(145deg,#0d1826,#07101a);box-shadow:var(--shadow)}
.promise-card h2{font:800 32px Manrope;letter-spacing:-.05em}.promise-card p{max-width:650px;margin:8px auto 20px;color:#718096;font-size:10px}.promise-card .btn{margin:0 4px}

footer{padding:58px 0 20px;background:#03070d;border-top:1px solid var(--line)}
.footer-grid{display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr;gap:38px;padding-bottom:40px}
.footer-grid h4{font-size:10px;margin-bottom:14px}.footer-grid p,.footer-grid a{display:block;color:#607087;font-size:9px;margin:8px 0}.footer-grid a:hover{color:var(--gold2)}
.footer-bottom{border-top:1px solid var(--line);padding-top:17px;color:#47566a;font-size:8px;display:flex;justify-content:space-between}
@media(max-width:950px){.links{display:none}.menu{display:block}.intro-grid,.story-grid,.network-grid{grid-template-columns:1fr}.story-title{position:static}.value-grid{grid-template-columns:1fr 1fr}.footer-grid{grid-template-columns:1fr 1fr}}
@media(max-width:650px){.hero{padding:58px 0 68px}.hero h1{font-size:43px}.intro,.story,.network,.promise,.values{padding:62px 0}.value-grid,.footer-grid{grid-template-columns:1fr}.intro-grid,.story-grid{gap:35px}.quote-card{padding:25px}.promise-card{padding:30px 20px}.footer-bottom{flex-direction:column;gap:7px}.actions .btn{display:none}}
</style>
</head>
<body>

<header class="topbar">
 <div class="container nav">
    <a class="brand" href="<?= \App\Core\View::url('/') ?>" aria-label="RC Courier home" style="display:flex; align-items:center; gap:12px; text-decoration:none;">
      <img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" srcset="<?= \App\Core\View::url('/assets/images/rc_logo_256.png') ?> 2x, <?= \App\Core\View::url('/assets/images/rc_logo_hd.png') ?> 3x" alt="RC Courier Logo" style="height: 48px; width: 48px; min-width: 48px; border-radius: 10px; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.3); border: 1.5px solid rgba(241,196,94,0.45); image-rendering: -webkit-optimize-contrast;">
      <div class="brand-text" style="display:flex; flex-direction:column; justify-content:center;">
        <span style="font-family:'Manrope','Inter',sans-serif; font-size: 20px; font-weight: 900; color: #ffffff; letter-spacing: -0.4px; line-height: 1.1;">RC COURIER</span>
        <small style="font-family:'Inter',sans-serif; font-size: 9px; font-weight: 700; color: #f1c45e; letter-spacing: 1px; text-transform: uppercase; margin-top: 2px;">UAE • GCC LOGISTICS</small>
      </div>
    </a>
  <nav class="links">
    <a href="<?= \App\Core\View::url('/services') ?>">Services</a>
    <a href="<?= \App\Core\View::url('/track') ?>">Track Shipment</a>
    <a href="<?= \App\Core\View::url('/quote') ?>">Get Quote</a>
    <a href="<?= \App\Core\View::url('/book') ?>">Book Shipment</a>
    <a href="<?= \App\Core\View::url('/locations') ?>">Locations</a>
    <a href="<?= \App\Core\View::url('/about') ?>" style="color:#fff">About Us</a>
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
  <div class="eyebrow">About RC Courier</div>
  <h1>Moving business.<br><span>Delivering trust.</span></h1>
  <p>RC Courier is a UAE-focused courier and logistics company built to make express delivery, freight and cross-border shipping simpler, faster and more dependable.</p>
  <div class="hero-stats">
   <div class="stat"><strong>7</strong> Emirates</div>
   <div class="stat"><strong>6</strong> GCC Markets</div>
   <div class="stat"><strong>220+</strong> Global Destinations</div>
   <div class="stat"><strong>24/7</strong> Support Mindset</div>
  </div>
 </div>
</section>

<section class="intro">
 <div class="container intro-grid">
  <div>
   <div class="kicker">Who we are</div>
   <h2>Logistics should feel <span>effortless.</span></h2>
   <p>RC Courier was created around a simple idea: every shipment deserves visibility, care and a clear delivery promise.</p>
   <p>From urgent documents and e-commerce parcels to GCC road freight and international air shipments, our service model is designed around the needs of modern UAE businesses and customers.</p>
   <p>We combine a premium digital experience with practical logistics operations so customers can quote, book, track and manage shipments from one connected journey.</p>
  </div>
  <div class="quote-card">
   <div class="mark">RC</div>
   <h3>A better standard for delivery.</h3>
   <p>Our goal is not simply to move parcels from A to B. It is to build a courier experience that feels professional at every touchpoint — from the first quote to the final proof of delivery.</p>
   <div class="quote-line"></div>
   <div class="signature"><strong>RC Courier UAE</strong>Premium courier • express delivery • logistics</div>
  </div>
 </div>
</section>

<section class="values">
 <div class="container">
  <div class="title">
   <div class="eyebrow">What drives us</div>
   <h2>Built around the customer.</h2>
   <p>Four principles shape the way RC Courier should operate and grow.</p>
  </div>
  <div class="value-grid">
   <article class="value"><div class="num">01 / RELIABILITY</div><h3>Keep the promise.</h3><p>Clear service commitments, disciplined operations and proactive communication help customers know what to expect.</p></article>
   <article class="value"><div class="num">02 / VISIBILITY</div><h3>Keep customers informed.</h3><p>Tracking, shipment milestones and operational updates turn uncertainty into a transparent delivery journey.</p></article>
   <article class="value"><div class="num">03 / SPEED</div><h3>Move with purpose.</h3><p>From same-day UAE routes to time-sensitive international shipments, speed is balanced with operational control.</p></article>
   <article class="value"><div class="num">04 / CARE</div><h3>Treat every shipment seriously.</h3><p>Packages represent products, documents, commitments and customer relationships — every shipment matters.</p></article>
   <article class="value"><div class="num">05 / TECHNOLOGY</div><h3>Make logistics smarter.</h3><p>Digital quoting, booking, tracking, invoicing and shipment management are designed to work as one system.</p></article>
   <article class="value"><div class="num">06 / PARTNERSHIP</div><h3>Grow with our customers.</h3><p>We aim to become an extension of our customers' operations, supporting both daily deliveries and long-term growth.</p></article>
  </div>
 </div>
</section>

<section class="story">
 <div class="container story-grid">
  <div class="story-title">
   <div class="kicker">Our approach</div>
   <h2>From pickup to proof of delivery.</h2>
   <p>A premium courier experience is built through hundreds of small operational decisions. Our model connects them into one clear journey.</p>
  </div>
  <div class="timeline">
   <div class="step"><div class="dot"></div><small>01 — PLAN</small><h3>Understand the shipment.</h3><p>Capture the route, service level, package profile and customer requirements before the shipment moves.</p></div>
   <div class="step"><div class="dot"></div><small>02 — COLLECT</small><h3>Make pickup simple.</h3><p>Scheduled collections and clear pickup information help customers hand over shipments with confidence.</p></div>
   <div class="step"><div class="dot"></div><small>03 — MOVE</small><h3>Use the right network.</h3><p>UAE express routes, hub operations, GCC road corridors and international partners are selected around the service requirement.</p></div>
   <div class="step"><div class="dot"></div><small>04 — TRACK</small><h3>Keep the journey visible.</h3><p>Shipment milestones provide a clear operational trail from acceptance through sorting and onward movement.</p></div>
   <div class="step"><div class="dot"></div><small>05 — DELIVER</small><h3>Close the loop.</h3><p>Delivery confirmation and proof-of-delivery information complete the journey and support better customer service.</p></div>
  </div>
 </div>
</section>

<section class="network">
 <div class="container">
  <div class="title">
   <div class="eyebrow">Our reach</div>
   <h2>UAE at the center. Gulf beyond.</h2>
   <p>Designed for local delivery today and regional commerce tomorrow.</p>
  </div>
  <div class="network-grid">
   <div class="network-card">
    <div class="kicker">UAE Coverage</div>
    <h3>All seven Emirates.</h3>
    <p>Our UAE service model is designed around major commercial centers, residential delivery routes and regional hub connectivity.</p>
    <div class="pills"><span class="pill"><b>DXB</b> Dubai</span><span class="pill"><b>AUH</b> Abu Dhabi</span><span class="pill"><b>SHJ</b> Sharjah</span><span class="pill"><b>AJM</b> Ajman</span><span class="pill"><b>RAK</b> Ras Al Khaimah</span><span class="pill"><b>FJR</b> Fujairah</span><span class="pill"><b>UAQ</b> Umm Al Quwain</span></div>
   </div>
   <div class="network-card">
    <div class="kicker">GCC & International</div>
    <h3>Routes that connect markets.</h3>
    <p>GCC road freight and international air services extend the delivery network beyond the UAE for businesses with regional and global requirements.</p>
    <div class="mini-map"><div class="orbit"></div><div class="m-dot m1"></div><div class="m-dot m2"></div><div class="m-dot m3"></div><div class="m-dot m4"></div></div>
   </div>
  </div>
 </div>
</section>

<section class="promise">
 <div class="container">
  <div class="promise-card">
   <div class="eyebrow">The RC promise</div>
   <h2>Professional delivery. Every mile.</h2>
   <p>Whether you are sending one urgent parcel or managing daily business shipments, RC Courier is being built to give you a faster, clearer and more premium logistics experience.</p>
   <a class="btn btn-gold" href="<?= \App\Core\View::url('/quote') ?>">Get a Quote</a>
   <a class="btn btn-dark" href="<?= \App\Core\View::url('/book') ?>">Book Shipment</a>
  </div>
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
</body>
</html>
