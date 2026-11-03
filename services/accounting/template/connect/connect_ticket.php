<?php
/**
 * Accounting -> ticketing DB bridge.
 */
require_once dirname(__DIR__) . '/includes/sr_env.php';
$db_ticket = sr_mysqli(getenv('SR_DB_NAME') ?: 'ticket');
if (!$db_ticket) {
	http_response_code(503);
	die('Ticketing database unavailable. Start MariaDB in XAMPP, then refresh.');
}
