<?php
/**
 * GET /cargo/api/console/load_context.php — manifest header + initial lines (C-P0-07).
 * Query: bus, date (optional — falls back to latest trip), q, page, per_page
 *
 * Bundles items on first paint so a single authenticated request fills the desk
 * before other SPA chrome requests can race the staff session cookie.
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_load.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$result = tessera_cargo_load_resolve_context(
	$db,
	(string) ($_GET['bus'] ?? ''),
	(string) ($_GET['date'] ?? '')
);

if (empty($result['ok'])) {
	tessera_console_error('not_found', (string) ($result['error'] ?? 'Manifest not found'), 404);
}

$ctx = $result['context'];
$pager = tessera_console_pagination(50, 100);
$filters = ['q' => (string) ($_GET['q'] ?? '')];
$payload = tessera_cargo_load_items(
	$db,
	(string) ($ctx['vehicle'] ?? ''),
	(string) ($ctx['date'] ?? ''),
	$filters,
	$pager['page'],
	$pager['per_page']
);

tessera_console_ok([
	'context' => $ctx,
	'username' => $username,
	'items' => $payload['items'],
	'total' => $payload['total'],
	'summary' => $payload['summary'],
	'filters' => $filters,
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
