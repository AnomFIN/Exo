<?php
/**
 * Database – thin PDO wrapper (MySQL).
 */
class Database
{
    private static ?PDO $pdo = null;

    public static function connect(
        string $host,
        string $name,
        string $user,
        string $pass,
        string $port = '3306'
    ): PDO {
        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        self::$pdo = new PDO($dsn, $user, $pass, $options);
        return self::$pdo;
    }

    /** Connect using values from Config / .env */
    public static function connectFromConfig(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }
        return self::connect(
            Config::get('DB_HOST', 'localhost'),
            Config::get('DB_NAME'),
            Config::get('DB_USER'),
            Config::get('DB_PASS'),
            Config::get('DB_PORT', '3306')
        );
    }

    public static function get(): PDO
    {
        if (self::$pdo === null) {
            return self::connectFromConfig();
        }
        return self::$pdo;
    }
}
