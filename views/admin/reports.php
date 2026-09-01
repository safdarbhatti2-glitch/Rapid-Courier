<div style="margin-bottom: 2rem;">
    <h2 style="font-weight: 700; color: #0f172a;">Financial & Operations Reports</h2>
    <p style="color: #64748b; font-size: 0.9rem;">Analytical overview of logistics performance and revenue metrics.</p>
</div>

<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 2rem;">
    <div class="card" style="border-top: 4px solid var(--accent);">
        <span style="font-size:0.8rem; color:var(--text-muted); display:block;">TOTAL INVOICED REVENUE</span>
        <h2 style="font-size: 1.8rem; font-weight: 800; color: var(--primary);"><?= number_format($revenue['total_rev'], 2) ?> AED</h2>
    </div>
    <div class="card" style="border-top: 4px solid var(--success);">
        <span style="font-size:0.8rem; color:var(--text-muted); display:block;">COLLECTED REVENUE</span>
        <h2 style="font-size: 1.8rem; font-weight: 800; color: var(--success);"><?= number_format($revenue['paid_rev'], 2) ?> AED</h2>
    </div>
    <div class="card" style="border-top: 4px solid var(--gold);">
        <span style="font-size:0.8rem; color:var(--text-muted); display:block;">OUTSTANDING BALANCES</span>
        <h2 style="font-size: 1.8rem; font-weight: 800; color: var(--gold);"><?= number_format($revenue['unpaid_rev'], 2) ?> AED</h2>
    </div>
</div>

<h3 style="font-weight: 700; color: #0f172a; margin-bottom: 1rem;">Shipments Distribution by Status</h3>
<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Shipment Volume</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shipmentsByStatus as $st): ?>
                <tr>
                    <td><span class="badge badge-info"><?= e($st['status']) ?></span></td>
                    <td><strong><?= e($st['cnt']) ?></strong> shipments</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
