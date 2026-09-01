<div style="margin-bottom: 2rem;">
    <h2 style="font-weight: 700; color: #0f172a;">Customer Management (CRM)</h2>
    <p style="color: #64748b; font-size: 0.9rem;">Corporate and individual registered accounts.</p>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Type</th>
                <th>Company</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Shipments</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $c): ?>
                <tr>
                    <td><strong><?= e($c['contact_name']) ?></strong></td>
                    <td><span class="badge badge-info"><?= e(strtoupper($c['customer_type'])) ?></span></td>
                    <td><?= e($c['company_name'] ?: 'Individual') ?></td>
                    <td><?= e($c['email']) ?></td>
                    <td><?= e($c['phone']) ?></td>
                    <td><strong><?= e($c['total_shipments']) ?></strong></td>
                    <td><span class="badge badge-success"><?= e($c['status']) ?></span></td>
                    <td>
                        <a href="<?= \App\Core\View::url('/admin/customers/' . $c['id']) ?>" class="btn btn-outline" style="padding:0.25rem 0.5rem; font-size:0.8rem;">View Profile</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
