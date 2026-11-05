<?php
/**
 * GET /cargo/api/console/load_lookup.php — authenticate luggage code for load (C-P0-07).
 * Query: code, bus, date
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_load.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$result = tessera_cargo_load_lookup(
	$db,
	(string) ($_GET['code'] ?? ''),
	(string) ($_GET['bus'] ?? ''),
	(string) ($_GET['date'] ?? '')
);

if (empty($result['ok'])) {
	$code = stripos((string) ($result['error'] ?? ''), 'not found') !== false ? 404 : 422;
	tessera_console_error(
		$code === 404 ? 'not_found' : 'validation_error',
		(string) ($result['error'] ?? 'Lookup failed'),
		$code
	);
}

tessera_console_ok([
	'parcel' => $result['parcel'],
]);
