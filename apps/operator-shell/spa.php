<?php
/**
 * Unified operator SPA fragment API - routes to module capture.
 */
require_once __DIR__ . '/includes/shell_bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Tessera-Operator-Spa: 1');

$module = isset($_GET['module']) ? strtolower((string) $_GET['module']) : 'tickets';
if (!in_array($module, ['tickets', 'cargo', 'accounts'], true)) {
	http_response_code(400);
	echo json_encode(['error' => 'Invalid module.'], JSON_UNESCAPED_UNICODE);
	exit;
}

$page = isset($_GET['page']) ? (string) $_GET['page'] : tessera_operator_spa_default_page($module);
$query = $_GET;
unset($query['module'], $query['page'], $query['spa']);

try {
	$payload = tessera_operator_spa_capture($module, $page, $query);
	echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
	http_response_code(400);
	echo json_encode([
		'error' => 'Could not load page fragment.',
		'detail' => $e->getMessage(),
	], JSON_UNESCAPED_UNICODE);
}
