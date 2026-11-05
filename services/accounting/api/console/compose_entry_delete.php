<?php
/**
 * POST /accounts/api/console/compose_entry_delete.php — delete ledger line (A-P0-02).
 * Body JSON: { id, route?, vehicle?, date?, point? }
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_compose.php';

[$db, $username] = tessera_accounts_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');

$raw = file_get_contents('php://input');
$json = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($json)) {
	$json = $_POST;
}
if (!is_array($json)) {
	tessera_console_error('validation_error', 'Expected JSON body', 422);
}

$id = (int) ($json['id'] ?? 0);
$route = trim((string) ($json['route'] ?? ''));
$bus = trim((string) ($json['vehicle'] ?? $json['bus'] ?? ''));
$date = trim((string) ($json['date'] ?? ''));
$point = (string) ($json['point'] ?? '');

/* Capture trip keys before delete if not provided. */
if ($id > 0 && ($route === '' || $bus === '' || $date === '')) {
	$q = $db->query("SELECT route_name, bus, trip_date, point FROM vehicle_account WHERE id={$id} LIMIT 1");
	$row = $q ? $q->fetch_assoc() : null;
	if ($row) {
		$route = $route !== '' ? $route : (string) ($row['route_name'] ?? '');
		$bus = $bus !== '' ? $bus : (string) ($row['bus'] ?? '');
		$date = $date !== '' ? $date : (string) ($row['trip_date'] ?? '');
		$point = $point !== '' ? $point : (string) ($row['point'] ?? '');
	}
}

$result = tessera_accounts_compose_delete_entry($db, $username, $id);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Delete failed'), 422);
}

$ledger = ($route !== '' && $bus !== '' && $date !== '')
	? tessera_accounts_compose_ledger($db, $route, $bus, $date, $point)
	: ['summary' => null, 'items' => []];
$ctx = ($route !== '' && $bus !== '' && $date !== '')
	? tessera_accounts_compose_context($db, $username, $route, $bus, $date)
	: ['context' => null];

tessera_console_ok([
	'summary' => $ledger['summary'],
	'context' => $ctx['context'] ?? null,
	'items' => $ledger['items'] ?? [],
]);
