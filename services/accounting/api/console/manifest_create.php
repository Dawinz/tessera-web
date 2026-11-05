<?php
/**
 * POST /accounts/api/console/manifest_create.php — register accounts manifest (A-P0-01).
 * Body JSON: { route, date, vehicle, vehicle_type? }
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_manifests.php';

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

$result = tessera_accounts_manifests_create($db, $username, [
	'route' => (string) ($json['route'] ?? ''),
	'date' => (string) ($json['date'] ?? ''),
	'vehicle' => (string) ($json['vehicle'] ?? ''),
	'vehicle_type' => (string) ($json['vehicle_type'] ?? 'Bus'),
]);

if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Create failed'), 422);
}

tessera_console_ok(['item' => $result['item'] ?? null], [], 201);
