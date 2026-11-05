<?php
/**
 * POST /cargo/api/console/load_item.php — load parcel onto manifest (C-P0-07).
 * Body JSON: { code, vehicle|bus, date, qty, to_be, paid }
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_load.php';

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

$vehicle = (string) ($json['vehicle'] ?? $json['bus'] ?? '');
$result = tessera_cargo_load_commit($db, $username, [
	'code' => (string) ($json['code'] ?? ''),
	'vehicle' => $vehicle,
	'date' => (string) ($json['date'] ?? ''),
	'qty' => $json['qty'] ?? 0,
	'to_be' => $json['to_be'] ?? 0,
	'paid' => $json['paid'] ?? 0,
]);

if (empty($result['ok'])) {
	tessera_console_error(
		'validation_error',
		(string) ($result['error'] ?? 'Load failed'),
		422,
		isset($result['code']) ? ['fields' => ['code' => (string) $result['code']]] : []
	);
}

tessera_console_ok([
	'item' => $result['item'] ?? null,
], [], 201);
