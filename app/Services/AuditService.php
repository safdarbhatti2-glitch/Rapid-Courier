<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Session;

class AuditService
{
    public static function log(string $action, string $entityType, ?int $entityId = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        $user = Session::get('user');
        $actorId = $user['id'] ?? null;
        $ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

        Database::execute("INSERT INTO audit_logs (actor_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
            $actorId,
            $action,
            $entityType,
            $entityId,
            $oldValues ? json_encode($oldValues) : null,
            $newValues ? json_encode($newValues) : null,
            $ip,
            $ua
        ]);
    }
}
