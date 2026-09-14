<?php

namespace App\Core;

class Response
{
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function success(mixed $data = [], string $message = 'Success', int $statusCode = 200): void
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'errors'  => []
        ], $statusCode);
    }

    public static function error(string $message = 'Error', array $errors = [], int $statusCode = 400): void
    {
        self::json([
            'success' => false,
            'message' => $message,
            'data'    => null,
            'errors'  => $errors
        ], $statusCode);
    }

    public static function apiSuccess(mixed $data = [], array $meta = [], int $statusCode = 200, ?string $requestId = null): void
    {
        $reqId = $requestId ?? ($_SERVER['HTTP_X_REQUEST_ID'] ?? ('RCREQ-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid('', true)), 0, 8))));
        header("X-Request-ID: {$reqId}");

        $meta['request_id'] = $reqId;
        $meta['timestamp']  = date('Y-m-d\TH:i:s\Z');
        $meta['version']    = 'v1';

        self::json([
            'success' => true,
            'data'    => $data,
            'meta'    => $meta
        ], $statusCode);
    }

    public static function apiError(string $code, string $message, array $fields = [], int $statusCode = 400, ?string $requestId = null): void
    {
        $reqId = $requestId ?? ($_SERVER['HTTP_X_REQUEST_ID'] ?? ('RCREQ-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid('', true)), 0, 8))));
        header("X-Request-ID: {$reqId}");

        $errorObj = [
            'code'    => $code,
            'message' => $message
        ];

        if (!empty($fields)) {
            $errorObj['fields'] = $fields;
        }

        self::json([
            'success' => false,
            'error'   => $errorObj,
            'meta'    => [
                'request_id' => $reqId,
                'timestamp'  => date('Y-m-d\TH:i:s\Z'),
                'version'    => 'v1'
            ]
        ], $statusCode);
    }

    public static function redirect(string $url, int $statusCode = 302): void
    {
        if (str_starts_with($url, '/')) {
            $url = View::url($url);
        }
        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }
}
