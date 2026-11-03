<?php
/**
 * Wave M - tenant staff capabilities.
 */
require_once __DIR__ . '/tessera_tenant.php';

if (!function_exists('tessera_role_defaults')) {
	function tessera_role_defaults(): array {
		return [
			'owner' => ['settings','payouts','staff','tickets','cargo','accounts','notifications','api_keys','reports'],
			'manager' => ['settings','staff','tickets','cargo','accounts','notifications','reports'],
			'tickets_agent' => ['tickets'],
			'cargo_agent' => ['cargo'],
			'accountant' => ['accounts','reports','payouts'],
		];
	}
}

if (!function_exists('tessera_map_legacy_role')) {
	function tessera_map_legacy_role(string $legacy): string {
		$l = strtolower(trim($legacy));
		if (str_contains($l, 'super')) {
			return 'owner';
		}
		if (str_contains($l, 'admin')) {
			return 'manager';
		}
		if (str_contains($l, 'account')) {
			return 'accountant';
		}
		if (str_contains($l, 'cargo')) {
			return 'cargo_agent';
		}
		return 'tickets_agent';
	}
}

if (!function_exists('tessera_user_role_code')) {
	function tessera_user_role_code(mysqli $db, string $username): string {
		$u = $db->real_escape_string($username);
		$hasCol = false;
		$c = @$db->query("SHOW COLUMNS FROM users LIKE 'tessera_role'");
		if ($c && $c->num_rows > 0) {
			$hasCol = true;
		}
		if ($hasCol) {
			$q = @$db->query("SELECT tessera_role, role FROM users WHERE username='{$u}' LIMIT 1");
			$row = $q ? $q->fetch_assoc() : null;
			if ($row && !empty($row['tessera_role'])) {
				return (string) $row['tessera_role'];
			}
			if ($row) {
				return tessera_map_legacy_role((string) ($row['role'] ?? ''));
			}
		} else {
			$q = @$db->query("SELECT role FROM users WHERE username='{$u}' LIMIT 1");
			$row = $q ? $q->fetch_assoc() : null;
			if ($row) {
				return tessera_map_legacy_role((string) ($row['role'] ?? ''));
			}
		}
		return 'tickets_agent';
	}
}

if (!function_exists('tessera_user_capabilities')) {
	function tessera_user_capabilities(mysqli $db, string $username): array {
		$code = tessera_user_role_code($db, $username);
		if (function_exists('control_table_exists') === false) {
			/* local check */
		}
		$defs = tessera_role_defaults();
		$caps = $defs[$code] ?? $defs['tickets_agent'];
		$q = @$db->query("SHOW TABLES LIKE 'tessera_role_definitions'");
		if ($q && $q->num_rows > 0) {
			$code_e = $db->real_escape_string($code);
			$r = @$db->query("SELECT capabilities_json FROM tessera_role_definitions WHERE code='{$code_e}' LIMIT 1");
			$row = $r ? $r->fetch_assoc() : null;
			if ($row && !empty($row['capabilities_json'])) {
				$decoded = json_decode((string) $row['capabilities_json'], true);
				if (is_array($decoded)) {
					$caps = $decoded;
				}
			}
		}
		return array_values(array_unique(array_map('strval', $caps)));
	}
}

if (!function_exists('tessera_can')) {
	function tessera_can(mysqli $db, string $username, string $capability): bool {
		return in_array($capability, tessera_user_capabilities($db, $username), true);
	}
}

if (!function_exists('tessera_require_capability')) {
	function tessera_require_capability(mysqli $db, string $username, string $capability): void {
		if (!tessera_can($db, $username, $capability)) {
			http_response_code(403);
			echo 'Access denied for this role.';
			exit;
		}
	}
}

if (!function_exists('tessera_set_user_role')) {
	/**
	 * Assign Tessera role and sync legacy menus across Tickets / Cargo / Accounts.
	 * Uses tessera_staff_sync.php when available; otherwise updates ticket DB only.
	 */
	function tessera_set_user_role(mysqli $db, string $username, string $roleCode): bool {
		$allowed = array_keys(tessera_role_defaults());
		if (!in_array($roleCode, $allowed, true)) {
			return false;
		}
		$sync = __DIR__ . '/tessera_staff_sync.php';
		if (is_file($sync)) {
			require_once $sync;
			if (function_exists('tessera_apply_staff_role_everywhere')) {
				$res = tessera_apply_staff_role_everywhere($username, $roleCode);
				return !empty($res['ok']);
			}
		}
		$c = @$db->query("SHOW COLUMNS FROM users LIKE 'tessera_role'");
		if (!$c || $c->num_rows === 0) {
			return false;
		}
		$u = $db->real_escape_string($username);
		$r = $db->real_escape_string($roleCode);
		return (bool) @$db->query("UPDATE users SET tessera_role='{$r}' WHERE username='{$u}' LIMIT 1");
	}
}
