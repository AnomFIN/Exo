<?php
/**
 * Auth – session-based authentication helpers.
 */
class Auth
{
    private static bool $started = false;

    public static function start(): void
    {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        session_start();
        self::$started = true;
    }

    public static function login(string $username): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['auth_user']   = $username;
        $_SESSION['auth_time']   = time();
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }

    public static function check(): bool
    {
        self::start();
        return isset($_SESSION['auth_user']);
    }

    public static function user(): string
    {
        self::start();
        return $_SESSION['auth_user'] ?? '';
    }

    /** Verify admin credentials against the database. */
    public static function attempt(string $username, string $password): bool
    {
        try {
            $pdo  = Database::get();
            $stmt = $pdo->prepare('SELECT password_hash FROM admins WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            $row = $stmt->fetch();
            if ($row && password_verify($password, $row['password_hash'])) {
                self::login($username);
                return true;
            }
        } catch (Throwable $e) {
            // DB unavailable – fail closed
        }
        return false;
    }

    /** Redirect to admin login if not authenticated. */
    public static function requireLogin(string $loginUrl = 'admin.php'): void
    {
        if (!self::check()) {
            header('Location: ' . $loginUrl);
            exit;
        }
    }
}
