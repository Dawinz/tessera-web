<?php
/**
 * Operator payout helpers (ticket DB) - used by Settings and can mirror Control.
 */
if (!function_exists('tessera_table_exists')) {
	function tessera_table_exists(mysqli $db, string $table): bool {
		$t = $db->real_escape_string($table);
		$r = @$db->query("SHOW TABLES LIKE '{$t}'");
		return $r && $r->num_rows > 0;
	}
}

if (!function_exists('tessera_get_tenant_payout')) {
	function tessera_get_tenant_payout(mysqli $db, int $tenant_id): array {
		$empty = [
			'tenant_id' => $tenant_id,
			'preferred_method' => 'mobile_money',
			'bank_name' => '', 'account_name' => '', 'account_number' => '', 'bank_branch' => '',
			'momo_network' => '', 'momo_msisdn' => '', 'momo_account_name' => '', 'notes' => '',
			'verified_status' => 'unverified',
		];
		if (!tessera_table_exists($db, 'tessera_tenant_payouts')) {
			return $empty;
		}
		$q = @$db->query('SELECT * FROM tessera_tenant_payouts WHERE tenant_id=' . (int) $tenant_id . ' LIMIT 1');
		$row = $q ? $q->fetch_assoc() : null;
		return $row ?: $empty;
	}
}

if (!function_exists('tessera_save_tenant_payout')) {
	function tessera_save_tenant_payout(mysqli $db, int $tenant_id, array $input, string $actor, bool $asOperator = true): bool {
		if (!tessera_table_exists($db, 'tessera_tenant_payouts')) {
			return false;
		}
		$pref = (string) ($input['preferred_method'] ?? 'mobile_money');
		if (!in_array($pref, ['bank', 'mobile_money'], true)) {
			$pref = 'mobile_money';
		}
		$existing = tessera_get_tenant_payout($db, $tenant_id);
		$verified = $asOperator ? 'pending' : (string) ($input['verified_status'] ?? $existing['verified_status'] ?? 'unverified');
		$fields = [
			'bank_name', 'account_name', 'account_number', 'bank_branch',
			'momo_network', 'momo_msisdn', 'momo_account_name', 'notes',
		];
		$vals = [];
		foreach ($fields as $f) {
			$v = trim((string) ($input[$f] ?? ''));
			if ($f === 'momo_msisdn') {
				$v = preg_replace('/\s+/', '', $v) ?? '';
			}
			$vals[$f] = $v;
		}
		$tid = (int) $tenant_id;
		$pref_e = $db->real_escape_string($pref);
		$vs_e = $db->real_escape_string($verified);
		$actor_e = $db->real_escape_string($actor);
		$sqlParts = [];
		foreach ($vals as $k => $v) {
			$sqlParts[$k] = $v !== '' ? "'" . $db->real_escape_string($v) . "'" : 'NULL';
		}
		return (bool) @$db->query(
			"INSERT INTO tessera_tenant_payouts
			 (tenant_id, preferred_method, bank_name, account_name, account_number, bank_branch,
			  momo_network, momo_msisdn, momo_account_name, notes, verified_status, updated_by)
			 VALUES ({$tid}, '{$pref_e}', {$sqlParts['bank_name']}, {$sqlParts['account_name']}, {$sqlParts['account_number']}, {$sqlParts['bank_branch']},
			  {$sqlParts['momo_network']}, {$sqlParts['momo_msisdn']}, {$sqlParts['momo_account_name']}, {$sqlParts['notes']}, '{$vs_e}', '{$actor_e}')
			 ON DUPLICATE KEY UPDATE
			  preferred_method='{$pref_e}', bank_name={$sqlParts['bank_name']}, account_name={$sqlParts['account_name']},
			  account_number={$sqlParts['account_number']}, bank_branch={$sqlParts['bank_branch']},
			  momo_network={$sqlParts['momo_network']}, momo_msisdn={$sqlParts['momo_msisdn']},
			  momo_account_name={$sqlParts['momo_account_name']}, notes={$sqlParts['notes']},
			  verified_status='{$vs_e}', updated_by='{$actor_e}'"
		);
	}
}

if (!function_exists('tessera_list_settlements')) {
	function tessera_list_settlements(mysqli $db, int $tenant_id, int $limit = 50): array {
		if (!tessera_table_exists($db, 'tessera_settlements')) {
			return [];
		}
		$limit = max(1, min(200, $limit));
		$q = @$db->query('SELECT * FROM tessera_settlements WHERE tenant_id=' . (int) $tenant_id . ' ORDER BY id DESC LIMIT ' . $limit);
		$rows = [];
		if ($q) {
			while ($r = $q->fetch_assoc()) {
				$rows[] = $r;
			}
		}
		return $rows;
	}
}
