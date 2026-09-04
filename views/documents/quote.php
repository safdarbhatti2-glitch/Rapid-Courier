<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= e($title) ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; margin: 0; padding: 2rem; background: #fff; }
        .quote-box { max-width: 800px; margin: auto; padding: 2rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0f172a; padding-bottom: 1.5rem; margin-bottom: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; font-size: 0.9rem; }
        th { background: #f8fafc; text-align: left; padding: 0.75rem; border-bottom: 2px solid #cbd5e1; }
        td { padding: 0.75rem; border-bottom: 1px solid #e2e8f0; }
        @media print { .no-print { display: none; } .quote-box { border: none; padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print" style="max-width:800px; margin:0 auto 1rem; text-align:right;">
        <button onclick="window.print()" style="padding:0.6rem 1.2rem; background:#0f172a; color:#fff; border:none; border-radius:0.375rem; font-weight:600; cursor:pointer;">🖨️ Print Quote</button>
    </div>

    <div class="quote-box">
        <div class="header">
            <div>
                <h2 style="margin:0; font-size:1.5rem; color:#0f172a;"><span style="color:#f1c45e;">RC</span> COURIER UAE</h2>
                <p style="font-size:0.85rem; color:#64748b;">Official Logistics Quotation</p>
            </div>
            <div style="text-align:right;">
                <h3 style="margin:0; color:#0f172a;"><?= e($quote['quote_number']) ?></h3>
                <p style="margin:0.2rem 0; font-size:0.85rem; color:#64748b;">Valid Until: <?= e(date('d M Y', strtotime($quote['valid_until']))) ?></p>
            </div>
        </div>

        <div style="margin-bottom:2rem; font-size:0.9rem;">
            <strong>Prepared For:</strong> <?= e($quote['contact_name']) ?><br>
            Email: <?= e($quote['contact_email']) ?> | Phone: <?= e($quote['contact_phone']) ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align:center;">Qty</th>
                    <th style="text-align:right;">Unit Price</th>
                    <th style="text-align:right;">Total (AED)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['description']) ?></td>
                        <td style="text-align:center;"><?= e($item['quantity']) ?></td>
                        <td style="text-align:right;"><?= e(number_format($item['unit_price'], 2)) ?></td>
                        <td style="text-align:right;"><strong><?= e(number_format($item['line_total'], 2)) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="text-align:right; font-size:1.1rem; font-weight:800; color:#0f172a;">
            Estimated Total: <?= e(number_format($quote['total'], 2)) ?> AED (Incl. 5% VAT)
        </div>
    </div>
</body>
</html>
