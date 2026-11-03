<?php
/**
 * Hybrid DB provisioner - dedicated ticket/cargo/account databases per operator.
 * Platform registry always stays on the shared ticket DB.
 */
require_once __DIR__ . '/sr_env.php';

if (!function_exists('tessera_db_name_for_slug')) {
	/** Safe MySQL database name fragment from tenant slug. */
	function tessera_db_name_for_slug(string $slug, string $module): string {
		$safe = preg_replace('/[^a-z0-9_]/', '_', strtolower($slug)) ?? 'tenant';
		$safe = trim($safe, '_');
		if ($safe === '') {
			$safe = 'tenant';
		}
		/* MySQL limit 64 chars: t_{slug}_{module} */
		$map = ['ticket' => 'ticket', 'cargo' => 'cargo', 'account' => 'account', 'accounts' => 'account'];
		$mod = $map[$module] ?? 'ticket';
		$maxSlug = 64 - strlen('t__') - strlen($mod);
		if (strlen($safe) > $maxSlug) {
			$safe = substr($safe, 0, $maxSlug);
		}
		return 't_' . $safe . '_' . $mod;
	}
}

if (!function_exists('tessera_db_admin_mysqli')) {
	/** Privileged connection (no default DB) for CREATE DATABASE / cloning. */
	function tessera_db_admin_mysqli(): ?mysqli {
		$cfg = sr_db_config_platform(getenv('SR_DB_NAME') ?: 'ticket');
		$db = @mysqli_init();
		if (!$db) {
			return null;
		}
		@$db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
		$ok = @$db->real_connect($cfg['host'], $cfg['user'], $cfg['pass'], '', $cfg['port']);
		if (!$ok || $db->connect_error) {
			$db = @mysqli_init();
			if (!$db) {
				return null;
			}
			@$db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
			$ok = @$db->real_connect($cfg['host'], 'root', $cfg['pass'], '', $cfg['port']);
		}
		if (!$db || $db->connect_error) {
			return null;
		}
		$db->set_charset('utf8mb4');
		return $db;
	}
}

if (!function_exists('tessera_db_seed_allowlist')) {
	/**
	 * Only these tables get row copies into a new operator DB (schema is cloned for all).
	 * Business tables stay empty. Platform tessera_* tables are never seeded.
	 * @return list<string>
	 */
	function tessera_db_seed_allowlist(string $module, bool $copyUsers): array {
		/* Lean templates only - never clone flagship bookings, staff, locations, or API secrets. */
		$base = [
			'currency', 'roles', 'category', 'sub_category',
			'constant', 'constants',
		];
		if ($copyUsers) {
			$base[] = 'users';
		}
		return $base;
	}
}

if (!function_exists('tessera_db_seed_empty_reference_tables')) {
	/**
	 * Copy allowlisted reference rows when the destination table is empty.
	 * Safe to run on already-provisioned operator DBs (does not overwrite).
	 * Ops nav (Home, Schedule, Manifest, ...) is built from `category` - that
	 * table was missing from the original seed list, so new operators had no tabs.
	 *
	 * @return list<string> table names seeded
	 */
	function tessera_db_seed_empty_reference_tables(mysqli $admin, string $src, string $dst, string $module, bool $copyUsers = false): array {
		$src = preg_replace('/[^a-zA-Z0-9_]/', '', $src) ?? '';
		$dst = preg_replace('/[^a-zA-Z0-9_]/', '', $dst) ?? '';
		if ($src === '' || $dst === '' || $src === $dst) {
			return [];
		}
		$seeded = [];
		foreach (tessera_db_seed_allowlist($module, $copyUsers) as $t) {
			$tSafe = preg_replace('/[^a-zA-Z0-9_]/', '', $t) ?? '';
			if ($tSafe === '' || str_starts_with($tSafe, 'tessera_')) {
				continue;
			}
			$srcHas = $admin->query("SHOW TABLES FROM `{$src}` LIKE '{$tSafe}'");
			$dstHas = $admin->query("SHOW TABLES FROM `{$dst}` LIKE '{$tSafe}'");
			if (!$srcHas || $srcHas->num_rows === 0 || !$dstHas || $dstHas->num_rows === 0) {
				continue;
			}
			$chk = $admin->query("SELECT COUNT(*) AS c FROM `{$dst}`.`{$tSafe}`");
			$already = ($chk && ($cr = $chk->fetch_assoc())) ? (int) $cr['c'] : 0;
			if ($already > 0) {
				continue;
			}
			if (@$admin->query("INSERT INTO `{$dst}`.`{$tSafe}` SELECT * FROM `{$src}`.`{$tSafe}`")) {
				$seeded[] = $tSafe;
			}
		}
		return $seeded;
	}
}

