<?php
/**
 * POST /cargo/api/console/checkout_toggle.php — mark / unmark destination checkout (C-P0-05).
 * Body JSON: { id }
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_checkout.php';

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
$result = tessera_cargo_checkout_toggle($db, $username, $id);
if (empty($result['ok'])) {
	$code = stripos((string) ($result['error'] ?? ''), 'not found') !== false ? 404 : 422;
	tessera_console_error($code === 404 ? 'not_found' : 'validation_error', (string) ($result['error'] ?? 'Checkout failed'), $code);
}

tessera_console_ok([
	'item' => $result['item'] ?? null,
	'state' => $result['state'] ?? null,
]);
