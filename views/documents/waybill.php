<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= e($title) ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; color: #000; margin: 0; padding: 1rem; background: #fff; }
        .label-box { width: 400px; margin: auto; padding: 1.5rem; border: 3px solid #000; border-radius: 0.25rem; background: #fff; }
        .barcode { text-align: center; margin: 1rem 0; font-size: 1.8rem; font-weight: 900; letter-spacing: 4px; border: 2px dashed #000; padding: 0.75rem; background: #f8fafc; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; border-bottom: 2px solid #000; padding-bottom: 0.5rem; margin-bottom: 0.5rem; }
        @media print { .no-print { display: none; } .label-box { border: 3px solid #000; } }
    </style>
</head>
<body>
    <div class="no-print" style="width:400px; margin:0 auto 1rem; text-align:right;">
        <button onclick="window.print()" style="padding:0.5rem 1rem; background:#0f172a; color:#fff; border:none; border-radius:0.25rem; font-weight:bold; cursor:pointer;">🖨️ Print Thermal Label</button>
    </div>

    <div class="label-box">
            <div style="display:flex; align-items:center; gap:8px;">
                <img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:32px; width:32px; border-radius:6px; object-fit:cover;">
                <div style="font-size:1.1rem; font-weight:900;">RC COURIER UAE</div>
            </div>
            <div style="font-size:0.9rem; font-weight:bold; text-transform:uppercase;"><?= e($shipment['service_name']) ?></div>
        </div>

        <div class="barcode">
            |||||||||||||||||||||||||||||<br>
            <?= e($shipment['tracking_number']) ?>
        </div>

        <div class="grid-2">
            <div>
                <strong>REF:</strong> <?= e($shipment['reference_number']) ?>
            </div>
            <div style="text-align:right;">
                <strong>WEIGHT:</strong> <?= e($shipment['weight_kg']) ?> KG
            </div>
        </div>

        <div style="border-bottom:2px solid #000; padding-bottom:0.5rem; margin-bottom:0.5rem;">
            <span style="font-size:0.75rem; display:block;">FROM (ORIGIN):</span>
            <strong><?= e($shipment['contact_name']) ?></strong> (<?= e($shipment['sender_phone']) ?>)<br>
            <?= e($shipment['origin_line1']) ?>, <?= e($shipment['origin_area']) ?><br>
            <strong><?= e(strtoupper($shipment['origin_emirate'])) ?>, UAE</strong>
        </div>

        <div style="border-bottom:2px solid #000; padding-bottom:0.5rem; margin-bottom:0.5rem; background:#f1f5f9; padding:0.5rem;">
            <span style="font-size:0.75rem; display:block; font-weight:bold;">TO (DELIVERY DESTINATION):</span>
            <strong style="font-size:1.1rem;"><?= e($shipment['contact_name']) ?></strong><br>
            <?= e($shipment['dest_line1']) ?>, <?= e($shipment['dest_area']) ?><br>
            <strong style="font-size:1.2rem; text-decoration:underline;"><?= e(strtoupper($shipment['dest_emirate'])) ?>, UAE</strong>
        </div>

        <div style="display:flex; justify-content:space-between; font-size:0.8rem; margin-top:0.5rem;">
            <div>DATE: <?= date('Y-m-d') ?></div>
            <div>HUB: DXB-DLC-01</div>
        </div>
    </div>
</body>
</html>
