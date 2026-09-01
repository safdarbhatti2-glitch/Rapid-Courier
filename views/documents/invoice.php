<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? 'RC Courier — UAE Tax Invoice') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{
  --ink:#16324a;
  --muted:#61778b;
  --line:#d8e4ec;
  --line-strong:#b9cbd8;
  --paper:#fff;
  --canvas:#edf3f7;
  --soft:#f6f9fb;
  --blue:#087fbd;
  --blue-soft:#eaf7fd;
  --gold:#d69b20;
  --gold-soft:#fff8e6;
  --green:#087f55;
  --red:#c92e3a;
}
*{box-sizing:border-box}
html,body{margin:0;padding:0}
body{
  background:var(--canvas);
  color:var(--ink);
  font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;
  font-size:14px;
  line-height:1.45;
}
.toolbar{
  width:min(1080px,calc(100% - 32px));
  margin:24px auto 14px;
  display:flex;
  justify-content:space-between;
  gap:12px;
}
.toolbar .left,.toolbar .right{display:flex;gap:8px;flex-wrap:wrap}
button, a.btn{
  border:1px solid #c8d6e0;
  background:#fff;
  color:var(--ink);
  border-radius:9px;
  padding:10px 14px;
  font-weight:750;
  cursor:pointer;
  text-decoration:none;
  display:inline-flex;
  align-items:center;
  gap:6px;
  font-size:13px;
}
button.primary, a.btn.primary{background:var(--ink);color:#fff;border-color:var(--ink)}
button.gold, a.btn.gold{background:var(--gold);color:#fff;border-color:var(--gold)}

.sheet{
  width:min(1080px,calc(100% - 32px));
  margin:0 auto 30px;
  background:#fff;
  border:2px solid #16324a;
  border-radius:12px;
  box-shadow:0 18px 55px rgba(32,61,81,.12);
  overflow:hidden;
  position:relative;
}
.topline{height:6px;background:linear-gradient(90deg,var(--gold),#f1c35c,var(--blue))}
.header{
  padding:30px 38px 25px;
  display:grid;
  grid-template-columns:1.25fr .9fr;
  gap:30px;
  border-bottom:1px solid var(--line);
}
.brand-row{display:flex;align-items:center;gap:13px}
.logo{
  width:52px;height:52px;border:1px solid #cbd9e3;border-radius:14px;
  display:grid;place-items:center;font-weight:900;font-size:18px;
  color:var(--gold);background:#f8fbfd;
}
.brand h1{font-size:21px;margin:0;color:var(--ink);letter-spacing:-.4px}
.brand small{display:block;color:var(--blue);font-weight:800;letter-spacing:.8px;margin-top:2px}
.company-info{margin-top:18px;color:var(--muted);font-size:12.5px}
.company-info strong{color:var(--ink)}
.invoice-meta{text-align:right}
.invoice-meta .label{
  color:var(--gold);font-size:11px;font-weight:900;letter-spacing:1.7px;
  text-transform:uppercase
}
.invoice-meta h2{
  margin:2px 0 14px;font-size:31px;letter-spacing:-1.1px;color:var(--ink)
}
.meta-grid{
  display:grid;grid-template-columns:1fr 1fr;gap:8px 18px;
  font-size:12px;text-align:left
}
.meta-grid span{color:var(--muted)}
.meta-grid b{display:block;color:var(--ink);margin-top:1px}

.tracking-band{
  margin:24px 38px 0;
  border:1px solid #b9dcec;
  background:linear-gradient(110deg,#f3fbfe,#e9f7fc);
  border-radius:15px;
  padding:18px 20px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:18px;
}
.tracking-label{
  font-size:10px;font-weight:900;letter-spacing:1.4px;color:var(--blue);
  text-transform:uppercase
}
.tracking-id{
  font-size:25px;font-weight:950;letter-spacing:1.1px;color:var(--ink);
  margin-top:2px
}
.tracking-status{
  padding:9px 14px;border-radius:999px;background:#fff;
  border:1px solid #b9d7e6;color:var(--blue);font-size:11px;font-weight:900
}

.content{padding:25px 38px 32px}
.parties{
  display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:22px
}
.party{
  border:1px solid var(--line);border-radius:14px;padding:18px;background:#fff
}
.party .eyebrow{
  color:var(--muted);font-size:10px;font-weight:900;letter-spacing:1px;text-transform:uppercase
}
.party h3{margin:7px 0 5px;font-size:17px}
.party p{margin:2px 0;color:var(--muted);font-size:12.5px}
.party p strong{color:var(--ink)}

.section-title{
  display:flex;align-items:end;justify-content:space-between;
  margin:24px 0 10px
}
.section-title h3{margin:0;font-size:15px}
.section-title span{color:var(--muted);font-size:11px}

.table-wrap{border:1px solid var(--line);border-radius:13px;overflow:hidden}
table{width:100%;border-collapse:collapse}
th{
  background:#f3f7fa;color:var(--ink);font-size:10px;
  text-transform:uppercase;letter-spacing:.45px;text-align:left;padding:12px 11px;
  border-bottom:1px solid var(--line)
}
td{padding:14px 11px;border-bottom:1px solid #e7eef3;font-size:12px;vertical-align:top}
tr:last-child td{border-bottom:0}
.num{text-align:right;white-space:nowrap}
.service-name{font-weight:800;color:var(--ink)}
.service-note{font-size:10.5px;color:var(--muted);margin-top:2px}

.lower{
  display:grid;grid-template-columns:1fr 340px;gap:22px;margin-top:20px
}
.info-card{
  border:1px solid var(--line);border-radius:14px;padding:18px;background:#fbfdfe
}
.info-card h4{margin:0 0 8px;font-size:12px}
.info-card p{margin:4px 0;color:var(--muted);font-size:11.5px}
.info-card b{color:var(--ink)}
.payment-badge{
  display:inline-flex;align-items:center;gap:7px;
  border:1px solid #9ed5bd;color:var(--green);background:#f1fbf6;
  border-radius:8px;padding:7px 10px;font-size:10px;font-weight:900
}
.payment-badge.unpaid{
  border-color:#e0ad48;color:#b78b35;background:#fffbeb;
}
.totals{
  border:1px solid var(--line);border-radius:14px;padding:17px 18px;background:#fff
}
.total-line{display:flex;justify-content:space-between;gap:15px;padding:7px 0;color:var(--muted);font-size:12px}
.total-line b{color:var(--ink)}
.total-line.grand{
  margin-top:5px;padding-top:14px;border-top:2px solid var(--ink);
  font-size:17px;color:var(--ink);font-weight:900
}
.total-line.grand b{color:var(--gold);font-size:20px}
.total-line.paid b{color:var(--green)}
.total-line.due b{color:var(--red)}

.verification{
  margin-top:22px;
  border:1px solid #c6dce9;border-radius:15px;
  padding:18px 20px;background:#f8fcfe;
  display:grid;grid-template-columns:116px 1fr;gap:20px;align-items:center
}
.qr-box{
  width:104px;height:104px;border:1px solid #cbdbe5;background:#fff;
  border-radius:10px;display:grid;place-items:center;padding:7px
}
#qrcode{width:90px;height:90px;display:grid;place-items:center}
.verify h3{margin:0 0 5px;font-size:14px}
.verify p{margin:3px 0;color:var(--muted);font-size:11.5px}
.verify .code{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;color:var(--blue);font-size:11px}

.terms{
  margin-top:22px;border:1px solid var(--line);border-radius:14px;
  padding:18px 20px;background:#fff
}
.terms h3{margin:0 0 9px;font-size:13px}
.terms ol{margin:0;padding-left:20px;color:var(--muted);font-size:10.8px}
.terms li{margin:5px 0}
.terms strong{color:var(--ink)}

.footer{
  border-top:1px solid var(--line);padding:17px 38px;
  display:flex;justify-content:space-between;gap:20px;
  color:var(--muted);font-size:10.5px;background:#f8fafc
}
.footer strong{color:var(--ink)}

@media(max-width:760px){
  .toolbar{width:calc(100% - 20px)}
  .sheet{width:calc(100% - 20px);border-radius:12px}
  .header{grid-template-columns:1fr;padding:23px}
  .invoice-meta{text-align:left}
  .meta-grid{text-align:left}
  .tracking-band{margin:18px 23px 0;align-items:flex-start;flex-direction:column}
  .content{padding:20px 23px 25px}
  .parties,.lower{grid-template-columns:1fr}
  .verification{grid-template-columns:1fr}
  .footer{padding:16px 23px;flex-direction:column}
  .table-wrap{overflow-x:auto}
  table{min-width:720px}
}

@page{size:A4;margin:10mm}
@media print{
  body{background:#fff;font-size:12px}
  .toolbar{display:none}
  .sheet{
    width:100%;margin:0;border:2px solid #16324a !important;box-shadow:none;border-radius:4px !important;box-sizing:border-box;
  }
  .topline{height:3px}
  .header{padding:18px 22px 16px}
  .tracking-band{margin:14px 22px 0;padding:12px 15px}
  .tracking-id{font-size:21px}
  .content{padding:17px 22px 22px}
  .footer{padding:12px 22px}
  .party,.info-card,.totals,.terms,.verification{break-inside:avoid}
  table{break-inside:auto}
  tr{break-inside:avoid}
  a{color:inherit;text-decoration:none}
}
</style>
</head>

<body>

<div class="toolbar">
  <div class="left">
    <a href="<?= \App\Core\View::url('/admin/invoices') ?>" class="btn">← Back to Invoices</a>
  </div>
  <div class="right">
    <a href="<?= \App\Core\View::url('/verify/invoice/' . $invoice['invoice_number']) ?>" target="_blank" class="btn">🔍 Verify Invoice</a>
    <button onclick="window.print()" class="primary">Print / Save PDF</button>
  </div>
</div>

<main class="sheet" id="invoice">
  <div class="topline"></div>

  <header class="header">
    <div>
      <div class="brand-row">
        <div class="logo" style="padding:0; border:none; background:transparent;"><img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:50px; width:50px; border-radius:10px; object-fit:cover; border: 1.5px solid #dcae3d; box-shadow: 0 2px 8px rgba(0,0,0,0.12);"></div>
        <div class="brand">
          <h1>RC COURIER</h1>
          <small>UAE • GCC LOGISTICS</small>
        </div>
      </div>

      <div class="company-info">
        <strong><?= e(!empty($company['company_name']) ? $company['company_name'] : 'RC Courier UAE LLC') ?></strong><br>
        <?= e(!empty($company['company_address']) ? $company['company_address'] : 'Dubai, United Arab Emirates') ?><br>
        TRN: <strong><?= e(!empty($company['company_trn']) ? $company['company_trn'] : '100987654321003') ?></strong><br>
        Email: <?= e(!empty($company['company_email']) ? $company['company_email'] : 'support@rccourier.ae') ?> · Tel: <?= e(!empty($company['company_phone']) ? $company['company_phone'] : '+971 4 800 2684') ?>
      </div>
    </div>

    <div class="invoice-meta">
      <div class="label">VAT DOCUMENT</div>
      <h2>TAX INVOICE</h2>
      <div class="meta-grid">
        <div><span>Invoice No.</span><b id="invoiceNo"><?= e($invoice['invoice_number']) ?></b></div>
        <div><span>Issue Date</span><b id="issueDate"><?= e(date('d M Y', strtotime($invoice['issue_date']))) ?></b></div>
        <div><span>Supply Date</span><b id="supplyDate"><?= e(date('d M Y', strtotime($invoice['issue_date']))) ?></b></div>
        <div><span>Currency</span><b><?= e($invoice['currency'] ?: 'AED') ?></b></div>
      </div>
    </div>
  </header>

  <section class="tracking-band">
    <div>
      <div class="tracking-label">Shipment Tracking ID</div>
      <div class="tracking-id" id="trackingId"><?= e($invoice['tracking_number'] ?: ($invoice['reference_number'] ?: 'RC84920412')) ?></div>
    </div>
    <div class="tracking-status"><?= e(!empty($invoice['shipment_status']) ? $invoice['shipment_status'] : ($invoice['status'] ?? 'BOOKED')) ?> · <?= e(strtoupper(!empty($invoice['service_name']) ? $invoice['service_name'] : 'EXPRESS SERVICE')) ?></div>
  </section>

  <section class="content">
    <div class="parties">
      <article class="party">
        <div class="eyebrow" style="color:var(--gold);">SENDER ADDRESS</div>
        <h3><?= e($invoice['sender_name'] ?: ($invoice['company_name'] ?: $invoice['contact_name'])) ?></h3>
        <p><?= e($invoice['sender_address'] ?: 'Logistics Central Station') ?></p>
        <p><?= e($invoice['sender_area'] ?: 'Al Quoz Industrial 3') ?>, <strong><?= e($invoice['sender_emirate'] ?: 'Dubai') ?>, UAE</strong></p>
        <p><strong>Contact Tel:</strong> <?= e($invoice['phone']) ?></p>
      </article>

      <article class="party">
        <div class="eyebrow" style="color:var(--blue);">RECEIVER ADDRESS</div>
        <h3><?= e($invoice['receiver_name'] ?: 'Valued Consignee') ?></h3>
        <p><?= e($invoice['receiver_address'] ?: 'Delivery Building / Street Address') ?></p>
        <p><?= e($invoice['receiver_area'] ?: 'Central Area') ?>, <strong><?= e($invoice['receiver_emirate'] ?: 'Abu Dhabi') ?></strong></p>
        <p><strong>Contact Tel:</strong> <?= e($invoice['phone']) ?></p>
      </article>
    </div>

    <div class="section-title">
      <h3>Services Supplied</h3>
      <span>All amounts expressed in UAE Dirham (AED)</span>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:35%">Description of service</th>
            <th style="width:12%">Qty</th>
            <th class="num">Unit price<br>(AED)</th>
            <th class="num">Discount<br>(AED)</th>
            <th class="num">VAT<br>(5%)</th>
            <th class="num">Line total<br>(AED)</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
              <tr>
                <td>
                  <div class="service-name"><?= e($item['description']) ?></div>
                  <div class="service-note"><?= e($invoice['item_description'] ?: 'Door-to-door UAE express delivery') ?></div>
                </td>
                <td><?= e($item['quantity']) ?> shipment</td>
                <td class="num"><?= e(number_format($item['unit_price'], 2)) ?></td>
                <td class="num"><?= e(number_format($item['discount'], 2)) ?></td>
                <td class="num"><?= e(number_format($item['line_tax'], 2)) ?></td>
                <td class="num"><strong><?= e(number_format($item['line_total'], 2)) ?></strong></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td>
                <div class="service-name">Same-Day Express Courier</div>
                <div class="service-note">Door-to-door UAE express delivery</div>
              </td>
              <td>1 shipment</td>
              <td class="num"><?= e(number_format($invoice['subtotal'], 2)) ?></td>
              <td class="num"><?= e(number_format($invoice['discount'], 2)) ?></td>
              <td class="num"><?= e(number_format($invoice['tax'], 2)) ?></td>
              <td class="num"><strong><?= e(number_format($invoice['total'], 2)) ?></strong></td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="lower">
      <div class="info-card">
        <h4>Shipment & Payment Information</h4>
        <p><b>Origin:</b> <?= e($invoice['sender_emirate'] ?: 'Dubai') ?>, UAE</p>
        <p><b>Destination:</b> <?= e($invoice['receiver_emirate'] ?: 'Abu Dhabi') ?>, UAE</p>
        <p><b>Package:</b> 1 parcel · <?= e(number_format($invoice['weight_kg'] ?: 1.0, 2)) ?> kg</p>
        <?php 
          $pMethod = !empty($payments) ? ($payments[0]['method'] ?? 'credit_card') : 'credit_card';
          $pRef    = !empty($payments) ? ($payments[0]['reference'] ?? '') : '';
          if (!str_starts_with($pRef, '**** **** **** ')) {
              $rand4 = preg_match('/\d{4}/', $pRef, $m) ? $m[0] : (string)mt_rand(1000, 9999);
              $pRef = '**** **** **** ' . $rand4;
          }
        ?>
        <p><b>Payment method:</b> <?= $pMethod === 'credit_card' ? 'Card Payment (' . e($pRef) . ')' : 'Cash Payment' ?></p>
        <p style="margin-top:12px">
          <?php if ($invoice['status'] === 'PAID'): ?>
            <span class="payment-badge">✓ PAYMENT STATUS: PAID</span>
          <?php else: ?>
            <span class="payment-badge unpaid">⏱ STATUS: <?= e($invoice['status']) ?></span>
          <?php endif; ?>
        </p>
      </div>

      <div class="totals">
        <div class="total-line"><span>Taxable amount</span><b><?= e(number_format($invoice['subtotal'], 2)) ?> AED</b></div>
        <div class="total-line"><span>VAT rate</span><b>5%</b></div>
        <div class="total-line"><span>VAT amount</span><b><?= e(number_format($invoice['tax'], 2)) ?> AED</b></div>
        <div class="total-line grand"><span>Total incl. VAT</span><b><?= e(number_format($invoice['total'], 2)) ?> AED</b></div>
        <div class="total-line paid"><span>Amount paid</span><b><?= e(number_format($invoice['amount_paid'], 2)) ?> AED</b></div>
        <div class="total-line due"><span>Balance due</span><b><?= e(number_format($invoice['balance_due'], 2)) ?> AED</b></div>
      </div>
    </div>

    <section class="verification">
      <div class="qr-box"><div id="qrcode" aria-label="Invoice verification QR code"></div></div>
      <div class="verify">
        <h3>Invoice Verification</h3>
        <p>Scan to verify this invoice and confirm its shipment and payment information through RC Courier's official verification portal.</p>
        <p class="code">Invoice: <span id="verifyInvoice"><?= e($invoice['invoice_number']) ?></span></p>
        <p class="code">Tracking: <span id="verifyTracking"><?= e($invoice['tracking_number'] ?: 'RC84920412') ?></span></p>
        <p class="code" id="verifyUrlText"></p>
      </div>
    </section>

    <section class="terms">
      <h3>Terms & Conditions — UAE Courier Services</h3>
      <ol>
        <li><strong>Tax invoice:</strong> This document records the taxable supply identified above. VAT is calculated at the applicable UAE VAT rate.</li>
        <li><strong>Payment:</strong> Unless otherwise agreed in writing, outstanding amounts are payable according to the customer's approved account terms.</li>
        <li><strong>Delivery:</strong> Delivery times are estimates unless a guaranteed service level has been expressly confirmed by RC Courier.</li>
        <li><strong>Shipment contents:</strong> The sender is responsible for accurate declarations and must not tender prohibited, restricted, hazardous or undeclared goods.</li>
        <li><strong>Claims:</strong> Loss, damage or delay claims are subject to RC Courier's applicable carriage terms, service conditions and statutory requirements.</li>
        <li><strong>VAT:</strong> Where a VAT treatment differs from the standard treatment, the applicable tax treatment and required statement should be shown on the final issued invoice.</li>
        <li><strong>Electronic verification:</strong> The QR code is provided for document verification and does not replace the accounting records required by applicable UAE law.</li>
      </ol>
    </section>
  </section>

  <footer class="footer">
    <div><strong><?= e(!empty($company['company_name']) ? $company['company_name'] : 'RC Courier UAE LLC') ?></strong> · <?= e(!empty($company['company_address']) ? $company['company_address'] : 'Dubai, United Arab Emirates') ?> · TRN <?= e(!empty($company['company_trn']) ? $company['company_trn'] : '100987654321003') ?></div>
    <div><?= e(!empty($company['company_email']) ? $company['company_email'] : 'support@rccourier.ae') ?> · rccourier.ae</div>
  </footer>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
(function(){
  const verificationUrl = <?= json_encode(\App\Core\View::qrUrl('/verify/invoice/' . $invoice['invoice_number'])) ?>;

  document.getElementById('verifyUrlText').textContent = verificationUrl;

  const qr = document.getElementById('qrcode');
  if(window.QRCode){
    new QRCode(qr, {
      text: verificationUrl,
      width: 90,
      height: 90,
      colorDark: '#16324a',
      colorLight: '#ffffff',
      correctLevel: QRCode.CorrectLevel.M
    });
  } else {
    qr.innerHTML = '<span style="font-size:9px;text-align:center;color:#61778b">QR unavailable</span>';
  }
})();
</script>
</body>
</html>
