<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">Account Settings</h1>
    <p style="color: #64748b;">Manage your user profile and contact details.</p>
</div>

<div class="card" style="max-width: 600px;">
    <form action="<?= \App\Core\View::url('/customer/profile') ?>" method="POST">
        <?= \App\Core\View::csrfField() ?>

        <div style="margin-bottom: 1.2rem;">
            <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.3rem;">Full Name</label>
            <input type="text" name="name" value="<?= e($user['name']) ?>" required style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:var(--radius);">
        </div>

        <div style="margin-bottom: 1.2rem;">
            <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.3rem;">Email Address</label>
            <input type="email" value="<?= e($user['email']) ?>" disabled style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:var(--radius); background:#f1f5f9;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.3rem;">Phone Number (+971)</label>
            <input type="text" name="phone" value="<?= e($user['phone']) ?>" required style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:var(--radius);">
        </div>

        <button type="submit" class="btn btn-primary">Save Profile Changes</button>
    </form>
</div>
