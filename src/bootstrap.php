<?php
/**
 * Bootstrap – autoload all src/ classes without Composer.
 *
 * Call require once from any entry point (index.php, admin.php, install.php).
 */

$srcDir = __DIR__;

// Load Storage interface before drivers that implement it
require_once $srcDir . '/Storage/StorageInterface.php';
require_once $srcDir . '/Storage/MysqlDriver.php';
require_once $srcDir . '/Storage/TxtDriver.php';

require_once $srcDir . '/Config.php';
require_once $srcDir . '/Database.php';
require_once $srcDir . '/Auth.php';
require_once $srcDir . '/Csrf.php';
