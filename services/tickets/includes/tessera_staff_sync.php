<?php
/**
 * Staff + menu role write-through across Tickets / Cargo / Accounts DBs.
 * Settings Tessera roles and legacy menu roles stay in sync.
 */
require_once __DIR__ . '/sr_env.php';
require_once __DIR__ . '/tessera_password.php';
require_once __DIR__ . '/tessera_rbac.php';

if (!function_exists('tessera_legacy_role_for_code')) {
	/** Map Tessera capability role -> legacy users.role / roles.level label. */
	function tessera_legacy_role_for_code(string $roleCode): string {
		switch ($roleCode) {
			case 'owner':
				return 'Super Administrator';
			case 'manager':
			case 'accountant':
				return 'Administrator';
			default:
				return 'Agent';
		}
	}
}

if (!function_exists('tessera_module_connections')) {
	/**
	 * @return array<string, mysqli> keyed ticket|cargo|account
	 */
	function tessera_module_connections(): array {
		$out = [];
		foreach (['ticket' => 'ticket', 'cargo' => 'cargo', 'account' => 'account'] as $key => $logical) {
			$db = @sr_mysqli($logical, false);
			if ($db instanceof mysqli && !$db->connect_error) {
				$out[$key] = $db;
			}
		}
		return $out;
	}
}

if (!function_exists('tessera_rebuild_user_menus')) {
	/**
	 * Replace roles_assigned for $username from the roles template for $legacyLevel.
	 */
	function tessera_rebuild_user_menus(mysqli $db, string $username, string $legacyLevel): bool {
		$u = $db->real_escape_string($username);
		$level = $db->real_escape_string($legacyLevel);
		@$db->query("DELETE FROM roles_assigned WHERE username='{$u}'");

		$q = @$db->query("SELECT name, level, file, category, arrangement FROM roles WHERE level='{$level}'");
		if (!$q) {
			return false;
		}
		$hasSubUrl = false;
		$c1 = @$db->query("SHOW COLUMNS FROM roles_assigned LIKE 'sub_url'");
		if ($c1 && $c1->num_rows > 0) {
			$hasSubUrl = true;
		}
		$hasSubRoute = false;
		$c2 = @$db->query("SHOW COLUMNS FROM roles_assigned LIKE 'sub_route'");
		if ($c2 && $c2->num_rows > 0) {
			$hasSubRoute = true;
		}
		$ok = true;
		$inserted = 0;
		while ($row = $q->fetch_assoc()) {
			$name = $db->real_escape_string((string) $row['name']);
			$lvl = $db->real_escape_string((string) $row['level']);
			$file = $db->real_escape_string((string) $row['file']);
			$cat = $db->real_escape_string((string) $row['category']);
			$arr = (int) $row['arrangement'];
			$cols = 'name, level, file, username, category, arrangement';
			$vals = "'{$name}','{$lvl}','{$file}','{$u}','{$cat}','{$arr}'";
			if ($hasSubUrl) {
				$cols .= ', sub_url';
				$vals .= ",''";
			}
			if ($hasSubRoute) {
				$cols .= ', sub_route';
				$vals .= ",''";
			}
			$ins = @$db->query("INSERT INTO roles_assigned ({$cols}) VALUES ({$vals})");
			if ($ins) {
				$inserted++;
			} else {
				$ok = false;
			}
		}
		/* Ensure Book list exists for admin-class roles (missing from some templates). */
		if (stripos($legacyLevel, 'admin') !== false || stripos($legacyLevel, 'super') !== false) {
			$chk = @$db->query("SELECT id FROM roles_assigned WHERE username='{$u}' AND file='tickets.php' LIMIT 1");
			if ($chk && $chk->num_rows === 0) {
				$cols = "name, level, file, username, category, arrangement";
				$vals = "'Book list','{$level}','tickets.php','{$u}','Book list',2";
				if ($hasSubUrl) {
					$cols .= ', sub_url';
					$vals .= ",''";
				}
				if ($hasSubRoute) {
					$cols .= ', sub_route';
					$vals .= ",''";
				}
				@$db->query("INSERT INTO roles_assigned ({$cols}) VALUES ({$vals})");
			}
		}
		/* Account templates can be thin - treat "user upserted + best-effort menus" as success when any rows inserted or template empty. */
		if ($inserted === 0 && $ok) {
			return true;
		}
		return $ok || $inserted > 0;
	}
}

