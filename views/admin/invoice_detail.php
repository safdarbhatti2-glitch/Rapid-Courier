<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
    <div>
        <h2 style="font-weight: 700; color: #0f172a;">Invoice <?= e($invoice['invoice_number']) ?></h2>
        <p style="color: #64748b;">Status: <span class="badge badge-<?= $invoice['status'] === 'PAID' ? 'success' : 'warning' ?>"><?= e($invoice['status']) ?></span></p>
    </div>
    <div style="display:flex; gap:0.5rem;">
        <a href="<?= \App\Core\View::url('/invoices/' . $invoice['id'] . '/pdf') ?>" target="_blank" class="btn btn-secondary">📄 Download PDF</a>
        <a href="<?= \App\Core\View::url('/invoices/' . $invoice['id'] . '/pdf?download=image') ?>" target="_blank" class="btn btn-primary" style="background:#168fd2; border:none;">🖼️ Download Image (PNG)</a>
        <a href="<?= \App\Core\View::url('/invoices/' . $invoice['id'] . '/thermal') ?>" target="_blank" class="btn btn-primary" style="background:#dcae3f; color:#0f172a; border:none; font-weight:700;">🖨️ Thermal Receipt</a>
        <?php if ($invoice['status'] !== 'VOID' && $invoice['status'] !== 'PAID'): ?>
            <form action="<?= \App\Core\View::url('/admin/invoices/' . $invoice['id'] . '/void') ?>" method="POST" onsubmit="return confirm('Are you sure you want to VOID this invoice?');">
                <?= \App\Core\View::csrfField() ?>
                <button type="submit" class="btn btn-outline" style="color:var(--danger); border-color:var(--danger);">Void Invoice</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <div>
        <!-- Invoice Details Card -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: var(--primary);">Line Items</h3>
            <div class="table-responsive" style="margin-bottom: 1rem;">
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
                <p style="font-size: 1.2rem; color: var(--primary);">Grand Total: <strong><?= e(number_format($invoice['total'], 2)) ?> AED</strong></p>
                <p style="color: var(--success);">Amount Paid: <strong><?= e(number_format($invoice['amount_paid'], 2)) ?> AED</strong></p>
                <p style="color: var(--accent);">Balance Due: <strong><?= e(number_format($invoice['balance_due'], 2)) ?> AED</strong></p>
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: var(--primary);">Payment Audit Trail</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Payment #</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $p): ?>
                            <tr>
                                <td><strong><?= e($p['payment_number']) ?></strong></td>
                                <td><span class="badge badge-info"><?= e($p['method']) ?></span></td>
                                <td><code><?= e($p['reference'] ?: 'N/A') ?></code></td>
                                <td><strong><?= e(number_format($p['amount'], 2)) ?> AED</strong></td>
                                <td><?= e(date('M d, Y H:i', strtotime($p['paid_at']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($payments)): ?>
                            <tr><td colspan="5" style="text-align:center; color:#94a3b8; padding:1.5rem;">No payments recorded yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <!-- Record Payment Card -->
        <?php if ($invoice['balance_due'] > 0 && $invoice['status'] !== 'VOID'): ?>
            <div class="card" style="border-top: 4px solid var(--success); margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: var(--primary);">Record New Payment</h3>
                <form action="<?= \App\Core\View::url('/admin/invoices/' . $invoice['id'] . '/payments') ?>" method="POST">
                    <?= \App\Core\View::csrfField() ?>

                    <div style="margin-bottom: 1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.3rem;">Amount (AED)</label>
                        <input type="number" step="0.01" name="amount" value="<?= e($invoice['balance_due']) ?>" max="<?= e($invoice['balance_due']) ?>" required style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:var(--radius);">
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.3rem;">Payment Method</label>
                        <select name="method" style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:var(--radius);">
                            <option value="credit_card">Credit / Debit Card</option>
                            <option value="bank_transfer">Bank Transfer / Wire</option>
                            <option value="cash">Cash on Delivery (COD)</option>
                            <option value="cheque">Company Cheque</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.3rem;">Transaction Reference</label>
                        <input type="text" name="reference" placeholder="e.g. TXN-98412-UAE" style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:var(--radius);">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">Record Payment</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="card" style="background:#f8fafc;">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: var(--primary);">Customer Info</h3>
            <p style="font-size:0.9rem;"><strong>Customer:</strong> <?= e($invoice['contact_name']) ?></p>
            <p style="font-size:0.9rem;"><strong>Company:</strong> <?= e($invoice['company_name'] ?: 'Individual') ?></p>
            <p style="font-size:0.9rem;"><strong>Email:</strong> <?= e($invoice['email']) ?></p>
            <p style="font-size:0.9rem;"><strong>Phone:</strong> <?= e($invoice['phone']) ?></p>
        </div>
    </div>
</div>
