<?php
/**
 * Cargo -> ticketing DB bridge (read/write tickets from cargo ops).
 */
require_once __DIR__ . '/includes/sr_env.php';
$db = sr_mysqli(getenv('SR_DB_NAME') ?: 'ticket');
if (!$db) {
	http_response_code(503);
	die('Ticketing database unavailable. Start MariaDB in XAMPP, then refresh.');
}
