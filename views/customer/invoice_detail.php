<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">Invoice <?= e($invoice['invoice_number']) ?></h1>
        <p style="color: #64748b;">Status: <span class="badge badge-<?= $invoice['status'] === 'PAID' ? 'success' : 'warning' ?>"><?= e($invoice['status']) ?></span></p>
    </div>
    <div>
        <a href="<?= \App\Core\View::url('/invoices/' . $invoice['id'] . '/pdf') ?>" target="_blank" class="btn btn-secondary">Print / Download PDF</a>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
        <div>
            <h4 style="font-weight: 700; color: var(--primary);">Billed To</h4>
            <p style="font-size: 0.9rem;"><?= e($invoice['company_name'] ?: $invoice['contact_name']) ?></p>
            <p style="font-size: 0.85rem; color: var(--text-muted);"><?= e($invoice['email']) ?> | <?= e($invoice['phone']) ?></p>
        </div>
        <div style="text-align: right;">
            <p style="font-size: 0.85rem;"><strong>Issue Date:</strong> <?= e(date('M d, Y', strtotime($invoice['issue_date']))) ?></p>
            <p style="font-size: 0.85rem;"><strong>Due Date:</strong> <?= e(date('M d, Y', strtotime($invoice['due_date']))) ?></p>
            <p style="font-size: 0.85rem;"><strong>TRN:</strong> <?= e($invoice['trn'] ?: '100987654321003') ?></p>
        </div>
    </div>

    <h4 style="font-weight: 700; margin-bottom: 1rem;">Line Items</h4>
    <div class="table-responsive" style="margin-bottom: 1.5rem;">
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Reference</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>VAT (5%)</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['description']) ?></td>
                        <td><code><?= e($item['reference'] ?: 'N/A') ?></code></td>
                        <td><?= e($item['quantity']) ?></td>
                        <td><?= e(number_format($item['unit_price'], 2)) ?> AED</td>
                        <td><?= e(number_format($item['line_tax'], 2)) ?> AED</td>
                        <td><strong><?= e(number_format($item['line_total'], 2)) ?> AED</strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div style="text-align: right; font-size: 0.95rem; line-height: 1.8;">
        <p>Subtotal: <strong><?= e(number_format($invoice['subtotal'], 2)) ?> AED</strong></p>
        <p>UAE VAT (5%): <strong><?= e(number_format($invoice['tax'], 2)) ?> AED</strong></p>
        <p style="font-size: 1.2rem; color: var(--primary); margin-top: 0.5rem;">Grand Total: <strong><?= e(number_format($invoice['total'], 2)) ?> AED</strong></p>
        <p style="color: var(--success);">Amount Paid: <strong><?= e(number_format($invoice['amount_paid'], 2)) ?> AED</strong></p>
        <p style="color: var(--accent);">Balance Due: <strong><?= e(number_format($invoice['balance_due'], 2)) ?> AED</strong></p>
    </div>
</div>
