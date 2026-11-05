<?php
/**
 * POST /cargo/api/console/manifest_remark.php
 * Body JSON: { id, remark_text, remark_html? }
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_manifests.php';

[$db, $username] = tessera_cargo_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);
if (!is_array($payload)) {
	tessera_console_error('validation_error', 'Invalid JSON body', 422);
}

$id = (int) ($payload['id'] ?? 0);
$text = (string) ($payload['remark_text'] ?? $payload['remark'] ?? '');
$html = (string) ($payload['remark_html'] ?? '');
$result = tessera_cargo_manifests_save_remark($db, $username, $id, $text, $html);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Could not save remark'), 422);
}

tessera_console_ok([
	'item' => $result['item'] ?? null,
]);
