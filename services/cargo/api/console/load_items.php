<?php
/**
 * GET /cargo/api/console/load_items.php — lines already loaded on a bus/date (C-P0-07).
 * Query: bus, date, q, page, per_page
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_load.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$bus = trim((string) ($_GET['bus'] ?? ''));
$date = trim((string) ($_GET['date'] ?? ''));
if ($bus === '' || $date === '') {
	$ctx = tessera_cargo_load_resolve_context($db, $bus, $date);
	if (empty($ctx['ok'])) {
		tessera_console_error('not_found', (string) ($ctx['error'] ?? 'Manifest not found'), 404);
	}
	$bus = (string) ($ctx['context']['vehicle'] ?? '');
	$date = (string) ($ctx['context']['date'] ?? '');
}

$pager = tessera_console_pagination(50, 100);
$filters = ['q' => (string) ($_GET['q'] ?? '')];
$payload = tessera_cargo_load_items($db, $bus, $date, $filters, $pager['page'], $pager['per_page']);

tessera_console_ok([
	'items' => $payload['items'],
	'total' => $payload['total'],
	'summary' => $payload['summary'],
	'bus' => $bus,
	'date' => $date,
	'filters' => $filters,
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
