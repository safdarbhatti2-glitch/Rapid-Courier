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
:root {
  --navy: #0b1830;
  --purple-dark: #581c87;
  --purple-light: #f3e8ff;
  --purple-text: #6b21a8;
  --gold-bg: #fffbeb;
  --gold-border: #fde68a;
  --gold-text: #b45309;
  --blue-bg: #eff6ff;
  --blue-border: #bfdbfe;
  --blue-text: #1d4ed8;
  --card-bg: #f8fafc;
  --card-border: #e2e8f0;
  --text-dark: #0f172a;
  --text-muted: #64748b;
  --text-light: #94a3b8;
}

* { box-sizing: border-box; }
html, body { margin: 0; padding: 0; }
body {
  background: #f1f5f9;
  color: var(--text-dark);
  font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  font-size: 13px;
  line-height: 1.5;
}

.toolbar {
  width: min(1000px, calc(100% - 32px));
  margin: 20px auto 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.toolbar a, .toolbar button {
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 13px;
  cursor: pointer;
  text-decoration: none;
  border: 1px solid #cbd5e1;
  background: #fff;
  color: var(--text-dark);
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.toolbar button.primary {
  background: var(--navy);
  color: #fff;
  border-color: var(--navy);
}

.sheet {
  width: min(1000px, calc(100% - 32px));
  margin: 0 auto 30px;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
  padding: 36px 40px;
  position: relative;
  overflow: hidden;
}

/* Header Grid */
.header-grid {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  border-bottom: 1.5px solid #f1f5f9;
  padding-bottom: 24px;
  margin-bottom: 24px;
}
.brand-left {
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.logo-row {
  display: flex;
  align-items: center;
  gap: 12px;
}
.brand-logo-img {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  object-fit: cover;
  box-shadow: 0 4px 12px rgba(11, 24, 48, 0.15);
  border: 1.5px solid rgba(241, 196, 94, 0.5);
  image-rendering: -webkit-optimize-contrast;
  flex-shrink: 0;
}
.brand-name {
  font-size: 20px;
  font-weight: 900;
  color: var(--navy);
  letter-spacing: -0.3px;
}
.brand-sub {
  font-size: 11px;
  font-weight: 700;
  color: var(--text-muted);
}
.company-details {
  font-size: 12px;
  color: var(--text-muted);
  line-height: 1.6;
}
.trn-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: var(--gold-bg);
  border: 1px solid var(--gold-border);
  color: var(--gold-text);
  padding: 4px 12px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 800;
  margin-top: 6px;
}

/* Header Right Meta */
.header-right {
  text-align: right;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 6px;
}
.doc-title {
  font-size: 28px;
  font-weight: 900;
  color: var(--navy);
  margin: 0;
  letter-spacing: -0.5px;
}
.vat-receipt-badge {
  background: var(--purple-light);
  color: var(--purple-text);
  font-size: 10px;
  font-weight: 800;
  padding: 4px 10px;
  border-radius: 4px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.meta-table {
  margin-top: 10px;
  font-size: 12px;
  border-collapse: collapse;
}
.meta-table td {
  padding: 3px 0;
  vertical-align: middle;
}
.meta-table td.label {
  color: var(--text-muted);
  font-weight: 600;
  text-align: right;
  white-space: nowrap;
  padding-right: 12px;
}
.meta-table td.val {
  color: var(--text-dark);
  font-weight: 800;
  font-family: Inter, ui-sans-serif, system-ui, sans-serif;
  text-align: left;
  white-space: nowrap;
}

/* 3-Column Parties Grid */
.parties-grid {
  display: grid;
  grid-template-columns: 1fr 1fr 1.1fr;
  gap: 16px;
  margin-bottom: 24px;
}
.party-card {
  background: var(--card-bg);
  border: 1px solid var(--card-border);
  border-radius: 10px;
  padding: 16px;
}
.party-card .card-title {
  font-size: 10px;
  font-weight: 800;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 5px;
}
.party-card .party-name {
  font-size: 15px;
  font-weight: 800;
  color: var(--navy);
  margin-bottom: 4px;
}
.party-card .party-address {
  font-size: 12px;
  color: var(--text-muted);
  line-height: 1.5;
}
.party-card .party-phone {
  font-size: 12px;
  color: var(--text-dark);
  font-weight: 700;
  margin-top: 6px;
}

/* Payment Info Card */
.pay-status-title {
  font-size: 14px;
  font-weight: 800;
  color: #15803d;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 4px;
}
.pay-method-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: #ccfbf1;
  color: #0f766e;
  border: 1px solid #99f6e4;
  padding: 3px 10px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 800;
  margin-bottom: 8px;
}
.pay-meta {
  font-size: 11.5px;
  color: var(--text-muted);
  line-height: 1.6;
}
.pay-meta b { color: var(--text-dark); }

/* Line Items Table */
.items-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}
.items-table th {
  border-bottom: 1.5px solid var(--text-dark);
  padding: 10px 8px;
  text-align: left;
  font-size: 10px;
  font-weight: 800;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.items-table th.right, .items-table td.right { text-align: right; }
.items-table th.center, .items-table td.center { text-align: center; }
.items-table td {
  padding: 14px 8px;
  border-bottom: 1px solid #f1f5f9;
  font-size: 12.5px;
  vertical-align: top;
}
.item-title { font-weight: 800; color: var(--navy); }
.item-sub { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

/* Subtotals Block */
.subtotals-wrapper {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 24px;
}
.subtotals-box {
  width: 380px;
}
.subtotal-row {
  display: flex;
  justify-content: space-between;
  padding: 5px 0;
  font-size: 13px;
  color: var(--text-muted);
}
.subtotal-row.tax { color: #0284c7; font-weight: 700; }
.subtotal-row b { color: var(--text-dark); }

/* Total Amount Due Black Banner */
.total-due-banner {
  background: var(--navy);
  color: #ffffff;
  border-radius: 8px;
  padding: 12px 18px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 8px;
}
.total-due-banner .banner-label {
  font-size: 14px;
  font-weight: 800;
}
.total-due-banner .banner-amount {
  font-size: 22px;
  font-weight: 900;
}
.amount-words {
  text-align: right;
  font-size: 11px;
  font-style: italic;
  color: var(--text-muted);
  margin-top: 6px;
}

/* UAE FTA VAT Breakdown Box */
.vat-breakdown-card {
  background: var(--gold-bg);
  border: 1px solid var(--gold-border);
  border-radius: 8px;
  padding: 14px 18px;
  margin-bottom: 16px;
}
.vat-card-header {
  font-size: 11px;
  font-weight: 800;
  color: var(--gold-text);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.vat-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
}
.vat-col .v-label {
  font-size: 10px;
  font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase;
}
.vat-col .v-val {
  font-size: 15px;
  font-weight: 800;
  color: var(--navy);
  margin-top: 2px;
}

/* UAE FTA Compliance Banner */
.fta-notice-card {
  background: var(--blue-bg);
  border: 1px solid var(--blue-border);
  border-radius: 8px;
  padding: 12px 16px;
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 20px;
}
.ae-badge {
  width: 36px;
  height: 36px;
  background: var(--blue-text);
  color: #ffffff;
  border-radius: 6px;
  display: grid;
  place-items: center;
  font-weight: 900;
  font-size: 14px;
  flex-shrink: 0;
}
.fta-text {
  font-size: 11px;
  color: #1e3a8a;
  line-height: 1.45;
}

/* Terms & Conditions Grid */
.terms-card {
  border: 1px solid var(--card-border);
  border-radius: 8px;
  padding: 16px 18px;
  background: #ffffff;
  margin-bottom: 24px;
}
.terms-header {
  font-size: 11px;
  font-weight: 800;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 10px;
}
.terms-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px 24px;
  font-size: 10.5px;
  color: var(--text-muted);
  padding-left: 14px;
  margin: 0;
}
.terms-grid li { margin-bottom: 4px; }

/* Footer Bar */
.footer-bar {
  border-top: 1px solid var(--card-border);
  padding-top: 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 11px;
  color: var(--text-muted);
}
.footer-contact strong { color: var(--navy); }
.verify-qr-block {
  display: flex;
  align-items: center;
  gap: 12px;
}
.qr-container {
  width: 130px;
  height: 130px;
  background: #ffffff;
  border: 1.5px solid #cbd5e1;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 8px;
  box-shadow: 0 4px 14px rgba(11, 24, 48, 0.08);
}
.qr-meta {
  text-align: right;
  font-size: 10px;
  line-height: 1.4;
}

@media print {
  body { background: #fff; }
  .toolbar { display: none; }
  .sheet { width: 100%; margin: 0; border: none; box-shadow: none; padding: 20px; }
  @page { size: A4; margin: 8mm; }
}
</style>
</head>
<body>

<div class="toolbar">
  <div>
    <a href="<?= \App\Core\View::url('/admin/invoices') ?>">← Back to Invoices</a>
  </div>
  <div>
    <a href="<?= \App\Core\View::url('/verify/invoice/' . $invoice['invoice_number']) ?>" target="_blank">🔍 Verify Invoice</a>
    <button onclick="window.print()" class="primary">Print / Save PDF</button>
  </div>
</div>

<?php
  // Helper for Number to Words (UAE Dirhams)
  function numToWords(float $amount): string {
      $units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
      $tens  = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
      
      $whole = (int)floor($amount);
      $fraction = (int)round(($amount - $whole) * 100);
      
      if ($whole === 0) {
          $str = 'Zero';
      } else {
          $words = [];
          if ($whole >= 1000) {
              $th = (int)floor($whole / 1000);
              $words[] = numToWords($th) . ' Thousand';
              $whole %= 1000;
          }
          if ($whole >= 100) {
              $h = (int)floor($whole / 100);
              $words[] = $units[$h] . ' Hundred';
              $whole %= 100;
          }
          if ($whole > 0) {
              if (!empty($words)) $words[] = 'And';
              if ($whole < 20) {
                  $words[] = $units[$whole];
              } else {
                  $t = (int)floor($whole / 10);
                  $u = $whole % 10;
                  $words[] = $tens[$t] . ($u > 0 ? ' ' . $units[$u] : '');
              }
          }
          $str = implode(' ', $words);
      }
      
      $res = $str . ' UAE Dirhams';
      if ($fraction > 0) {
          $res .= ' And ' . $fraction . ' Fils';
      }
      return $res . ' Only';
  }

  $totalVal = (float)$invoice['total'];
  $subtotalVal = (float)$invoice['subtotal'];
  $taxVal = (float)$invoice['tax'];
  $amountWords = numToWords($totalVal);

  $invNum = $invoice['invoice_number'];
  $trkNum = !empty($invoice['tracking_number']) ? $invoice['tracking_number'] : ($invoice['reference_number'] ?: 'RC84920412');
  $pickupOrIssueDate = !empty($invoice['pickup_at']) ? $invoice['pickup_at'] : (!empty($invoice['issue_date']) ? $invoice['issue_date'] : null);
  $issueDateFormatted = !empty($pickupOrIssueDate) ? date('d M Y', strtotime($pickupOrIssueDate)) : date('d M Y');
?>

<main class="sheet">
  <!-- Header Grid -->
  <header class="header-grid">
    <div class="brand-left">
      <div class="logo-row">
        <img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" srcset="<?= \App\Core\View::url('/assets/images/rc_logo_256.png') ?> 2x, <?= \App\Core\View::url('/assets/images/rc_logo_hd.png') ?> 3x" alt="RC Courier Logo" class="brand-logo-img">
        <div>
          <div class="brand-name">RC Courier LLC</div>
          <div class="brand-sub">UAE's Premier Courier & Logistics Partner</div>
        </div>
      </div>
      <div class="company-details">
        <?= e(!empty($company['company_name']) ? $company['company_name'] : 'RC Courier Logistics LLC') ?><br>
        Office 412, Business Bay Tower, Business Bay Dubai, UAE — PO Box 90878<br>
        Tel: <?= e(!empty($company['company_phone']) ? $company['company_phone'] : '+971 4 800 2684') ?> · Email: <?= e(!empty($company['company_email']) ? $company['company_email'] : 'billing@rapid-courier.com') ?><br>
        Web: www.rapid-courier.com
      </div>
      <div>
        <div class="trn-pill">
          🔑 TRN: <?= e(!empty($company['company_trn']) ? $company['company_trn'] : '100987654321003') ?> · VAT Reg: AE-VAT-RC-2024
        </div>
      </div>
    </div>

    <div class="header-right">
      <h1 class="doc-title">Tax Invoice</h1>
      <div class="vat-receipt-badge">VAT RECEIPT · UAE FTA COMPLIANT</div>
      <table class="meta-table">
        <tr><td class="label">Invoice #:</td><td class="val"><?= e($invNum) ?></td></tr>
        <tr><td class="label">Issue Date:</td><td class="val"><?= e($issueDateFormatted) ?></td></tr>
        <tr><td class="label">Currency:</td><td class="val">UAE Dirham (AED)</td></tr>
        <tr><td class="label">Tracking:</td><td class="val"><?= e($trkNum) ?></td></tr>
      </table>
    </div>
  </header>

  <!-- 3-Column Parties Grid -->
  <section class="parties-grid">
    <div class="party-card">
      <div class="card-title">📌 BILL TO / SENDER (FROM)</div>
      <div class="party-name"><?= e($invoice['sender_name'] ?: ($invoice['company_name'] ?: $invoice['contact_name'])) ?></div>
      <div class="party-address">
        <?= e($invoice['sender_address'] ?: 'Logistics Central Station') ?><br>
        <?= e($invoice['sender_area'] ?: 'Al Quoz Industrial 3') ?>, <?= e($invoice['sender_emirate'] ?: 'Dubai') ?>, UAE
      </div>
      <div class="party-phone">📞 <?= e($invoice['phone']) ?></div>
    </div>

    <div class="party-card">
      <div class="card-title">📌 RECEIVER (TO)</div>
      <div class="party-name"><?= e($invoice['receiver_name'] ?: 'Valued Consignee') ?></div>
      <div class="party-address">
        <?= e($invoice['receiver_address'] ?: 'Delivery Building / Street Address') ?><br>
        <?= e($invoice['receiver_area'] ?: 'Central District') ?>, <?= e($invoice['receiver_emirate'] ?: 'Abu Dhabi') ?>, UAE
      </div>
      <div class="party-phone">📞 <?= e($invoice['phone']) ?></div>
    </div>

    <div class="party-card">
      <div class="card-title">PAYMENT INFORMATION</div>
      <?php if ($invoice['status'] === 'PAID'): ?>
        <div class="pay-status-title">Payment Received ✓</div>
        <div class="pay-method-badge">💳 Credit Card</div>
      <?php else: ?>
        <div class="pay-status-title" style="color: #b45309;">Payment Pending ⏱</div>
        <div class="pay-method-badge" style="background: #fffbeb; color: #b45309; border-color: #fde68a;">💵 Pending Settlement</div>
      <?php endif; ?>
      <?php 
        $pRef = !empty($payments) ? ($payments[0]['reference'] ?? '') : '';
        if (empty($pRef)) { $pRef = 'TXN-' . mt_rand(100000000000, 999999999999); }
      ?>
      <div class="pay-meta">
        Ref: <b><?= e($pRef) ?></b><br>
        Date: <b><?= e($issueDateFormatted) ?></b><br>
        Status: <b><?= $invoice['status'] === 'PAID' ? 'Paid in Full' : e($invoice['status']) ?></b>
      </div>
    </div>
  </section>

  <!-- Items Table -->
  <table class="items-table">
    <thead>
      <tr>
        <th style="width: 5%;">#</th>
        <th style="width: 45%;">SERVICE DESCRIPTION</th>
        <th class="center" style="width: 8%;">QTY</th>
        <th class="right" style="width: 14%;">UNIT PRICE</th>
        <th class="center" style="width: 8%;">DISCOUNT</th>
        <th class="right" style="width: 10%;">VAT (5%)</th>
        <th class="right" style="width: 10%;">TOTAL (AED)</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($items)): ?>
        <?php $idx = 1; foreach ($items as $item): ?>
          <tr>
            <td><?= $idx++ ?></td>
            <td>
              <div class="item-title"><?= e($item['description']) ?> — <?= e($invoice['sender_emirate'] ?: 'Dubai') ?> to <?= e($invoice['receiver_emirate'] ?: 'Abu Dhabi') ?></div>
              <div class="item-sub">Door-to-door delivery · Weight: <?= e(number_format($invoice['weight_kg'] ?: 1.0, 1)) ?>kg</div>
            </td>
            <td class="center"><?= e($item['quantity']) ?></td>
            <td class="right">AED <?= e(number_format($item['unit_price'], 2)) ?></td>
            <td class="center">—</td>
            <td class="right">AED <?= e(number_format($item['line_tax'], 2)) ?></td>
            <td class="right" style="font-weight: 800;">AED <?= e(number_format($item['line_total'], 2)) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td>1</td>
          <td>
            <div class="item-title"><?= e(!empty($invoice['service_name']) ? $invoice['service_name'] : 'Express Courier Service') ?> — <?= e($invoice['sender_emirate'] ?: 'Dubai') ?> to <?= e($invoice['receiver_emirate'] ?: 'Abu Dhabi') ?></div>
            <div class="item-sub">Door-to-door delivery · Weight: <?= e(number_format($invoice['weight_kg'] ?: 1.0, 1)) ?>kg</div>
          </td>
          <td class="center">1</td>
          <td class="right">AED <?= e(number_format($subtotalVal, 2)) ?></td>
          <td class="center">—</td>
          <td class="right">AED <?= e(number_format($taxVal, 2)) ?></td>
          <td class="right" style="font-weight: 800;">AED <?= e(number_format($totalVal, 2)) ?></td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Subtotals Block -->
  <div class="subtotals-wrapper">
    <div class="subtotals-box">
      <div class="subtotal-row">
        <span>Subtotal (excl. VAT)</span>
        <b>AED <?= number_format($subtotalVal, 2) ?></b>
      </div>
      <div class="subtotal-row tax">
        <span>VAT @ 5% (UAE FTA)</span>
        <b>+ AED <?= number_format($taxVal, 2) ?></b>
      </div>
      <div class="total-due-banner">
        <div class="banner-label">Total Amount Due</div>
        <div class="banner-amount">AED <?= number_format($totalVal, 2) ?></div>
      </div>
      <div class="amount-words">Amount in words: <b><?= e($amountWords) ?></b></div>
    </div>
  </div>

  <!-- UAE FTA VAT Breakdown Box -->
  <div class="vat-breakdown-card">
    <div class="vat-card-header">🔒 UAE FTA VAT BREAKDOWN — FEDERAL TAX AUTHORITY COMPLIANT</div>
    <div class="vat-grid">
      <div class="vat-col">
        <div class="v-label">TAXABLE AMOUNT</div>
        <div class="v-val">AED <?= number_format($subtotalVal, 2) ?></div>
      </div>
      <div class="vat-col">
        <div class="v-label">VAT RATE</div>
        <div class="v-val">5.00%</div>
      </div>
      <div class="vat-col">
        <div class="v-label">VAT AMOUNT</div>
        <div class="v-val">AED <?= number_format($taxVal, 2) ?></div>
      </div>
      <div class="vat-col">
        <div class="v-label">TOTAL INC. VAT</div>
        <div class="v-val">AED <?= number_format($totalVal, 2) ?></div>
      </div>
    </div>
  </div>

  <!-- UAE FTA Compliance Banner -->
  <div class="fta-notice-card">
    <div class="ae-badge">AE</div>
    <div class="fta-text">
      <strong>UAE Federal Tax Authority (FTA) Compliance:</strong> This is a valid Tax Invoice issued under UAE Federal Decree-Law No. 8 of 2017 on Value Added Tax. Tax charged at 5% standard rate. RC Courier LLC is registered with UAE FTA under TRN <strong>100987654321003</strong>. This document must be retained for 5 years as required by UAE tax law. VAT queries: <strong>billing@rapid-courier.com</strong>
    </div>
  </div>

  <!-- Terms & Conditions Grid -->
  <div class="terms-card">
    <div class="terms-header">📋 TERMS & CONDITIONS — UAE REGULATORY COMPLIANCE</div>
    <ul class="terms-grid">
      <li>Liability limited to AED 500 unless additional insurance purchased.</li>
      <li>Not liable for delays due to customs, force majeure, or incorrect address.</li>
      <li>Prohibited items: cash, firearms, narcotics, perishables, hazardous materials.</li>
      <li>Claims for loss/damage must be filed within 7 days of expected delivery.</li>
      <li>All disputes subject to Dubai Courts under UAE Federal Law.</li>
      <li>Refunds processed within 5–7 business days to original payment method.</li>
      <li>By using RC Courier you agree to full Terms at rapid-courier.com/terms.</li>
      <li>Computer-generated invoice valid without signature per UAE e-commerce law.</li>
    </ul>
  </div>

  <!-- Footer Bar -->
  <footer class="footer-bar">
    <div class="footer-contact">
      <strong>RC Courier LLC</strong> · Office 412, Business Bay Tower, Business Bay Dubai, UAE — PO Box 90878<br>
      📞 +971 4 800 2684 · ✉ billing@rapid-courier.com · 🌐 www.rapid-courier.com<br>
      Track: rapid-courier.com/track · Receipt: <strong><?= e($invNum) ?></strong>
    </div>

    <div class="verify-qr-block">
      <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 6px;">
        <div style="text-align: right;" class="qr-meta">
          <strong>Generated: RC Courier System (system)</strong><br>
          <strong><?= e($invNum) ?></strong>
        </div>
        <div style="background: #ffffff; padding: 4px 8px; border: 1px solid #cbd5e1; border-radius: 6px; display: inline-block;">
          <svg id="invoiceBarcodeSvg"></svg>
        </div>
      </div>
      <div class="qr-container">
        <div id="qrcode"></div>
      </div>
    </div>
  </footer>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
<script>
(function(){
  const invoiceNum = <?= json_encode($invNum) ?>;
  const trkNum = <?= json_encode($trkNum) ?>;
  const verificationUrl = <?= json_encode(\App\Core\View::qrUrl('/v/' . $invoice['invoice_number'])) ?>;

  if(window.JsBarcode){
    JsBarcode("#invoiceBarcodeSvg", trkNum || invoiceNum, {
      format: "CODE128",
      lineColor: "#0b1830",
      width: 1.2,
      height: 35,
      displayValue: true,
      fontSize: 10,
      font: "Inter",
      margin: 2
    });
  }

  const qr = document.getElementById('qrcode');
  if(window.QRCode){
    new QRCode(qr, {
      text: verificationUrl,
      width: 114,
      height: 114,
      colorDark: '#0b1830',
      colorLight: '#ffffff',
      correctLevel: QRCode.CorrectLevel.H
    });
  }
})();
</script>
</body>
</html>
