<?php

namespace App\Policies;

class ShipmentPolicy
{
    public static function canView(array $user, array $shipment): bool
    {
        if (in_array($user['role_name'], ['super_admin', 'admin', 'operations_manager', 'dispatcher'])) {
            return true;
        }

        if ($user['role_name'] === 'customer' && isset($user['customer_id'])) {
            return (string)$shipment['customer_id'] === (string)$user['customer_id'];
        }

        return false;
    }

    public static function canUpdateStatus(array $user): bool
    {
        return in_array($user['role_name'], ['super_admin', 'admin', 'operations_manager', 'dispatcher']);
    }

    public static function canCancel(array $user, array $shipment): bool
    {
        if (in_array($user['role_name'], ['super_admin', 'admin', 'operations_manager'])) {
            return true;
        }

        if ($user['role_name'] === 'customer' && isset($user['customer_id'])) {
            return (string)$shipment['customer_id'] === (string)$user['customer_id'] && $shipment['status'] === 'BOOKED';
        }

        return false;
    }
}