if (!function_exists('tessera_upsert_staff_row')) {
	/**
	 * Upsert a staff user on one module DB (schema-tolerant).
	 *
	 * @param array{username:string,password?:string,firstname?:string,surname?:string,email?:string,number?:string,role:string,tessera_role?:string,status?:string} $user
	 */
	function tessera_upsert_staff_row(mysqli $db, array $user): bool {
		$username = trim((string) ($user['username'] ?? ''));
		if ($username === '') {
			return false;
		}
		$u = $db->real_escape_string($username);
		$role = $db->real_escape_string((string) ($user['role'] ?? 'Agent'));
		$firstname = $db->real_escape_string((string) ($user['firstname'] ?? $username));
		$surname = $db->real_escape_string((string) ($user['surname'] ?? ''));
		$email = $db->real_escape_string((string) ($user['email'] ?? ''));
		$number = $db->real_escape_string((string) ($user['number'] ?? ''));
		$status = $db->real_escape_string((string) ($user['status'] ?? 'active'));
		$tesseraRole = isset($user['tessera_role']) ? $db->real_escape_string((string) $user['tessera_role']) : '';

		$existing = @$db->query("SELECT id, password FROM users WHERE username='{$u}' LIMIT 1");
		$row = $existing ? $existing->fetch_assoc() : null;

		$passwordSql = '';
		if (!empty($user['password'])) {
			$ph = $db->real_escape_string(tessera_password_hash((string) $user['password']));
			$passwordSql = "password='{$ph}',";
		}

		$hasTessera = false;
		$c = @$db->query("SHOW COLUMNS FROM users LIKE 'tessera_role'");
		if ($c && $c->num_rows > 0) {
			$hasTessera = true;
		}
		$hasFullname = false;
		$c2 = @$db->query("SHOW COLUMNS FROM users LIKE 'fullname'");
		if ($c2 && $c2->num_rows > 0) {
			$hasFullname = true;
		}

		if ($row) {
			$setTessera = ($hasTessera && $tesseraRole !== '') ? ", tessera_role='{$tesseraRole}'" : '';
			$setFull = $hasFullname ? ", fullname='" . $db->real_escape_string(trim($firstname . ' ' . $surname)) . "'" : '';
			$sql = "UPDATE users SET {$passwordSql} firstname='{$firstname}', surname='{$surname}',
				email='{$email}', number='{$number}', role='{$role}', status='{$status}'{$setTessera}{$setFull}
				WHERE username='{$u}' LIMIT 1";
			return (bool) @$db->query($sql);
		}

		$ph = !empty($user['password'])
			? $db->real_escape_string(tessera_password_hash((string) $user['password']))
			: $db->real_escape_string(tessera_password_hash(bin2hex(random_bytes(8))));
		$cols = "username,password,firstname,surname,email,number,role,status,button_name,button_icon,button_class,image";
		$vals = "'{$u}','{$ph}','{$firstname}','{$surname}','{$email}','{$number}','{$role}','{$status}','De activate','uil uil-exclamation-octagon me-2','btn btn-soft-danger','user.png'";
		/* STRICT SQL: several legacy NOT NULL columns have no defaults. */
		$colCheck = static function (mysqli $db, string $col): bool {
			$c = @$db->query("SHOW COLUMNS FROM users LIKE '{$col}'");
			return $c && $c->num_rows > 0;
		};
		if ($colCheck($db, 'location')) {
			$cols .= ',location';
			$vals .= ",''";
		}
		if ($colCheck($db, 'level')) {
			$cols .= ',level';
			$vals .= ",'{$role}'";
		}
		if ($hasTessera) {
			$tr = $tesseraRole !== '' ? $tesseraRole : $db->real_escape_string(tessera_map_legacy_role($role));
			$cols .= ',tessera_role';
			$vals .= ",'{$tr}'";
		}
		if ($hasFullname) {
			$fn = $db->real_escape_string(trim($firstname . ' ' . $surname));
			$cols .= ',fullname';
			$vals .= ",'{$fn}'";
		}
		try {
			return (bool) @$db->query("INSERT INTO users ({$cols}) VALUES ({$vals})");
		} catch (Throwable $e) {
			return false;
		}
	}
}

