<?php

namespace App\Core;

class Autoloader
{
    protected static string $baseDir;

    public static function register(string $baseDir): void
    {
        self::$baseDir = rtrim($baseDir, '/\\') . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR;

        spl_autoload_register(function (string $class) {
            $prefix = 'App\\';
            if (str_starts_with($class, $prefix)) {
                $relativeClass = substr($class, strlen($prefix));
                $file = self::$baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

                if (file_exists($file)) {
                    require_once $file;
                }
            }
        });
    }
}
