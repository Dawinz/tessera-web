<?php
/**
 * POST /accounts/api/console/compose_entry.php — add income or expense line (A-P0-02).
 * Body JSON: { type, route, vehicle, date, point, current_route?, category, particular, amount, currency?, detail? }
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

$result = tessera_accounts_compose_add_entry($db, $username, $json);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Save failed'), 422);
}

$route = trim((string) ($json['route'] ?? ''));
$bus = trim((string) ($json['vehicle'] ?? $json['bus'] ?? ''));
$date = trim((string) ($json['date'] ?? ''));
$point = (string) ($json['point'] ?? '');
$ledger = tessera_accounts_compose_ledger($db, $route, $bus, $date, $point);
$ctx = tessera_accounts_compose_context($db, $username, $route, $bus, $date);

tessera_console_ok([
	'item' => $result['item'] ?? null,
	'summary' => $ledger['summary'],
	'context' => $ctx['context'] ?? null,
], [], 201);
