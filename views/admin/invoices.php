<div style="margin-bottom: 1.5rem;">
    <h2 style="font-weight: 700; color: #0f172a;">Financial Invoices & Receipts</h2>
    <p style="color: #64748b; font-size: 0.9rem;">Audit immutable tax invoices, track aging balances, and record payments.</p>
</div>

<!-- Search Filter Toolbar -->
<div class="card" style="margin-bottom: 1.5rem; background: #0d172b; border-radius: 12px; padding: 1.2rem;">
    <form action="<?= \App\Core\View::url('/admin/invoices') ?>" method="GET" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:center;">
        <div style="flex:3; min-width:260px; position:relative;">
            <input type="text" name="q" value="<?= e($search ?? '') ?>" placeholder="🔍 Search by Tracking ID (e.g. RC84920412), Invoice #, or Customer..." style="width:100%; padding:0.7rem 1rem; border:1px solid #233047; border-radius:8px; background:#18263d; color:#fff; font-size:0.9rem; outline:none;">
        </div>
        <div style="flex:1; min-width:160px;">
            <select name="status" style="width:100%; padding:0.7rem 1rem; border:1px solid #233047; border-radius:8px; background:#18263d; color:#fff; font-size:0.9rem; outline:none;">
                <option value="">All Statuses</option>
                <option value="PAID" <?= ($status ?? '') === 'PAID' ? 'selected' : '' ?>>PAID</option>
                <option value="ISSUED" <?= ($status ?? '') === 'ISSUED' ? 'selected' : '' ?>>ISSUED</option>
                <option value="OVERDUE" <?= ($status ?? '') === 'OVERDUE' ? 'selected' : '' ?>>OVERDUE</option>
                <option value="DRAFT" <?= ($status ?? '') === 'DRAFT' ? 'selected' : '' ?>>DRAFT</option>
                <option value="VOID" <?= ($status ?? '') === 'VOID' ? 'selected' : '' ?>>VOID</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="padding:0.7rem 1.5rem; font-weight:700;">Filter Invoices</button>
        <?php if (!empty($search) || !empty($status)): ?>
            <a href="<?= \App\Core\View::url('/admin/invoices') ?>" class="btn btn-outline" style="padding:0.7rem 1rem; color:#94a3b8; border-color:#334155;">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Issue Date</th>
                <th>Total (AED)</th>
                <th>Paid</th>
                <th>Balance Due</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $inv): ?>
                <tr>
                    <td><strong><?= e($inv['invoice_number']) ?></strong></td>
                    <td><?= e($inv['company_name'] ?: $inv['contact_name']) ?></td>
                    <td><span class="badge badge-<?= $inv['status'] === 'PAID' ? 'success' : 'warning' ?>"><?= e($inv['status']) ?></span></td>
                    <td><?= e(date('M d, Y', strtotime($inv['issue_date']))) ?></td>
                    <td><?= e(number_format($inv['total'], 2)) ?> AED</td>
                    <td><?= e(number_format($inv['amount_paid'], 2)) ?> AED</td>
                    <td><strong style="color:var(--accent);"><?= e(number_format($inv['balance_due'], 2)) ?> AED</strong></td>
                    <td>
                        <a href="<?= \App\Core\View::url('/admin/invoices/' . $inv['id']) ?>" class="btn btn-outline" style="padding:0.25rem 0.5rem; font-size:0.8rem;">Manage</a>
                        <a href="<?= \App\Core\View::url('/invoices/' . $inv['id'] . '/pdf') ?>" target="_blank" class="btn btn-secondary" style="padding:0.25rem 0.5rem; font-size:0.8rem;">📄 PDF</a>
                        <a href="<?= \App\Core\View::url('/invoices/' . $inv['id'] . '/pdf?download=image') ?>" target="_blank" class="btn btn-primary" style="padding:0.25rem 0.5rem; font-size:0.8rem; background:#168fd2; border:none;">🖼️ Image</a>
                        <a href="<?= \App\Core\View::url('/invoices/' . $inv['id'] . '/thermal') ?>" target="_blank" class="btn btn-primary" style="padding:0.25rem 0.5rem; font-size:0.8rem; background:#dcae3f; color:#0f172a; border:none; font-weight:700;">🖨️ Thermal</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($invoices)): ?>
                <tr>
                    <td colspan="8" style="text-align:center; padding: 2.5rem; color:#94a3b8;">
                        No invoices found matching your tracking ID or search criteria.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
