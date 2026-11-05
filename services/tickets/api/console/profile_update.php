<?php
/**
 * POST /tickets/api/console/profile_update.php — update own profile (S-07).
 * Body JSON: { firstname, surname?, email?, number? }
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

$result = tessera_settings_profile_update($db, $username, $json);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Save failed'), 422);
}

tessera_console_ok([
	'item' => $result['item'],
	'message' => (string) ($result['message'] ?? 'Profile saved'),
]);
