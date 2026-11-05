<?php
/**
 * POST /cargo/api/console/manifest_update.php — update vehicle manifest (C-P0-04).
 * Body JSON: { id, vehicle?, driver?, conductor?, route?, date? }
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_manifests.php';

[$db, $username] = tessera_cargo_console_boot('POST, OPTIONS');
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
$result = tessera_cargo_manifests_update($db, $username, $id, [
	'vehicle' => (string) ($json['vehicle'] ?? ''),
	'driver' => (string) ($json['driver'] ?? ''),
	'conductor' => (string) ($json['conductor'] ?? ''),
	'route' => (string) ($json['route'] ?? ''),
	'date' => (string) ($json['date'] ?? ''),
]);

if (empty($result['ok'])) {
	$code = stripos((string) ($result['error'] ?? ''), 'not found') !== false ? 404 : 422;
	$errCode = $code === 404 ? 'not_found' : 'validation_error';
	tessera_console_error($errCode, (string) ($result['error'] ?? 'Update failed'), $code);
}

tessera_console_ok(['item' => $result['item'] ?? null]);
