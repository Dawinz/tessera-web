<?php
/**
 * Accounts SPA fragment API (JSON). Used by app.php + tessera-accounts-spa.js
 */
require_once __DIR__ . '/includes/auth_bootstrap.php';
require_once __DIR__ . '/includes/tessera_accounts_spa.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Tessera-Accounts-Spa: 1');

$page = isset($_GET['page']) ? (string) $_GET['page'] : 'manifest.php';
$query = $_GET;
unset($query['page'], $query['spa']);

try {
	$payload = tessera_accounts_spa_capture($page, $query);
	echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
	http_response_code(400);
	echo json_encode([
		'error' => 'Could not load page fragment.',
		'detail' => $e->getMessage(),
	], JSON_UNESCAPED_UNICODE);
}
