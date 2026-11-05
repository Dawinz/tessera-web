<?php
/**
 * POST /accounts/api/console/daily_create.php — add daily expense (A-P0-03).
 * Body JSON: { route, category, particular, date, amount, account?, vehicle?, detail?, item?, quantity? }
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_daily.php';

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

$result = tessera_accounts_daily_add($db, $username, $json);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Save failed'), 422);
}

$route = trim((string) ($json['route'] ?? ''));
$month = date('F');
$year = (int) date('Y');
$list = tessera_accounts_daily_list($db, $route, $month, $year);

tessera_console_ok([
	'item' => $result['item'] ?? null,
	'summary' => $list['summary'],
	'cash' => $list['cash'],
], [], 201);
