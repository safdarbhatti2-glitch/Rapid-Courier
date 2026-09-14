<?php

namespace App\Services;

use App\Core\Database;
use Exception;

class ApiService
{
    /**
     * Create new API Key pair for a customer
     */
    public static function createApiKey(int $customerId, string $name, string $environment = 'live', array $permissions = [], int $rateLimitRpm = 60): array
    {
        $prefix = ($environment === 'test') ? 'rc_test_' : 'rc_live_';
        $rawKey = $prefix . bin2hex(random_bytes(24));
        $rawSecret = 'sec_' . bin2hex(random_bytes(32));
        $secretHash = password_hash($rawSecret, PASSWORD_DEFAULT);

        if (empty($permissions)) {
            $permissions = [
                'shipments:create', 'shipments:read', 'shipments:update', 'shipments:cancel',
                'tracking:read', 'quotes:create', 'invoices:read', 'labels:read', 'webhooks:manage'
            ];
        }

        Database::execute(
            "INSERT INTO api_keys (customer_id, name, api_key, api_secret_hash, environment, permissions, status, rate_limit_rpm) VALUES (?, ?, ?, ?, ?, ?, 'active', ?)",
            [
                $customerId,
                $name,
                $rawKey,
                $secretHash,
                $environment,
                json_encode($permissions),
                $rateLimitRpm
            ]
        );

        $keyId = Database::lastInsertId();

        return [
            'id'             => $keyId,
            'customer_id'    => $customerId,
            'name'           => $name,
            'api_key'        => $rawKey,
            'api_secret'     => $rawSecret, // Shown once to user
            'environment'    => $environment,
            'permissions'    => $permissions,
            'rate_limit_rpm' => $rateLimitRpm,
            'status'         => 'active'
        ];
    }

    /**
     * Authenticate API Request by Key & Secret
     */
    public static function authenticate(string $apiKey, string $apiSecret): ?array
    {
        $keyRecord = Database::fetchOne(
            "SELECT k.*, c.contact_name, c.company_name, c.email as customer_email FROM api_keys k JOIN customers c ON k.customer_id = c.id WHERE k.api_key = ? AND k.status = 'active'",
            [$apiKey]
        );

        if (!$keyRecord) {
            return null;
        }

        if (!password_verify($apiSecret, $keyRecord['api_secret_hash'])) {
            return null;
        }

        // Touch last_used_at
        Database::execute("UPDATE api_keys SET last_used_at = ? WHERE id = ?", [date('Y-m-d H:i:s'), $keyRecord['id']]);

        $keyRecord['permissions_list'] = !empty($keyRecord['permissions']) ? json_decode($keyRecord['permissions'], true) : [];
        return $keyRecord;
    }

    /**
     * Verify Scope Permission
     */
    public static function hasPermission(array $keyRecord, string $requiredPermission): bool
    {
        $perms = $keyRecord['permissions_list'] ?? [];
        return in_array('*', $perms, true) || in_array($requiredPermission, $perms, true);
    }

    /**
     * Check & Increment Per-Key Rate Limit (Requests per minute)
     */
    public static function checkRateLimit(int $apiKeyId, int $limitRpm = 60): bool
    {
        $currentMinute = (int)floor(time() / 60);

        $row = Database::fetchOne(
            "SELECT id, request_count FROM api_rate_limits WHERE api_key_id = ? AND window_time = ?",
            [$apiKeyId, $currentMinute]
        );

        if ($row) {
            if ((int)$row['request_count'] >= $limitRpm) {
                return false; // Rate limit exceeded
            }
            Database::execute("UPDATE api_rate_limits SET request_count = request_count + 1 WHERE id = ?", [$row['id']]);
        } else {
            Database::execute("INSERT INTO api_rate_limits (api_key_id, window_time, request_count) VALUES (?, ?, 1)", [$apiKeyId, $currentMinute]);
        }

        return true;
    }

    /**
     * Check Idempotency Key
     */
    public static function getIdempotentResponse(int $customerId, string $idempotencyKey, string $endpoint): ?array
    {
        if (empty($idempotencyKey)) {
            return null;
        }

        $row = Database::fetchOne(
            "SELECT response_code, response_body FROM api_idempotency WHERE customer_id = ? AND idempotency_key = ? AND endpoint = ?",
            [$customerId, $idempotencyKey, $endpoint]
        );

        if ($row) {
            return [
                'code' => (int)$row['response_code'],
                'body' => json_decode($row['response_body'], true)
            ];
        }

        return null;
    }

    /**
     * Save Idempotency Response
     */
    public static function saveIdempotentResponse(int $customerId, string $idempotencyKey, string $endpoint, string $requestHash, int $code, array $body): void
    {
        if (empty($idempotencyKey)) {
            return;
        }

        try {
            Database::execute(
                "INSERT INTO api_idempotency (customer_id, idempotency_key, endpoint, request_hash, response_code, response_body) VALUES (?, ?, ?, ?, ?, ?)",
                [$customerId, $idempotencyKey, $endpoint, $requestHash, $code, json_encode($body)]
            );
        } catch (Exception $e) {
            // Ignore duplicate insert errors
        }
    }

    /**
     * Log API Request Audit
     */
    public static function logAudit(string $requestId, ?int $apiKeyId, ?int $customerId, string $method, string $endpoint, int $responseCode, int $executionTimeMs, string $ipAddress, ?string $userAgent): void
    {
        try {
            Database::execute(
                "INSERT INTO api_audit_logs (request_id, api_key_id, customer_id, method, endpoint, response_code, execution_time_ms, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$requestId, $apiKeyId, $customerId, $method, $endpoint, $responseCode, $executionTimeMs, $ipAddress, substr($userAgent ?? '', 0, 255)]
            );
        } catch (Exception $e) {
            // Audit logging failsafe
        }
    }
}
