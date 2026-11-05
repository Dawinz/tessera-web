<?php
/**
 * POST /cargo/api/console/luggage_update.php — save luggage edits (C-P1-04).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_ops.php';

[$db, $username] = tessera_cargo_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);
if (!is_array($payload)) {
	tessera_console_error('validation_error', 'Invalid JSON body', 422);
}

$result = tessera_cargo_ops_luggage_update($db, $username, $payload);
if (empty($result['ok'])) {
	$status = (int) ($result['status'] ?? 422);
	$code = $status === 404 ? 'not_found' : ($status === 409 ? 'conflict' : 'validation_error');
	tessera_console_error($code, (string) ($result['error'] ?? 'Could not save'), $status);
}

tessera_console_ok(['item' => $result['item'] ?? null]);
