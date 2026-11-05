<?php
/**
 * POST /accounts/api/console/daily_update.php — edit daily expense (A-P0-03).
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

$result = tessera_accounts_daily_update($db, $json);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Update failed'), 422);
}

$item = $result['item'] ?? null;
$route = is_array($item) ? (string) ($item['route'] ?? '') : '';
$month = is_array($item) ? (string) ($item['month'] ?? date('F')) : date('F');
$year = is_array($item) ? (int) ($item['year'] ?? date('Y')) : (int) date('Y');
$list = $route !== '' ? tessera_accounts_daily_list($db, $route, $month, $year) : ['summary' => null, 'cash' => null];

tessera_console_ok([
	'item' => $item,
	'summary' => $list['summary'],
	'cash' => $list['cash'] ?? null,
]);
