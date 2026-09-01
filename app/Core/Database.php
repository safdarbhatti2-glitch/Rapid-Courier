<?php

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $driver = EnvLoader::get('DB_DRIVER', 'mysql');
            $baseDir = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2);

            if ($driver === 'sqlite') {
                $dbPath = EnvLoader::get('DB_SQLITE_PATH', $baseDir . '/database/database.sqlite');
                if (!file_exists(dirname($dbPath))) {
                    @mkdir(dirname($dbPath), 0777, true);
                }
                $dsn = "sqlite:" . $dbPath;
                self::$instance = new PDO($dsn, null, null, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
                self::$instance->exec("PRAGMA foreign_keys = ON;");
            } else {
                $host = EnvLoader::get('DB_HOST', '127.0.0.1');
                $port = EnvLoader::get('DB_PORT', '3306');
                $db   = EnvLoader::get('DB_NAME', 'rc_courier');
                $user = EnvLoader::get('DB_USER', 'root');
                $pass = EnvLoader::get('DB_PASS', '');

                $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                try {
                    self::$instance = new PDO($dsn, $user, $pass, $options);
                } catch (PDOException $e) {
                    $dbPath = $baseDir . '/database/database.sqlite';
                    if (!file_exists(dirname($dbPath))) {
                        @mkdir(dirname($dbPath), 0777, true);
                    }
                    $dsn = "sqlite:" . $dbPath;
                    self::$instance = new PDO($dsn, null, null, [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]);
                    self::$instance->exec("PRAGMA foreign_keys = ON;");
                }
            }
        }

        return self::$instance;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function execute(string $sql, array $params = []): bool
    {
        $stmt = self::getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    public static function lastInsertId(): string
    {
        return self::getConnection()->lastInsertId();
    }

    public static function beginTransaction(): void
    {
        self::getConnection()->beginTransaction();
    }

    public static function commit(): void
    {
        self::getConnection()->commit();
    }

    public static function rollBack(): void
    {
        if (self::getConnection()->inTransaction()) {
            self::getConnection()->rollBack();
        }
    }
}
