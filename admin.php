<?php
declare(strict_types=1);

/**
 * admin.php – Admin panel (requires MySQL).
 * Session-based login, CSRF protection, prepared statements throughout.
 */

require_once __DIR__ . '/src/bootstrap.php';

Config::load(__DIR__ . '/.env');

Auth::start();

// ── Storage (MySQL required for admin) ────────────────────────────────────────
function adminStorage(): MysqlDriver
{
    try {
        $pdo = Database::connectFromConfig();
        return new MysqlDriver($pdo);
    } catch (Throwable $e) {
        http_response_code(503);
        exit('<p style="font-family:sans-serif;padding:2rem">Admin panel requires MySQL. '
            . 'Please configure <code>.env</code> or run <a href="install.php">install.php</a>.</p>');
    }
}

// ── Route / Action ────────────────────────────────────────────────────────────
$action = $_GET['action'] ?? 'login';
$msg    = '';
$error  = '';

// ── Logout ────────────────────────────────────────────────────────────────────
if ($action === 'logout') {
    Csrf::requireValid();
    Auth::logout();
    header('Location: admin.php');
    exit;
}

// ── Login ─────────────────────────────────────────────────────────────────────
if (!Auth::check()) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
        Csrf::requireValid();
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        // Need DB for login
        $storage = adminStorage();
        if (Auth::attempt($u, $p)) {
            header('Location: admin.php?action=dashboard');
            exit;
        }
        $error = 'Invalid username or password.';
    }
    renderLogin($error);
    exit;
}

// ── Authenticated area ────────────────────────────────────────────────────────
$storage = adminStorage();

switch ($action) {
    case 'settings':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::requireValid();
            $storage->setSetting('site_title',       trim($_POST['site_title']       ?? ''));
            $storage->setSetting('site_description', trim($_POST['site_description'] ?? ''));
            $msg = 'Settings saved.';
        }
        renderSettings($storage, $msg);
        break;

    case 'pages':
        renderPageList($storage);
        break;

    case 'page_edit':
        $slug = trim($_GET['slug'] ?? '');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::requireValid();
            $newSlug = preg_replace('/[^a-zA-Z0-9\-_]/', '', trim($_POST['slug'] ?? ''));
            if ($newSlug === '') {
                $error = 'Slug cannot be empty.';
            } else {
                // If slug changed, delete old record first
                if ($slug !== '' && $slug !== $newSlug) {
                    $storage->deletePage($slug);
                }
                $storage->savePage([
                    'slug'             => $newSlug,
                    'title'            => trim($_POST['title']            ?? ''),
                    'html'             => $_POST['html']                  ?? '',
                    'meta_description' => trim($_POST['meta_description'] ?? ''),
                    'meta_keywords'    => trim($_POST['meta_keywords']    ?? ''),
                ]);
                header('Location: admin.php?action=pages&msg=saved');
                exit;
            }
        }
        $page = ($slug !== '') ? $storage->getPage($slug) : null;
        renderPageEdit($page, $slug, $error);
        break;

    case 'page_delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::requireValid();
            $slug = preg_replace('/[^a-zA-Z0-9\-_]/', '', trim($_POST['slug'] ?? ''));
            $storage->deletePage($slug);
            header('Location: admin.php?action=pages&msg=deleted');
            exit;
        }
        header('Location: admin.php?action=pages');
        exit;

    default: // dashboard
        renderDashboard($storage);
        break;
}
exit;

// =============================================================================
// Render helpers
// =============================================================================

