<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{
  --paper:#ffffff;
  --ink:#000000;
}
*{box-sizing:border-box}
body{
  margin:0;
  padding:15px 5px;
  background:#cbd5e1;
  font-family:'Courier Prime',monospace,'Courier New',Courier;
  font-size:10.5px;
  color:#000;
  line-height:1.25;
}
.toolbar{
  width:72mm;
  max-width:300px;
  margin:0 auto 12px;
  display:flex;
  gap:8px;
  justify-content:center;
}
.btn{
  padding:8px 12px;
  border-radius:6px;
  font-family:Inter,sans-serif;
  font-size:11.5px;
  font-weight:700;
  cursor:pointer;
  border:none;
  transition:transform .15s;
}
.btn:hover{transform:translateY(-1px)}
.btn-print{background:#0f172a;color:#fff}
.btn-image{background:#dcae3f;color:#0f172a}

/* 80mm Thermal Receipt Canvas */
.receipt-wrap{
  width:72mm;
  max-width:300px;
  margin:0 auto;
  background:#fff;
  padding:12px 10px;
  box-shadow:0 8px 24px rgba(0,0,0,.15);
  border:1px solid #94a3b8;
}

.center{text-align:center}
.right{text-align:right}
.bold{font-weight:700}
.dashed{border-top:1px dashed #000;margin:8px 0}
.solid-double{border-top:2px double #000;margin:8px 0}

.brand-title{font-family:Inter,sans-serif;font-size:15px;font-weight:900;letter-spacing:-.4px;margin:0 0 2px}
.brand-sub{font-size:9px;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:4px}
.company-meta{font-size:9.5px;line-height:1.3}

.tracking-box{
  border:1.5px solid #000;
  padding:6px 4px;
  margin:6px 0;
  text-align:center;
}
.tracking-title{font-size:8px;text-transform:uppercase;letter-spacing:0.8px}
.tracking-num{font-family:Inter,sans-serif;font-size:13.5px;font-weight:900;letter-spacing:0.3px;margin:2px 0}

.parties-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:6px;
  margin:6px 0;
  font-size:9.5px;
}
.party-cell strong{display:block;font-size:10.5px;margin-top:1px;word-break:break-word}

.items-table{width:100%;border-collapse:collapse;margin:6px 0;font-size:9.5px}
.items-table th{border-bottom:1px solid #000;padding-bottom:3px;text-align:left}
.items-table td{padding:3px 0;vertical-align:top}

.total-row{display:flex;justify-content:space-between;margin:2px 0;font-size:9.5px}
.total-row.grand{font-size:12.5px;font-weight:700;padding:4px 0;border-top:1px solid #000;border-bottom:1px solid #000;margin:6px 0}

.barcode-strip{
  font-family:monospace;
  font-size:22px;
  letter-spacing:3px;
  margin:6px 0 2px;
  user-select:none;
}

#barcodeSvg{
  width: 100% !important;
  max-width: 100% !important;
  height: auto !important;
  display: block;
  margin: 0 auto;
}

.qr-box{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:4px;
  margin:8px 0 4px;
}

@media print{
  @page {
    size: 80mm auto;
    margin: 0;
  }
  html, body {
    width: 80mm;
    margin: 0;
    padding: 0;
    background: #fff;
  }
  .toolbar{display:none!important}
  .receipt-wrap{
    width: 72mm !important;
    max-width: 72mm !important;
    margin: 0 auto !important;
    padding: 3mm 1.5mm !important;
    box-shadow: none !important;
    border: none !important;
  }
}
</style>
</head>
<body>

<div class="toolbar">
  <button class="btn btn-print" onclick="window.print()">🖨️ Print 80mm</button>
  <button class="btn btn-image" id="downloadImageBtn">🖼️ PNG Image</button>
</div>

<div class="receipt-wrap" id="thermalReceiptCard">
  <div class="center">
    <div style="margin-bottom:6px;"><img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:48px; width:48px; border-radius:10px; object-fit:cover; border: 1.5px solid #000;"></div>
    <div class="brand-title">RC COURIER UAE</div>
    <div class="brand-sub">Express Logistics & Cargo LLC</div>
    <div class="company-meta">
      <?= e($company['company_address'] ?? 'Dubai Logistics Hub, UAE') ?><br>
      TRN: <strong><?= e($company['company_trn'] ?? '100987654321003') ?></strong><br>
      Tel: <?= e($company['company_phone'] ?? '+971 4 800 2684') ?>
    </div>
  </div>

  <div class="dashed"></div>

  <div class="tracking-box">
    <div class="tracking-title">WAYBILL / TRACKING NUMBER</div>
    <div class="tracking-num"><?= e($invoice['tracking_number'] ?: ($invoice['reference_number'] ?: 'RC84920412')) ?></div>
    <div style="font-size:9px; font-weight:700; text-transform:uppercase;">
      <?= e(strtoupper($invoice['service_name'] ?: 'EXPRESS SERVICE')) ?>
    </div>
  </div>

  <div class="parties-grid">
    <div class="party-cell">
      <span style="font-size:8px; text-transform:uppercase;">01 SENDER</span>
      <strong><?= e($invoice['sender_name'] ?: ($invoice['company_name'] ?: $invoice['contact_name'])) ?></strong>
      <div><?= e($invoice['sender_emirate'] ?: 'Dubai') ?>, UAE</div>
    </div>
    <div class="party-cell">
      <span style="font-size:8px; text-transform:uppercase;">02 RECEIVER</span>
      <strong><?= e($invoice['receiver_name'] ?: 'Valued Consignee') ?></strong>
      <div><?= e($invoice['receiver_emirate'] ?: 'Abu Dhabi') ?>, UAE</div>
    </div>
  </div>

  <div class="dashed"></div>

  <div style="display:flex; justify-content:space-between; font-size:9.5px;">
    <span>INV #: <strong><?= e($invoice['invoice_number']) ?></strong></span>
    <span>DATE: <strong><?= date('d/m/Y', strtotime($invoice['issue_date'])) ?></strong></span>
  </div>

  <div class="dashed"></div>

  <table class="items-table">
    <thead>
      <tr>
        <th>Item / Service</th>
        <th style="text-align:center;">Qty</th>
        <th class="right">Total</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= e($item['description']) ?></td>
          <td style="text-align:center;"><?= e($item['quantity']) ?></td>
          <td class="right"><?= number_format((float)($item['line_total'] ?? 0), 2) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="dashed"></div>

  <div class="total-row">
    <span>Subtotal:</span>
    <span><?= number_format((float)($invoice['subtotal'] ?? 0), 2) ?> AED</span>
  </div>
  <div class="total-row">
    <span>UAE VAT (5%):</span>
    <span><?= number_format((float)($invoice['tax'] ?? $invoice['tax_amount'] ?? 0), 2) ?> AED</span>
  </div>

  <div class="total-row grand">
    <span>TOTAL (AED):</span>
    <span><?= number_format((float)($invoice['total'] ?? 0), 2) ?> AED</span>
  </div>

  <div class="total-row">
    <span>Amount Paid:</span>
    <span><?= number_format((float)($invoice['amount_paid'] ?? 0), 2) ?> AED</span>
  </div>
  <div class="total-row">
    <span>Balance Due:</span>
    <span><strong><?= number_format((float)($invoice['balance_due'] ?? 0), 2) ?> AED</strong></span>
  </div>

  <div class="dashed"></div>

  <div style="font-size:9.5px; line-height:1.4;">
    <div>Status: <strong>[ <?= e($invoice['status']) ?> ]</strong></div>
    <?php 
      $pMethod = !empty($payments) ? ($payments[0]['method'] ?? 'credit_card') : 'credit_card';
      $pRef    = !empty($payments) ? ($payments[0]['reference'] ?? '') : '';
      if (!str_starts_with($pRef, '**** **** **** ')) {
          $rand4 = preg_match('/\d{4}/', $pRef, $m) ? $m[0] : (string)mt_rand(1000, 9999);
          $pRef = '**** **** **** ' . $rand4;
      }
    ?>
    <?php if ($pMethod === 'credit_card'): ?>
      <div>Payment Method: <strong>Card Payment (<?= e($pRef) ?>)</strong></div>
    <?php else: ?>
      <div>Payment Method: <strong>Cash Payment</strong></div>
    <?php endif; ?>
    <?php if (!empty($payments[0]['payment_number'])): ?>
      <div>Txn Ref #: <code><?= e($payments[0]['payment_number']) ?></code></div>
    <?php endif; ?>
  </div>

  <div class="center" style="margin-top:10px;">
    <div class="qr-box">
      <div id="qrcode"></div>
      <div style="font-size:8px;">Scan to verify invoice online</div>
    </div>

    <div style="margin-top:6px;">
      <svg id="barcodeSvg" style="max-width:100%; height:auto;"></svg>
    </div>
  </div>

  <div class="dashed"></div>

  <div class="center" style="font-size:8.5px; line-height:1.3;">
    Thank you for choosing RC Courier UAE!<br>
    Track online: <strong>rccourier.ae/track</strong><br>
    Support Helpline: <strong>800-RC-COURIER</strong>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
(function(){
  const invoice = <?= json_encode($invoice['invoice_number']) ?>;
  const trackingNumber = <?= json_encode($invoice['tracking_number'] ?: ($invoice['reference_number'] ?: 'RC84920412')) ?>;
  const verificationUrl = <?= json_encode(\App\Core\View::qrUrl('/verify/invoice/' . $invoice['invoice_number'])) ?>;

  const qr = document.getElementById('qrcode');
  if(window.QRCode){
    new QRCode(qr, {
      text: verificationUrl,
      width: 60,
      height: 60,
      colorDark: '#000000',
      colorLight: '#ffffff',
      correctLevel: QRCode.CorrectLevel.M
    });
  }

  if(window.JsBarcode){
    JsBarcode("#barcodeSvg", trackingNumber, {
      format: "CODE128",
      lineColor: "#000000",
      width: 1.1,
      height: 35,
      displayValue: true,
      fontSize: 9,
      font: "Courier Prime",
      margin: 0
    });
  }

  document.getElementById('downloadImageBtn').addEventListener('click', function(){
    const target = document.getElementById('thermalReceiptCard');
    html2canvas(target, { scale: 2.5 }).then(canvas => {
      const link = document.createElement('a');
      link.download = 'Thermal_Receipt_80mm_' + invoice + '.png';
      link.href = canvas.toDataURL('image/png');
      link.click();
    });
  });
})();
</script>
</body>
</html>
