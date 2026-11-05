<?php
/**
 * POST /accounts/api/console/withdraw_create.php — add cash deposit (A-P0-04).
 * Body JSON: { route, date, amount, authorizer }
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_withdraw.php';

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

$result = tessera_accounts_withdraw_add($db, $username, $json);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Save failed'), 422);
}

$route = trim((string) ($json['route'] ?? ''));
$list = tessera_accounts_withdraw_list($db, $route, '', 1, 25);

tessera_console_ok([
	'item' => $result['item'] ?? null,
	'summary' => $list['summary'],
], [], 201);
