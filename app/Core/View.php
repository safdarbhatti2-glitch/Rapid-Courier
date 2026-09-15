<?php

namespace App\Core;

class View
{
    protected static string $viewsDir = '';

    public static function setViewsDir(string $dir): void
    {
        self::$viewsDir = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR;
    }

    public static function render(string $viewPath, array $data = [], ?string $layout = 'main'): void
    {
        extract($data);

        // Helper function for contextual escaping
        if (!function_exists('e')) {
            function e(?string $value): string {
                return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
            }
        }

        $viewFile = self::$viewsDir . str_replace('.', DIRECTORY_SEPARATOR, $viewPath) . '.php';
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View file not found: {$viewPath} ({$viewFile})");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout === null) {
            echo $content;
            return;
        }

        $layoutFile = self::$viewsDir . 'layouts' . DIRECTORY_SEPARATOR . $layout . '.php';
        if (!file_exists($layoutFile)) {
            throw new \RuntimeException("Layout file not found: {$layout} ({$layoutFile})");
        }

        require $layoutFile;
    }

    public static function csrfField(): string
    {
        $token = Session::getCsrfToken();
        return '<input type="hidden" name="_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function getBaseUrl(): string
    {
        if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            return "{$scheme}://{$_SERVER['HTTP_HOST']}";
        }

        $envUrl = EnvLoader::get('APP_URL', '');
        if (!empty($envUrl)) {
            return rtrim($envUrl, '/');
        }

        return 'http://127.0.0.1:8000';
    }

    public static function asset(string $path): string
    {
        return self::getBaseUrl() . '/' . ltrim($path, '/');
    }

    public static function url(string $path = ''): string
    {
        return self::getBaseUrl() . '/' . ltrim($path, '/');
    }

    public static function qrUrl(string $path = ''): string
    {
        $envUrl = EnvLoader::get('APP_URL', '');

        // If APP_URL is set to a live production domain, return that directly
        if (!empty($envUrl) && !str_contains($envUrl, 'localhost') && !str_contains($envUrl, '127.0.0.1') && !str_contains($envUrl, '::1')) {
            return rtrim($envUrl, '/') . '/' . ltrim($path, '/');
        }

        // For local development, resolve reachable host/port so mobile phones on local Wi-Fi can scan screen QR codes
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000';
        $hostParts = explode(':', $host);
        $hostname = $hostParts[0];
        $port = isset($hostParts[1]) ? ':' . $hostParts[1] : '';

        if ($hostname === '127.0.0.1' || $hostname === 'localhost' || $hostname === '::1') {
            $lanIp = gethostbyname(gethostname());
            if (!empty($lanIp) && $lanIp !== '127.0.0.1' && $lanIp !== '::1') {
                $hostname = $lanIp;
            }
        }

        return "{$scheme}://{$hostname}{$port}/" . ltrim($path, '/');
    }
}
