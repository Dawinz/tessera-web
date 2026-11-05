<?php
/**
 * POST /accounts/api/console/compose_entry_update.php — edit ledger line (A-P0-02).
 * Body JSON: { id, category, particular, amount, currency?, detail?, type? }
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

$result = tessera_accounts_compose_update_entry($db, $username, $json);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Update failed'), 422);
}

$item = $result['item'] ?? null;
$route = '';
$bus = '';
$date = '';
$point = '';
if (is_array($item)) {
	$id = (int) ($item['id'] ?? 0);
	$q = $db->query("SELECT route_name, bus, trip_date, point FROM vehicle_account WHERE id={$id} LIMIT 1");
	$row = $q ? $q->fetch_assoc() : null;
	if ($row) {
		$route = (string) ($row['route_name'] ?? '');
		$bus = (string) ($row['bus'] ?? '');
		$date = (string) ($row['trip_date'] ?? '');
		$point = (string) ($row['point'] ?? '');
	}
}

$ledger = ($route !== '' && $bus !== '' && $date !== '')
	? tessera_accounts_compose_ledger($db, $route, $bus, $date, $point)
	: ['summary' => null];
$ctx = ($route !== '' && $bus !== '' && $date !== '')
	? tessera_accounts_compose_context($db, $username, $route, $bus, $date)
	: ['context' => null];

tessera_console_ok([
	'item' => $item,
	'summary' => $ledger['summary'],
	'context' => $ctx['context'] ?? null,
]);
