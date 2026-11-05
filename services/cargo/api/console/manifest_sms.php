<?php
/**
 * POST /cargo/api/console/manifest_sms.php
 * Body JSON: { id }
 * Send packed notifications for parcels on the manifest (classic send_bulk.php).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_manifests.php';

[$db, $username] = tessera_cargo_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);
if (!is_array($payload)) {
	tessera_console_error('validation_error', 'Invalid JSON body', 422);
}

$id = (int) ($payload['id'] ?? 0);
$result = tessera_cargo_manifests_send_sms($db, $username, $id);
if (empty($result['ok'])) {
	$code = str_contains((string) ($result['error'] ?? ''), 'already') ? 'conflict' : 'validation_error';
	$status = $code === 'conflict' ? 409 : 422;
	tessera_console_error($code, (string) ($result['error'] ?? 'SMS failed'), $status);
}

tessera_console_ok([
	'notified' => (int) ($result['notified'] ?? 0),
	'item' => $result['item'] ?? null,
]);
