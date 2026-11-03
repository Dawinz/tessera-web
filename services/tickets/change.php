<?php
/**
 * Toggle one menu page for a staff user (Tickets roles UI).
 */
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/includes/tessera_staff_sync.php';

$wantsJson = (isset($_SERVER['HTTP_ACCEPT']) && str_contains((string) $_SERVER['HTTP_ACCEPT'], 'application/json'))
	|| (isset($_POST['format']) && $_POST['format'] === 'json');

$respond = static function (bool $ok, string $message, array $extra = []) use ($wantsJson): void {
	if ($wantsJson) {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(array_merge(['success' => $ok, 'message' => $message], $extra));
		exit;
	}
	/* Legacy UI expects the misspelled token */
	echo $ok ? 'succesfull' : $message;
	exit;
};

$change = (string) ($_POST['change'] ?? '');
$file = trim((string) ($_POST['file'] ?? ''));
$target = trim((string) ($_POST['username_names'] ?? ''));

if ($file === '' || $target === '' || $target === 'List of user in the system') {
	$respond(false, 'Pick a user and page.');
}
if (!preg_match('/^[a-z0-9_\\-\\.]+\\.php$/i', $file)) {
	$respond(false, 'Invalid page.');
}

$u = mysqli_real_escape_string($db, $target);
$userQ = mysqli_query($db, "SELECT role FROM users WHERE username='{$u}' LIMIT 1");
$user = $userQ ? mysqli_fetch_assoc($userQ) : null;
if (!$user) {
	$respond(false, 'User not found.');
}
$legacyLevel = (string) $user['role'];
$fileEsc = mysqli_real_escape_string($db, $file);
$levelEsc = mysqli_real_escape_string($db, $legacyLevel);

if ($change === 'on') {
	mysqli_query($db, "DELETE FROM roles_assigned WHERE username='{$u}' AND file='{$fileEsc}'");

	$tpl = mysqli_query(
		$db,
		"SELECT name, level, file, category, arrangement FROM roles
		 WHERE file='{$fileEsc}' AND level='{$levelEsc}' LIMIT 1"
	);
	if (!$tpl || mysqli_num_rows($tpl) === 0) {
		$tpl = mysqli_query($db, "SELECT name, level, file, category, arrangement FROM roles WHERE file='{$fileEsc}' LIMIT 1");
	}

	if ($tpl && ($row = mysqli_fetch_assoc($tpl))) {
		$name = mysqli_real_escape_string($db, (string) $row['name']);
		$lvl = mysqli_real_escape_string($db, (string) $row['level']);
		$cat = mysqli_real_escape_string($db, (string) $row['category']);
		$arr = (int) $row['arrangement'];
	} else {
		$name = mysqli_real_escape_string($db, pathinfo($file, PATHINFO_FILENAME));
		$lvl = $levelEsc;
		$cat = 'Registration';
		$arr = 99;
	}

	$ins = mysqli_query(
		$db,
		"INSERT INTO roles_assigned (name, level, file, username, category, arrangement)
		 VALUES ('{$name}','{$lvl}','{$fileEsc}','{$u}','{$cat}','{$arr}')"
	);
	if (!$ins) {
		$respond(false, 'insert failed: ' . mysqli_error($db));
	}

	foreach (tessera_module_connections() as $key => $modDb) {
		if ($key === 'ticket') {
			continue;
		}
		@$modDb->query("DELETE FROM roles_assigned WHERE username='{$u}' AND file='{$fileEsc}'");
		$cols = 'name, level, file, username, category, arrangement';
		$vals = "'{$name}','{$lvl}','{$fileEsc}','{$u}','{$cat}','{$arr}'";
		$c1 = @$modDb->query("SHOW COLUMNS FROM roles_assigned LIKE 'sub_url'");
		if ($c1 && $c1->num_rows > 0) {
			$cols .= ', sub_url';
			$vals .= ",''";
		}
		$c2 = @$modDb->query("SHOW COLUMNS FROM roles_assigned LIKE 'sub_route'");
		if ($c2 && $c2->num_rows > 0) {
			$cols .= ', sub_route';
			$vals .= ",''";
		}
		@$modDb->query("INSERT INTO roles_assigned ({$cols}) VALUES ({$vals})");
	}

	$respond(true, 'succesfull', ['assigned' => true, 'file' => $file, 'username' => $target]);
}

mysqli_query($db, "DELETE FROM roles_assigned WHERE file='{$fileEsc}' AND username='{$u}'");
foreach (tessera_module_connections() as $key => $modDb) {
	if ($key === 'ticket') {
		continue;
	}
	@$modDb->query("DELETE FROM roles_assigned WHERE file='{$fileEsc}' AND username='{$u}'");
}
$respond(true, 'succesfull', ['assigned' => false, 'file' => $file, 'username' => $target]);
