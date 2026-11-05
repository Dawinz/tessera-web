<?php
/**
 * GET  /accounts/api/console/compose_attachments.php — list trip attachments.
 * POST /accounts/api/console/compose_attachments.php — upload (multipart) or delete JSON.
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_compose.php';

$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
[$db, $username] = tessera_accounts_console_boot('GET, POST, OPTIONS');

if ($method === 'GET') {
	$route = trim((string) ($_GET['route'] ?? ''));
	$bus = trim((string) ($_GET['vehicle'] ?? $_GET['bus'] ?? ''));
	$date = trim((string) ($_GET['date'] ?? ''));
	tessera_console_ok([
		'items' => tessera_accounts_compose_attachments($db, $route, $bus, $date),
	]);
}

tessera_console_require_method('POST');

$contentType = (string) ($_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '');
$isMultipart = stripos($contentType, 'multipart/form-data') !== false;

if ($isMultipart || !empty($_FILES['file'])) {
	$route = trim((string) ($_POST['route'] ?? ''));
	$bus = trim((string) ($_POST['vehicle'] ?? $_POST['bus'] ?? ''));
	$date = trim((string) ($_POST['date'] ?? ''));
	$point = trim((string) ($_POST['point'] ?? ''));
	$currentRoute = trim((string) ($_POST['current_route'] ?? $route));
	$file = isset($_FILES['file']) && is_array($_FILES['file']) ? $_FILES['file'] : [];
	$result = tessera_accounts_compose_attachment_save($db, $route, $bus, $date, $point, $currentRoute, $file);
	if (empty($result['ok'])) {
		tessera_console_error('validation_error', (string) ($result['error'] ?? 'Upload failed'), 422);
	}
	tessera_console_ok([
		'item' => $result['item'] ?? null,
		'items' => tessera_accounts_compose_attachments($db, $route, $bus, $date),
	]);
}

$raw = file_get_contents('php://input');
$json = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($json)) {
	$json = $_POST;
}
if (!is_array($json)) {
	tessera_console_error('validation_error', 'Expected JSON or multipart body', 422);
}

$action = strtolower(trim((string) ($json['action'] ?? 'delete')));
if ($action === 'delete') {
	$id = (int) ($json['id'] ?? 0);
	$result = tessera_accounts_compose_attachment_delete($db, $id);
	if (empty($result['ok'])) {
		tessera_console_error('validation_error', (string) ($result['error'] ?? 'Delete failed'), 422);
	}
	$route = trim((string) ($json['route'] ?? ''));
	$bus = trim((string) ($json['vehicle'] ?? $json['bus'] ?? ''));
	$date = trim((string) ($json['date'] ?? ''));
	tessera_console_ok([
		'items' => tessera_accounts_compose_attachments($db, $route, $bus, $date),
	]);
}

tessera_console_error('validation_error', 'Unknown action', 422);