if (!function_exists('tessera_db_apply_role_templates')) {
	/**
	 * Give each local user the menu template for their role when they have no assignments yet.
	 * Cheaper and cleaner than copying flagship `roles_assigned` (all SR Classic staff).
	 */
	function tessera_db_apply_role_templates(mysqli $admin, string $dst): int {
		$dst = preg_replace('/[^a-zA-Z0-9_]/', '', $dst) ?? '';
		if ($dst === '') {
			return 0;
		}
		$hasRoles = $admin->query("SHOW TABLES FROM `{$dst}` LIKE 'roles'");
		$hasAssigned = $admin->query("SHOW TABLES FROM `{$dst}` LIKE 'roles_assigned'");
		$hasUsers = $admin->query("SHOW TABLES FROM `{$dst}` LIKE 'users'");
		if (!$hasRoles || $hasRoles->num_rows === 0 || !$hasAssigned || $hasAssigned->num_rows === 0 || !$hasUsers || $hasUsers->num_rows === 0) {
			return 0;
		}
		$users = $admin->query("SELECT username, role FROM `{$dst}`.`users`");
		if (!$users) {
			return 0;
		}
		$n = 0;
		while ($u = $users->fetch_assoc()) {
			$username = (string) ($u['username'] ?? '');
			$role = (string) ($u['role'] ?? '');
			if ($username === '' || $role === '') {
				continue;
			}
			$ue = $admin->real_escape_string($username);
			$re = $admin->real_escape_string($role);
			$chk = $admin->query("SELECT COUNT(*) AS c FROM `{$dst}`.`roles_assigned` WHERE username='{$ue}'");
			$have = ($chk && ($cr = $chk->fetch_assoc())) ? (int) $cr['c'] : 0;
			if ($have > 0) {
				continue;
			}
			$ok = $admin->query(
				"INSERT INTO `{$dst}`.`roles_assigned` (name, level, file, username, category, arrangement)
				 SELECT name, level, file, '{$ue}', category, arrangement
				 FROM `{$dst}`.`roles`
				 WHERE level='{$re}'"
			);
			if ($ok) {
				$n += (int) $admin->affected_rows;
			}
		}
		return $n;
	}
}

