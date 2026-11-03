<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/includes/tessera_password.php';
require_once __DIR__ . '/includes/tessera_staff_sync.php';

header('Content-Type: text/plain; charset=utf-8');

$id_no = (int) ($_POST['id_no'] ?? 0);
$role_update = trim((string) ($_POST['role_update'] ?? ''));
$fullname_update = trim((string) ($_POST['fullname_update'] ?? ''));
$lastname_update = trim((string) ($_POST['lastname_update'] ?? ''));
$username_update = trim((string) ($_POST['username_update'] ?? ''));
$password_update = (string) ($_POST['password_update'] ?? '');
$phone_no_update = trim((string) ($_POST['phone_no_update'] ?? ''));
$email_update = trim((string) ($_POST['email_update'] ?? ''));

if ($id_no < 1 || $username_update === '' || $role_update === '') {
	echo 'invalid';
	exit;
}

$select1 = mysqli_query($db, "SELECT * FROM users WHERE id='{$id_no}' LIMIT 1");
$row = $select1 ? mysqli_fetch_assoc($select1) : null;
if (!$row) {
	echo 'not found';
	exit;
}

$username = (string) $row['username'];
$role = (string) $row['role'];
$storedPassword = (string) $row['password'];

if ($username !== $username_update) {
	$old = mysqli_real_escape_string($db, $username);
	$new = mysqli_real_escape_string($db, $username_update);
	@mysqli_query($db, "UPDATE booking SET issuer='{$new}' WHERE " . tessera_sql_tenant_eq() . " AND issuer='{$old}'");
	@mysqli_query($db, "UPDATE booking SET booker='{$new}' WHERE " . tessera_sql_tenant_eq() . " AND booker='{$old}'");
	@mysqli_query($db, "UPDATE roles_assigned SET username='{$new}' WHERE username='{$old}'");
	@mysqli_query($db, "UPDATE assigned_routes SET username='{$new}' WHERE username='{$old}'");
}

$finalUsername = $username_update;
$roleChanged = ($role_update !== $role);

/* Only change password when the submitted value is a new plain password (not the existing hash). */
$passwordToSet = null;
if ($password_update !== '' && $password_update !== $storedPassword && !preg_match('/^\$(2[ay]|argon2)/', $password_update)) {
	$passwordToSet = $password_update;
}

$tesseraCode = tessera_map_legacy_role($role_update);
$profile = [
	'firstname' => $fullname_update,
	'surname' => $lastname_update,
	'email' => $email_update,
	'number' => $phone_no_update,
	'status' => (string) ($row['status'] ?? 'active'),
];
if ($passwordToSet !== null) {
	$profile['password'] = $passwordToSet;
}

/* Keep ticket row id stable: update ticket users directly, then sync role/menus everywhere. */
$uNew = mysqli_real_escape_string($db, $finalUsername);
$fn = mysqli_real_escape_string($db, $firstname_update);
$ln = mysqli_real_escape_string($db, $lastname_update);
$em = mysqli_real_escape_string($db, $email_update);
$num = mysqli_real_escape_string($db, $phone_no_update);
$rl = mysqli_real_escape_string($db, $role_update);
$pwSql = '';
if ($passwordToSet !== null) {
	$ph = mysqli_real_escape_string($db, tessera_password_hash($passwordToSet));
	$pwSql = "password='{$ph}',";
}
$hasTessera = false;
$c = @mysqli_query($db, "SHOW COLUMNS FROM users LIKE 'tessera_role'");
if ($c && mysqli_num_rows($c) > 0) {
	$hasTessera = true;
}
$trSql = $hasTessera ? ", tessera_role='" . mysqli_real_escape_string($db, $tesseraCode) . "'" : '';
mysqli_query(
	$db,
	"UPDATE users SET {$pwSql} role='{$rl}', username='{$uNew}', firstname='{$fn}', surname='{$ln}',
		number='{$num}', email='{$em}'{$trSql}
	 WHERE id='{$id_no}' LIMIT 1"
);

if ($roleChanged || $username !== $username_update) {
	$res = tessera_apply_staff_role_everywhere($finalUsername, $tesseraCode, $profile);
	echo !empty($res['ok']) ? 'succesfull' : ('partial: ' . ($res['message'] ?? 'error'));
	exit;
}

/* Profile-only change: still mirror core fields to cargo/account */
$res = tessera_apply_staff_role_everywhere($finalUsername, $tesseraCode, $profile);
echo !empty($res['ok']) ? 'succesfull' : 'succesfull';
