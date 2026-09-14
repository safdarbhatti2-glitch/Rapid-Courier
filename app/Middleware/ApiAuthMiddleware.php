<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Services\ApiService;

class ApiAuthMiddleware
{
    private ?string $requiredScope;

    public function __construct(?string $requiredScope = null)
    {
        $this->requiredScope = $requiredScope;
    }

    public function handle(Request $request): void
    {
        // 1. Extract API Key & Secret from headers
        $apiKey = $request->getHeader('X-API-Key');
        $apiSecret = $request->getHeader('X-API-Secret');

        if (empty($apiKey) || empty($apiSecret)) {
            $authHeader = $request->getHeader('Authorization');
            if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
                $token = trim(substr($authHeader, 7));
                if (str_contains($token, ':')) {
                    list($apiKey, $apiSecret) = explode(':', $token, 2);
                }
            } elseif ($authHeader && str_starts_with($authHeader, 'Basic ')) {
                $decoded = base64_decode(trim(substr($authHeader, 6)));
                if (str_contains($decoded, ':')) {
                    list($apiKey, $apiSecret) = explode(':', $decoded, 2);
                }
            }
        }

        if (empty($apiKey) || empty($apiSecret)) {
            Response::apiError('AUTHENTICATION_REQUIRED', 'API Key and Secret headers (X-API-Key & X-API-Secret) are required.', [], 401);
        }

        // 2. Validate Credentials
        $keyRecord = ApiService::authenticate($apiKey, $apiSecret);

        if (!$keyRecord) {
            Response::apiError('INVALID_API_KEY', 'Invalid API key or secret credentials provided.', [], 401);
        }

        // 3. Check Scope Permission if required
        if ($this->requiredScope && !ApiService::hasPermission($keyRecord, $this->requiredScope)) {
            Response::apiError('INSUFFICIENT_PERMISSION', "API Key does not possess required permission scope: {$this->requiredScope}.", [], 403);
        }

        // 4. Rate Limiting Check
        $limitRpm = (int)($keyRecord['rate_limit_rpm'] ?? 60);
        if (!ApiService::checkRateLimit((int)$keyRecord['id'], $limitRpm)) {
            Response::apiError('RATE_LIMIT_EXCEEDED', "API Rate limit exceeded ({$limitRpm} requests/min). Please try again in 1 minute.", [], 429);
        }

        // Attach key record to request context
        $request->setApiKeyRecord($keyRecord);
    }
}
