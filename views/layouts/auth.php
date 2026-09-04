<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Authentication — RC Courier UAE') ?></title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= \App\Core\View::asset('assets/images/rc_logo_256.png') ?>">
    <link rel="shortcut icon" href="<?= \App\Core\View::asset('assets/images/rc_logo.png') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= \App\Core\View::asset('assets/css/main.css') ?>">
    <style>
        body { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem; }
        .auth-card { background: #fff; width: 100%; max-width: 440px; padding: 2.5rem; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1); }
    </style>
</head>
<body>
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="<?= \App\Core\View::url('/') ?>" style="display: inline-flex; align-items: center; gap: 10px; text-decoration: none;">
                <img src="<?= \App\Core\View::url('/assets/images/rc_logo.png') ?>" alt="RC Courier Logo" style="height: 44px; width: 44px; border-radius: 10px; border: 1.5px solid rgba(220,168,63,0.5);">
                <div style="text-align: left;">
                    <span style="font-size: 1.3rem; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;">RC COURIER</span>
                    <small style="font-size: 0.65rem; font-weight: 700; color: #dca83f; letter-spacing: 1px; text-transform: uppercase;">UAE • GCC LOGISTICS</small>
                </div>
            </a>
        </div>

        <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
            <div class="alert alert-success"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
            <div class="alert alert-error"><?= e($msg) ?></div>
        <?php endif; ?>

        <?= $content ?>
    </div>
</body>
</html>
