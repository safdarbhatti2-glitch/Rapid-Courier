<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">My Invoices & Accounting</h1>
    <p style="color: #64748b;">View and download tax-compliant invoices and payment receipts.</p>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Status</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Total (AED)</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $inv): ?>
                <tr>
                    <td><strong><?= e($inv['invoice_number']) ?></strong></td>
                    <td><span class="badge badge-<?= $inv['status'] === 'PAID' ? 'success' : 'warning' ?>"><?= e($inv['status']) ?></span></td>
                    <td><?= e(date('M d, Y', strtotime($inv['issue_date']))) ?></td>
                    <td><?= e(date('M d, Y', strtotime($inv['due_date']))) ?></td>
                    <td><?= e(number_format($inv['total'], 2)) ?> AED</td>
                    <td><?= e(number_format($inv['amount_paid'], 2)) ?> AED</td>
                    <td><?= e(number_format($inv['balance_due'], 2)) ?> AED</td>
                    <td>
                        <a href="<?= \App\Core\View::url('/customer/invoices/' . $inv['id']) ?>" class="btn btn-outline" style="padding:0.25rem 0.5rem; font-size:0.8rem;">View</a>
                        <a href="<?= \App\Core\View::url('/invoices/' . $inv['id'] . '/pdf') ?>" target="_blank" class="btn btn-secondary" style="padding:0.25rem 0.5rem; font-size:0.8rem;">PDF</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($invoices)): ?>
                <tr><td colspan="8" style="text-align:center; color:#94a3b8; padding:2rem;">No invoices issued yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
