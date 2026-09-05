<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Get a premium shipping quote from RC Courier UAE for domestic, GCC and international logistics.">
<title><?= e($title ?? 'Get a Quote | RC Courier UAE') ?></title>
<!-- Favicons -->
<link rel="icon" type="image/png" sizes="32x32" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">
<link rel="icon" type="image/png" sizes="192x192" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
<link rel="shortcut icon" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root{
 --bg:#050a12;--bg2:#09111d;--panel:#0c1624;--panel2:#101c2b;--gold:#dca83f;--gold2:#f3ca6a;
 --white:#f8fafc;--muted:#91a0b5;--blue:#42b5ff;--green:#38c88b;--line:rgba(255,255,255,.085);
 --shadow:0 30px 90px rgba(0,0,0,.38)
}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Inter,Arial,sans-serif;background:var(--bg);color:var(--white);line-height:1.5}
button,input,select,textarea{font:inherit}button{cursor:pointer}a{text-decoration:none;color:inherit}
.container{width:min(1120px,92%);margin:auto}
.topbar{height:80px;position:sticky;top:0;z-index:50;background:rgba(5,10,18,.9);backdrop-filter:blur(18px);border-bottom:1px solid var(--line)}
.nav{height:100%;display:flex;align-items:center;justify-content:space-between;gap:22px}
.brand{display:flex;align-items:center;gap:9px;font-family:Manrope;font-weight:800;white-space:nowrap}
.logo{width:38px;height:38px;border-radius:11px;display:grid;place-items:center;color:var(--gold2);border:1px solid rgba(220,168,63,.5);background:linear-gradient(145deg,#172334,#080d15);font-size:13px}
.brand span{font-size:15px;letter-spacing:-.045em}.brand small{display:block;color:var(--gold);font:700 6px Inter;letter-spacing:.12em}
.links{display:flex;gap:21px;color:#aebaca;font-size:11px}.links a:hover{color:#fff}
.actions{display:flex;gap:8px}.btn{border:0;border-radius:11px;padding:11px 17px;font-size:11px;font-weight:800;transition:.25s;display:inline-flex;align-items:center;justify-content:center;}
.btn-gold{background:linear-gradient(135deg,var(--gold2),var(--gold));color:#10151c;box-shadow:0 10px 30px rgba(220,168,63,.14)}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 15px 35px rgba(220,168,63,.28)}
.btn-dark{background:#0d1724;color:#fff;border:1px solid var(--line)}
.menu{display:none;background:none;border:0;color:#fff;font-size:24px}

.hero{position:relative;overflow:hidden;padding:70px 0 55px;text-align:center;background:
 radial-gradient(circle at 50% 0,rgba(220,168,63,.14),transparent 34%),
 radial-gradient(circle at 12% 55%,rgba(66,181,255,.08),transparent 25%),linear-gradient(180deg,#070d17,#050a12)}
.hero:before{content:"";position:absolute;inset:0;opacity:.2;background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);background-size:70px 70px;mask-image:linear-gradient(#000,transparent)}
.eyebrow{position:relative;display:inline-flex;align-items:center;gap:9px;color:var(--gold2);font-size:10px;font-weight:800;letter-spacing:.17em;text-transform:uppercase}
.eyebrow:before,.eyebrow:after{content:"";height:1px;width:25px;background:var(--gold)}
.hero h1{position:relative;font:800 clamp(38px,5.5vw,60px)/1.05 Manrope;letter-spacing:-.065em;margin:15px auto 13px}
.hero h1 span{background:linear-gradient(90deg,#fff,var(--gold2));color:transparent;background-clip:text;-webkit-background-clip:text}
.hero p{position:relative;max-width:670px;margin:auto;color:var(--muted);font-size:12px}

.quote-area{padding:0 0 85px;position:relative}
.quote-layout{display:grid;grid-template-columns:1.55fr .8fr;gap:18px;align-items:start}
.card{border:1px solid var(--line);border-radius:22px;background:linear-gradient(145deg,#0d1826,#09121e);box-shadow:var(--shadow)}
.form-card{padding:30px}
.card-head{display:flex;justify-content:space-between;align-items:flex-start;gap:15px;margin-bottom:25px}
.card-head h2{font:800 21px Manrope;letter-spacing:-.04em}.card-head p{font-size:10px;color:#718198;margin-top:4px}
.live-badge{font-size:8px;font-weight:800;color:#8de2ba;background:rgba(56,200,139,.08);border:1px solid rgba(56,200,139,.18);border-radius:20px;padding:7px 9px;white-space:nowrap}
.section-label{font-size:9px;color:var(--gold2);font-weight:800;text-transform:uppercase;letter-spacing:.13em;margin:25px 0 12px;display:flex;align-items:center;gap:9px}
.section-label:after{content:"";height:1px;background:var(--line);flex:1}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.field{display:flex;flex-direction:column;gap:6px}.field.full{grid-column:1/-1}
label{font-size:9px;color:#94a2b5;font-weight:700}
input,select,textarea{width:100%;border:1px solid #1c2b3d;background:#07111d;color:#e9eef5;border-radius:11px;padding:12px 13px;outline:0;font-size:10px;transition:.2s}
input::placeholder,textarea::placeholder{color:#4e6075}
input:focus,select:focus,textarea:focus{border-color:rgba(220,168,63,.7);box-shadow:0 0 0 3px rgba(220,168,63,.07)}
select{appearance:none;background-image:linear-gradient(45deg,transparent 50%,#8c9bae 50%),linear-gradient(135deg,#8c9bae 50%,transparent 50%);background-position:calc(100% - 15px) 50%,calc(100% - 11px) 50%;background-size:4px 4px;background-repeat:no-repeat;padding-right:32px}
textarea{min-height:78px;resize:vertical}
.input-prefix{position:relative}.input-prefix span{position:absolute;left:13px;top:12px;color:#6d7d90;font-size:10px}.input-prefix input{padding-left:38px}

.service-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:9px}
.service-option{position:relative}.service-option input{position:absolute;opacity:0;pointer-events:none}
.service-option label{display:block;height:100%;padding:14px;border:1px solid #1c2b3d;border-radius:13px;background:#08121f;cursor:pointer;transition:.2s}
.service-option label strong{display:block;color:#e9eef5;font-size:10px;margin-bottom:3px}.service-option label span{display:block;color:#64758a;font-size:8px;line-height:1.4}
.service-option input:checked+label{border-color:rgba(220,168,63,.75);background:linear-gradient(145deg,rgba(220,168,63,.13),rgba(220,168,63,.035));box-shadow:inset 0 0 0 1px rgba(220,168,63,.12)}
.service-option input:checked+label:after{content:"✓";position:absolute;right:9px;top:8px;width:17px;height:17px;border-radius:50%;display:grid;place-items:center;background:var(--gold);color:#10151c;font-size:9px;font-weight:900}

.form-footer{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-top:25px;padding-top:20px;border-top:1px solid var(--line)}
.note{color:#64758a;font-size:8px;max-width:350px}.note b{color:#a9b5c5}.submit{min-width:170px}

.summary{padding:25px;position:sticky;top:94px;overflow:hidden}
.summary:before{content:"";position:absolute;width:230px;height:230px;border-radius:50%;right:-100px;top:-110px;background:rgba(220,168,63,.09);filter:blur(5px)}
.summary h3{position:relative;font:800 18px Manrope;letter-spacing:-.035em}.summary-sub{position:relative;color:#68788d;font-size:9px;margin-top:3px}
.quote-total{position:relative;margin:22px 0;padding:20px;border-radius:15px;background:linear-gradient(145deg,#172434,#0b1521);border:1px solid rgba(220,168,63,.16)}
.quote-total label{display:block;color:#718197;font-size:8px;text-transform:uppercase;letter-spacing:.1em}.quote-total strong{display:block;font:800 32px Manrope;color:var(--gold2);letter-spacing:-.055em;margin-top:4px}.quote-total small{color:#5f7085;font-size:8px}
.summary-row{display:flex;justify-content:space-between;gap:10px;padding:10px 0;border-bottom:1px solid var(--line);font-size:9px}.summary-row span:first-child{color:#6f8095}.summary-row span:last-child{color:#d8e0ea;text-align:right}
.summary-row.total{border:0;padding-top:15px}.summary-row.total span:last-child{color:#fff;font-weight:800}
.vat{margin-top:14px;padding:11px;border-radius:11px;background:rgba(56,200,139,.05);border:1px solid rgba(56,200,139,.13);font-size:8px;color:#7cae9a}
.summary-actions{display:flex;gap:8px;margin-top:17px}.summary-actions button, .summary-actions a{flex:1}

.trust{padding:70px 0;background:#070e18;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.trust-head{text-align:center;margin-bottom:30px}.trust-head h2{font:800 28px Manrope;letter-spacing:-.045em}.trust-head p{color:#718096;font-size:10px;margin-top:4px}
.trust-grid{display:grid;grid-template-columns:repeat(4,1fr);border:1px solid var(--line);border-radius:18px;overflow:hidden}
.trust-item{padding:22px;text-align:center;border-right:1px solid var(--line)}.trust-item:last-child{border:0}
.trust-icon{margin:auto;width:38px;height:38px;display:grid;place-items:center;border-radius:12px;color:var(--gold2);background:rgba(220,168,63,.08);border:1px solid rgba(220,168,63,.18)}
.trust-item h4{font-size:10px;margin:10px 0 3px}.trust-item p{font-size:8px;color:#66778c}

.faq{padding:75px 0}.faq-grid{display:grid;grid-template-columns:.9fr 1.4fr;gap:55px}.faq h2{font:800 34px Manrope;letter-spacing:-.055em}.faq-intro{color:#718096;font-size:10px;margin-top:10px}
details{border-bottom:1px solid var(--line);padding:17px 0}summary{list-style:none;cursor:pointer;font-size:11px;font-weight:700}summary::-webkit-details-marker{display:none}summary:after{content:"+";float:right;color:var(--gold2);font-size:17px}details[open] summary:after{content:"−"}details p{color:#6e7f94;font-size:9px;padding:10px 24px 0 0}

footer{padding:58px 0 20px;background:#03070d;border-top:1px solid var(--line)}
.footer-grid{display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr;gap:38px;padding-bottom:40px}.footer-grid h4{font-size:10px;margin-bottom:14px}.footer-grid p,.footer-grid a{display:block;color:#607087;font-size:9px;margin:8px 0}.footer-grid a:hover{color:var(--gold2)}
.footer-bottom{border-top:1px solid var(--line);padding-top:17px;color:#47566a;font-size:8px;display:flex;justify-content:space-between}
.toast{position:fixed;right:22px;bottom:22px;z-index:100;padding:14px 16px;border-radius:13px;background:#0d1927;border:1px solid rgba(220,168,63,.3);box-shadow:var(--shadow);font-size:10px;color:#dce5ef;transform:translateY(30px);opacity:0;pointer-events:none;transition:.3s}.toast.show{transform:none;opacity:1}
@media(max-width:950px){.links{display:none}.menu{display:block}.quote-layout{grid-template-columns:1fr}.summary{position:static}.trust-grid{grid-template-columns:1fr 1fr}.trust-item:nth-child(2){border-right:0}.trust-item:nth-child(-n+2){border-bottom:1px solid var(--line)}.faq-grid{grid-template-columns:1fr;gap:30px}}
@media(max-width:650px){.topbar{height:66px}.hero{padding:58px 0 45px}.form-card{padding:20px}.grid{grid-template-columns:1fr}.field.full{grid-column:auto}.service-grid{grid-template-columns:1fr}.form-footer{align-items:stretch;flex-direction:column}.submit{width:100%}.trust-grid{grid-template-columns:1fr}.trust-item{border-right:0!important;border-bottom:1px solid var(--line)}.trust-item:last-child{border-bottom:0}.footer-grid{grid-template-columns:1fr}.footer-bottom{flex-direction:column;gap:7px}}
</style>
</head>
<body>

<header class="topbar">
 <div class="container nav">
   <a class="brand" href="<?= \App\Core\View::url('/') ?>" aria-label="RC Courier home" style="display:flex; align-items:center; gap:12px; text-decoration:none;">
    <img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" srcset="<?= \App\Core\View::url('/assets/images/rc_logo_256.png') ?> 2x, <?= \App\Core\View::url('/assets/images/rc_logo_hd.png') ?> 3x" alt="RC Courier Logo" style="height: 48px; width: 48px; border-radius: 10px; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.3); border: 1.5px solid rgba(241,196,94,0.45); image-rendering: -webkit-optimize-contrast;">
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
    <button class="menu">☰</button>
  </div>
 </div>
</header>

<section class="hero">
 <div class="container">
  <div class="eyebrow">RC Courier Smart Quotation</div>
  <h1>Know your delivery cost.<br><span>Before you ship.</span></h1>
  <p>Build a professional shipping quote in seconds. Select your service, route and shipment details to receive an indicative price in AED.</p>
 </div>
</section>

<main class="quote-area">
 <div class="container quote-layout">
  <section class="card form-card">
   <div class="card-head">
    <div><h2>Build your shipment quote</h2><p>Tell us about the shipment and we'll calculate the estimate.</p></div>
    <div class="live-badge">● AED PRICING</div>
   </div>

   <form id="quoteForm" action="<?= \App\Core\View::url('/book') ?>" method="GET">
    <div class="section-label">01 · Shipment route</div>
    <div class="grid">
     <div class="field">
      <label for="origin">Pickup location</label>
      <select id="origin" name="origin_emirate" required>
        <option value="">Select Emirate</option>
        <option value="Dubai" selected>Dubai</option>
        <option value="Abu Dhabi">Abu Dhabi</option>
        <option value="Sharjah">Sharjah</option>
        <option value="Ajman">Ajman</option>
        <option value="Ras Al Khaimah">Ras Al Khaimah</option>
        <option value="Fujairah">Fujairah</option>
        <option value="Umm Al Quwain">Umm Al Quwain</option>
      </select>
     </div>
     <div class="field">
      <label for="destination">Delivery location</label>
      <select id="destination" name="destination_emirate" required>
        <option value="">Select destination</option>
        <option value="Dubai">Dubai</option>
        <option value="Abu Dhabi" selected>Abu Dhabi</option>
        <option value="Sharjah">Sharjah</option>
        <option value="Ajman">Ajman</option>
        <option value="Ras Al Khaimah">Ras Al Khaimah</option>
        <option value="Fujairah">Fujairah</option>
        <option value="Umm Al Quwain">Umm Al Quwain</option>
        <option value="Saudi Arabia">Saudi Arabia</option>
        <option value="Oman">Oman</option>
        <option value="Qatar">Qatar</option>
        <option value="Bahrain">Bahrain</option>
        <option value="Kuwait">Kuwait</option>
        <option value="International">International</option>
      </select>
     </div>
    </div>

    <div class="section-label">02 · Service</div>
    <div class="service-grid">
     <div class="service-option"><input type="radio" name="service" id="same" value="Same-Day Express" data-base="35" checked><label for="same"><strong>Same-Day Express</strong><span>Fast UAE delivery</span></label></div>
     <div class="service-option"><input type="radio" name="service" id="next" value="Next-Day Delivery" data-base="25"><label for="next"><strong>Next-Day Delivery</strong><span>Cost-effective UAE service</span></label></div>
     <div class="service-option"><input type="radio" name="service" id="gcc" value="GCC Road Freight" data-base="250"><label for="gcc"><strong>GCC Road Freight</strong><span>Door-to-door GCC cargo</span></label></div>
     <div class="service-option"><input type="radio" name="service" id="air" value="International Air" data-base="450"><label for="air"><strong>International Air</strong><span>Priority global shipping</span></label></div>
     <div class="service-option"><input type="radio" name="service" id="heavy" value="Heavy Freight" data-base="650"><label for="heavy"><strong>Heavy Freight</strong><span>FTL & logistics</span></label></div>
     <div class="service-option"><input type="radio" name="service" id="ecom" value="E-Commerce Logistics" data-base="30"><label for="ecom"><strong>E-Commerce</strong><span>Fulfillment & last-mile</span></label></div>
    </div>

    <div class="section-label">03 · Package details</div>
    <div class="grid">
     <div class="field"><label for="weight">Actual weight</label><div class="input-prefix"><span>KG</span><input id="weight" name="weight" type="number" min="0.1" step="0.1" value="1.0" required></div></div>
     <div class="field"><label for="pieces">Number of pieces</label><input id="pieces" name="pieces" type="number" min="1" value="1" required></div>
     <div class="field"><label for="length">Length (cm)</label><input id="length" name="length" type="number" min="1" value="20"></div>
     <div class="field"><label for="width">Width (cm)</label><input id="width" name="width" type="number" min="1" value="15"></div>
     <div class="field"><label for="height">Height (cm)</label><input id="height" name="height" type="number" min="1" value="10"></div>
     <div class="field"><label for="contents">Package contents</label><input id="contents" name="contents" placeholder="e.g. Documents, electronics"></div>
    </div>

    <div class="section-label">04 · Contact details</div>
    <div class="grid">
     <div class="field"><label for="name">Full name</label><input id="name" name="name" placeholder="Your name" required></div>
     <div class="field"><label for="phone">UAE phone number</label><input id="phone" name="phone" type="tel" placeholder="+971 5X XXX XXXX" required></div>
     <div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" placeholder="you@company.com" required></div>
     <div class="field"><label for="company">Company (optional)</label><input id="company" name="company" placeholder="Company name"></div>
     <div class="field full"><label for="notes">Additional requirements</label><textarea id="notes" name="notes" placeholder="Pickup timing, fragile goods, special handling or other requirements..."></textarea></div>
    </div>

    <div class="form-footer">
     <div class="note"><b>Indicative pricing:</b> Final rates may vary based on route, volumetric weight, surcharges, customs, fuel and account-specific pricing. VAT is shown separately.</div>
     <button class="btn btn-gold submit" type="submit">Calculate & Book →</button>
    </div>
   </form>
  </section>

  <aside class="card summary">
   <h3>Your estimated quote</h3>
   <p class="summary-sub">Live calculation · AED</p>
   <div class="quote-total"><label>Estimated total</label><strong id="total">AED 38.85</strong><small>Including 5% UAE VAT</small></div>
   <div class="summary-row"><span>Service</span><span id="sumService">Same-Day Express</span></div>
   <div class="summary-row"><span>Base rate</span><span id="sumBase">AED 35.00</span></div>
   <div class="summary-row"><span>Weight / handling</span><span id="sumWeight">AED 0.00</span></div>
   <div class="summary-row"><span>Fuel & operations</span><span id="sumFuel">AED 0.00</span></div>
   <div class="summary-row"><span>Subtotal</span><span id="sumSubtotal">AED 37.00</span></div>
   <div class="summary-row"><span>VAT 5%</span><span id="sumVat">AED 1.85</span></div>
   <div class="summary-row total"><span>Estimated payable</span><span id="sumTotal">AED 38.85</span></div>
   <div class="vat">&check; Transparent AED estimate. Server calculation powered by RC Courier Pricing Engine.</div>
   <div class="summary-actions">
      <a href="<?= \App\Core\View::url('/book') ?>" class="btn btn-gold" id="requestQuote">Book This Shipment</a>
      <button class="btn btn-dark" onclick="window.print()">Print</button>
   </div>
  </aside>
 </div>
</main>

<section class="trust">
 <div class="container">
  <div class="trust-head"><h2>Built around how UAE businesses ship.</h2><p>One quotation experience for local deliveries, GCC freight and international logistics.</p></div>
  <div class="trust-grid">
   <div class="trust-item"><div class="trust-icon">AED</div><h4>UAE Pricing</h4><p>Quotes presented clearly in AED with VAT separated.</p></div>
   <div class="trust-item"><div class="trust-icon">↗</div><h4>Smart Routing</h4><p>Designed for UAE Emirates, GCC and international destinations.</p></div>
   <div class="trust-item"><div class="trust-icon">◈</div><h4>Business Ready</h4><p>Ready for customer-specific rates and corporate accounts.</p></div>
   <div class="trust-item"><div class="trust-icon">&check;</div><h4>Fast Response</h4><p>Turn an estimate into a booking or sales quotation.</p></div>
  </div>
 </div>
</section>

<section class="faq">
 <div class="container faq-grid">
  <div><div class="eyebrow">Need to know</div><h2>Quote with confidence.</h2><p class="faq-intro">The public quote is an estimate. Your production system can apply the exact RC Courier rate card and commercial rules.</p></div>
  <div>
   <details open><summary>Is the displayed price final?</summary><p>No. The quotation calculates an indicative estimate based on base rates, weight, and volume. Final billing applies actual volumetric weight and route rules upon hub verification.</p></details>
   <details><summary>Can I quote GCC shipments?</summary><p>Yes. The interface includes GCC destinations (Saudi Arabia, Oman, Qatar, Bahrain, Kuwait) and applies cross-border freight rules.</p></details>
   <details><summary>Can corporate customers have special pricing?</summary><p>Yes. Corporate account pricing rules stored in MySQL automatically apply discounted rate cards for logged-in business users.</p></details>
   <details><summary>Can a quote become a booking?</summary><p>Yes. Clicking "Book This Shipment" transfers your quote reference and package details directly into the booking checkout pipeline.</p></details>
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

<div class="toast" id="toast"></div>

<script>
const form=document.getElementById('quoteForm');
const toast=document.getElementById('toast');

function money(v){return 'AED '+v.toFixed(2)}
function calculate(){
 const service=document.querySelector('input[name="service"]:checked');
 const base=Number(service.dataset.base||0);
 const weight=Math.max(0.1,Number(document.getElementById('weight').value)||0.1);
 const pieces=Math.max(1,Number(document.getElementById('pieces').value)||1);
 const volume=(Number(document.getElementById('length').value)||0)*(Number(document.getElementById('width').value)||0)*(Number(document.getElementById('height').value)||0);
 const volumetric=volume/5000;
 const chargeable=Math.max(weight,volumetric);
 const weightCharge=Math.max(0,chargeable-1)*3*pieces;
 const fuel=(base+weightCharge)*0.04;
 const handling=pieces>1?(pieces-1)*2:0;
 const subtotal=base+weightCharge+fuel+handling;
 const vat=subtotal*0.05;
 const total=subtotal+vat;

 document.getElementById('sumService').textContent=service.value;
 document.getElementById('sumBase').textContent=money(base);
 document.getElementById('sumWeight').textContent=money(weightCharge+handling);
 document.getElementById('sumFuel').textContent=money(fuel);
 document.getElementById('sumSubtotal').textContent=money(subtotal);
 document.getElementById('sumVat').textContent=money(vat);
 document.getElementById('sumTotal').textContent=money(total);
 document.getElementById('total').textContent=money(total);
}
form.addEventListener('input',calculate);
form.addEventListener('change',calculate);

function showToast(message){
 toast.textContent=message;toast.classList.add('show');
 clearTimeout(window.toastTimer);window.toastTimer=setTimeout(()=>toast.classList.remove('show'),3800);
}
calculate();
</script>
</body>
</html>
