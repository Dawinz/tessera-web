<?php
/**
 * POST /tickets/api/console/profile_password.php — change own password (S-07).
 * Body JSON: { current_password, new_password }
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_settings_profile.php';

tessera_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}

$raw = file_get_contents('php://input');
$json = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($json)) {
	$json = $_POST;
}
if (!is_array($json)) {
	tessera_console_error('validation_error', 'Expected JSON body', 422);
}

$result = tessera_settings_profile_password(
	$db,
	$username,
	(string) ($json['current_password'] ?? $json['current'] ?? ''),
	(string) ($json['new_password'] ?? $json['password'] ?? '')
);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Password update failed'), 422);
}

tessera_console_ok(['message' => (string) ($result['message'] ?? 'Password updated')]);
