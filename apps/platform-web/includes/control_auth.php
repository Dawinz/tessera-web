<?php
/**
 * Platform multi-admin auth for Tessera Control.
 */

function control_admin_roles(): array {
	return ['owner', 'support', 'readonly'];
}

function control_can(string $capability, ?array $user = null): bool {
	$user = $user ?? ($GLOBALS['control_user'] ?? null);
	$role = is_array($user) ? (string) ($user['role'] ?? 'readonly') : 'readonly';
	$map = [
		'owner' => ['view', 'manage_tenants', 'manage_payments', 'manage_staff', 'manage_admins', 'export_audit', 'impersonate', 'manage_billing', 'manage_ops', 'manage_support'],
		'support' => ['view', 'manage_tenants', 'manage_staff', 'export_audit', 'impersonate', 'manage_support'],
		'readonly' => ['view', 'export_audit'],
	];
	$caps = $map[$role] ?? $map['readonly'];
	return in_array($capability, $caps, true);
}

function control_list_admins(?mysqli $db, bool $db_ok): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_platform_admins')) {
		return [];
	}
	$rows = [];
	$q = @$db->query('SELECT id, email, name, role, status, last_login_at, created_at, updated_at FROM tessera_platform_admins ORDER BY id ASC');
	if ($q) {
		while ($row = $q->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	return $rows;
}

function control_get_admin_by_email(?mysqli $db, bool $db_ok, string $email): ?array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_platform_admins')) {
		return null;
	}
	$email_e = $db->real_escape_string(strtolower(trim($email)));
	$q = @$db->query("SELECT * FROM tessera_platform_admins WHERE email='{$email_e}' LIMIT 1");
	if ($q && ($row = $q->fetch_assoc())) {
		return $row;
	}
	return null;
}

function control_ensure_bootstrap_admin(?mysqli $db, bool $db_ok): void {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_platform_admins')) {
		return;
	}
	$q = @$db->query('SELECT COUNT(*) AS c FROM tessera_platform_admins');
	$row = $q ? $q->fetch_assoc() : null;
	if ($row && (int) $row['c'] > 0) {
		return;
	}
	$cfg = control_config();
	$email = strtolower(trim($cfg['owner_email']));
	$name = $cfg['owner_name'];
	$hash = tessera_password_hash($cfg['owner_password']);
	$email_e = $db->real_escape_string($email);
	$name_e = $db->real_escape_string($name);
	$hash_e = $db->real_escape_string($hash);
	@$db->query(
		"INSERT INTO tessera_platform_admins (email, name, role, password_hash, status)
		 VALUES ('{$email_e}', '{$name_e}', 'owner', '{$hash_e}', 'active')"
	);
}

function control_touch_admin_login(?mysqli $db, bool $db_ok, int $id): void {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_platform_admins')) {
		return;
	}
	$id = (int) $id;
	@$db->query("UPDATE tessera_platform_admins SET last_login_at=NOW() WHERE id={$id} LIMIT 1");
}

/**
 * Authenticate Control user. Prefer DB admins; fall back to env bootstrap when table empty/missing.
 *
 * @return array{ok:bool,email?:string,name?:string,role?:string,id?:int}
 */
function control_attempt_login(?mysqli $db, bool $db_ok, string $email, string $password): array {
	$email = strtolower(trim($email));
	if ($email === '' || $password === '') {
		return ['ok' => false];
	}

	control_ensure_bootstrap_admin($db, $db_ok);
	$admin = control_get_admin_by_email($db, $db_ok, $email);
	if ($admin) {
		if (($admin['status'] ?? '') !== 'active') {
			return ['ok' => false];
		}
		if (!tessera_password_verify($password, (string) $admin['password_hash'])) {
			return ['ok' => false];
		}
		control_touch_admin_login($db, $db_ok, (int) $admin['id']);
		return [
			'ok' => true,
			'id' => (int) $admin['id'],
			'email' => (string) $admin['email'],
			'name' => (string) $admin['name'],
			'role' => (string) $admin['role'],
		];
	}

	/* Env bootstrap only when no DB row for this email (legacy / first deploy). */
	$cfg = control_config();
	if (hash_equals($cfg['owner_email'], $email) && hash_equals($cfg['owner_password'], $password)) {
		return [
			'ok' => true,
			'id' => 0,
			'email' => $cfg['owner_email'],
			'name' => $cfg['owner_name'],
			'role' => 'owner',
		];
	}

	return ['ok' => false];
}

function control_create_admin(?mysqli $db, bool $db_ok, array $input, string $actor): ?array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_platform_admins')) {
		return null;
	}
	$email = strtolower(trim((string) ($input['email'] ?? '')));
	$name = trim((string) ($input['name'] ?? ''));
	$role = (string) ($input['role'] ?? 'support');
	$password = (string) ($input['password'] ?? '');
	if ($email === '' || $name === '' || strlen($password) < 8 || !in_array($role, control_admin_roles(), true)) {
		return null;
	}
	if (control_get_admin_by_email($db, $db_ok, $email)) {
		return null;
	}
	$hash = tessera_password_hash($password);
	$email_e = $db->real_escape_string($email);
	$name_e = $db->real_escape_string($name);
	$role_e = $db->real_escape_string($role);
	$hash_e = $db->real_escape_string($hash);
	$ok = @$db->query(
		"INSERT INTO tessera_platform_admins (email, name, role, password_hash, status)
		 VALUES ('{$email_e}', '{$name_e}', '{$role_e}', '{$hash_e}', 'active')"
	);
	if (!$ok) {
		return null;
	}
	$id = (int) $db->insert_id;
	control_audit($db, $db_ok, $actor, 'admin.create', null, ['admin_id' => $id, 'email' => $email, 'role' => $role]);
	return control_get_admin_by_email($db, $db_ok, $email);
}

function control_set_admin_status(?mysqli $db, bool $db_ok, int $id, string $status, string $actor): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_platform_admins')) {
		return false;
	}
	if (!in_array($status, ['active', 'disabled'], true)) {
		return false;
	}
	$id = (int) $id;
	$status_e = $db->real_escape_string($status);
	$ok = @$db->query("UPDATE tessera_platform_admins SET status='{$status_e}' WHERE id={$id} LIMIT 1");
	if ($ok) {
		control_audit($db, $db_ok, $actor, 'admin.status', null, ['admin_id' => $id, 'status' => $status]);
	}
	return (bool) $ok;
}

function control_reset_admin_password(?mysqli $db, bool $db_ok, int $id, string $password, string $actor): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_platform_admins') || strlen($password) < 8) {
		return false;
	}
	$id = (int) $id;
	$hash_e = $db->real_escape_string(tessera_password_hash($password));
	$ok = @$db->query("UPDATE tessera_platform_admins SET password_hash='{$hash_e}' WHERE id={$id} LIMIT 1");
	if ($ok) {
		control_audit($db, $db_ok, $actor, 'admin.password_reset', null, ['admin_id' => $id]);
	}
	return (bool) $ok;
}
