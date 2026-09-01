<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? 'Create Shipment — RC Courier Admin') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root{--nav:#091225;--bg:#f4f7fa;--ink:#102039;--muted:#708096;--line:#e3e9f0;--gold:#e0ad48;--gold2:#f6d27a;--blue:#168fd2;--red:#ef6a6a;--green:#38c88b}
*{box-sizing:border-box}body{margin:0;font:16px/1.5 Inter,Arial,sans-serif;background:var(--bg);color:var(--ink)}
.app{min-height:100vh}.side{position:fixed;inset:0 auto 0 0;width:245px;background:linear-gradient(180deg,#081021,#0d172b);color:#9fb0c7;padding:22px 14px;z-index:5}
.logo{padding:0 8px 28px;font-weight:800;font-size:19px;color:#fff}.logo b{color:var(--gold2)}.mini{display:inline-grid;place-items:center;width:34px;height:34px;border:1px solid #b78b35;border-radius:10px;color:#f5cf77;margin-right:8px;font-size:13px}
.label{font-size:10px;text-transform:uppercase;letter-spacing:.15em;color:#50627b;padding:13px 9px 7px}.nav a{display:block;padding:11px 10px;margin:3px 0;border-radius:9px;color:#9fb0c7;text-decoration:none;font-size:13px;font-weight:600}.nav a:hover,.nav .active{background:rgba(224,173,72,.12);color:#f5cf77}.bottom{position:absolute;bottom:20px;left:14px;right:14px;border-top:1px solid #233047;padding-top:15px}.user{display:flex;gap:9px;align-items:center;margin-bottom:12px}.avatar{width:32px;height:32px;border-radius:50%;background:#18263d;color:#f5cf77;display:grid;place-items:center;font-weight:800;font-size:11px}.user small{display:block;color:#63758d}.logout{width:100%;padding:10px;border:1px solid #34435a;border-radius:9px;background:transparent;color:#fff;cursor:pointer}
.main{margin-left:245px}.top{height:72px;background:#fff;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 30px;position:sticky;top:0;z-index:4}.top h2{font-size:18px;margin:0}.top span{font-size:12.5px;color:var(--muted)}.content{padding:29px;max-width:1400px}.heading{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:20px}.heading h1{font-size:29px;margin:0 0 4px}.heading p{margin:0;color:var(--muted);font-size:13px}.btn{border:0;border-radius:9px;background:linear-gradient(135deg,var(--gold2),var(--gold));padding:12px 20px;font-weight:800;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;color:#102039}.btn-dark{background:#101c2b;color:#fff}
.layout{display:grid;grid-template-columns:1.6fr .9fr;gap:20px;align-items:start}
.card{background:#fff;border:1px solid var(--line);border-radius:18px;padding:25px;box-shadow:0 18px 50px rgba(16,31,51,.06);margin-bottom:20px}
.card h2{font-size:17px;margin:0 0 15px;color:#102039;display:flex;align-items:center;gap:8px}.card h2 span{color:var(--gold);font-size:14.5px}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}.field{display:flex;flex-direction:column;gap:5px}.full{grid-column:1/-1}
label{font-size:11.5px;color:#64748b;font-weight:700}input,select,textarea{width:100%;border:1px solid var(--line);background:#f8fafc;color:#1e293b;border-radius:9px;padding:11px 12px;outline:0;font-size:13px;transition:.2s}input:focus,select:focus,textarea:focus{border-color:var(--gold);background:#fff;box-shadow:0 0 0 3px rgba(224,173,72,.12)}textarea{min-height:70px;resize:vertical}
.choice-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}.choice{position:relative}.choice input{position:absolute;opacity:0;pointer-events:none}.choice label{display:block;padding:11px;border:1px solid var(--line);border-radius:11px;background:#f8fafc;cursor:pointer;height:100%;transition:.2s}.choice label strong{display:block;color:#0f172a;font-size:12.5px;margin-bottom:2px}.choice label span{display:block;color:#64748b;font-size:10.5px}.choice input:checked+label{border-color:var(--gold);background:rgba(224,173,72,.08);box-shadow:inset 0 0 0 1px var(--gold)}
.checkbox-row{display:flex;align-items:center;gap:8px;margin-top:12px;padding:10px 12px;background:#f1f5f9;border-radius:9px;border:1px solid #e2e8f0;font-size:11.5px;color:#334155}
.checkbox-row input{width:auto;margin:0;accent-color:var(--gold)}
.summary-box{background:linear-gradient(145deg,#0d1826,#08111c);color:#fff;border-radius:18px;padding:24px;position:sticky;top:92px}
.summary-box h3{font-size:18px;margin:0 0 4px}.summary-sub{color:#708096;font-size:11.5px;margin-bottom:15px}
.sum-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.08);font-size:11.5px;color:#9fb0c7}.sum-row.total{border:0;padding-top:12px;font-size:14px;font-weight:800;color:#fff}.sum-row.total span:last-child{color:var(--gold2);font-size:22px}
.alert{padding:12px 15px;border-radius:11px;font-size:12.5px;margin-bottom:15px;font-weight:600}.alert-error{background:#fdf2f2;color:#d93838;border:1px solid #f8d7d7}
@media(max-width:950px){.side{display:none}.main{margin:0}.layout{grid-template-columns:1fr}}
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
    <h2>Create New Shipment — Admin Portal</h2>
    <span>Signed in as <b><?= e($adminUser['name'] ?? 'Admin User') ?></b> (<?= e(strtoupper($adminUser['role_name'] ?? 'ADMIN')) ?>)</span>
  </header>

  <section class="content">
    <div class="heading">
      <div>
        <h1>Create New Shipment</h1>
        <p>Book and dispatch a new shipment directly into the RC Courier network.</p>
      </div>
      <a href="<?= \App\Core\View::url('/admin/shipments') ?>" class="btn btn-dark">← Back to Shipments</a>
    </div>

    <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
      <div class="alert alert-error"><?= e($msg) ?></div>
    <?php endif; ?>

    <form action="<?= \App\Core\View::url('/admin/shipments/create') ?>" method="POST" id="createForm">
      <input type="hidden" name="_token" value="<?= \App\Core\Session::getCsrfToken() ?>">

      <div class="layout">
        <div>
          <!-- 01 Customer Selection -->
          <div class="card">
            <h2><span>01</span> Account & Customer Selection</h2>
            <div class="grid">
              <div class="field full">
                <label for="customer">Select Account Customer</label>
                <select id="customer" name="customer_id" required>
                  <?php foreach ($customers as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= e($c['contact_name']) ?> (<?= e($c['company_name'] ?: 'Individual') ?> — <?= e($c['email']) ?>)</option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <!-- 02 Service Level -->
          <div class="card">
            <h2><span>02</span> Route & Service Level</h2>
            <div class="choice-grid">
              <div class="choice"><input type="radio" name="service_id" id="s1" value="1" data-base="35" checked><label for="s1"><strong>Same-Day Express</strong><span>Priority UAE</span></label></div>
              <div class="choice"><input type="radio" name="service_id" id="s2" value="2" data-base="25"><label for="s2"><strong>Next-Day Delivery</strong><span>Standard UAE</span></label></div>
              <div class="choice"><input type="radio" name="service_id" id="s3" value="3" data-base="250"><label for="s3"><strong>GCC Road Freight</strong><span>Door-to-door cargo</span></label></div>
              <div class="choice"><input type="radio" name="service_id" id="s4" value="4" data-base="450"><label for="s4"><strong>International Air</strong><span>Priority worldwide</span></label></div>
              <div class="choice"><input type="radio" name="service_id" id="s5" value="5" data-base="650"><label for="s5"><strong>Heavy Freight</strong><span>FTL logistics</span></label></div>
              <div class="choice"><input type="radio" name="service_id" id="s6" value="1" data-base="30"><label for="s6"><strong>E-Commerce</strong><span>Fulfillment</span></label></div>
            </div>
          </div>

          <!-- 03 SENDER SECTION -->
          <div class="card">
            <h2><span>03</span> Sender Details & Pickup Address</h2>
            <div class="grid">
              <div class="field"><label for="sName">Sender Contact Name</label><input id="sName" name="sender_name" placeholder="Sender Full Name" required></div>
              <div class="field"><label for="sPhone">Sender Mobile / Phone</label><input id="sPhone" name="sender_phone" placeholder="+971 5X XXX XXXX" required></div>
              <div class="field"><label for="origin">Pickup Emirate</label>
                <select id="origin" name="origin_emirate" required>
                  <?php foreach ($emirates as $e): ?>
                    <option value="<?= $e ?>" <?= $e === 'Dubai' ? 'selected' : '' ?>><?= $e ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="field"><label for="sArea">Pickup Area</label><input id="sArea" name="pickup_area" placeholder="e.g. Al Quoz Industrial 3" required></div>
              <div class="field full"><label for="sAddr">Pickup Building / Street Address</label><input id="sAddr" name="sender_address" placeholder="Building name, office/unit number, street" required></div>
              <div class="field full"><label for="pickupAt">Pickup Date &amp; Time</label><input type="datetime-local" id="pickupAt" name="pickup_at" value="<?= date('Y-m-d\TH:i') ?>" required style="font-weight:700; color:var(--ink);"></div>
            </div>
          </div>

          <!-- 04 RECEIVER SECTION (With Save Address Option) -->
          <div class="card">
            <h2><span>04</span> Receiver Details & Delivery Address</h2>
            
            <?php if (!empty($addresses)): ?>
              <div class="field full" style="margin-bottom:12px;">
                <label for="addressSelect">Load Saved Receiver Address (Optional)</label>
                <select id="addressSelect" onchange="loadSavedAddress(this)">
                  <option value="">-- Choose from Customer Address Book --</option>
                  <?php foreach ($addresses as $addr): ?>
                    <option value="<?= $addr['id'] ?>" 
                            data-label="<?= e($addr['label']) ?>"
                            data-line1="<?= e($addr['address_line1']) ?>"
                            data-area="<?= e($addr['area']) ?>"
                            data-emirate="<?= e($addr['emirate']) ?>">
                      <?= e($addr['label']) ?>: <?= e($addr['address_line1']) ?>, <?= e($addr['area']) ?> (<?= e($addr['emirate']) ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>

            <div class="grid">
              <div class="field"><label for="rName">Receiver Contact Name</label><input id="rName" name="receiver_name" placeholder="Receiver Full Name" required></div>
              <div class="field"><label for="rPhone">Receiver Mobile / Phone</label><input id="rPhone" name="receiver_phone" placeholder="+971 5X XXX XXXX" required></div>
              <div class="field"><label for="dest">Delivery Destination</label>
                <select id="dest" name="destination_emirate" required>
                  <?php foreach ($emirates as $e): ?>
                    <option value="<?= $e ?>" <?= $e === 'Abu Dhabi' ? 'selected' : '' ?>><?= $e ?></option>
                  <?php endforeach; ?>
                  <option value="Saudi Arabia">Saudi Arabia</option>
                  <option value="Oman">Oman</option>
                  <option value="Qatar">Qatar</option>
                  <option value="Bahrain">Bahrain</option>
                  <option value="Kuwait">Kuwait</option>
                  <option value="International">International</option>
                </select>
              </div>
              <div class="field"><label for="rArea">Delivery Area</label><input id="rArea" name="delivery_area" placeholder="e.g. Business Bay" required></div>
              <div class="field full"><label for="rAddr">Delivery Building / Street Address</label><input id="rAddr" name="receiver_address" placeholder="Building name, villa number, street" required></div>
            </div>

            <!-- Save Address for Future Option -->
            <div class="checkbox-row">
              <input type="checkbox" id="saveAddr" name="save_receiver_address" value="1" onchange="toggleLabelInput(this)">
              <label for="saveAddr" style="cursor:pointer;font-weight:700;color:#0f172a;">
                <b>☑ Save this receiver address to customer's address book for future shipments</b>
              </label>
            </div>

            <div class="field full" id="labelField" style="display:none;margin-top:10px;">
              <label for="addrLabel">Address Book Label (e.g. Main Office, Warehouse, Home)</label>
              <input id="addrLabel" name="receiver_address_label" placeholder="e.g. Abu Dhabi Branch Warehouse" value="Receiver Address">
            </div>
          </div>

          <!-- 05 Package Specifications -->
          <div class="card">
            <h2><span>05</span> Package Specifications</h2>
            <div class="grid">
              <div class="field"><label for="weight">Weight (KG)</label><input id="weight" name="weight_kg" type="number" step="0.1" min="0.1" value="1.0" required></div>
              <div class="field"><label for="pieces">Quantity (Pieces)</label><input id="pieces" name="quantity" type="number" min="1" value="1" required></div>
              <div class="field full"><label for="value">Declared Value (AED)</label><input id="value" name="declared_value" type="number" min="0" value="0"></div>
              <div class="field full"><label for="desc">Item Description</label><input id="desc" name="item_description" placeholder="e.g. Commercial documents, electronics" required></div>
              <div class="field full"><label for="notes">Internal / Special Notes</label><textarea id="notes" name="notes" placeholder="Special handling or delivery instructions..."></textarea></div>
            </div>
          </div>

          <!-- 06 Payment Method & Billing -->
          <div class="card">
            <h2><span>06</span> Payment Method &amp; Shipping Charges</h2>
            <div class="grid">
              <div class="field">
                <label for="payMethod">Select Payment Method</label>
                <select id="payMethod" name="payment_method" onchange="togglePaymentFields(this.value)" required style="font-weight:700; color:var(--ink);">
                  <option value="cash" selected>Cash Payment</option>
                  <option value="credit_card">Card Payment</option>
                </select>
              </div>
              <div class="field" id="cardNumField" style="display:none;">
                <label for="cardNum">Card Number (Masked)</label>
                <input id="cardNum" name="card_number" readonly style="font-weight:800; letter-spacing:1px; color:var(--blue); background:#eef7fc;">
              </div>
              <div class="field full">
                <label for="customShipping" style="color:#b47a0d; font-weight:800;">Manual Shipping Charge (AED) (Optional Price Override)</label>
                <input id="customShipping" name="custom_shipping_charge" type="number" step="0.01" min="0" placeholder="e.g. 50.00 (Leave blank to use tariff calculator rate)" style="border-color:var(--gold); background:#fffdf5; font-weight:700;">
              </div>
            </div>
          </div>
        </div>

        <!-- Sticky Live Pricing Summary -->
        <aside class="summary-box">
          <h3>Calculated Summary</h3>
          <div class="summary-sub">Live AED pricing breakdown</div>

          <div class="sum-row"><span>Base Rate / Shipping Charge</span><span id="sumBase">AED 35.00</span></div>
          <div class="sum-row"><span>Weight / Handling</span><span id="sumWeight">AED 0.00</span></div>
          <div class="sum-row"><span>Fuel & Ops</span><span id="sumFuel">AED 0.00</span></div>
          <div class="sum-row"><span>Subtotal</span><span id="sumSubtotal">AED 37.00</span></div>
          <div class="sum-row"><span>UAE VAT (5%)</span><span id="sumVat">AED 1.85</span></div>
          <div class="sum-row total"><span>Total Payable</span><span id="sumTotal">AED 38.85</span></div>

          <div style="margin-top:20px;padding-top:15px;border-top:1px solid rgba(255,255,255,.1);font-size:8px;color:#708096;line-height:1.4;">
            ✓ Generating reference `SHP-2026-XXXXXX` and tracking code `AE-XXXXX-2026` upon submission.
          </div>

          <button type="submit" class="btn" style="width:100%;margin-top:20px;padding:13px;">Confirm & Create Shipment →</button>
        </aside>
      </div>
    </form>
  </section>
</main>

<script>
const form = document.getElementById('createForm');

function toggleLabelInput(cb){
  document.getElementById('labelField').style.display = cb.checked ? 'block' : 'none';
}

function generateRandomCardNumber(){
  const rand4 = Math.floor(1000 + Math.random() * 9000);
  return '**** **** **** ' + rand4;
}

function togglePaymentFields(val){
  const cardField = document.getElementById('cardNumField');
  const cardInput = document.getElementById('cardNum');
  if(val === 'credit_card'){
    cardField.style.display = 'flex';
    cardInput.value = generateRandomCardNumber();
  } else {
    cardField.style.display = 'none';
    cardInput.value = '';
  }
}

function loadSavedAddress(sel){
  const opt = sel.options[sel.selectedIndex];
  if(!opt.value) return;
  if(opt.dataset.line1) document.getElementById('rAddr').value = opt.dataset.line1;
  if(opt.dataset.area) document.getElementById('rArea').value = opt.dataset.area;
  if(opt.dataset.emirate) document.getElementById('dest').value = opt.dataset.emirate;
}

function calc(){
  const s = document.querySelector('input[name="service_id"]:checked');
  let base = Number(s ? s.dataset.base || 35 : 35);
  const w = Math.max(0.1, Number(document.getElementById('weight').value) || 0.1);
  const p = Math.max(1, Number(document.getElementById('pieces').value) || 1);
  let wChg = Math.max(0, w - 1) * 3 * p;

  const customInput = document.getElementById('customShipping');
  const customVal = customInput ? parseFloat(customInput.value) : NaN;

  let fuel = 0;
  let sub = 0;

  if (!isNaN(customVal) && customVal >= 0 && customInput.value.trim() !== '') {
    base = customVal;
    wChg = 0;
    fuel = 0;
    sub = customVal;
  } else {
    fuel = (base + wChg) * 0.04;
    sub = base + wChg + fuel;
  }

  const vat = sub * 0.05;
  const tot = sub + vat;

  document.getElementById('sumBase').textContent = 'AED ' + base.toFixed(2);
  document.getElementById('sumWeight').textContent = 'AED ' + wChg.toFixed(2);
  document.getElementById('sumFuel').textContent = 'AED ' + fuel.toFixed(2);
  document.getElementById('sumSubtotal').textContent = 'AED ' + sub.toFixed(2);
  document.getElementById('sumVat').textContent = 'AED ' + vat.toFixed(2);
  document.getElementById('sumTotal').textContent = 'AED ' + tot.toFixed(2);
}
form.addEventListener('input', calc);
form.addEventListener('change', calc);
calc();
</script>
</body>
</html>
