<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="RC Courier UAE — premium same-day, next-day, GCC freight, international and logistics services with transparent AED pricing.">
<title>Services | RC Courier UAE</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

<style>
:root{
  --bg:#050a12;--bg2:#09111d;--panel:#0c1624;--panel2:#101c2c;
  --gold:#dca83f;--gold2:#f3c968;--white:#f8fafc;--muted:#91a0b5;
  --blue:#39a7ff;--green:#35c58a;--line:rgba(255,255,255,.09);
  --shadow:0 25px 70px rgba(0,0,0,.32);--radius:20px
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:Inter,Arial,sans-serif;background:var(--bg);color:var(--white);line-height:1.6}
button,input,select{font:inherit}
button{cursor:pointer}
a{text-decoration:none;color:inherit}
.container{width:min(1160px,92%);margin:auto}
.topbar{height:80px;position:sticky;top:0;z-index:50;background:rgba(5,10,18,.84);backdrop-filter:blur(20px);border-bottom:1px solid var(--line)}
.nav{height:100%;display:flex;align-items:center;justify-content:space-between;gap:24px}
.brand{display:flex;align-items:center;gap:9px;font-family:Manrope;font-weight:800;white-space:nowrap}
.logo{width:38px;height:38px;border:1px solid rgba(220,168,63,.5);border-radius:11px;display:grid;place-items:center;color:var(--gold2);background:linear-gradient(145deg,#172234,#080d15);font-size:13px}
.brand span{font-size:15px;letter-spacing:-.04em}.brand small{display:block;color:var(--gold);font:700 6px Inter;letter-spacing:.12em}
.links{display:flex;gap:24px;font-size:12px;color:#b8c3d2}.links a:hover{color:#fff}
.actions{display:flex;gap:8px}.btn{border:0;border-radius:11px;padding:11px 17px;font-size:12px;font-weight:800;transition:.25s;display:inline-flex;align-items:center;justify-content:center;}
.btn-gold{background:linear-gradient(135deg,var(--gold2),var(--gold));color:#111827;box-shadow:0 10px 30px rgba(220,168,63,.16)}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 15px 35px rgba(220,168,63,.28)}
.btn-dark{background:#0d1725;color:#fff;border:1px solid var(--line)}
.menu{display:none;background:none;border:0;color:#fff;font-size:25px}

.hero{position:relative;padding:105px 0 85px;text-align:center;overflow:hidden;background:
 radial-gradient(circle at 50% 0,rgba(220,168,63,.16),transparent 34%),
 radial-gradient(circle at 15% 50%,rgba(42,105,165,.12),transparent 32%),
 linear-gradient(180deg,#070d17,#050a12)}
.hero:before{content:"";position:absolute;inset:0;opacity:.25;background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);background-size:75px 75px;mask-image:linear-gradient(to bottom,#000,transparent)}
.eyebrow{position:relative;display:inline-flex;align-items:center;gap:9px;color:var(--gold2);font-size:11px;font-weight:800;letter-spacing:.16em;text-transform:uppercase}
.eyebrow:before,.eyebrow:after{content:"";width:26px;height:1px;background:var(--gold)}
.hero h1{position:relative;font:800 clamp(42px,6vw,70px)/1.02 Manrope;letter-spacing:-.065em;margin:17px auto 18px;max-width:850px}
.hero h1 span{background:linear-gradient(90deg,#fff,var(--gold2));color:transparent;background-clip:text;-webkit-background-clip:text}
.hero p{position:relative;max-width:650px;margin:auto;color:var(--muted);font-size:14px}
.hero-stats{position:relative;margin:42px auto 0;display:flex;justify-content:center;gap:0;max-width:650px;border:1px solid var(--line);border-radius:18px;background:rgba(255,255,255,.025);overflow:hidden}
.stat{flex:1;padding:18px;border-right:1px solid var(--line)}.stat:last-child{border:0}
.stat strong{display:block;font:800 25px Manrope;color:var(--gold2)}.stat small{font-size:9px;color:#7d8ba0}

.toolbar{padding:25px 0;background:#070d16;border-bottom:1px solid var(--line);position:sticky;top:74px;z-index:30}
.toolbar-inner{display:flex;justify-content:space-between;align-items:center;gap:15px}
.filters{display:flex;gap:8px;flex-wrap:wrap}
.filter{background:#0b1522;color:#9eacc0;border:1px solid var(--line);border-radius:10px;padding:9px 13px;font-size:10px;font-weight:800}
.filter.active,.filter:hover{color:#101722;background:var(--gold2);border-color:var(--gold2)}
.currency{font-size:10px;color:#8998ac}.currency strong{color:var(--gold2)}

.services{padding:75px 0 100px}
.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:18px}
.card{position:relative;min-height:390px;padding:28px;border:1px solid var(--line);border-radius:var(--radius);overflow:hidden;background:
 linear-gradient(145deg,rgba(255,255,255,.055),rgba(255,255,255,.018));
 transition:.3s;display:flex;flex-direction:column}
.card:hover{transform:translateY(-7px);border-color:rgba(220,168,63,.38);box-shadow:var(--shadow)}
.card.featured{border-color:rgba(220,168,63,.35);background:linear-gradient(145deg,rgba(220,168,63,.10),rgba(255,255,255,.025))}
.card:after{content:"";position:absolute;width:230px;height:230px;right:-100px;top:-100px;border-radius:50%;background:rgba(220,168,63,.10);filter:blur(10px)}
.card-head{display:flex;justify-content:space-between;gap:15px;position:relative;z-index:1}
.icon{width:52px;height:52px;border-radius:15px;display:grid;place-items:center;color:var(--gold2);font-size:21px;border:1px solid rgba(220,168,63,.27);background:rgba(220,168,63,.08)}
.badge{align-self:flex-start;color:#64c1ff;background:rgba(57,167,255,.09);border:1px solid rgba(57,167,255,.2);border-radius:20px;padding:6px 9px;font-size:8px;font-weight:900;letter-spacing:.1em;text-transform:uppercase}
.card h2{position:relative;z-index:1;font:800 25px Manrope;letter-spacing:-.035em;margin:20px 0 7px}
.card .lead{position:relative;z-index:1;color:#c7d0dc;font-size:12px;min-height:48px}
.features{position:relative;z-index:1;margin:19px 0;display:grid;grid-template-columns:1fr 1fr;gap:9px}
.features div{font-size:10px;color:#8392a7}.features div:before{content:"✓";color:var(--green);font-weight:900;margin-right:7px}
.price-row{position:relative;z-index:1;margin-top:auto;border-top:1px solid var(--line);padding-top:17px;display:flex;align-items:end;justify-content:space-between;gap:15px}
.price small{display:block;color:#738198;font-size:9px}.price strong{font:800 25px Manrope;color:var(--gold2)}.price em{font:normal 10px Inter;color:#7d8ca1}
.card-actions{display:flex;gap:7px}.card-actions .btn{padding:10px 13px}

.compare{padding:80px 0;background:#070e18;border-top:1px solid var(--line)}
.section-head{text-align:center;max-width:700px;margin:0 auto 40px}
.section-head .eyebrow{margin-bottom:12px}.section-head h2{font:800 clamp(30px,4vw,45px)/1.08 Manrope;letter-spacing:-.045em}.section-head p{color:var(--muted);font-size:13px;margin-top:10px}
.table-wrap{overflow:auto;border:1px solid var(--line);border-radius:18px;background:#0a1320}
table{width:100%;border-collapse:collapse;min-width:760px}
th,td{text-align:left;padding:17px;border-bottom:1px solid var(--line);font-size:11px}
th{color:var(--gold2);font-size:10px;text-transform:uppercase;letter-spacing:.08em;background:#0e1927}
td{color:#aab6c7}td:first-child{color:#fff;font-weight:700}
.check{color:var(--green);font-weight:900}.dash{color:#56657a}

.why{padding:90px 0;background:linear-gradient(180deg,#050a12,#09111c)}
.why-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.why-card{padding:28px;border:1px solid var(--line);border-radius:18px;background:rgba(255,255,255,.025)}
.why-card h3{font:700 18px Manrope;margin:14px 0 6px}.why-card p{font-size:11px;color:#8291a6}
.why-icon{color:var(--gold2);font-size:22px}

.cta{margin:0 auto 85px;max-width:1160px;width:92%;padding:35px 40px;border-radius:22px;background:linear-gradient(100deg,#9d6c1b,#e6b84f 55%,#b67e22);color:#08101a;display:flex;justify-content:space-between;align-items:center;gap:20px;box-shadow:0 30px 70px rgba(0,0,0,.35)}
.cta h2{font:800 28px Manrope;letter-spacing:-.04em}.cta p{font-size:11px;opacity:.75;margin-top:3px}.cta .btn{background:#07101a;color:#fff}

footer{padding:65px 0 20px;border-top:1px solid var(--line);background:#03070d}
.footer-grid{display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr;gap:40px;padding-bottom:45px}
.footer-grid h4{font-size:11px;margin-bottom:14px}.footer-grid p,.footer-grid a{display:block;color:#65748a;font-size:10px;margin:8px 0}.footer-grid a:hover{color:var(--gold2)}
.footer-bottom{border-top:1px solid var(--line);padding-top:18px;color:#4f5d70;font-size:9px;display:flex;justify-content:space-between}

.modal{display:none;position:fixed;inset:0;z-index:100;background:rgba(0,0,0,.76);backdrop-filter:blur(10px);align-items:center;justify-content:center;padding:18px}
.modal.open{display:flex}.modal-box{width:min(560px,100%);background:#0b1522;border:1px solid var(--line);border-radius:22px;padding:28px;position:relative;box-shadow:var(--shadow)}
.close{position:absolute;right:16px;top:13px;border:0;background:none;color:#8b99ad;font-size:25px}
.modal-box h2{font:800 27px Manrope}.modal-box>p{color:#7f8da2;font-size:11px;margin:4px 0 20px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:11px}.field{display:flex;flex-direction:column;gap:5px}.field.full{grid-column:1/-1}.field label{font-size:9px;color:#9aa8ba;font-weight:700}.field input,.field select{background:#07101b;border:1px solid var(--line);border-radius:10px;padding:11px;color:#fff;outline:0}.field input:focus,.field select:focus{border-color:var(--gold)}
.form-actions{margin-top:18px;display:flex;justify-content:flex-end;gap:8px}
.result{display:none;margin-top:14px;padding:13px;border-radius:10px;background:rgba(53,197,138,.08);border:1px solid rgba(53,197,138,.2);color:#a7e7ca;font-size:11px}

.alert { padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 600; font-size: 0.9rem; }
.alert-success { background: rgba(53,197,138,.15); color: #6ee7b7; border: 1px solid rgba(53,197,138,.3); }
.alert-error { background: rgba(225,29,72,.15); color: #fda4af; border: 1px solid rgba(225,29,72,.3); }

@media(max-width:900px){.links{display:none}.menu{display:block}.grid{grid-template-columns:1fr}.why-grid{grid-template-columns:1fr}.footer-grid{grid-template-columns:1fr 1fr}.toolbar{top:74px}}
@media(max-width:620px){
 .topbar{height:66px}.toolbar{top:66px}.hero{padding:75px 0 60px}.hero-stats{flex-direction:column}.stat{border-right:0;border-bottom:1px solid var(--line)}.stat:last-child{border-bottom:0}
 .toolbar-inner{align-items:flex-start;flex-direction:column}.filters{overflow:auto;flex-wrap:nowrap;width:100%;padding-bottom:3px}.filter{white-space:nowrap}
 .services{padding-top:55px}.card{min-height:420px}.features{grid-template-columns:1fr}.cta{flex-direction:column;align-items:flex-start;padding:28px}.footer-grid{grid-template-columns:1fr}.footer-bottom{flex-direction:column;gap:8px}.form-grid{grid-template-columns:1fr}.field.full{grid-column:auto}
}
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
      <a href="<?= \App\Core\View::url('/about') ?>">About Us</a>
      <a href="<?= \App\Core\View::url('/contact') ?>">Contact</a>
    </nav>
    <div class="actions">
      <?php $user = \App\Core\Session::get('user'); ?>
      <?php if ($user): ?>
          <?php $dashUrl = ($user['role_name'] === 'customer') ? '/customer' : '/admin'; ?>
          <a href="<?= \App\Core\View::url($dashUrl) ?>" class="btn btn-gold">Dashboard</a>
      <?php endif; ?>
      <button class="menu" aria-label="Menu">☰</button>
    </div>
  </div>
</header>

<section class="hero">
  <div class="container">
    <div class="eyebrow">Premium UAE & GCC Logistics</div>
    <h1>Services engineered for <span>speed, control & trust.</span></h1>
    <p>From urgent same-day parcels to cross-border freight, RC Courier provides a premium logistics experience across the UAE, GCC and worldwide.</p>
    <div class="hero-stats">
      <div class="stat"><strong>7</strong><small>UAE Emirates</small></div>
      <div class="stat"><strong>6</strong><small>GCC Markets</small></div>
      <div class="stat"><strong>220+</strong><small>Global Destinations</small></div>
      <div class="stat"><strong>AED</strong><small>Transparent Pricing</small></div>
    </div>
  </div>
</section>

<div class="toolbar">
  <div class="container toolbar-inner">
    <div class="filters">
      <button class="filter active" data-filter="all">All Services</button>
      <button class="filter" data-filter="uae">UAE Delivery</button>
      <button class="filter" data-filter="gcc">GCC Freight</button>
      <button class="filter" data-filter="international">International</button>
      <button class="filter" data-filter="business">Business</button>
    </div>
    <div class="currency">Pricing displayed in <strong>AED · UAE Dirham</strong></div>
  </div>
</div>

<main>
<section class="services" id="services">
  <div class="container">
    <div class="section-head">
      <div class="eyebrow">Our Services</div>
      <h2>Choose the service that fits your shipment.</h2>
      <p>Clear service options, premium handling and AED-based pricing. Final rates are calculated from your shipment details, route and account pricing.</p>
    </div>

    <div class="grid" id="serviceGrid">
      <article class="card featured" data-category="uae">
        <div class="card-head"><div class="icon">◈</div><div class="badge">Most Popular</div></div>
        <h2>Same-Day Express UAE</h2>
        <p class="lead">Priority intra-UAE delivery for urgent documents, parcels and time-sensitive orders.</p>
        <div class="features"><div>Doorstep pickup</div><div>Live tracking</div><div>Priority handling</div><div>Digital POD</div></div>
        <div class="price-row">
            <div class="price"><small>Starting from</small><strong>AED 35</strong><em> / shipment</em></div>
            <div class="card-actions">
                <a href="<?= \App\Core\View::url('/quote') ?>" class="btn btn-dark">Quote</a>
                <a href="<?= \App\Core\View::url('/book') ?>" class="btn btn-gold">Book</a>
            </div>
        </div>
      </article>

      <article class="card" data-category="uae">
        <div class="card-head"><div class="icon">◉</div><div class="badge">Next Business Day</div></div>
        <h2>Next-Day Delivery</h2>
        <p class="lead">Reliable next-business-day delivery across all seven Emirates at an efficient rate.</p>
        <div class="features"><div>All UAE Emirates</div><div>Doorstep delivery</div><div>Online tracking</div><div>POD available</div></div>
        <div class="price-row">
            <div class="price"><small>Starting from</small><strong>AED 25</strong><em> / shipment</em></div>
            <div class="card-actions">
                <a href="<?= \App\Core\View::url('/quote') ?>" class="btn btn-dark">Quote</a>
                <a href="<?= \App\Core\View::url('/book') ?>" class="btn btn-gold">Book</a>
            </div>
        </div>
      </article>

      <article class="card" data-category="gcc">
        <div class="card-head"><div class="icon">◇</div><div class="badge">GCC Road</div></div>
        <h2>GCC Overland Cargo</h2>
        <p class="lead">Door-to-door road freight from the UAE to Saudi Arabia, Oman, Qatar, Bahrain and Kuwait.</p>
        <div class="features"><div>Cross-border transport</div><div>Commercial cargo</div><div>Shipment tracking</div><div>Customs support</div></div>
        <div class="price-row">
            <div class="price"><small>Starting from</small><strong>AED 250</strong><em> / shipment</em></div>
            <div class="card-actions">
                <a href="<?= \App\Core\View::url('/quote') ?>" class="btn btn-dark">Quote</a>
                <a href="<?= \App\Core\View::url('/book') ?>" class="btn btn-gold">Book</a>
            </div>
        </div>
      </article>

      <article class="card" data-category="international">
        <div class="card-head"><div class="icon">✈</div><div class="badge">220+ Destinations</div></div>
        <h2>International Air Priority</h2>
        <p class="lead">Fast international parcel and document dispatch for time-sensitive global shipments.</p>
        <div class="features"><div>Air express</div><div>Global tracking</div><div>Export support</div><div>Door-to-door</div></div>
        <div class="price-row">
            <div class="price"><small>Starting from</small><strong>AED 120</strong><em> / shipment</em></div>
            <div class="card-actions">
                <a href="<?= \App\Core\View::url('/quote') ?>" class="btn btn-dark">Quote</a>
                <a href="<?= \App\Core\View::url('/book') ?>" class="btn btn-gold">Book</a>
            </div>
        </div>
      </article>

      <article class="card" data-category="business">
        <div class="card-head"><div class="icon">▥</div><div class="badge">B2B</div></div>
        <h2>Heavy Freight & Logistics</h2>
        <p class="lead">Full truckload, pallet, warehousing and commercial logistics solutions for businesses.</p>
        <div class="features"><div>FTL & LTL options</div><div>Pallet shipments</div><div>Warehouse support</div><div>Account management</div></div>
        <div class="price-row">
            <div class="price"><small>Custom pricing from</small><strong>AED 500</strong><em> / shipment</em></div>
            <div class="card-actions">
                <a href="<?= \App\Core\View::url('/contact') ?>" class="btn btn-dark">Quote</a>
                <a href="<?= \App\Core\View::url('/contact') ?>" class="btn btn-gold">Book</a>
            </div>
        </div>
      </article>

      <article class="card" data-category="business">
        <div class="card-head"><div class="icon">▣</div><div class="badge">E-Commerce</div></div>
        <h2>E-Commerce Fulfillment</h2>
        <p class="lead">Pickup, storage, order fulfillment, last-mile delivery and returns for online businesses.</p>
        <div class="features"><div>Order fulfillment</div><div>COD-ready workflow</div><div>Returns management</div><div>Merchant reporting</div></div>
        <div class="price-row">
            <div class="price"><small>Plans from</small><strong>AED 15</strong><em> / order</em></div>
            <div class="card-actions">
                <a href="<?= \App\Core\View::url('/contact') ?>" class="btn btn-dark">Quote</a>
                <a href="<?= \App\Core\View::url('/contact') ?>" class="btn btn-gold">Book</a>
            </div>
        </div>
      </article>

      <article class="card" data-category="international">
        <div class="card-head"><div class="icon">≈</div><div class="badge">Sea Freight</div></div>
        <h2>Sea Freight & Cargo</h2>
        <p class="lead">Cost-efficient international ocean freight for commercial cargo, pallets and containers.</p>
        <div class="features"><div>FCL & LCL</div><div>Port-to-door</div><div>Documentation support</div><div>Cargo visibility</div></div>
        <div class="price-row">
            <div class="price"><small>Custom quote</small><strong>AED 850+</strong><em> / shipment</em></div>
            <div class="card-actions">
                <a href="<?= \App\Core\View::url('/contact') ?>" class="btn btn-dark">Quote</a>
                <a href="<?= \App\Core\View::url('/contact') ?>" class="btn btn-gold">Book</a>
            </div>
        </div>
      </article>
    </div>
  </div>
</section>

<section class="compare">
  <div class="container">
    <div class="section-head"><div class="eyebrow">Service Comparison</div><h2>See what is included.</h2><p>A premium overview for customers comparing delivery options before requesting the final AED quote.</p></div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Service</th><th>Delivery</th><th>Tracking</th><th>Door Pickup</th><th>POD</th><th>Best For</th></tr></thead>
        <tbody>
          <tr><td>Same-Day Express</td><td>Same day</td><td class="check">&check;</td><td class="check">&check;</td><td class="check">&check;</td><td>Urgent parcels</td></tr>
          <tr><td>Next-Day Delivery</td><td>Next day</td><td class="check">&check;</td><td class="check">&check;</td><td class="check">&check;</td><td>Daily e-commerce</td></tr>
          <tr><td>GCC Overland</td><td>Route based</td><td class="check">&check;</td><td class="check">&check;</td><td class="check">&check;</td><td>Regional cargo</td></tr>
          <tr><td>International Air</td><td>Priority</td><td class="check">&check;</td><td class="check">&check;</td><td class="check">&check;</td><td>Global parcels</td></tr>
          <tr><td>Heavy Freight</td><td>Scheduled</td><td class="check">&check;</td><td class="check">&check;</td><td class="check">&check;</td><td>Commercial cargo</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="why" id="coverage">
  <div class="container">
    <div class="section-head"><div class="eyebrow">The RC Standard</div><h2>More than delivery.</h2><p>Designed around the things UAE businesses actually care about: visibility, response time, accountability and predictable service.</p></div>
    <div class="why-grid">
      <article class="why-card"><div class="why-icon">◉</div><h3>Live Shipment Visibility</h3><p>Customers and staff can follow shipment milestones from booking through final delivery.</p></article>
      <article class="why-card"><div class="why-icon">◇</div><h3>UAE + GCC Network</h3><p>One logistics partner for domestic UAE movements and cross-border Gulf shipments.</p></article>
      <article class="why-card"><div class="why-icon">✓</div><h3>Digital Proof of Delivery</h3><p>Capture delivery confirmation and make it available through the customer account.</p></article>
      <article class="why-card"><div class="why-icon">▣</div><h3>Business Accounts</h3><p>Account pricing, quotations, invoices, statements and shipment history for corporate customers.</p></article>
    </div>
  </div>
</section>
</main>

<section class="cta" id="contact">
  <div class="container" style="padding:0;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;">
      <div><h2>Need a tailored logistics solution?</h2><p>Get a professional quotation in AED based on your exact route, weight and service.</p></div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a href="<?= \App\Core\View::url('/quote') ?>" class="btn" style="background:#07101a;color:#fff">Request AED Quote →</a>
        <a href="<?= \App\Core\View::url('/book') ?>" class="btn" style="background:#fff;color:#07101a">Book Shipment</a>
      </div>
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
        <p style="max-width:300px;margin-top:15px">Premium courier, express delivery and logistics solutions across the UAE, GCC and international destinations.</p>
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
        <a href="<?= \App\Core\View::url('/locations') ?>">Locations & Coverage</a>
        <a href="<?= \App\Core\View::url('/contact') ?>">Corporate Solutions</a>
        <a href="<?= \App\Core\View::url('/contact') ?>">Contact</a>
      </div>
      <div>
        <h4>Support</h4>
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
        <span>All prices shown in AED unless stated otherwise.</span>
    </div>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const filters = document.querySelectorAll('.filter');
    const cards = document.querySelectorAll('.card[data-category]');
    filters.forEach(f => f.addEventListener('click', () => {
      filters.forEach(x => x.classList.remove('active')); f.classList.add('active');
      const cat = f.dataset.filter;
      cards.forEach(c => c.style.display = (cat === 'all' || c.dataset.category === cat) ? 'flex' : 'none');
    }));
});
</script>
</body>
</html>
