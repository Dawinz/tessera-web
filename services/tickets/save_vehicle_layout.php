<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/vehicle_seat_layout.php';
include 'connect.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo json_encode(['error' => 'unauthorized']);
	exit;
}

if (!tessera_vehicle_has_layout_column($db)) {
	http_response_code(503);
	echo json_encode(['error' => 'migration_required', 'message' => 'Run database migration 019_vehicle_seat_layout_json.sql']);
	exit;
}

$id = (int) ($_POST['vehicle_id'] ?? 0);
$tenantId = (int) tessera_current_tenant_id();
$payload = $_POST['layout'] ?? '';
if (is_array($payload)) {
	$payload = json_encode($payload);
}

if ($id < 1 || $tenantId < 1 || !is_string($payload) || trim($payload) === '') {
	http_response_code(400);
	echo json_encode(['error' => 'invalid']);
	exit;
}

$data = json_decode($payload, true);
if (!is_array($data)) {
	http_response_code(400);
	echo json_encode(['error' => 'invalid_json']);
	exit;
}

$patterns = [];
if (!empty($data['patterns']) && is_array($data['patterns'])) {
	$patterns = array_values(array_filter(array_map(static function ($p) {
		return preg_replace('/[^i\-]/', '', (string) $p);
	}, $data['patterns'])));
} elseif (!empty($data['grid']) && is_array($data['grid'])) {
	$patterns = tessera_grid_to_patterns($data['grid']);
}

$extras = [];
if (!empty($data['extras']) && is_array($data['extras'])) {
	foreach ($data['extras'] as $label) {
		$label = trim((string) $label);
		if ($label !== '') {
			$extras[] = $label;
		}
	}
}

if ($patterns === []) {
	http_response_code(400);
	echo json_encode(['error' => 'empty_layout']);
	exit;
}

$stmt = $db->prepare('SELECT seats FROM vehicle WHERE id = ? AND tenant_id = ? LIMIT 1');
$stmt->bind_param('ii', $id, $tenantId);
$stmt->execute();
$res = $stmt->get_result();
$row = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$row) {
	http_response_code(404);
	echo json_encode(['error' => 'not_found']);
	exit;
}

$layoutSeats = tessera_count_seats_in_patterns($patterns, $extras);
$capacity = (int) $row['seats'];
$json = tessera_vehicle_layout_to_json($patterns, $extras);

$upd = $db->prepare('UPDATE vehicle SET seat_layout_json = ? WHERE id = ? AND tenant_id = ? LIMIT 1');
$upd->bind_param('sii', $json, $id, $tenantId);
$ok = $upd->execute();
$upd->close();

if (!$ok) {
	http_response_code(500);
	echo json_encode(['error' => 'save_failed']);
	exit;
}

echo json_encode([
	'ok' => true,
	'layout_seats' => $layoutSeats,
	'layout_ok' => $layoutSeats === $capacity,
	'patterns' => $patterns,
	'extras' => $extras,
	'labels' => tessera_layout_labels_for_vehicle($patterns, $extras),
], JSON_UNESCAPED_UNICODE);
