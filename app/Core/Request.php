<?php

namespace App\Core;

class Request
{
    private string $method;
    private string $uri;
    private array $get;
    private array $post;
    private array $headers;
    private ?array $jsonBody = null;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        
        // Handle method override
        if ($this->method === 'POST' && isset($_POST['_method'])) {
            $this->method = strtoupper($_POST['_method']);
        }

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $baseDir = dirname($scriptName);

        // Normalize base path for subfolder setups (e.g. /RC courier/public)
        if ($baseDir !== '/' && str_starts_with($uri, $baseDir)) {
            $uri = substr($uri, strlen($baseDir));
        }

        $position = strpos($uri, '?');
        if ($position !== false) {
            $uri = substr($uri, 0, $position);
        }

        $this->uri = '/' . ltrim($uri, '/');
        $this->get = $_GET;
        $this->post = $_POST;
        $this->headers = getallheaders() ?: [];

        $contentType = $this->getHeader('Content-Type') ?? '';
        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            $this->jsonBody = json_decode($raw, true) ?: [];
        }
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getHeader(string $name): ?string
    {
        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === strtolower($name)) {
                return $value;
            }
        }
        return null;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        if ($this->jsonBody !== null) {
            return $this->jsonBody[$key] ?? $default;
        }

        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function all(): array
    {
        if ($this->jsonBody !== null) {
            return array_merge($this->get, $this->jsonBody);
        }

        return array_merge($this->get, $this->post);
    }

    public function isJson(): bool
    {
        $contentType = $this->getHeader('Content-Type') ?? '';
        return str_contains($contentType, 'application/json');
    }

    public function ip(): string
    {
        return $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    public function userAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }
}
