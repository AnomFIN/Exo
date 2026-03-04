<?php
/**
 * Config – loads .env from project root (no Composer required).
 */
class Config
{
    private static array $data = [];
    private static bool  $loaded = false;

    public static function load(string $envFile = null): void
    {
        if (self::$loaded) {
            return;
        }
        $envFile = $envFile ?? dirname(__DIR__) . '/.env';
        if (is_file($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#') {
                    continue;
                }
                if (str_contains($line, '=')) {
                    [$key, $value] = explode('=', $line, 2);
                    $key = trim($key);
                    if ($key !== '') {
                        self::$data[$key] = trim($value);
                    }
                }
            }
        }
        self::$loaded = true;
    }

    public static function get(string $key, string $default = ''): string
    {
        self::load();
        return self::$data[$key] ?? $default;
    }

    public static function all(): array
    {
        self::load();
        return self::$data;
    }
}
