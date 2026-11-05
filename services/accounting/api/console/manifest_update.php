<?php
/**
 * POST /accounts/api/console/manifest_update.php — update vehicle/date (A-P0-01).
 * Body JSON: { id, vehicle?, date?, route? }
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_manifests.php';

[$db, $username] = tessera_accounts_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');

$raw = file_get_contents('php://input');
$json = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($json)) {
	$json = $_POST;
}
if (!is_array($json)) {
	tessera_console_error('validation_error', 'Expected JSON body', 422);
}

$result = tessera_accounts_manifests_update($db, [
	'id' => (int) ($json['id'] ?? 0),
	'route' => (string) ($json['route'] ?? ''),
	'date' => (string) ($json['date'] ?? ''),
	'vehicle' => (string) ($json['vehicle'] ?? ''),
]);

if (empty($result['ok'])) {
	$code = stripos((string) ($result['error'] ?? ''), 'not found') !== false ? 404 : 422;
	tessera_console_error(
		$code === 404 ? 'not_found' : 'validation_error',
		(string) ($result['error'] ?? 'Update failed'),
		$code
	);
}

tessera_console_ok(['item' => $result['item'] ?? null]);
