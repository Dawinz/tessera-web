<?php
/**
 * GET  /tickets/api/console/print_transfer.php — passengers and target trips.
 * POST /tickets/api/console/print_transfer.php — move seats (all | multiple | selected).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_prints.php';

tessera_console_boot('GET, POST, OPTIONS');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
if ($method === 'GET') {
	tessera_console_ok(tessera_prints_transfer_board($db, $_GET));
}

if ($method !== 'POST') {
	tessera_console_error('method_not_allowed', 'Method not allowed', 405);
}

$raw = file_get_contents('php://input');
$json = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($json)) {
	$json = $_POST;
}
if (!is_array($json)) {
	tessera_console_error('validation_error', 'Expected JSON body', 422);
}

$result = tessera_prints_transfer_apply($db, $username, $json);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Transfer failed'), 422, [
		'skipped' => $result['skipped'] ?? [],
	]);
}

tessera_console_ok([
	'moved' => (int) ($result['moved'] ?? 0),
	'skipped' => $result['skipped'] ?? [],
]);
