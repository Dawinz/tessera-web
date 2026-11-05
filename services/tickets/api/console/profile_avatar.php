<?php
/**
 * POST /tickets/api/console/profile_avatar.php — upload own photo (S-07).
 * multipart/form-data field: avatar (or my_image)
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

$file = null;
if (!empty($_FILES['avatar']) && is_array($_FILES['avatar'])) {
	$file = $_FILES['avatar'];
} elseif (!empty($_FILES['my_image']) && is_array($_FILES['my_image'])) {
	$file = $_FILES['my_image'];
}
if (!$file) {
	tessera_console_error('validation_error', 'Expected avatar file upload', 422);
}

$result = tessera_settings_profile_avatar($db, $username, $file);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Upload failed'), 422);
}

tessera_console_ok([
	'item' => $result['item'],
	'message' => (string) ($result['message'] ?? 'Photo updated'),
]);
