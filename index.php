<?php
declare(strict_types=1);

/**
 * index.php – Public entry point.
 * Works immediately after FTP upload on shared hosting (Apache / LiteSpeed).
 */

require_once __DIR__ . '/src/bootstrap.php';

Config::load(__DIR__ . '/.env');

// ── Determine storage driver ─────────────────────────────────────────────────
function getStorage(): StorageInterface
{
    $dbName = Config::get('DB_NAME');
    if ($dbName !== '') {
        try {
            $pdo = Database::connectFromConfig();
            return new MysqlDriver($pdo);
        } catch (Throwable $e) {
            // Fall through to TxtDriver
        }
    }
    return new TxtDriver(__DIR__ . '/storage');
}

$storage = getStorage();

// ── Routing ──────────────────────────────────────────────────────────────────
$rawSlug = trim($_GET['page'] ?? '', " \t\n\r\0\x0B/");
$slug    = ($rawSlug === '') ? 'home' : $rawSlug;

// Allow only safe slug characters
if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $slug)) {
    http_response_code(400);
    exit('Invalid page slug.');
}

$page = $storage->getPage($slug);

if ($page === null) {
    http_response_code(404);
    $page = [
        'title'            => '404 – Page Not Found',
        'html'             => '<p>The page you are looking for does not exist.</p>',
        'meta_description' => '',
        'meta_keywords'    => '',
    ];
}

// ── Site settings ────────────────────────────────────────────────────────────
$siteTitle       = $storage->getSetting('site_title', Config::get('APP_NAME', 'My Site'));
$siteDescription = $storage->getSetting('site_description', '');
$siteUrl         = rtrim(Config::get('APP_URL', ''), '/');

// ── Build canonical URL ───────────────────────────────────────────────────────
$protocol  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host      = htmlspecialchars($_SERVER['HTTP_HOST'] ?? '', ENT_QUOTES, 'UTF-8');
$canonical = ($siteUrl !== '') ? $siteUrl . ($slug === 'home' ? '/' : '/?page=' . rawurlencode($slug))
                               : $protocol . '://' . $host . ($slug === 'home' ? '/' : '/?page=' . rawurlencode($slug));

// ── Security headers ─────────────────────────────────────────────────────────
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

// ── Escape output values ─────────────────────────────────────────────────────
$pageTitle       = htmlspecialchars($page['title'],            ENT_QUOTES, 'UTF-8');
$pageDesc        = htmlspecialchars($page['meta_description'] ?? $siteDescription, ENT_QUOTES, 'UTF-8');
$pageKeywords    = htmlspecialchars($page['meta_keywords']    ?? '', ENT_QUOTES, 'UTF-8');
$siteTitleEsc    = htmlspecialchars($siteTitle,                ENT_QUOTES, 'UTF-8');
$canonicalEsc    = htmlspecialchars($canonical,                ENT_QUOTES, 'UTF-8');

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> – <?= $siteTitleEsc ?></title>
    <meta name="description" content="<?= $pageDesc ?>">
<?php if ($pageKeywords !== ''): ?>
    <meta name="keywords" content="<?= $pageKeywords ?>">
<?php endif; ?>
    <!-- Open Graph -->
    <meta property="og:title"       content="<?= $pageTitle ?>">
    <meta property="og:description" content="<?= $pageDesc ?>">
    <meta property="og:url"         content="<?= $canonicalEsc ?>">
    <meta property="og:type"        content="website">
    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary">
    <meta name="twitter:title"       content="<?= $pageTitle ?>">
    <meta name="twitter:description" content="<?= $pageDesc ?>">
    <!-- Canonical -->
    <link rel="canonical" href="<?= $canonicalEsc ?>">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, sans-serif; color: #222; background: #fff; }
        .site-header { background: #1a1a2e; color: #fff; padding: 1rem 2rem; }
        .site-header a { color: #e2b96f; text-decoration: none; font-weight: bold; font-size: 1.25rem; }
        .site-content { max-width: 900px; margin: 2rem auto; padding: 0 1.5rem; }
        .site-footer { text-align: center; padding: 2rem; color: #999; font-size: .85rem; margin-top: 3rem; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <header class="site-header">
        <a href="/"><?= $siteTitleEsc ?></a>
    </header>
    <main class="site-content">
        <?= $page['html'] /* HTML stored in DB/file – admin is responsible for safe content */ ?>
    </main>
    <footer class="site-footer">
        &copy; <?= date('Y') ?> <?= $siteTitleEsc ?>
    </footer>
</body>
</html>
