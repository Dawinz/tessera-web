<?php
/**
 * Cargo -> accounting DB bridge.
 */
require_once __DIR__ . '/includes/sr_env.php';
$db_account = sr_mysqli(getenv('SR_ACCOUNT_DB') ?: 'account');
if (!$db_account) {
	http_response_code(503);
	die('Accounting database unavailable. Start MariaDB in XAMPP, then refresh.');
}
