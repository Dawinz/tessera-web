<?php
/**
 * POST /tickets/api/console/staff_role.php — assign capability role (S-02).
 * Body JSON: { username, role }
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_settings_staff.php';

tessera_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');
$sessionUser = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $sessionUser, 'staff');

$raw = file_get_contents('php://input');
$json = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($json)) {
	$json = $_POST;
}
if (!is_array($json)) {
	tessera_console_error('validation_error', 'Expected JSON body', 422);
}

$target = trim((string) ($json['username'] ?? $json['target_username'] ?? ''));
$role = (string) ($json['role'] ?? $json['tessera_role'] ?? '');
$res = tessera_apply_staff_role_everywhere($target, $role);
if (empty($res['ok'])) {
	tessera_console_error('validation_error', (string) ($res['message'] ?? 'Role update failed'), 422);
}

$list = tessera_settings_staff_list($db);
tessera_console_ok([
	'message' => (string) ($res['message'] ?? 'Role updated'),
	'modules' => $res['modules'] ?? [],
	'summary' => $list['summary'],
]);
