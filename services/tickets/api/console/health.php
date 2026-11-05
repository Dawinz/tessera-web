<?php
/**
 * GET /tickets/api/console/health.php — console API liveness (no auth).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$dbOk = false;
$dbDetail = '';
try {
	$connect = dirname(__DIR__, 2) . '/connect.php';
	if (is_file($connect)) {
		require_once $connect;
		if (isset($db) && $db instanceof mysqli && !$db->connect_error) {
			$dbOk = (bool) @$db->query('SELECT 1');
		}
	}
	if (!$dbOk) {
		$dbDetail = 'unavailable';
	}
} catch (Throwable $e) {
	$dbDetail = 'unavailable';
}

tessera_console_ok([
	'service' => 'tessera-console-api',
	'db' => $dbOk ? 'ok' : 'error',
	'detail' => $dbDetail,
	'endpoints' => [
		'GET health.php',
		'GET me.php',
		'GET sample_list.php',
		'GET schedule_filters.php',
		'GET schedule_events.php',
	],
], [], $dbOk ? 200 : 503);
