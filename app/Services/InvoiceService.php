<?php

namespace App\Services;

use App\Core\Database;
use Exception;
use RuntimeException;

class InvoiceService
{
    public static function createInvoice(array $data): array
    {
        Database::beginTransaction();

        try {
            $year = date('Y');
            $countRow = Database::fetchOne("SELECT COUNT(*) as cnt FROM invoices");
            $nextSeq = str_pad((string)($countRow['cnt'] + 1), 6, '0', STR_PAD_LEFT);
            $invoiceNumber = "INV-{$year}-{$nextSeq}";

            $items = $data['items'] ?? [];
            $subtotal = 0.0;
            $taxTotal = 0.0;

            foreach ($items as $item) {
                $qty = max(1, (int)($item['quantity'] ?? 1));
                $price = (float)($item['unit_price'] ?? 0.0);
                $disc = (float)($item['discount'] ?? 0.0);
                $rate = (float)($item['tax_rate'] ?? 5.0);

                $lineSub = ($qty * $price) - $disc;
                $lineTax = round($lineSub * ($rate / 100.0), 2);
                
                $subtotal += $lineSub;
                $taxTotal += $lineTax;
            }

            $total = round($subtotal + $taxTotal, 2);
            $issueDate = date('Y-m-d');
            $dueDate   = date('Y-m-d', strtotime('+30 days'));

            Database::execute("INSERT INTO invoices (invoice_number, customer_id, shipment_id, status, issue_date, due_date, currency, subtotal, discount, tax, total, amount_paid, balance_due, trn, notes, issued_at) VALUES (?, ?, ?, 'ISSUED', ?, ?, 'AED', ?, 0.00, ?, ?, 0.00, ?, ?, ?, NOW())", [
                $invoiceNumber,
                $data['customer_id'],
                $data['shipment_id'] ?? null,
                $issueDate,
                $dueDate,
                $subtotal,
                $taxTotal,
                $total,
                $total,
                $data['trn'] ?? '100987654321003',
                $data['notes'] ?? 'UAE Commercial Courier Services Invoice'
            ]);

            $invoiceId = Database::lastInsertId();

            // Store items
            foreach ($items as $item) {
                $qty = max(1, (int)($item['quantity'] ?? 1));
                $price = (float)($item['unit_price'] ?? 0.0);
                $disc = (float)($item['discount'] ?? 0.0);
                $rate = (float)($item['tax_rate'] ?? 5.0);
                $lineSub = ($qty * $price) - $disc;
                $lineTax = round($lineSub * ($rate / 100.0), 2);
                $lineTot = round($lineSub + $lineTax, 2);

                Database::execute("INSERT INTO invoice_items (invoice_id, description, reference, quantity, unit_price, discount, tax_rate, line_subtotal, line_tax, line_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                    $invoiceId,
                    $item['description'],
                    $item['reference'] ?? null,
                    $qty,
                    $price,
                    $disc,
                    $rate,
                    $lineSub,
                    $lineTax,
                    $lineTot
                ]);
            }

            // Store Tax Summary
            Database::execute("INSERT INTO invoice_taxes (invoice_id, tax_name, rate, amount) VALUES (?, 'UAE Standard VAT', 5.00, ?)", [
                $invoiceId, $taxTotal
            ]);

            AuditService::log('invoice_issue', 'invoice', $invoiceId, null, ['invoice_number' => $invoiceNumber, 'total' => $total]);

            Database::commit();

            return [
                'id'             => $invoiceId,
                'invoice_number' => $invoiceNumber,
                'total'          => $total,
                'balance_due'    => $total
            ];

        } catch (Exception $e) {
            Database::rollBack();
            throw new RuntimeException("Invoice creation failed: " . $e->getMessage());
        }
    }

    public static function recordPayment(int $invoiceId, float $amount, string $method, ?string $reference, ?int $createdBy): array
    {
        Database::beginTransaction();

        try {
            $invoice = Database::fetchOne("SELECT * FROM invoices WHERE id = ?", [$invoiceId]);
            if (!$invoice) {
                throw new RuntimeException("Invoice #{$invoiceId} not found");
            }

            if ($invoice['status'] === 'VOID') {
                throw new RuntimeException("Cannot record payment on a voided invoice.");
            }

            $currentPaid = (float)$invoice['amount_paid'];
            $totalAmount = (float)$invoice['total'];
            $newPaid     = round($currentPaid + $amount, 2);
            $newBalance  = max(0.0, round($totalAmount - $newPaid, 2));

            $newStatus = ($newBalance <= 0.0) ? 'PAID' : 'PARTIALLY_PAID';

            // Generate Payment Number
            $year = date('Y');
            $countRow = Database::fetchOne("SELECT COUNT(*) as cnt FROM payments");
            $nextSeq = str_pad((string)($countRow['cnt'] + 1), 6, '0', STR_PAD_LEFT);
            $payNumber = "PAY-{$year}-{$nextSeq}";

            Database::execute("INSERT INTO payments (payment_number, invoice_id, customer_id, amount, currency, method, reference, status, paid_at, created_by) VALUES (?, ?, ?, ?, 'AED', ?, ?, 'completed', NOW(), ?)", [
                $payNumber,
                $invoiceId,
                $invoice['customer_id'],
                $amount,
                $method,
                $reference,
                $createdBy
            ]);

            // Update Invoice Status
            Database::execute("UPDATE invoices SET amount_paid = ?, balance_due = ?, status = ?, updated_at = NOW() WHERE id = ?", [
                $newPaid, $newBalance, $newStatus, $invoiceId
            ]);

            AuditService::log('payment_record', 'invoice', $invoiceId, ['amount_paid' => $currentPaid], ['amount_paid' => $newPaid, 'payment_number' => $payNumber]);

            Database::commit();

            return [
                'payment_number' => $payNumber,
                'amount_paid'    => $newPaid,
                'balance_due'    => $newBalance,
                'status'         => $newStatus
            ];

        } catch (Exception $e) {
            Database::rollBack();
            throw new RuntimeException("Payment recording failed: " . $e->getMessage());
        }
    }

    public static function voidInvoice(int $invoiceId): bool
    {
        $invoice = Database::fetchOne("SELECT * FROM invoices WHERE id = ?", [$invoiceId]);
        if (!$invoice) {
            throw new RuntimeException("Invoice #{$invoiceId} not found");
        }

        if ($invoice['status'] === 'PAID') {
            throw new RuntimeException("Cannot void a fully paid invoice.");
        }

        Database::execute("UPDATE invoices SET status = 'VOID', voided_at = NOW(), updated_at = NOW() WHERE id = ?", [$invoiceId]);

        AuditService::log('invoice_void', 'invoice', $invoiceId, ['status' => $invoice['status']], ['status' => 'VOID']);

        return true;
    }
}
