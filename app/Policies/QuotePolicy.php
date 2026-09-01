<?php

namespace App\Policies;

class QuotePolicy
{
    public static function canView(array $user, array $quote): bool
    {
        if (in_array($user['role_name'], ['super_admin', 'admin', 'sales', 'operations_manager'])) {
            return true;
        }

        if ($user['role_name'] === 'customer' && isset($user['customer_id'])) {
            return (string)$quote['customer_id'] === (string)$user['customer_id'];
        }

        return false;
    }

    public static function canManage(array $user): bool
    {
        return in_array($user['role_name'], ['super_admin', 'admin', 'sales']);
    }

    public static function canConvert(array $user): bool
    {
        return in_array($user['role_name'], ['super_admin', 'admin', 'sales', 'operations_manager']);
    }
}
