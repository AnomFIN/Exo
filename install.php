<?php
declare(strict_types=1);

/**
 * install.php – Web-based installer.
 * Deletes / moves install.php after use is NOT required; instead a lock file
 * is created so the installer refuses to re-run.
 */

define('LOCK_FILE',  __DIR__ . '/storage/install.lock');
define('ENV_FILE',   __DIR__ . '/.env');

require_once __DIR__ . '/src/bootstrap.php';

Auth::start();

// ── CSRF bootstrap ────────────────────────────────────────────────────────────
$csrfToken = Csrf::token();

// ── Already installed? ────────────────────────────────────────────────────────
$isInstalled = is_file(LOCK_FILE);

// ── Handle POST ───────────────────────────────────────────────────────────────
$errors  = [];
$success = false;
$envContents = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isInstalled) {
    Csrf::requireValid();

    $dbHost  = trim($_POST['db_host']  ?? 'localhost');
    $dbPort  = trim($_POST['db_port']  ?? '3306');
    $dbName  = trim($_POST['db_name']  ?? '');
    $dbUser  = trim($_POST['db_user']  ?? '');
    $dbPass  = $_POST['db_pass']       ?? '';
    $adminU  = trim($_POST['admin_user'] ?? '');
    $adminP  = $_POST['admin_pass']      ?? '';
    $adminP2 = $_POST['admin_pass2']     ?? '';
    $appName = trim($_POST['app_name'] ?? 'My Site');
    $appUrl  = rtrim(trim($_POST['app_url'] ?? ''), '/');

    // Validate
    if ($dbName === '')  $errors[] = 'Database name is required.';
    if ($dbUser === '')  $errors[] = 'Database user is required.';
    if ($adminU === '')  $errors[] = 'Admin username is required.';
    if (strlen($adminP) < 8) $errors[] = 'Admin password must be at least 8 characters.';
    if ($adminP !== $adminP2) $errors[] = 'Admin passwords do not match.';

    if (empty($errors)) {
        // Test DB connection
        try {
            $pdo = Database::connect($dbHost, $dbName, $dbUser, $dbPass, $dbPort);
        } catch (PDOException $e) {
            $errors[] = 'Database connection failed: ' . $e->getMessage();
        }
    }

    if (empty($errors)) {
        // Create tables
        try {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS settings (
                    setting_key   VARCHAR(100) NOT NULL PRIMARY KEY,
                    setting_value TEXT NOT NULL DEFAULT ''
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

                CREATE TABLE IF NOT EXISTS admins (
                    id            INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    username      VARCHAR(100) NOT NULL UNIQUE,
                    password_hash VARCHAR(255) NOT NULL,
                    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

                CREATE TABLE IF NOT EXISTS pages (
                    slug             VARCHAR(200) NOT NULL PRIMARY KEY,
                    title            VARCHAR(500) NOT NULL DEFAULT '',
                    html             MEDIUMTEXT   NOT NULL DEFAULT '',
                    meta_description VARCHAR(500) NOT NULL DEFAULT '',
                    meta_keywords    VARCHAR(500) NOT NULL DEFAULT '',
                    updated_at       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");

            // Insert default admin
            $hash = password_hash($adminP, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare(
                'INSERT INTO admins (username, password_hash) VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)'
            );
            $stmt->execute([$adminU, $hash]);

            // Insert default settings
            $driver = new MysqlDriver($pdo);
            $driver->setSetting('site_title',       $appName);
            $driver->setSetting('site_description', '');

            // Insert a default home page
            $driver->savePage([
                'slug'             => 'home',
                'title'            => 'Welcome',
                'html'             => '<h1>Welcome to ' . htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') . '</h1><p>Edit this page in the admin panel.</p>',
                'meta_description' => 'Welcome to ' . $appName,
                'meta_keywords'    => '',
            ]);

        } catch (PDOException $e) {
            $errors[] = 'Failed to create tables: ' . $e->getMessage();
        }
    }

    if (empty($errors)) {
        // Build .env contents
        $envContents = "DB_HOST={$dbHost}\n"
            . "DB_PORT={$dbPort}\n"
            . "DB_NAME={$dbName}\n"
            . "DB_USER={$dbUser}\n"
            . "DB_PASS={$dbPass}\n"
            . "APP_NAME={$appName}\n"
            . "APP_URL={$appUrl}\n";

        // Write .env file
        $envWritten = false;
        if (is_writable(dirname(ENV_FILE))) {
            if (file_put_contents(ENV_FILE, $envContents) !== false) {
                $envWritten = true;
            }
        }

        // Write lock file
        if (!is_dir(dirname(LOCK_FILE))) {
            mkdir(dirname(LOCK_FILE), 0755, true);
        }
        file_put_contents(LOCK_FILE, date('c'));

        $success = true;
        if (!$envWritten) {
            // Show env contents for manual copy-paste – do NOT show DB pass in page
            // We pass a flag and let the template handle display
        }
        // Clear DB pass from display
        $dbPass  = '';
        $adminP  = '';
        $adminP2 = '';

        $isInstalled = true; // prevent re-render of form
    }
}

// ── HTML ──────────────────────────────────────────────────────────────────────
$envWritten = $envWritten ?? false;
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exo Installer</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, sans-serif; background: #f4f4f8; color: #222; }
        .wrap { max-width: 620px; margin: 3rem auto; background: #fff; border-radius: 8px;
                box-shadow: 0 2px 12px rgba(0,0,0,.1); padding: 2.5rem; }
        h1 { margin-top: 0; color: #1a1a2e; }
        h2 { color: #444; font-size: 1.1rem; margin-top: 1.8rem; border-bottom: 1px solid #eee; padding-bottom: .4rem; }
        label { display: block; margin-top: 1rem; font-weight: 600; font-size: .9rem; }
        input[type=text], input[type=password], input[type=url], input[type=number] {
            display: block; width: 100%; margin-top: .3rem; padding: .5rem .75rem;
            border: 1px solid #ccc; border-radius: 4px; font-size: 1rem; }
        input:focus { outline: 2px solid #e2b96f; border-color: #e2b96f; }
        .btn { display: inline-block; margin-top: 1.5rem; padding: .65rem 1.5rem;
               background: #1a1a2e; color: #fff; border: none; border-radius: 4px;
               font-size: 1rem; cursor: pointer; }
        .btn:hover { background: #2a2a4e; }
        .errors { background: #ffe0e0; border: 1px solid #f99; border-radius: 4px;
                  padding: .75rem 1rem; margin-bottom: 1rem; }
        .errors li { margin: .3rem 0; font-size: .9rem; }
        .success { background: #e0ffe5; border: 1px solid #7c7; border-radius: 4px; padding: 1rem; }
        .notice  { background: #fff8e0; border: 1px solid #cc9; border-radius: 4px;
                   padding: .75rem 1rem; margin-top: 1rem; font-size: .9rem; }
        pre { background: #f4f4f8; border: 1px solid #ccc; border-radius: 4px;
              padding: .75rem; font-size: .85rem; overflow-x: auto; white-space: pre-wrap; word-break: break-all; }
        .locked { text-align: center; padding: 2rem; }
        .locked h2 { border: none; }
    </style>
</head>
<body>
<div class="wrap">
    <h1>⚙️ Exo Installer</h1>

<?php if ($isInstalled && !$success): ?>
    <div class="locked">
        <h2>Already Installed</h2>
        <p>The installer has already been run. To reinstall, delete <code>storage/install.lock</code> and reload this page.</p>
        <p><a href="index.php">← Back to site</a> &nbsp;|&nbsp; <a href="admin.php">Admin Panel</a></p>
    </div>
<?php elseif ($success): ?>
    <div class="success">
        <strong>✅ Installation successful!</strong><br>
        Your site is ready. <a href="index.php">View site</a> | <a href="admin.php">Admin panel</a>
    </div>
<?php if (!$envWritten): ?>
    <div class="notice">
        <strong>⚠️ Could not write <code>.env</code> automatically.</strong><br>
        Create a file named <code>.env</code> in your web root with the following contents:
        <pre><?= htmlspecialchars(
            preg_replace('/^\s*DB_PASS=.*/m', 'DB_PASS=YOUR_DB_PASSWORD_HERE', $envContents),
            ENT_QUOTES, 'UTF-8'
        ) ?></pre>
        Replace <code>YOUR_DB_PASSWORD_HERE</code> with the actual database password you entered.
    </div>
<?php endif; ?>
<?php else: ?>
    <?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <form method="post" action="install.php" autocomplete="off">
        <?= Csrf::field() ?>

        <h2>Database Connection</h2>
        <label for="db_host">DB Host</label>
        <input type="text" id="db_host" name="db_host" value="localhost" required>
        <label for="db_port">DB Port</label>
        <input type="number" id="db_port" name="db_port" value="3306" min="1" max="65535" required>
        <label for="db_name">DB Name</label>
        <input type="text" id="db_name" name="db_name" value="" required>
        <label for="db_user">DB User</label>
        <input type="text" id="db_user" name="db_user" value="" required autocomplete="off">
        <label for="db_pass">DB Password</label>
        <input type="password" id="db_pass" name="db_pass" value="" autocomplete="new-password">

        <h2>Admin Account</h2>
        <label for="admin_user">Admin Username</label>
        <input type="text" id="admin_user" name="admin_user" value="" required autocomplete="off">
        <label for="admin_pass">Admin Password <small>(min 8 chars)</small></label>
        <input type="password" id="admin_pass" name="admin_pass" value="" required autocomplete="new-password" minlength="8">
        <label for="admin_pass2">Confirm Admin Password</label>
        <input type="password" id="admin_pass2" name="admin_pass2" value="" required autocomplete="new-password" minlength="8">

        <h2>Site Settings</h2>
        <label for="app_name">Site Name</label>
        <input type="text" id="app_name" name="app_name" value="My Site" required>
        <label for="app_url">Site URL <small>(optional, e.g. https://example.com)</small></label>
        <input type="url" id="app_url" name="app_url" value="" placeholder="https://example.com">

        <button type="submit" class="btn">Run Installation</button>
    </form>
<?php endif; ?>
</div>
</body>
</html>
