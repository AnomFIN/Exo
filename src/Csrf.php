<?php
/**
 * Csrf – stateless double-submit CSRF tokens stored in the session.
 */
class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function token(): string
    {
        Auth::start();
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::SESSION_KEY];
    }

    public static function field(): string
    {
        $token = htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="_csrf" value="' . $token . '">';
    }

    public static function verify(): bool
    {
        Auth::start();
        $submitted = $_POST['_csrf'] ?? '';
        $stored    = $_SESSION[self::SESSION_KEY] ?? '';
        if ($stored === '' || $submitted === '') {
            return false;
        }
        return hash_equals($stored, $submitted);
    }

    /** Call this to reject requests with invalid CSRF tokens. */
    public static function requireValid(): void
    {
        if (!self::verify()) {
            http_response_code(403);
            exit('Invalid CSRF token.');
        }
    }
}
