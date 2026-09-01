<div style="margin-bottom: 2rem;">
    <h2 style="font-weight: 700; color: #0f172a;">Real-Time Tracking Dispatcher</h2>
    <p style="color: #64748b; font-size: 0.9rem;">Operational oversight of active parcel flows and status updates.</p>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Tracking #</th>
                <th>Reference</th>
                <th>Customer</th>
                <th>Service</th>
                <th>Status</th>
                <th>Last Update</th>
                <th>Quick Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shipments as $s): ?>
                <tr>
                    <td><code><?= e($s['tracking_number']) ?></code></td>
                    <td><strong><?= e($s['reference_number']) ?></strong></td>
                    <td><?= e($s['contact_name']) ?></td>
                    <td><?= e($s['service_name']) ?></td>
                    <td><span class="badge badge-info"><?= e($s['status']) ?></span></td>
                    <td><?= e(date('M d, H:i', strtotime($s['updated_at']))) ?></td>
                    <td>
                        <a href="<?= \App\Core\View::url('/admin/shipments/' . $s['id']) ?>" class="btn btn-primary" style="padding:0.25rem 0.5rem; font-size:0.8rem;">Update Status</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
