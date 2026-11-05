<?php
/**
 * GET  /accounts/api/console/compose_pull.php — candidate source dates for pull.
 * POST /accounts/api/console/compose_pull.php — pull tickets + cargo into ledger.
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_compose.php';

$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
[$db, $username] = tessera_accounts_console_boot('GET, POST, OPTIONS');

if ($method === 'GET') {
	$currentRoute = trim((string) ($_GET['current_route'] ?? $_GET['route'] ?? ''));
	$bus = trim((string) ($_GET['vehicle'] ?? $_GET['bus'] ?? ''));
	tessera_console_ok([
		'dates' => tessera_accounts_compose_source_dates($currentRoute, $bus),
	]);
}

tessera_console_require_method('POST');

$raw = file_get_contents('php://input');
$json = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($json)) {
	$json = $_POST;
}
if (!is_array($json)) {
	tessera_console_error('validation_error', 'Expected JSON body', 422);
}

$route = trim((string) ($json['route'] ?? ''));
$currentRoute = trim((string) ($json['current_route'] ?? $route));
$bus = trim((string) ($json['vehicle'] ?? $json['bus'] ?? ''));
$date = trim((string) ($json['date'] ?? ''));
$sourceDate = trim((string) ($json['source_date'] ?? $json['trip_date'] ?? $date));
$point = trim((string) ($json['point'] ?? ''));

$result = tessera_accounts_compose_pull_tickets_cargo(
	$db,
	$username,
	$route,
	$currentRoute,
	$bus,
	$date,
	$sourceDate
);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Pull failed'), 422);
}

$ledger = ($route !== '' && $bus !== '' && $date !== '')
	? tessera_accounts_compose_ledger($db, $route, $bus, $date, $point)
	: ['summary' => null, 'items' => []];
$ctx = tessera_accounts_compose_context($db, $username, $route, $bus, $date);

tessera_console_ok([
	'created' => (int) ($result['created'] ?? 0),
	'summary' => $ledger['summary'] ?? null,
	'items' => $ledger['items'] ?? [],
	'context' => $ctx['context'] ?? null,
]);
