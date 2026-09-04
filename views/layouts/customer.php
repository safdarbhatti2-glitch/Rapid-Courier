<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Customer Portal — RC Courier UAE') ?></title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
    <link rel="shortcut icon" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= \App\Core\View::asset('assets/css/main.css') ?>">
    <style>
        .cust-layout { display: flex; min-height: 100vh; }
        .cust-sidebar { width: 250px; background: #0f172a; color: #94a3b8; padding: 1.5rem 1rem; flex-shrink: 0; }
        .cust-sidebar a { color: #94a3b8; padding: 0.6rem 0.8rem; display: block; border-radius: 0.375rem; font-weight: 500; }
        .cust-sidebar a:hover, .cust-sidebar a.active { background: #1e293b; color: #fff; }
        .cust-main { flex: 1; background: #f8fafc; padding: 2rem; }
    </style>
</head>
<body>
    <div class="cust-layout">
        <aside class="cust-sidebar">
            <a href="<?= \App\Core\View::url('/customer') ?>" style="font-size:1.1rem; font-weight:700; color:#fff; margin-bottom:2rem; display:flex; align-items:center; gap:8px;">
                <img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:34px; width:34px; border-radius:6px; object-fit:cover;">
                <span>RC</span> COURIER PORTAL
            </a>
            <ul style="list-style:none;">
                <li style="margin-bottom:0.5rem;"><a href="<?= \App\Core\View::url('/customer') ?>">Dashboard Overview</a></li>
                <li style="margin-bottom:0.5rem;"><a href="<?= \App\Core\View::url('/customer/shipments') ?>">My Shipments</a></li>
                <li style="margin-bottom:0.5rem;"><a href="<?= \App\Core\View::url('/book') ?>">Book New Shipment</a></li>
                <li style="margin-bottom:0.5rem;"><a href="<?= \App\Core\View::url('/customer/invoices') ?>">Invoices & Receipts</a></li>
                <li style="margin-bottom:0.5rem;"><a href="<?= \App\Core\View::url('/customer/quotes') ?>">Quotation History</a></li>
                <li style="margin-bottom:0.5rem;"><a href="<?= \App\Core\View::url('/customer/profile') ?>">My Profile</a></li>
                <li style="margin-top: 2rem; border-top: 1px solid #334155; padding-top: 1rem;">
                    <form action="<?= \App\Core\View::url('/logout') ?>" method="POST">
                        <?= \App\Core\View::csrfField() ?>
                        <button type="submit" class="btn btn-outline" style="width:100%; color:#f87171; border-color:#475569;">Sign Out</button>
                    </form>
                </li>
            </ul>
        </aside>
        <main class="cust-main">
            <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
                <div class="alert alert-success"><?= e($msg) ?></div>
            <?php endif; ?>
            <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
                <div class="alert alert-error"><?= e($msg) ?></div>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>
</body>
</html>
