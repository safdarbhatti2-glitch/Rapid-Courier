<?php

namespace App\Policies;

class InvoicePolicy
{
    public static function canView(array $user, array $invoice): bool
    {
        if (in_array($user['role_name'], ['super_admin', 'admin', 'finance'])) {
            return true;
        }

        if ($user['role_name'] === 'customer' && isset($user['customer_id'])) {
            return (string)$invoice['customer_id'] === (string)$user['customer_id'];
        }

        return false;
    }

    public static function canIssue(array $user): bool
    {
        return in_array($user['role_name'], ['super_admin', 'admin', 'finance']);
    }

    public static function canVoid(array $user): bool
    {
        return in_array($user['role_name'], ['super_admin', 'admin', 'finance']);
    }

    public static function canRecordPayment(array $user): bool
    {
        return in_array($user['role_name'], ['super_admin', 'admin', 'finance']);
    }
}
