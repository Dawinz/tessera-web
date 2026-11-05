<?php
/**
 * POST /accounts/api/console/daily_delete.php — delete daily expense (A-P0-03).
 * Body JSON: { id, route?, month?, year? }
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_daily.php';

[$db] = tessera_accounts_console_boot('POST, OPTIONS');
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
$month = trim((string) ($json['month'] ?? date('F')));
$year = (int) ($json['year'] ?? date('Y'));

if ($id > 0 && $route === '') {
	$q = $db->query("SELECT route_name, month, year FROM office_expenses WHERE id={$id} LIMIT 1");
	$row = $q ? $q->fetch_assoc() : null;
	if ($row) {
		$route = (string) ($row['route_name'] ?? '');
		$month = (string) ($row['month'] ?? $month);
		$year = (int) ($row['year'] ?? $year);
	}
}

$result = tessera_accounts_daily_delete($db, $id);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Delete failed'), 422);
}

$list = $route !== ''
	? tessera_accounts_daily_list($db, $route, $month, $year)
	: ['summary' => null, 'cash' => null, 'items' => []];

tessera_console_ok([
	'items' => $list['items'] ?? [],
	'summary' => $list['summary'],
	'cash' => $list['cash'] ?? null,
]);
