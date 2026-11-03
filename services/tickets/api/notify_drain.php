<?php
/**
 * Drain notification outbox (SMS). Protect with CONTROL_CRON_TOKEN or staff session.
 */
header('Content-Type: application/json; charset=utf-8');
$token = (string) ($_GET['token'] ?? $_SERVER['HTTP_X_CONTROL_TOKEN'] ?? '');
$expected = (string) (getenv('CONTROL_CRON_TOKEN') ?: '');
$okToken = $expected !== '' && hash_equals($expected, $token);
if (!$okToken) {
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if (empty($_SESSION['username'])) {
		http_response_code(401);
		echo json_encode(['ok' => false, 'error' => 'unauthorized']);
		exit;
	}
}
require_once dirname(__DIR__) . '/connect.php';
require_once dirname(__DIR__) . '/includes/tessera_messaging.php';
$result = tessera_notify_drain($db, 30);
echo json_encode(['ok' => true] + $result);
