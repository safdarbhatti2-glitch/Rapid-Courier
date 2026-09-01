<div style="margin-bottom: 2rem;">
    <h2 style="font-weight: 700; color: #0f172a;">Security Audit Logs</h2>
    <p style="color: #64748b; font-size: 0.9rem;">Trace all administrative actions, login attempts, financial state changes, and updates.</p>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Log ID</th>
                <th>Actor</th>
                <th>Action</th>
                <th>Entity</th>
                <th>Entity ID</th>
                <th>IP Address</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $l): ?>
                <tr>
                    <td><code>#<?= $l['id'] ?></code></td>
                    <td><?= e($l['actor_name'] ?: 'System / Guest') ?><br><small><?= e($l['actor_email'] ?? '') ?></small></td>
                    <td><span class="badge badge-info"><?= e($l['action']) ?></span></td>
                    <td><code><?= e($l['entity_type']) ?></code></td>
                    <td>#<?= e($l['entity_id'] ?: 'N/A') ?></td>
                    <td><?= e($l['ip_address']) ?></td>
                    <td><?= e(date('M d, Y H:i:s', strtotime($l['created_at']))) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
