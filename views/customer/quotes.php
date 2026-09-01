<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">My Quotations</h1>
    <p style="color: #64748b;">Review price estimates and converted freight quotes.</p>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Quote #</th>
                <th>Status</th>
                <th>Valid Until</th>
                <th>Total (AED)</th>
                <th>Notes</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quotes as $q): ?>
                <tr>
                    <td><strong><?= e($q['quote_number']) ?></strong></td>
                    <td><span class="badge badge-info"><?= e($q['status']) ?></span></td>
                    <td><?= e(date('M d, Y', strtotime($q['valid_until']))) ?></td>
                    <td><?= e(number_format($q['total'], 2)) ?> AED</td>
                    <td><?= e($q['notes']) ?></td>
                    <td>
                        <a href="<?= \App\Core\View::url('/quotes/' . $q['id'] . '/pdf') ?>" target="_blank" class="btn btn-secondary" style="padding:0.25rem 0.5rem; font-size:0.8rem;">PDF</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($quotes)): ?>
                <tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:2rem;">No quotations generated yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
