<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin — RC Courier UAE') ?></title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
    <link rel="shortcut icon" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= \App\Core\View::asset('assets/css/main.css') ?>">
    <style>
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: #0f172a; color: #94a3b8; padding: 1.5rem 1rem; flex-shrink: 0; }
        .sidebar-brand { font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 2rem; display: block; }
        .sidebar-brand span { color: #dc2626; }
        .sidebar-menu { list-style: none; }
        .sidebar-menu li { margin-bottom: 0.5rem; }
        .sidebar-menu a { color: #94a3b8; padding: 0.6rem 0.8rem; display: block; border-radius: 0.375rem; font-weight: 500; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #1e293b; color: #fff; }
        .admin-content { flex: 1; background: #f8fafc; padding: 2rem; }
        .admin-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: #fff; padding: 1rem 1.5rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <a href="<?= \App\Core\View::url('/admin') ?>" class="sidebar-brand" style="display:flex; align-items:center; gap:8px;">
                <img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height:34px; width:34px; border-radius:6px; object-fit:cover;">
                <span>RC</span> COURIER ADMIN
            </a>
            <ul class="sidebar-menu">
                <li><a href="<?= \App\Core\View::url('/admin') ?>">Dashboard</a></li>
                <li><a href="<?= \App\Core\View::url('/admin/shipments') ?>">Shipments</a></li>
                <li><a href="<?= \App\Core\View::url('/admin/tracking') ?>">Tracking Dispatcher</a></li>
                <li><a href="<?= \App\Core\View::url('/admin/quotes') ?>">Quotations</a></li>
                <li><a href="<?= \App\Core\View::url('/admin/invoices') ?>">Invoices & Accounting</a></li>
                <li><a href="<?= \App\Core\View::url('/admin/settings') ?>">System Settings</a></li>
                <li style="margin-top: 2rem; border-top: 1px solid #334155; padding-top: 1rem;">
                    <form action="<?= \App\Core\View::url('/logout') ?>" method="POST">
                        <?= \App\Core\View::csrfField() ?>
                        <button type="submit" class="btn btn-outline" style="width:100%; color:#f87171; border-color:#475569;">Sign Out</button>
                    </form>
                </li>
            </ul>
        </aside>
        <main class="admin-content">
            <div class="admin-topbar">
                <h3 style="font-weight: 700; color: #0f172a;"><?= e($title ?? 'Admin Portal') ?></h3>
                <?php $user = \App\Core\Session::get('user'); ?>
                <div style="font-size:0.9rem; color: #64748b;">
                    Signed in as <strong style="color:#0f172a;"><?= e($user['name'] ?? 'Admin User') ?></strong> (<?= e(strtoupper($user['role_name'] ?? 'ADMIN')) ?>)
                </div>
            </div>

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
