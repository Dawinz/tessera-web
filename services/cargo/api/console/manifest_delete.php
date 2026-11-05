<?php
/**
 * POST /cargo/api/console/manifest_delete.php
 * Body JSON: { id }
 * Deletes an empty vehicle manifest row (classic delete_vehicle_name.php).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_manifests.php';

[$db] = tessera_cargo_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);
if (!is_array($payload)) {
	tessera_console_error('validation_error', 'Invalid JSON body', 422);
}

$id = (int) ($payload['id'] ?? 0);
$result = tessera_cargo_manifests_delete($db, $id);
if (empty($result['ok'])) {
	$msg = (string) ($result['error'] ?? 'Could not delete manifest');
	$code = str_contains($msg, 'loaded') || str_contains($msg, 'cannot') ? 'conflict' : 'validation_error';
	$status = $code === 'conflict' ? 409 : ($msg === 'Manifest not found' ? 404 : 422);
	tessera_console_error($code, $msg, $status);
}

tessera_console_ok([
	'id' => (int) ($result['id'] ?? $id),
]);
