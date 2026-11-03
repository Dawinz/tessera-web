<?php
/**
 * Cargo SPA fragment API (JSON).
 */
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once dirname(__DIR__) . '/tickets/includes/tessera_operator_spa.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Tessera-Operator-Spa: 1');

$page = isset($_GET['page']) ? (string) $_GET['page'] : 'home.php';
$query = $_GET;
unset($query['page'], $query['spa'], $query['module']);

try {
	$payload = tessera_operator_spa_capture('cargo', $page, $query);
	echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
	http_response_code(400);
	echo json_encode([
		'error' => 'Could not load page fragment.',
		'detail' => $e->getMessage(),
	], JSON_UNESCAPED_UNICODE);
}
