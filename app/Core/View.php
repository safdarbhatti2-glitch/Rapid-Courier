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

    public static function asset(string $path): string
    {
        $baseUrl = rtrim(EnvLoader::get('APP_URL', '/'), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }

    public static function url(string $path = ''): string
    {
        $baseUrl = rtrim(EnvLoader::get('APP_URL', '/'), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }

    public static function qrUrl(string $path = ''): string
    {
        $baseUrl = rtrim(EnvLoader::get('APP_URL', ''), '/');
        
        // If APP_URL is empty or contains localhost / 127.0.0.1 / ::1, resolve real LAN IPv4 for mobile scanners
        if (empty($baseUrl) || str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1') || str_contains($baseUrl, '::1')) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            
            $hostIp = gethostbyname(gethostname());
            if (empty($hostIp) || $hostIp === '127.0.0.1' || $hostIp === '::1') {
                $hostIp = $_SERVER['SERVER_ADDR'] ?? '';
            }
            if (empty($hostIp) || $hostIp === '127.0.0.1' || $hostIp === '::1') {
                $hostIp = '192.168.18.42'; // Fallback LAN IP
            }

            // Extract script directory path if needed (e.g. /rc-courier/public)
            $dir = '';
            if (isset($_SERVER['SCRIPT_NAME'])) {
                $dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                if ($dir === '.' || $dir === '/' || $dir === '\\') {
                    $dir = '';
                }
            }

            $baseUrl = "{$scheme}://{$hostIp}{$dir}";
        }

        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}
