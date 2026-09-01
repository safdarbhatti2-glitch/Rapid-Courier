<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Authentication — Antigravity Express UAE') ?></title>
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
            <a href="<?= \App\Core\View::url('/') ?>" style="font-size: 1.3rem; font-weight: 800; color: #0f172a; text-decoration: none;">
                <span style="color: #dc2626;">ANTIGRAVITY</span> EXPRESS
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
