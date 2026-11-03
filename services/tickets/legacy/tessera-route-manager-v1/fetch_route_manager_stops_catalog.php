<?php
require_once __DIR__ . '/../../includes/tessera_staff_boot.php';
include 'connect.php';

header('Content-Type: application/json; charset=utf-8');

$tw = tessera_sql_tenant_eq();
$rows = [];
$q = mysqli_query($db, "SELECT id, name, category, location FROM stops WHERE $tw ORDER BY name ASC LIMIT 500");
while ($q && ($row = mysqli_fetch_assoc($q))) {
	$rows[] = [
		'id' => (int) $row['id'],
		'name' => (string) $row['name'],
		'category' => (string) $row['category'],
		'location' => (string) $row['location'],
	];
}

echo json_encode(['stops' => $rows], JSON_UNESCAPED_UNICODE);
