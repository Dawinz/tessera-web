<?php
/**
 * GET  /accounts/api/console/compose_transfer.php — other trips that can receive lines.
 * POST /accounts/api/console/compose_transfer.php — move selected ledger lines.
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_compose.php';

[$db, $username] = tessera_accounts_console_boot('GET, POST, OPTIONS');
$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

if ($method === 'GET') {
	$route = trim((string) ($_GET['route'] ?? $_GET['route_name'] ?? ''));
	$bus = trim((string) ($_GET['vehicle'] ?? $_GET['bus'] ?? ''));
	$date = trim((string) ($_GET['date'] ?? $_GET['trip_date'] ?? ''));
	tessera_console_ok([
		'targets' => tessera_accounts_compose_transfer_targets($db, $username, $route, $bus, $date),
	]);
}

if ($method !== 'POST') {
	tessera_console_error('method_not_allowed', 'Method not allowed', 405);
}

$raw = file_get_contents('php://input');
$json = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($json)) {
	$json = $_POST;
}
if (!is_array($json)) {
	tessera_console_error('validation_error', 'Expected JSON body', 422);
}

$ids = $json['ids'] ?? [];
if (is_string($ids)) {
	$ids = preg_split('/\s*,\s*/', $ids) ?: [];
}
if (!is_array($ids)) {
	$ids = [];
}

$result = tessera_accounts_compose_transfer(
	$db,
	$username,
	(string) ($json['route'] ?? ''),
	(string) ($json['vehicle'] ?? $json['bus'] ?? ''),
	(string) ($json['date'] ?? ''),
	(string) ($json['target_route'] ?? ''),
	(string) ($json['target_vehicle'] ?? $json['target_bus'] ?? ''),
	(string) ($json['target_date'] ?? ''),
	$ids
);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Transfer failed'), 422);
}

tessera_console_ok([
	'moved' => (int) ($result['moved'] ?? 0),
	'context' => $result['context'] ?? null,
]);