if (!function_exists('tessera_apply_staff_role_everywhere')) {
	/**
	 * Set Tessera + legacy role, rebuild menus, mirror user to cargo/account.
	 *
	 * @return array{ok:bool,message:string,modules:array<string,bool>}
	 */
	function tessera_apply_staff_role_everywhere(string $username, string $roleCode, ?array $profile = null): array {
		$username = trim($username);
		$allowed = array_keys(tessera_role_defaults());
		if ($username === '' || !in_array($roleCode, $allowed, true)) {
			return ['ok' => false, 'message' => 'Invalid user or role.', 'modules' => []];
		}
		$legacy = tessera_legacy_role_for_code($roleCode);
		$modules = tessera_module_connections();
		if (empty($modules['ticket'])) {
			return ['ok' => false, 'message' => 'Tickets database unavailable.', 'modules' => []];
		}

		$ticket = $modules['ticket'];
		$u = $ticket->real_escape_string($username);
		$q = @$ticket->query("SELECT username, firstname, surname, email, number, password, status FROM users WHERE username='{$u}' LIMIT 1");
		$base = $q ? $q->fetch_assoc() : null;
		if (!$base && empty($profile['password'])) {
			return ['ok' => false, 'message' => 'User not found. Create the staff account first.', 'modules' => []];
		}

		$user = [
			'username' => $username,
			'firstname' => (string) ($profile['firstname'] ?? $base['firstname'] ?? $username),
			'surname' => (string) ($profile['surname'] ?? $base['surname'] ?? ''),
			'email' => (string) ($profile['email'] ?? $base['email'] ?? ''),
			'number' => (string) ($profile['number'] ?? $base['number'] ?? ''),
			'role' => $legacy,
			'tessera_role' => $roleCode,
			'status' => (string) ($profile['status'] ?? $base['status'] ?? 'active'),
		];
		if (!empty($profile['password'])) {
			$user['password'] = (string) $profile['password'];
		}

		$results = [];
		foreach ($modules as $key => $db) {
			try {
				mysqli_report(MYSQLI_REPORT_OFF);
				$okUser = tessera_upsert_staff_row($db, $user);
				/* Copy hashed password from ticket when only mirroring */
				if ($okUser && $key !== 'ticket' && empty($user['password']) && !empty($base['password'])) {
					$ph = $db->real_escape_string((string) $base['password']);
					@$db->query("UPDATE users SET password='{$ph}' WHERE username='{$u}' LIMIT 1");
				}
				$okMenus = false;
				/* Account DB may have a thin roles template - still try rebuild */
				$okMenus = tessera_rebuild_user_menus($db, $username, $legacy);
				$results[$key] = $okUser && $okMenus;
			} catch (Throwable $e) {
				$results[$key] = false;
			}
		}

		$all = !in_array(false, $results, true);
		return [
			'ok' => $all,
			'message' => $all
				? 'Role applied in Tickets, Cargo, and Accounts.'
				: 'Role partially applied: ' . json_encode($results),
			'modules' => $results,
		];
	}
}

if (!function_exists('tessera_create_staff_everywhere')) {
	/**
	 * Create staff in all module DBs with menus for the chosen Tessera role.
	 *
	 * @return array{ok:bool,message:string}
	 */
	function tessera_create_staff_everywhere(array $user, string $roleCode): array {
		$username = trim((string) ($user['username'] ?? ''));
		$password = (string) ($user['password'] ?? '');
		if ($username === '' || $password === '') {
			return ['ok' => false, 'message' => 'Username and password are required.'];
		}
		$allowed = array_keys(tessera_role_defaults());
		if (!in_array($roleCode, $allowed, true)) {
			return ['ok' => false, 'message' => 'Invalid role.'];
		}
		$modules = tessera_module_connections();
		if (empty($modules['ticket'])) {
			return ['ok' => false, 'message' => 'Tickets database unavailable.'];
		}
		$ticket = $modules['ticket'];
		$u = $ticket->real_escape_string($username);
		$exists = @$ticket->query("SELECT id FROM users WHERE username='{$u}' LIMIT 1");
		if ($exists && $exists->num_rows > 0) {
			return ['ok' => false, 'message' => 'Username already exists.'];
		}
		$user['role'] = tessera_legacy_role_for_code($roleCode);
		$user['tessera_role'] = $roleCode;
		$user['password'] = $password;
		return tessera_apply_staff_role_everywhere($username, $roleCode, $user);
	}
}