if (!function_exists('tessera_db_clone_schema')) {
	/**
	 * Create $dst DB and clone table structure (+ allowlisted seed data) from $src.
	 * @return array{ok:bool,tables:int,seeded:int,error?:string}
	 */
	function tessera_db_clone_schema(mysqli $admin, string $src, string $dst, string $module = 'ticket', bool $copyUsers = false): array {
		$src = preg_replace('/[^a-zA-Z0-9_]/', '', $src) ?? '';
		$dst = preg_replace('/[^a-zA-Z0-9_]/', '', $dst) ?? '';
		if ($src === '' || $dst === '' || $src === $dst) {
			return ['ok' => false, 'tables' => 0, 'seeded' => 0, 'error' => 'bad db name'];
		}
		if (!$admin->query("CREATE DATABASE IF NOT EXISTS `{$dst}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
			return ['ok' => false, 'tables' => 0, 'seeded' => 0, 'error' => $admin->error];
		}
		$tables = [];
		$q = $admin->query("SHOW TABLES FROM `{$src}`");
		if (!$q) {
			return ['ok' => false, 'tables' => 0, 'seeded' => 0, 'error' => $admin->error];
		}
		while ($row = $q->fetch_row()) {
			$tables[] = (string) $row[0];
		}
		$allow = array_fill_keys(tessera_db_seed_allowlist($module, $copyUsers), true);
		$count = 0;
		$seeded = 0;
		foreach ($tables as $t) {
			$tSafe = preg_replace('/[^a-zA-Z0-9_]/', '', $t) ?? '';
			if ($tSafe === '') {
				continue;
			}
			if (!$admin->query("CREATE TABLE IF NOT EXISTS `{$dst}`.`{$tSafe}` LIKE `{$src}`.`{$tSafe}`")) {
				return ['ok' => false, 'tables' => $count, 'seeded' => $seeded, 'error' => "create {$tSafe}: {$admin->error}"];
			}
			$count++;
			if (!isset($allow[$tSafe]) || str_starts_with($tSafe, 'tessera_')) {
				continue;
			}
			$chk = $admin->query("SELECT COUNT(*) AS c FROM `{$dst}`.`{$tSafe}`");
			$already = ($chk && ($cr = $chk->fetch_assoc())) ? (int) $cr['c'] : 0;
			if ($already > 0) {
				continue;
			}
			if (@$admin->query("INSERT INTO `{$dst}`.`{$tSafe}` SELECT * FROM `{$src}`.`{$tSafe}`")) {
				$seeded++;
			}
		}
		return ['ok' => true, 'tables' => $count, 'seeded' => $seeded];
	}
}

if (!function_exists('tessera_provision_tenant_databases')) {
	/**
	 * Ensure dedicated DBs exist for a tenant row and persist names on tessera_tenants.
	 * @param array<string,mixed> $opts copy_users?: bool, force?: bool
	 * @return array{ok:bool,slug:string,db_ticket?:string,db_cargo?:string,db_account?:string,error?:string,details?:array}
	 */
	function tessera_provision_tenant_databases(string $slug, array $opts = []): array {
		$slug = strtolower(trim($slug));
		if ($slug === '' || !preg_match('/^[a-z0-9-]+$/', $slug)) {
			return ['ok' => false, 'slug' => $slug, 'error' => 'invalid slug'];
		}
		if ($slug === 'sr-classic') {
			return ['ok' => true, 'slug' => $slug, 'db_ticket' => null, 'db_cargo' => null, 'db_account' => null, 'error' => 'flagship uses shared DBs'];
		}

		$reg = function_exists('tessera_registry_mysqli') ? tessera_registry_mysqli() : null;
		if (!$reg && function_exists('sr_mysqli_platform')) {
			$reg = sr_mysqli_platform(getenv('SR_DB_NAME') ?: 'ticket');
		}
		if (!$reg) {
			$adminProbe = tessera_db_admin_mysqli();
			if ($adminProbe) {
				$cfg = sr_db_config_platform(getenv('SR_DB_NAME') ?: 'ticket');
				$adminProbe->select_db($cfg['name']);
				$reg = $adminProbe;
			}
		}
		if (!$reg) {
			return ['ok' => false, 'slug' => $slug, 'error' => 'registry unavailable'];
		}

		$slug_e = $reg->real_escape_string($slug);
		$q = $reg->query("SELECT * FROM tessera_tenants WHERE slug='{$slug_e}' LIMIT 1");
		$row = $q ? $q->fetch_assoc() : null;
		if (!$row) {
			return ['ok' => false, 'slug' => $slug, 'error' => 'tenant not found'];
		}

		$dbTicket = tessera_db_name_for_slug($slug, 'ticket');
		$dbCargo = tessera_db_name_for_slug($slug, 'cargo');
		$dbAccount = tessera_db_name_for_slug($slug, 'account');

		$srcTicket = getenv('SR_DB_NAME') ?: 'ticket';
		$srcCargo = getenv('SR_CARGO_DB') ?: 'cargo';
		$srcAccount = getenv('SR_ACCOUNT_DB') ?: 'account';
		$copyUsers = array_key_exists('copy_users', $opts) ? (bool) $opts['copy_users'] : false;

		if (
			empty($opts['force'])
			&& ($row['db_mode'] ?? '') === 'dedicated'
			&& !empty($row['db_ticket'])
			&& !empty($row['db_provisioned_at'])
		) {
			$repair = [];
			$adminRepair = tessera_db_admin_mysqli();
			if ($adminRepair) {
				$dt = (string) $row['db_ticket'];
				$dc = (string) ($row['db_cargo'] ?? $dbCargo);
				$da = (string) ($row['db_account'] ?? $dbAccount);
				$repair['ticket'] = tessera_db_seed_empty_reference_tables($adminRepair, $srcTicket, $dt, 'ticket', $copyUsers);
				$repair['cargo'] = tessera_db_seed_empty_reference_tables($adminRepair, $srcCargo, $dc, 'cargo', $copyUsers);
				$repair['account'] = tessera_db_seed_empty_reference_tables($adminRepair, $srcAccount, $da, 'account', $copyUsers);
				$repair['menus_ticket'] = tessera_db_apply_role_templates($adminRepair, $dt);
				$repair['menus_cargo'] = tessera_db_apply_role_templates($adminRepair, $dc);
				$repair['menus_account'] = tessera_db_apply_role_templates($adminRepair, $da);
			}
			return [
				'ok' => true,
				'slug' => $slug,
				'db_ticket' => (string) $row['db_ticket'],
				'db_cargo' => (string) ($row['db_cargo'] ?? $dbCargo),
				'db_account' => (string) ($row['db_account'] ?? $dbAccount),
				'details' => ['skipped' => 'already provisioned', 'reference_seed' => $repair],
			];
		}

		$admin = tessera_db_admin_mysqli();
		if (!$admin) {
			return ['ok' => false, 'slug' => $slug, 'error' => 'admin connection failed'];
		}

		$details = [];
		$r1 = tessera_db_clone_schema($admin, $srcTicket, $dbTicket, 'ticket', $copyUsers);
		$details['ticket'] = $r1;
		if (!$r1['ok']) {
			return ['ok' => false, 'slug' => $slug, 'error' => 'ticket clone: ' . ($r1['error'] ?? '?'), 'details' => $details];
		}
		$r2 = tessera_db_clone_schema($admin, $srcCargo, $dbCargo, 'cargo', $copyUsers);
		$details['cargo'] = $r2;
		if (!$r2['ok']) {
			return ['ok' => false, 'slug' => $slug, 'error' => 'cargo clone: ' . ($r2['error'] ?? '?'), 'details' => $details];
		}
		$r3 = tessera_db_clone_schema($admin, $srcAccount, $dbAccount, 'account', $copyUsers);
		$details['account'] = $r3;
		if (!$r3['ok']) {
			return ['ok' => false, 'slug' => $slug, 'error' => 'account clone: ' . ($r3['error'] ?? '?'), 'details' => $details];
		}

		/* Stamp tenant_id defaults inside dedicated DBs when columns exist. */
		$tenantId = (int) $row['id'];
		foreach (
			[
				$dbTicket => ['vehicle', 'routes', 'route_day', 'booking', 'mobile_trips', 'mobile_bookings'],
				$dbCargo => ['parcel_info'],
				$dbAccount => ['vehicle_account'],
			] as $dbName => $tables
		) {
			foreach ($tables as $t) {
				$col = $admin->query("SHOW COLUMNS FROM `{$dbName}`.`{$t}` LIKE 'tenant_id'");
				if ($col && $col->num_rows > 0) {
					@$admin->query("ALTER TABLE `{$dbName}`.`{$t}` ALTER COLUMN tenant_id SET DEFAULT {$tenantId}");
				}
			}
		}

		$details['menus_ticket'] = tessera_db_apply_role_templates($admin, $dbTicket);
		$details['menus_cargo'] = tessera_db_apply_role_templates($admin, $dbCargo);
		$details['menus_account'] = tessera_db_apply_role_templates($admin, $dbAccount);

		$dt = $reg->real_escape_string($dbTicket);
		$dc = $reg->real_escape_string($dbCargo);
		$da = $reg->real_escape_string($dbAccount);
		$ok = $reg->query(
			"UPDATE tessera_tenants
			 SET db_mode='dedicated', db_ticket='{$dt}', db_cargo='{$dc}', db_account='{$da}', db_provisioned_at=NOW()
			 WHERE slug='{$slug_e}' LIMIT 1"
		);
		if (!$ok) {
			return ['ok' => false, 'slug' => $slug, 'error' => 'registry update: ' . $reg->error, 'details' => $details];
		}

		return [
			'ok' => true,
			'slug' => $slug,
			'db_ticket' => $dbTicket,
			'db_cargo' => $dbCargo,
			'db_account' => $dbAccount,
			'details' => $details,
		];
	}
}

if (!function_exists('tessera_provision_all_dedicated_tenants')) {
	/**
	 * Provision dedicated DBs for every non-flagship tenant marked dedicated / missing names.
	 * @return list<array<string,mixed>>
	 */
	function tessera_provision_all_dedicated_tenants(array $opts = []): array {
		require_once __DIR__ . '/tessera_tenant.php';
		$reg = tessera_registry_mysqli();
		if (!$reg) {
			return [['ok' => false, 'error' => 'registry unavailable']];
		}
		$out = [];
		$q = $reg->query(
			"SELECT slug FROM tessera_tenants
			 WHERE slug <> 'sr-classic'
			   AND (db_mode='dedicated' OR db_ticket IS NULL OR db_ticket='')
			 ORDER BY id ASC"
		);
		if (!$q) {
			return [['ok' => false, 'error' => $reg->error]];
		}
		while ($row = $q->fetch_assoc()) {
			$out[] = tessera_provision_tenant_databases((string) $row['slug'], $opts);
		}
		return $out;
	}
}
