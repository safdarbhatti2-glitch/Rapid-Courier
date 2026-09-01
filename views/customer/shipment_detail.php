<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">Shipment <?= e($shipment['reference_number']) ?></h1>
        <p style="color: #64748b;">Tracking Number: <code><?= e($shipment['tracking_number']) ?></code></p>
    </div>
    <div>
        <a href="<?= \App\Core\View::url('/shipments/' . $shipment['id'] . '/label') ?>" target="_blank" class="btn btn-secondary">Print Waybill Label</a>
    </div>
</div>

<div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <div>
        <div class="card" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: var(--primary);">Shipment Parameters</h3>
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.9rem;">
                <div><strong>Service:</strong> <?= e($shipment['service_name']) ?></div>
                <div><strong>Current Status:</strong> <span class="badge badge-info"><?= e($shipment['status']) ?></span></div>
                <div><strong>Weight:</strong> <?= e($shipment['weight_kg']) ?> kg</div>
                <div><strong>Total Charges:</strong> <?= e(number_format($shipment['total'], 2)) ?> AED</div>
                <div><strong>Origin:</strong> <?= e($shipment['origin_addr']) ?> (<?= e($shipment['origin_emirate']) ?>)</div>
                <div><strong>Destination:</strong> <?= e($shipment['dest_addr']) ?> (<?= e($shipment['dest_emirate']) ?>)</div>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: var(--primary);">Live Status History</h3>
            <div class="timeline">
                <?php foreach ($events as $ev): ?>
                    <div class="timeline-item">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--secondary);"><?= e(date('M d, Y — H:i', strtotime($ev['event_time']))) ?></div>
                        <div style="font-weight: 700; color: var(--primary);"><?= e($ev['status']) ?> — <span style="font-weight: normal; color: var(--text-muted);"><?= e($ev['location_name']) ?></span></div>
                        <?php if (!empty($ev['public_notes'])): ?>
                            <p style="font-size: 0.85rem; color: #475569; margin-top: 0.2rem;"><?= e($ev['public_notes']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div>
        <div class="card" style="background: #f8fafc;">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: var(--primary);">Need Assistance?</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Contact our Dubai central logistics dispatch desk for immediate support.</p>
            <p style="font-size: 0.85rem;"><strong>Phone:</strong> +971 4 800 2684</p>
            <p style="font-size: 0.85rem;"><strong>Email:</strong> support@antigravityexpress.ae</p>
        </div>
    </div>
</div>
