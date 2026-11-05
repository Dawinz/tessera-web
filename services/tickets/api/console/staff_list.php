<?php
/**
 * GET /tickets/api/console/staff_list.php — staff directory (S-02).
 * Query: q?
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_settings_staff.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'staff');

$payload = tessera_settings_staff_list($db, (string) ($_GET['q'] ?? ''));

tessera_console_ok([
	'items' => $payload['items'],
	'summary' => $payload['summary'],
]);
