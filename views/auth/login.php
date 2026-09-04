<h2 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; text-align: center;">Welcome Back</h2>
<p style="color: #64748b; font-size: 0.9rem; text-align: center; margin-bottom: 2rem;">Sign in to your RC Courier account</p>

<form action="<?= \App\Core\View::url('/login') ?>" method="POST">
    <?= \App\Core\View::csrfField() ?>
    
    <div style="margin-bottom: 1.2rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem; color: #334155;">Email Address</label>
        <input type="email" name="email" required placeholder="name@company.ae" style="width: 100%; padding: 0.7rem; border: 1px solid #cbd5e1; border-radius: 0.375rem;">
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem; color: #334155;">Password</label>
        <input type="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 0.7rem; border: 1px solid #cbd5e1; border-radius: 0.375rem;">
    </div>

    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Sign In</button>
</form>

<div style="margin-top: 1.5rem; text-align: center; font-size: 0.85rem; color: #64748b;">
    Don't have an account? <a href="<?= \App\Core\View::url('/register') ?>" style="font-weight: 600;">Register Here</a>
</div>

<div style="margin-top: 2rem; background: #f8fafc; padding: 0.75rem; border-radius: 0.375rem; font-size: 0.8rem; color: #475569;">
    <strong>Demo Credentials:</strong><br>
    • Admin: <code>admin@antigravityexpress.ae</code> / <code>Admin@123456</code><br>
    • Customer: <code>demo.customer@example.ae</code> / <code>Customer@123456</code>
</div>
