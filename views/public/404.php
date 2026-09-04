<div class="container" style="text-align: center; padding: 5rem 1rem;">
    <h1 style="font-size: 4rem; font-weight: 800; color: var(--accent);">404</h1>
    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--primary); margin-bottom: 1rem;">Page Not Found</h2>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">The requested URI <code><?= e($uri) ?></code> does not exist on RC Courier UAE.</p>
    <a href="<?= \App\Core\View::url('/') ?>" class="btn btn-primary">Return to Homepage</a>
</div>
