<?php
/**
 * GET /accounts/api/console/compose_context.php — trip context + boarding points (A-P0-02).
 * Query: route, vehicle|bus, date|trip_date
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_compose.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$route = (string) ($_GET['route'] ?? $_GET['route_name'] ?? '');
$bus = (string) ($_GET['vehicle'] ?? $_GET['bus'] ?? '');
$date = (string) ($_GET['date'] ?? $_GET['trip_date'] ?? '');

$result = tessera_accounts_compose_context($db, $username, $route, $bus, $date);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Context failed'), 422);
}

tessera_console_ok([
	'context' => $result['context'],
	'points' => $result['points'],
]);
