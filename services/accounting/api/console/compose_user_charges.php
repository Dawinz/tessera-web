<?php
/**
 * GET /accounts/api/console/compose_user_charges.php — cargo uploader charge summary.
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_compose.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
unset($db, $username);

$route = trim((string) ($_GET['route'] ?? ''));
$currentRoute = trim((string) ($_GET['current_route'] ?? $route));
$bus = trim((string) ($_GET['vehicle'] ?? $_GET['bus'] ?? ''));
$date = trim((string) ($_GET['date'] ?? ''));

$result = tessera_accounts_compose_user_charges($route, $currentRoute, $bus, $date);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Failed to load user charges'), 422);
}

tessera_console_ok([
	'totals' => $result['totals'] ?? ['paid' => 0, 'to_pay' => 0, 'due' => 0],
	'rows' => $result['rows'] ?? [],
]);
