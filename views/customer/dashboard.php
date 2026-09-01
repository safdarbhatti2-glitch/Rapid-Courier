<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">Welcome, <?= e($user['name']) ?></h1>
    <p style="color: #64748b;">Manage your UAE shipments, track live deliveries, and download invoices.</p>
</div>

<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 2rem;">
    <div class="card" style="border-left: 4px solid var(--accent);">
        <span style="font-size:0.8rem; color:var(--text-muted); display:block;">ACTIVE DELIVERIES</span>
        <h2 style="font-size: 2rem; font-weight: 800; color: var(--primary);"><?= $activeCount ?></h2>
    </div>
    <div class="card" style="border-left: 4px solid var(--secondary);">
        <span style="font-size:0.8rem; color:var(--text-muted); display:block;">TOTAL SHIPMENTS</span>
        <h2 style="font-size: 2rem; font-weight: 800; color: var(--primary);"><?= $shipmentsCount ?></h2>
    </div>
    <div class="card" style="border-left: 4px solid var(--gold);">
        <span style="font-size:0.8rem; color:var(--text-muted); display:block;">MY INVOICES</span>
        <h2 style="font-size: 2rem; font-weight: 800; color: var(--primary);"><?= $invoicesCount ?></h2>
    </div>
    <div class="card" style="border-left: 4px solid var(--success);">
        <span style="font-size:0.8rem; color:var(--text-muted); display:block;">QUOTATIONS</span>
        <h2 style="font-size: 2rem; font-weight: 800; color: var(--primary);"><?= $quotesCount ?></h2>
    </div>
</div>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <h3 style="font-size: 1.2rem; font-weight: 700; color: #0f172a;">Recent Shipments</h3>
    <a href="<?= \App\Core\View::url('/book') ?>" class="btn btn-primary" style="padding:0.4rem 0.8rem; font-size:0.85rem;">+ Book New Shipment</a>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Tracking #</th>
                <th>Service</th>
                <th>Status</th>
                <th>Total (AED)</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentShipments as $s): ?>
                <tr>
                    <td><strong><?= e($s['reference_number']) ?></strong></td>
                    <td><code><?= e($s['tracking_number']) ?></code></td>
                    <td><?= e($s['service_name']) ?></td>
                    <td><span class="badge badge-info"><?= e($s['status']) ?></span></td>
                    <td><?= e(number_format($s['total'], 2)) ?> AED</td>
                    <td><?= e(date('M d, Y', strtotime($s['created_at']))) ?></td>
                    <td><a href="<?= \App\Core\View::url('/customer/shipments/' . $s['id']) ?>" class="btn btn-outline" style="padding:0.2rem 0.5rem; font-size:0.8rem;">View</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($recentShipments)): ?>
                <tr><td colspan="7" style="text-align:center; color:#94a3b8; padding:2rem;">No shipments recorded yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
