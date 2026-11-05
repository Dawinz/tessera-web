<?php
/**
 * POST /cargo/api/console/load_remove.php
 * Body JSON: { id }
 * Remove a loaded line from the vehicle manifest (classic delete_manifest.php).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_load.php';

[$db, $username] = tessera_cargo_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);
if (!is_array($payload)) {
	tessera_console_error('validation_error', 'Invalid JSON body', 422);
}

$id = (int) ($payload['id'] ?? 0);
$result = tessera_cargo_load_remove($db, $username, $id);
if (empty($result['ok'])) {
	$msg = (string) ($result['error'] ?? 'Could not remove line');
	$code = str_contains($msg, 'locked') ? 'conflict' : 'validation_error';
	$status = $code === 'conflict' ? 409 : ($msg === 'Loaded line not found' || $msg === 'Parcel not found' ? 404 : 422);
	tessera_console_error($code, $msg, $status);
}

tessera_console_ok([
	'id' => (int) ($result['id'] ?? $id),
]);
