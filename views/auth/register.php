<h2 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; text-align: center;">Create Account</h2>
<p style="color: #64748b; font-size: 0.9rem; text-align: center; margin-bottom: 2rem;">Register for Antigravity Express UAE</p>

<form action="<?= \App\Core\View::url('/register') ?>" method="POST">
    <?= \App\Core\View::csrfField() ?>

    <div style="margin-bottom: 1rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.3rem; color: #334155;">Full Name</label>
        <input type="text" name="name" required placeholder="e.g. Tariq Mansoor" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 0.375rem;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.3rem; color: #334155;">Email Address</label>
        <input type="email" name="email" required placeholder="name@domain.ae" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 0.375rem;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.3rem; color: #334155;">UAE Mobile Phone (+971)</label>
        <input type="text" name="phone" required placeholder="+971 50 123 4567" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 0.375rem;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.3rem; color: #334155;">Company Name (Optional)</label>
        <input type="text" name="company_name" placeholder="e.g. Al Habtoor FZE" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 0.375rem;">
    </div>

    <div style="margin-bottom: 1rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.3rem; color: #334155;">Password</label>
        <input type="password" name="password" required placeholder="At least 8 characters" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 0.375rem;">
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.3rem; color: #334155;">Confirm Password</label>
        <input type="password" name="password_confirmation" required placeholder="Repeat password" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 0.375rem;">
    </div>

    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Create Account</button>
</form>

<div style="margin-top: 1.5rem; text-align: center; font-size: 0.85rem; color: #64748b;">
    Already have an account? <a href="<?= \App\Core\View::url('/login') ?>" style="font-weight: 600;">Sign In</a>
</div>