function layout_start(string $title): void
{
    $user = htmlspecialchars(Auth::user(), ENT_QUOTES, 'UTF-8');
    $csrf = Csrf::field();
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{$title} – Exo Admin</title>
        <style>
            *, *::before, *::after { box-sizing: border-box; }
            body { margin: 0; font-family: system-ui, sans-serif; background: #f4f4f8; color: #222; }
            .admin-header { background: #1a1a2e; color: #fff; padding: .75rem 1.5rem;
                            display: flex; align-items: center; justify-content: space-between; }
            .admin-header a { color: #e2b96f; text-decoration: none; font-weight: 600; }
            .admin-nav { display: flex; gap: 1.5rem; }
            .admin-nav a { color: #ccc; text-decoration: none; font-size: .9rem; }
            .admin-nav a:hover { color: #fff; }
            .wrap { max-width: 900px; margin: 2rem auto; padding: 0 1.5rem; }
            h1 { color: #1a1a2e; margin-top: 0; }
            .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08);
                    padding: 1.5rem; margin-bottom: 1.5rem; }
            label { display: block; font-weight: 600; font-size: .9rem; margin-top: 1rem; }
            input[type=text], input[type=password], textarea, select {
                display: block; width: 100%; margin-top: .3rem; padding: .5rem .75rem;
                border: 1px solid #ccc; border-radius: 4px; font-size: 1rem; }
            textarea { resize: vertical; }
            input:focus, textarea:focus { outline: 2px solid #e2b96f; border-color: #e2b96f; }
            .btn { display: inline-block; padding: .55rem 1.2rem; border: none; border-radius: 4px;
                   font-size: .95rem; cursor: pointer; text-decoration: none; }
            .btn-primary { background: #1a1a2e; color: #fff; }
            .btn-primary:hover { background: #2a2a4e; }
            .btn-danger  { background: #c0392b; color: #fff; }
            .btn-danger:hover  { background: #a93226; }
            .btn-sm { padding: .3rem .7rem; font-size: .85rem; }
            .msg   { background: #e0ffe5; border: 1px solid #7c7; border-radius: 4px; padding: .6rem 1rem; margin-bottom: 1rem; }
            .error { background: #ffe0e0; border: 1px solid #f99; border-radius: 4px; padding: .6rem 1rem; margin-bottom: 1rem; }
            table { width: 100%; border-collapse: collapse; }
            th, td { text-align: left; padding: .6rem .75rem; border-bottom: 1px solid #eee; font-size: .9rem; }
            th { background: #f4f4f8; font-weight: 600; }
            .actions { display: flex; gap: .5rem; align-items: center; }
        </style>
    </head>
    <body>
    <header class="admin-header">
        <a href="admin.php?action=dashboard">⚙️ Exo Admin</a>
        <nav class="admin-nav">
            <a href="admin.php?action=dashboard">Dashboard</a>
            <a href="admin.php?action=pages">Pages</a>
            <a href="admin.php?action=settings">Settings</a>
            <a href="index.php" target="_blank">View Site ↗</a>
        </nav>
        <form method="post" action="admin.php?action=logout" style="margin:0">
            {$csrf}
            <button type="submit" class="btn btn-sm" style="background:#c0392b;color:#fff">Logout ({$user})</button>
        </form>
    </header>
    <div class="wrap">
    HTML;
}

function layout_end(): void
{
    echo '</div></body></html>';
}

function renderLogin(string $error = ''): void
{
    $csrf = Csrf::field();
    $err  = $error !== '' ? '<p class="error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>' : '';
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login – Exo</title>
        <style>
            *, *::before, *::after { box-sizing: border-box; }
            body { margin: 0; font-family: system-ui, sans-serif; background: #f4f4f8; display: flex;
                   align-items: center; justify-content: center; min-height: 100vh; }
            .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,.1);
                    padding: 2.5rem; width: 100%; max-width: 380px; }
            h1 { margin-top: 0; color: #1a1a2e; font-size: 1.5rem; }
            label { display: block; font-weight: 600; font-size: .9rem; margin-top: 1rem; }
            input { display: block; width: 100%; margin-top: .3rem; padding: .5rem .75rem;
                    border: 1px solid #ccc; border-radius: 4px; font-size: 1rem; }
            input:focus { outline: 2px solid #e2b96f; border-color: #e2b96f; }
            .btn { display: block; width: 100%; margin-top: 1.5rem; padding: .65rem;
                   background: #1a1a2e; color: #fff; border: none; border-radius: 4px;
                   font-size: 1rem; cursor: pointer; }
            .btn:hover { background: #2a2a4e; }
            .error { background: #ffe0e0; border: 1px solid #f99; border-radius: 4px;
                     padding: .6rem 1rem; margin-bottom: 1rem; font-size: .9rem; }
        </style>
    </head>
    <body>
    <div class="card">
        <h1>⚙️ Admin Login</h1>
        {$err}
        <form method="post" action="admin.php?action=login" autocomplete="off">
            {$csrf}
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autofocus autocomplete="off">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
            <button type="submit" class="btn">Sign In</button>
        </form>
    </div>
    </body>
    </html>
    HTML;
}

function renderDashboard(MysqlDriver $storage): void
{
    layout_start('Dashboard');
    $pageCount = count($storage->listPages());
    $siteTitle = htmlspecialchars($storage->getSetting('site_title', 'My Site'), ENT_QUOTES, 'UTF-8');
    echo <<<HTML
    <h1>Dashboard</h1>
    <div class="card">
        <p><strong>Site:</strong> {$siteTitle}</p>
        <p><strong>Pages:</strong> {$pageCount}</p>
        <p>
            <a class="btn btn-primary" href="admin.php?action=page_edit">+ New Page</a>
            &nbsp;
            <a class="btn btn-primary" href="admin.php?action=settings">Settings</a>
        </p>
    </div>
    HTML;
    layout_end();
}

function renderSettings(MysqlDriver $storage, string $msg = ''): void
{
    layout_start('Settings');
    $csrf  = Csrf::field();
    $title = htmlspecialchars($storage->getSetting('site_title'), ENT_QUOTES, 'UTF-8');
    $desc  = htmlspecialchars($storage->getSetting('site_description'), ENT_QUOTES, 'UTF-8');
    $msgHtml = $msg !== '' ? '<p class="msg">' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</p>' : '';
    echo <<<HTML
    <h1>Site Settings</h1>
    {$msgHtml}
    <div class="card">
        <form method="post" action="admin.php?action=settings">
            {$csrf}
            <label for="site_title">Site Title</label>
            <input type="text" id="site_title" name="site_title" value="{$title}" required>
            <label for="site_description">Meta Description</label>
            <textarea id="site_description" name="site_description" rows="3">{$desc}</textarea>
            <button type="submit" class="btn btn-primary" style="margin-top:1rem">Save Settings</button>
        </form>
    </div>
    HTML;
    layout_end();
}

function renderPageList(MysqlDriver $storage): void
{
    layout_start('Pages');
    $pages   = $storage->listPages();
    $msgParam = htmlspecialchars($_GET['msg'] ?? '', ENT_QUOTES, 'UTF-8');
    $msgHtml  = '';
    if ($msgParam === 'saved')   $msgHtml = '<p class="msg">Page saved.</p>';
    if ($msgParam === 'deleted') $msgHtml = '<p class="msg">Page deleted.</p>';

    echo '<h1>Pages</h1>';
    echo $msgHtml;
    echo '<div class="card">';
    echo '<p><a class="btn btn-primary" href="admin.php?action=page_edit">+ New Page</a></p>';
    if (empty($pages)) {
        echo '<p>No pages yet.</p>';
    } else {
        echo '<table><thead><tr><th>Slug</th><th>Title</th><th>Actions</th></tr></thead><tbody>';
        foreach ($pages as $p) {
            $slug  = htmlspecialchars($p['slug'],  ENT_QUOTES, 'UTF-8');
            $title = htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8');
            $csrf  = Csrf::field();
            echo <<<HTML
            <tr>
                <td><code>{$slug}</code></td>
                <td>{$title}</td>
                <td class="actions">
                    <a class="btn btn-primary btn-sm" href="admin.php?action=page_edit&slug={$slug}">Edit</a>
                    <form method="post" action="admin.php?action=page_delete"
                          onsubmit="return confirm('Delete page \\'{$slug}\\'?')" style="display:inline">
                        {$csrf}
                        <input type="hidden" name="slug" value="{$slug}">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            HTML;
        }
        echo '</tbody></table>';
    }
    echo '</div>';
    layout_end();
}

function renderPageEdit(?array $page, string $currentSlug, string $error = ''): void
{
    $isNew = ($page === null && $currentSlug === '');
    layout_start($isNew ? 'New Page' : 'Edit Page');
    $csrf  = Csrf::field();
    $slug  = htmlspecialchars($page['slug']             ?? $currentSlug,  ENT_QUOTES, 'UTF-8');
    $title = htmlspecialchars($page['title']            ?? '',             ENT_QUOTES, 'UTF-8');
    $html  = htmlspecialchars($page['html']             ?? '',             ENT_QUOTES, 'UTF-8');
    $desc  = htmlspecialchars($page['meta_description'] ?? '',             ENT_QUOTES, 'UTF-8');
    $kw    = htmlspecialchars($page['meta_keywords']    ?? '',             ENT_QUOTES, 'UTF-8');
    $err   = $error !== '' ? '<p class="error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>' : '';
    $heading = $isNew ? 'New Page' : 'Edit Page';
    $action  = $isNew ? 'admin.php?action=page_edit' : "admin.php?action=page_edit&slug={$slug}";
    echo <<<HTML
    <h1>{$heading}</h1>
    {$err}
    <div class="card">
        <form method="post" action="{$action}">
            {$csrf}
            <label for="slug">Slug <small>(URL path, e.g. <code>about</code>)</small></label>
            <input type="text" id="slug" name="slug" value="{$slug}" required pattern="[a-zA-Z0-9\-_]+"
                   title="Only letters, numbers, hyphens and underscores">
            <label for="title">Page Title</label>
            <input type="text" id="title" name="title" value="{$title}" required>
            <label for="html">Page HTML Content</label>
            <textarea id="html" name="html" rows="12">{$html}</textarea>
            <label for="meta_description">Meta Description</label>
            <input type="text" id="meta_description" name="meta_description" value="{$desc}" maxlength="500">
            <label for="meta_keywords">Meta Keywords <small>(optional)</small></label>
            <input type="text" id="meta_keywords" name="meta_keywords" value="{$kw}" maxlength="500">
            <div style="margin-top:1.2rem; display:flex; gap:1rem; align-items:center">
                <button type="submit" class="btn btn-primary">Save Page</button>
                <a href="admin.php?action=pages">← Cancel</a>
            </div>
        </form>
    </div>
    HTML;
    layout_end();
}
