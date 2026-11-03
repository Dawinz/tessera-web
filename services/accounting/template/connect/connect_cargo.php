<?php
/**
 * Accounting -> cargo DB bridge.
 */
require_once dirname(__DIR__) . '/includes/sr_env.php';
$db_up = sr_mysqli(getenv('SR_CARGO_DB') ?: 'cargo');
if (!$db_up) {
	http_response_code(503);
	die('Cargo database unavailable. Start MariaDB in XAMPP, then refresh.');
}
