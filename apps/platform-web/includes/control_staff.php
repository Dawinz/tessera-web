<?php
/**
 * Operator staff bootstrap (Tickets / Cargo / Accounts users tables).
 */

/** Open that operator’s dedicated module DB, or null to keep using the registry connection. */
function control_operator_app_mysqli(?array $tenant, string $module = 'ticket'): ?mysqli {
	if (!$tenant || !function_exists('sr_mysqli_platform')) {
		return null;
	}
	if (($tenant['db_mode'] ?? '') !== 'dedicated') {
		return null;
	}
	$map = [
		'ticket' => 'db_ticket',
		'tickets' => 'db_ticket',
		'cargo' => 'db_cargo',
		'account' => 'db_account',
		'accounts' => 'db_account',
	];
	$col = $map[$module] ?? 'db_ticket';
	$name = trim((string) ($tenant[$col] ?? ''));
	if ($name === '') {
		return null;
	}
	$mysqli = sr_mysqli_platform($name);
	if ($mysqli && !$mysqli->connect_error) {
		return $mysqli;
	}
	return null;
}

/** Tickets staff connection for this operator (dedicated DB when provisioned). */
function control_operator_staff_mysqli(?mysqli $registry, bool $registry_ok, ?array $tenant): array {
	$dedicated = control_operator_app_mysqli($tenant, 'ticket');
	if ($dedicated) {
		return ['db' => $dedicated, 'ok' => true, 'dedicated' => true];
	}
	return ['db' => $registry, 'ok' => $registry_ok, 'dedicated' => false];
}

function control_list_operator_staff(?mysqli $db, bool $db_ok, int $limit = 50): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'users')) {
		return ['ok' => false, 'rows' => [], 'count' => 0];
	}
	$limit = max(1, min(200, $limit));
	$count = 0;
	$cq = @$db->query('SELECT COUNT(*) AS c FROM users');
	if ($cq && ($cr = $cq->fetch_assoc())) {
		$count = (int) $cr['c'];
	}
	$rows = [];
	$q = @$db->query("SELECT id, username, firstname, surname, email, number, role, status FROM users ORDER BY id DESC LIMIT {$limit}");
	if ($q) {
		while ($row = $q->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	return ['ok' => true, 'rows' => $rows, 'count' => $count];
}

/** Schema-tolerant users INSERT (dedicated Cargo/Accounts DBs require fullname + level). */
function control_insert_staff_user_row(
	mysqli $db,
	string $username,
	string $hash,
	string $firstname,
	string $surname,
	string $role,
	string $email,
	string $number
): bool {
	$colCheck = static function (mysqli $db, string $col): bool {
		$c = @$db->query("SHOW COLUMNS FROM users LIKE '{$col}'");
		return $c && $c->num_rows > 0;
	};

	$username_e = $db->real_escape_string($username);
	$hash_e = $db->real_escape_string($hash);
	$fn_e = $db->real_escape_string($firstname);
	$sn_e = $db->real_escape_string($surname);
	$role_e = $db->real_escape_string($role);
	$email_e = $db->real_escape_string($email);
	$number_e = $db->real_escape_string($number);
	$img = 'user.png';

	$cols = 'username, password, firstname, surname, role, location, email, number, status, button_name, button_icon, button_class, image';
	$vals = "'{$username_e}', '{$hash_e}', '{$fn_e}', '{$sn_e}', '{$role_e}', '', '{$email_e}', '{$number_e}', 'active',
		'De activate', 'uil uil-exclamation-octagon me-2', 'btn btn-soft-danger', '{$img}'";

	if ($colCheck($db, 'fullname')) {
		$fullname_e = $db->real_escape_string(trim($firstname . ' ' . $surname));
		$cols .= ', fullname';
		$vals .= ", '{$fullname_e}'";
	}
	if ($colCheck($db, 'level')) {
		$cols .= ', level';
		$vals .= ", '{$role_e}'";
	}
	if ($colCheck($db, 'tessera_role')) {
		$tesseraRole = 'agent';
		if ($role === 'Super Administrator') {
			$tesseraRole = 'owner';
		} elseif ($role === 'Administrator') {
			$tesseraRole = 'manager';
		}
		$tr_e = $db->real_escape_string($tesseraRole);
		$cols .= ', tessera_role';
		$vals .= ", '{$tr_e}'";
	}

	return (bool) @$db->query("INSERT INTO users ({$cols}) VALUES ({$vals})");
}

function control_staff_assign_roles(mysqli $db, string $username, string $role): void {
	$role_e = $db->real_escape_string($role);
	$username_e = $db->real_escape_string($username);
	$rq = @$db->query("SELECT name, level, file, category, arrangement FROM roles WHERE level='{$role_e}'");
	if (!$rq) {
		return;
	}
	while ($r = $rq->fetch_assoc()) {
		$name = $db->real_escape_string((string) $r['name']);
		$level = $db->real_escape_string((string) $r['level']);
		$file = $db->real_escape_string((string) $r['file']);
		$category = $db->real_escape_string((string) $r['category']);
		$arr = $db->real_escape_string((string) $r['arrangement']);
		@$db->query(
			"INSERT INTO roles_assigned (name, level, file, username, category, arrangement)
			 VALUES ('{$name}', '{$level}', '{$file}', '{$username_e}', '{$category}', '{$arr}')"
		);
	}
}

/**
 * Create first / additional operator admin in that operator’s Tickets users.
 * Also mirrors the same login into Cargo and Accounts dedicated DBs when present.
 *
 * @return array{ok:bool,error?:string,user_id?:int}
 */
function control_create_operator_staff(?mysqli $db, bool $db_ok, int $tenant_id, array $input, string $actor, ?mysqli $auditDb = null, ?array $tenant = null): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'users')) {
		return ['ok' => false, 'error' => 'users_table_missing'];
	}

	$username = trim((string) ($input['username'] ?? ''));
	$password = (string) ($input['password'] ?? '');
	$firstname = trim((string) ($input['firstname'] ?? ''));
	$surname = trim((string) ($input['surname'] ?? ''));
	$email = trim((string) ($input['email'] ?? ''));
	$number = trim((string) ($input['number'] ?? ''));
	$role = (string) ($input['role'] ?? 'Administrator');
	$allowedRoles = ['Administrator', 'Super Administrator', 'Agent'];
	if (!in_array($role, $allowedRoles, true)) {
		$role = 'Administrator';
	}

	if ($username === '' || $firstname === '' || $surname === '' || $email === '' || strlen($password) < 6) {
		return ['ok' => false, 'error' => 'validation'];
	}

	$username_e = $db->real_escape_string($username);
	$email_e = $db->real_escape_string($email);
	$number_e = $db->real_escape_string($number);
	$dup = @$db->query(
		"SELECT id FROM users WHERE username='{$username_e}' OR email='{$email_e}'"
		. ($number !== '' ? " OR number='{$number_e}'" : '')
		. ' LIMIT 1'
	);
	if ($dup && $dup->num_rows > 0) {
		return ['ok' => false, 'error' => 'duplicate'];
	}

	$hash = tessera_password_hash($password);
	/* users.password is varchar(78) — bcrypt (~60) fits */
	if (strlen($hash) > 78) {
		return ['ok' => false, 'error' => 'password_column'];
	}

	$fn_e = $db->real_escape_string($firstname);
	$sn_e = $db->real_escape_string($surname);
	$role_e = $db->real_escape_string($role);
	$hash_e = $db->real_escape_string($hash);
	$img = 'user.png';

	$ok = control_insert_staff_user_row($db, $username, $hash, $firstname, $surname, $role, $email, $number);
	if (!$ok) {
		return ['ok' => false, 'error' => 'insert_failed'];
	}

	$userId = (int) $db->insert_id;
	if (control_table_exists($db, 'roles') && control_table_exists($db, 'roles_assigned')) {
		control_staff_assign_roles($db, $username, $role);
	}

	$auditConn = $auditDb ?? $db;
	control_audit($auditConn, $auditConn instanceof mysqli, $actor, 'staff.create', $tenant_id, [
		'user_id' => $userId,
		'username' => $username,
		'role' => $role,
	]);

	if ($tenant) {
		foreach (['cargo', 'account'] as $mod) {
			$other = control_operator_app_mysqli($tenant, $mod);
			if (!$other || $other === $db || !control_table_exists($other, 'users')) {
				continue;
			}
			$dupOther = @$other->query(
				"SELECT id FROM users WHERE username='{$username_e}' OR email='{$email_e}'"
				. ($number !== '' ? " OR number='{$number_e}'" : '')
				. ' LIMIT 1'
			);
			if ($dupOther && $dupOther->num_rows > 0) {
				continue;
			}
			if (control_insert_staff_user_row($other, $username, $hash, $firstname, $surname, $role, $email, $number)
				&& control_table_exists($other, 'roles')
				&& control_table_exists($other, 'roles_assigned')) {
				control_staff_assign_roles($other, $username, $role);
			}
		}
	}

	return ['ok' => true, 'user_id' => $userId];
}

