<?php
/**
 * Optional cron/webhook endpoint to force alert scan.
 * Protect with CONTROL_CRON_TOKEN env (query ?token=…).
 */
require_once __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

$expected = (string) (getenv('CONTROL_CRON_TOKEN') ?: '');
$got = (string) ($_GET['token'] ?? $_SERVER['HTTP_X_CONTROL_TOKEN'] ?? '');
if ($expected === '' || !hash_equals($expected, $got)) {
	http_response_code(403);
	echo json_encode(['ok' => false, 'error' => 'forbidden']);
	exit;
}

$ids = control_alerts_scan($db ?? null, !empty($control_db_ok), true);

/* Also drain operator notification outbox (SMS / WhatsApp / push) */
$notify = ['sent' => 0, 'failed' => 0];
$msgInc = '/var/www/html/tickets/includes/tessera_messaging.php';
if (is_file($msgInc) && !empty($control_db_ok) && isset($db) && $db instanceof mysqli) {
	require_once $msgInc;
	if (function_exists('tessera_notify_drain')) {
		$notify = tessera_notify_drain($db, 40);
	}
}

echo json_encode(['ok' => true, 'emitted' => count($ids), 'ids' => $ids, 'notify' => $notify]);
