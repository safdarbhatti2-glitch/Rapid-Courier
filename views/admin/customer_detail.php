<div style="margin-bottom: 2rem;">
    <h2 style="font-weight: 700; color: #0f172a;">Customer: <?= e($customer['contact_name']) ?></h2>
    <p style="color: #64748b;"><?= e($customer['company_name'] ?: 'Individual Customer') ?> | Email: <?= e($customer['email']) ?> | Phone: <?= e($customer['phone']) ?></p>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: var(--primary);">Customer History</h3>
    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div>
            <strong>TRN Number:</strong> <?= e($customer['trn'] ?: 'Unspecified') ?>
        </div>
        <div>
            <strong>Status:</strong> <span class="badge badge-success"><?= e($customer['status']) ?></span>
        </div>
    </div>
</div>

<h3 style="font-weight: 700; color: #0f172a; margin-bottom: 1rem;">Customer Shipments</h3>
<div class="table-responsive" style="margin-bottom: 2rem;">
    <table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Tracking #</th>
                <th>Status</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shipments as $s): ?>
                <tr>
                    <td><strong><?= e($s['reference_number']) ?></strong></td>
                    <td><code><?= e($s['tracking_number']) ?></code></td>
                    <td><span class="badge badge-info"><?= e($s['status']) ?></span></td>
                    <td><?= e(number_format($s['total'], 2)) ?> AED</td>
                    <td><?= e(date('M d, Y', strtotime($s['created_at']))) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
