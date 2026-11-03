<?php
/**
 * Menu permissions for Settings -> Staff (grouped by nav category).
 */
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/tessera_rbac.php';
require_once __DIR__ . '/connect.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo json_encode(['ok' => false, 'error' => 'unauthorized']);
	exit;
}

if (!tessera_can($db, (string) $_SESSION['username'], 'staff')) {
	http_response_code(403);
	echo json_encode(['ok' => false, 'error' => 'forbidden']);
	exit;
}

$target = trim((string) ($_GET['username'] ?? ''));
if ($target === '') {
	http_response_code(400);
	echo json_encode(['ok' => false, 'error' => 'username_required']);
	exit;
}

$uEsc = mysqli_real_escape_string($db, $target);
$userQ = mysqli_query($db, "SELECT username, firstname, surname, role, status FROM users WHERE username='{$uEsc}' LIMIT 1");
$userRow = $userQ ? mysqli_fetch_assoc($userQ) : null;
if (!$userRow) {
	http_response_code(404);
	echo json_encode(['ok' => false, 'error' => 'user_not_found']);
	exit;
}

$hasTesseraRole = false;
$c = @$db->query("SHOW COLUMNS FROM users LIKE 'tessera_role'");
if ($c && $c->num_rows > 0) {
	$hasTesseraRole = true;
	$rq = mysqli_query($db, "SELECT tessera_role FROM users WHERE username='{$uEsc}' LIMIT 1");
	if ($rq && ($rr = mysqli_fetch_assoc($rq))) {
		$userRow['tessera_role'] = (string) ($rr['tessera_role'] ?? '');
	}
}

$legacyLevel = mysqli_real_escape_string($db, (string) ($userRow['role'] ?? ''));
$assigned = [];
$aq = mysqli_query($db, "SELECT file FROM roles_assigned WHERE username='{$uEsc}'");
while ($aq && ($ar = mysqli_fetch_assoc($aq))) {
	$assigned[(string) $ar['file']] = true;
}

$groups = [];
$total = 0;
$assignedCount = 0;

$sql = "SELECT r.name, r.file, r.category, r.arrangement, r.image,
        COALESCE(NULLIF(p.page_name, ''), r.name) AS page_name,
        COALESCE(NULLIF(p.detail, ''), r.name) AS detail
        FROM roles r
        LEFT JOIN pages p ON p.page = r.file
        WHERE r.level = '{$legacyLevel}' AND (r.username = '' OR r.username IS NULL)
        ORDER BY r.category ASC, r.arrangement ASC, r.name ASC";
$rq = mysqli_query($db, $sql);
$seen = [];
while ($rq && ($row = mysqli_fetch_assoc($rq))) {
	$file = (string) $row['file'];
	if ($file === '' || isset($seen[$file])) {
		continue;
	}
	$seen[$file] = true;
	$cat = trim((string) ($row['category'] ?? 'Other'));
	if ($cat === '') {
		$cat = 'Other';
	}
	if (!isset($groups[$cat])) {
		$groups[$cat] = [];
	}
	$isOn = !empty($assigned[$file]);
	$groups[$cat][] = [
		'file' => $file,
		'name' => (string) $row['name'],
		'page_name' => (string) $row['page_name'],
		'description' => (string) $row['detail'],
		'icon' => (string) ($row['image'] ?? 'file'),
		'assigned' => $isOn,
	];
	$total++;
	if ($isOn) {
		$assignedCount++;
	}
}

$outGroups = [];
foreach ($groups as $category => $items) {
	$outGroups[] = [
		'category' => $category,
		'items' => $items,
		'assigned' => count(array_filter($items, static fn ($i) => !empty($i['assigned']))),
		'total' => count($items),
	];
}

usort($outGroups, static function ($a, $b) {
	$order = ['Home' => 1, 'Book list' => 2, 'Schedule' => 3, 'Vehicle manager' => 4, 'Manifest' => 5, 'Reports' => 6, 'Settings' => 7];
	$wa = $order[$a['category']] ?? 50;
	$wb = $order[$b['category']] ?? 50;
	if ($wa === $wb) {
		return strcmp($a['category'], $b['category']);
	}
	return $wa <=> $wb;
});

echo json_encode([
	'ok' => true,
	'user' => [
		'username' => (string) $userRow['username'],
		'firstname' => (string) ($userRow['firstname'] ?? ''),
		'surname' => (string) ($userRow['surname'] ?? ''),
		'role' => (string) ($userRow['role'] ?? ''),
		'tessera_role' => (string) ($userRow['tessera_role'] ?? ''),
		'status' => (string) ($userRow['status'] ?? ''),
		'effective_role' => tessera_user_role_code($db, $target),
	],
	'groups' => $outGroups,
	'counts' => [
		'assigned' => $assignedCount,
		'total' => $total,
	],
], JSON_UNESCAPED_UNICODE);
