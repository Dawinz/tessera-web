<?php
/**
 * Single JSON payload for Tickets Home dashboard (KPIs + chart series).
 * Legacy endpoint — same data as /tickets/api/console/home.php.
 */
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/tessera_home_dashboard.php';
include 'connect.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo json_encode(['ok' => false, 'error' => 'unauthorized']);
	exit;
}

$payload = tessera_home_dashboard_payload($db, (string) ($_GET['route'] ?? ''));
$payload['ok'] = true;
echo json_encode($payload, JSON_UNESCAPED_UNICODE);
