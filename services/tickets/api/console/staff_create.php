<?php
/**
 * POST /tickets/api/console/staff_create.php — create staff across modules (S-02).
 * Body JSON: { username, password, firstname?, surname?, email?, number?, role }
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_settings_staff.php';

tessera_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'staff');

$raw = file_get_contents('php://input');
$json = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($json)) {
	$json = $_POST;
}
if (!is_array($json)) {
	tessera_console_error('validation_error', 'Expected JSON body', 422);
}

$role = (string) ($json['role'] ?? $json['tessera_role'] ?? '');
$res = tessera_create_staff_everywhere([
	'username' => trim((string) ($json['username'] ?? '')),
	'password' => (string) ($json['password'] ?? ''),
	'firstname' => trim((string) ($json['firstname'] ?? '')),
	'surname' => trim((string) ($json['surname'] ?? '')),
	'email' => trim((string) ($json['email'] ?? '')),
	'number' => trim((string) ($json['number'] ?? '')),
	'status' => 'active',
], $role);

if (empty($res['ok'])) {
	tessera_console_error('validation_error', (string) ($res['message'] ?? 'Create failed'), 422);
}

$list = tessera_settings_staff_list($db);
tessera_console_ok([
	'message' => (string) ($res['message'] ?? 'Staff created'),
	'summary' => $list['summary'],
], [], 201);
