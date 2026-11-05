<?php
/**
 * GET  /tickets/api/console/deleted_tickets.php — deleted ticket audit (T-P1-06).
 * POST /tickets/api/console/deleted_tickets.php — restore { id }
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_p1_reports.php';

$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
tessera_console_boot('GET, POST, OPTIONS');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

if ($method === 'GET') {
	$pager = tessera_console_pagination(100, 200);
	$payload = tessera_deleted_tickets_payload($db, (string) ($_GET['q'] ?? ''), $pager['page'], $pager['per_page']);
	tessera_console_ok($payload, ['page' => $pager['page'], 'per_page' => $pager['per_page']]);
}

tessera_console_require_method('POST');
$raw = file_get_contents('php://input');
$json = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($json)) {
	$json = $_POST;
}
$id = (int) (($json['id'] ?? 0));
$result = tessera_deleted_tickets_restore($db, $username, $id);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Restore failed'), 422);
}
tessera_console_ok(['restored' => true, 'id' => $id]);
