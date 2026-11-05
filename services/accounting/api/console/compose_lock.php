<?php
/**
 * POST /accounts/api/console/compose_lock.php — lock or unlock trip ledger (A-P0-02 gap).
 * Body JSON: { route, vehicle, date, action: lock|unlock, password? }
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

$route = trim((string) ($json['route'] ?? ''));
$bus = trim((string) ($json['vehicle'] ?? $json['bus'] ?? ''));
$date = trim((string) ($json['date'] ?? ''));
$action = trim((string) ($json['action'] ?? 'lock'));
$password = (string) ($json['password'] ?? '');

$result = tessera_accounts_compose_lock($db, $username, $route, $bus, $date, $action, $password);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Lock failed'), 422);
}

tessera_console_ok([
	'context' => $result['context'] ?? null,
	'points' => $result['points'] ?? [],
]);
