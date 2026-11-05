<?php
/**
 * GET /cargo/api/console/contact_lookup.php?q=
 * Phone autocomplete for modern register luggage (classic replace_no.php).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';

[$db] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$q = (string) ($_GET['q'] ?? '');
$limit = (int) ($_GET['limit'] ?? 10);
$items = tessera_cargo_contact_lookup($db, $q, $limit);

tessera_console_ok([
	'q' => preg_replace('/\D+/', '', $q) ?? '',
	'items' => $items,
]);
