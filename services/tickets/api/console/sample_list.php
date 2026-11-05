<?php
/**
 * GET /tickets/api/console/sample_list.php — sample paginated list for G1 smoke.
 * Returns empty items until real schedule endpoints land with T-P0-01.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

$connect = dirname(__DIR__, 2) . '/connect.php';
if (!is_file($connect)) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
require_once $connect;
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}

tessera_console_require_capability($db, $username, 'tickets');

$pager = tessera_console_pagination(25, 100);
$sort = tessera_console_sort(['id', 'date'], 'date', 'desc');

tessera_console_ok([
	'items' => [],
	'total' => 0,
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
	'sort' => $sort['sort'],
	'order' => $sort['order'],
	'note' => 'Placeholder list for Phase 1 smoke; replaced by schedule/book APIs in P0 slices',
]);
