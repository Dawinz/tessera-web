<?php
/**
 * POST /cargo/api/console/register_luggage.php — register parcels (C-P0-01).
 * Body JSON: { sender_name, sender_code, sender_phone, receivers: [...] }
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';

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

$result = tessera_cargo_register_parcels($db, $username, [
	'sender_name' => (string) ($json['sender_name'] ?? ''),
	'sender_code' => (string) ($json['sender_code'] ?? ''),
	'sender_phone' => (string) ($json['sender_phone'] ?? ''),
	'receivers' => is_array($json['receivers'] ?? null) ? $json['receivers'] : [],
]);

if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Register failed'), 422);
}

tessera_console_ok([
	'receipts' => $result['receipts'] ?? [],
	'summary' => $result['summary'] ?? null,
]);
