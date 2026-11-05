<?php
/**
 * POST /accounts/api/console/compose_point_check.php — toggle point analysed/seen.
 * Body JSON: { route, vehicle, date, point }
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
$point = trim((string) ($json['point'] ?? ''));

$result = tessera_accounts_compose_point_check($db, $username, $route, $bus, $date, $point);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Point check failed'), 422);
}

tessera_console_ok([
	'context' => $result['context'] ?? null,
	'points' => $result['points'] ?? [],
]);
