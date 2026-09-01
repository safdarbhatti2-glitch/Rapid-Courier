<div style="margin-bottom: 2rem;">
    <h2 style="font-weight: 700; color: #0f172a;">Quotation Management</h2>
    <p style="color: #64748b; font-size: 0.9rem;">Review incoming quotes and convert accepted proposals into active shipments.</p>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Quote #</th>
                <th>Contact Name</th>
                <th>Email / Phone</th>
                <th>Status</th>
                <th>Valid Until</th>
                <th>Total (AED)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quotes as $q): ?>
                <tr>
                    <td><strong><?= e($q['quote_number']) ?></strong></td>
                    <td><?= e($q['contact_name']) ?></td>
                    <td><?= e($q['contact_email']) ?><br><small><?= e($q['contact_phone']) ?></small></td>
                    <td><span class="badge badge-info"><?= e($q['status']) ?></span></td>
                    <td><?= e(date('M d, Y', strtotime($q['valid_until']))) ?></td>
                    <td><?= e(number_format($q['total'], 2)) ?> AED</td>
                    <td>
                        <a href="<?= \App\Core\View::url('/quotes/' . $q['id'] . '/pdf') ?>" target="_blank" class="btn btn-secondary" style="padding:0.25rem 0.5rem; font-size:0.8rem;">PDF</a>
                        <?php if ($q['status'] !== 'CONVERTED'): ?>
                            <form action="<?= \App\Core\View::url('/admin/quotes/' . $q['id'] . '/convert') ?>" method="POST" style="display:inline;">
                                <?= \App\Core\View::csrfField() ?>
                                <button type="submit" class="btn btn-primary" style="padding:0.25rem 0.5rem; font-size:0.8rem;">Convert to Shipment</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
