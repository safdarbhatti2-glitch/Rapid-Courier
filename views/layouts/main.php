<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'RC Courier | UAE & GCC Logistics') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= \App\Core\View::asset('assets/css/main.css') ?>?v=<?= time() ?>">
</head>
<body>
    <!-- Topbar Navigation -->
    <header class="topbar">
        <div class="container nav">
            <a class="brand" href="<?= \App\Core\View::url('/') ?>" aria-label="RC Courier home" style="display:flex; align-items:center; gap:12px; text-decoration:none;">
                <img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" srcset="<?= \App\Core\View::url('/assets/images/rc_logo_256.png') ?> 2x, <?= \App\Core\View::url('/assets/images/rc_logo_hd.png') ?> 3x" alt="RC Courier Logo" style="height: 48px; width: 48px; border-radius: 10px; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.3); border: 1.5px solid rgba(241,196,94,0.45); image-rendering: -webkit-optimize-contrast;">
                <div class="brand-text" style="display:flex; flex-direction:column; justify-content:center;">
                    <span style="font-family:'Manrope','Inter',sans-serif; font-size: 20px; font-weight: 900; color: #ffffff; letter-spacing: -0.4px; line-height: 1.1;">RC COURIER</span>
                    <small style="font-family:'Inter',sans-serif; font-size: 9px; font-weight: 700; color: #f1c45e; letter-spacing: 1px; text-transform: uppercase; margin-top: 2px;">UAE • GCC LOGISTICS</small>
                </div>
            </a>

            <nav class="nav-links" aria-label="Main navigation">
                <a href="<?= \App\Core\View::url('/services') ?>">Services</a>
                <a href="<?= \App\Core\View::url('/track') ?>">Track Shipment</a>
                <a href="<?= \App\Core\View::url('/quote') ?>">Get Quote</a>
                <a href="<?= \App\Core\View::url('/book') ?>">Book Shipment</a>
                <a href="<?= \App\Core\View::url('/locations') ?>">Locations</a>
                <a href="<?= \App\Core\View::url('/about') ?>">About Us</a>
                <a href="<?= \App\Core\View::url('/contact') ?>">Contact</a>
            </nav>

            <div class="nav-actions">
                <?php $user = \App\Core\Session::get('user'); ?>
                <?php if ($user): ?>
                    <?php $dashUrl = ($user['role_name'] === 'customer') ? '/customer' : '/admin'; ?>
                    <a href="<?= \App\Core\View::url($dashUrl) ?>" class="btn btn-gold">Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Flash Messages Container -->
    <div style="padding-top: 90px; margin-bottom: -70px; z-index: 999; position: relative;">
        <div class="container">
            <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
                <div class="alert alert-success"><?= e($msg) ?></div>
            <?php endif; ?>
            <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
                <div class="alert alert-error"><?= e($msg) ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main View Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a class="brand" href="<?= \App\Core\View::url('/') ?>" style="display:flex; align-items:center; gap:10px; text-decoration:none;">
                        <img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" srcset="<?= \App\Core\View::url('/assets/images/rc_logo_256.png') ?> 2x, <?= \App\Core\View::url('/assets/images/rc_logo_hd.png') ?> 3x" alt="RC Courier Logo" style="height: 40px; width: 40px; border-radius: 8px; object-fit: cover; border: 1px solid rgba(241,196,94,0.3); image-rendering: -webkit-optimize-contrast;">
                        <div class="brand-text" style="display:flex; flex-direction:column; justify-content:center;">
                            <span style="font-family:'Manrope','Inter',sans-serif; font-size: 18px; font-weight: 900; color: #ffffff; letter-spacing: -0.4px; line-height: 1.1;">RC COURIER</span>
                            <small style="font-family:'Inter',sans-serif; font-size: 8.5px; font-weight: 700; color: #f1c45e; letter-spacing: 0.8px; text-transform: uppercase; margin-top: 2px;">UAE • GCC LOGISTICS</small>
                        </div>
                    </a>
                    <p>Premium courier, express delivery and logistics solutions across all 7 UAE Emirates, GCC and international destinations.</p>
                </div>
                <div class="footer-col">
                    <h4>Services</h4>
                    <a href="<?= \App\Core\View::url('/services') ?>">Same-Day Delivery</a>
                    <a href="<?= \App\Core\View::url('/services') ?>">Next-Day Delivery</a>
                    <a href="<?= \App\Core\View::url('/services') ?>">GCC Road Freight</a>
                    <a href="<?= \App\Core\View::url('/services') ?>">International Air</a>
                    <a href="<?= \App\Core\View::url('/services') ?>">E-Commerce Logistics</a>
                </div>
                <div class="footer-col">
                    <h4>Company</h4>
                    <a href="<?= \App\Core\View::url('/about') ?>">About RC Courier</a>
                    <a href="<?= \App\Core\View::url('/locations') ?>">Our Hubs & Coverage</a>
                    <a href="<?= \App\Core\View::url('/contact') ?>">Contact Us</a>
                    <a href="<?= \App\Core\View::url('/contact') ?>">Corporate Solutions</a>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <a href="<?= \App\Core\View::url('/track') ?>">Track Shipment</a>
                    <a href="<?= \App\Core\View::url('/quote') ?>">Get a Quote</a>
                    <a href="<?= \App\Core\View::url('/book') ?>">Book Shipment</a>
                    <p style="margin-top:10px;">Dubai Logistics City, UAE</p>
                    <p>Phone: +971 4 800 2684</p>
                    <p>support@rccourier.ae</p>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© <?= date('Y') ?> RC Courier UAE LLC. All rights reserved. TRN: 100987654321003.</span>
                <span>UAE • GCC • Worldwide</span>
            </div>
        </div>
    </footer>
</body>
</html>
