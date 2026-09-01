<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">My Shipments</h1>
        <p style="color: #64748b;">Complete record of all booked express courier shipments.</p>
    </div>
    <a href="<?= \App\Core\View::url('/book') ?>" class="btn btn-primary">+ Book Shipment</a>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Tracking #</th>
                <th>Service</th>
                <th>Route</th>
                <th>Status</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shipments as $s): ?>
                <tr>
                    <td><strong><?= e($s['reference_number']) ?></strong></td>
                    <td><code><?= e($s['tracking_number']) ?></code></td>
                    <td><?= e($s['service_name']) ?></td>
                    <td><?= e($s['origin_emirate']) ?> &rarr; <?= e($s['destination_emirate']) ?></td>
                    <td><span class="badge badge-info"><?= e($s['status']) ?></span></td>
                    <td><?= e(number_format($s['total'], 2)) ?> AED</td>
                    <td>
                        <a href="<?= \App\Core\View::url('/customer/shipments/' . $s['id']) ?>" class="btn btn-outline" style="padding:0.25rem 0.5rem; font-size:0.8rem;">Details</a>
                        <a href="<?= \App\Core\View::url('/shipments/' . $s['id'] . '/label') ?>" target="_blank" class="btn btn-secondary" style="padding:0.25rem 0.5rem; font-size:0.8rem;">Waybill</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($shipments)): ?>
                <tr><td colspan="7" style="text-align:center; color:#94a3b8; padding:2rem;">No shipments found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