/**
 * Reset operator staff password (audited).
 *
 * @return array{ok:bool,error?:string}
 */
function control_reset_operator_staff_password(?mysqli $db, bool $db_ok, int $tenant_id, int $user_id, string $password, string $actor, ?mysqli $auditDb = null, ?array $tenant = null): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'users') || strlen($password) < 6) {
		return ['ok' => false, 'error' => 'validation'];
	}
	$user_id = (int) $user_id;
	$hash = tessera_password_hash($password);
	if (strlen($hash) > 78) {
		return ['ok' => false, 'error' => 'password_column'];
	}
	$uq = @$db->query("SELECT username FROM users WHERE id={$user_id} LIMIT 1");
	$uname = ($uq && ($ur = $uq->fetch_assoc())) ? (string) ($ur['username'] ?? '') : '';
	$hash_e = $db->real_escape_string($hash);
	$ok = @$db->query("UPDATE users SET password='{$hash_e}' WHERE id={$user_id} LIMIT 1");
	if (!$ok || $db->affected_rows < 1) {
		return ['ok' => false, 'error' => 'not_found'];
	}
	if ($tenant && $uname !== '') {
		$ue = '';
		foreach (['cargo', 'account'] as $mod) {
			$other = control_operator_app_mysqli($tenant, $mod);
			if (!$other || $other === $db || !control_table_exists($other, 'users')) {
				continue;
			}
			if ($ue === '') {
				$ue = $other->real_escape_string($uname);
			}
			$hash_o = $other->real_escape_string($hash);
			@$other->query("UPDATE users SET password='{$hash_o}' WHERE username='{$ue}' LIMIT 1");
		}
	}
	$auditConn = $auditDb ?? $db;
	control_audit($auditConn, $auditConn instanceof mysqli, $actor, 'staff.password_reset', $tenant_id, ['user_id' => $user_id]);
	return ['ok' => true];
}
