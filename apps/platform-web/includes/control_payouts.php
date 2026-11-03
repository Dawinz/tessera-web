<?php
/**
 * Wave L — operator payout profiles + settlements + payment method mode.
 */

function control_get_tenant_payout(?mysqli $db, bool $db_ok, int $tenant_id): array {
	$empty = [
		'tenant_id' => $tenant_id,
		'preferred_method' => 'mobile_money',
		'bank_name' => '',
		'account_name' => '',
		'account_number' => '',
		'bank_branch' => '',
		'momo_network' => '',
		'momo_msisdn' => '',
		'momo_account_name' => '',
		'notes' => '',
		'verified_status' => 'unverified',
		'verified_at' => null,
		'verified_by' => null,
	];
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenant_payouts')) {
		return $empty;
	}
	$q = @$db->query('SELECT * FROM tessera_tenant_payouts WHERE tenant_id=' . (int) $tenant_id . ' LIMIT 1');
	$row = $q ? $q->fetch_assoc() : null;
	return $row ?: $empty;
}

function control_save_tenant_payout(?mysqli $db, bool $db_ok, int $tenant_id, array $input, string $actor, bool $asOperator = false): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenant_payouts')) {
		return false;
	}
	$pref = (string) ($input['preferred_method'] ?? 'mobile_money');
	if (!in_array($pref, ['bank', 'mobile_money'], true)) {
		$pref = 'mobile_money';
	}
	$bank_name = trim((string) ($input['bank_name'] ?? ''));
	$account_name = trim((string) ($input['account_name'] ?? ''));
	$account_number = trim((string) ($input['account_number'] ?? ''));
	$bank_branch = trim((string) ($input['bank_branch'] ?? ''));
	$momo_network = trim((string) ($input['momo_network'] ?? ''));
	$momo_msisdn = preg_replace('/\s+/', '', (string) ($input['momo_msisdn'] ?? '')) ?? '';
	$momo_account_name = trim((string) ($input['momo_account_name'] ?? ''));
	$notes = trim((string) ($input['notes'] ?? ''));

	$existing = control_get_tenant_payout($db, $db_ok, $tenant_id);
	$verified = (string) ($existing['verified_status'] ?? 'unverified');
	$verified_at = $existing['verified_at'] ?? null;
	$verified_by = $existing['verified_by'] ?? null;

	if (!$asOperator && isset($input['verified_status'])) {
		$vs = (string) $input['verified_status'];
		if (in_array($vs, ['unverified', 'pending', 'verified', 'rejected'], true)) {
			$verified = $vs;
			if ($vs === 'verified') {
				$verified_at = date('Y-m-d H:i:s');
				$verified_by = $actor;
			}
		}
	} elseif ($asOperator) {
		/* Operator edits reset verification */
		$verified = 'pending';
		$verified_at = null;
		$verified_by = null;
	}

	$tid = (int) $tenant_id;
	$pref_e = $db->real_escape_string($pref);
	$bn = $bank_name !== '' ? "'" . $db->real_escape_string($bank_name) . "'" : 'NULL';
	$an = $account_name !== '' ? "'" . $db->real_escape_string($account_name) . "'" : 'NULL';
	$ano = $account_number !== '' ? "'" . $db->real_escape_string($account_number) . "'" : 'NULL';
	$bb = $bank_branch !== '' ? "'" . $db->real_escape_string($bank_branch) . "'" : 'NULL';
	$mn = $momo_network !== '' ? "'" . $db->real_escape_string($momo_network) . "'" : 'NULL';
	$mm = $momo_msisdn !== '' ? "'" . $db->real_escape_string($momo_msisdn) . "'" : 'NULL';
	$man = $momo_account_name !== '' ? "'" . $db->real_escape_string($momo_account_name) . "'" : 'NULL';
	$nt = $notes !== '' ? "'" . $db->real_escape_string($notes) . "'" : 'NULL';
	$vs_e = $db->real_escape_string($verified);
	$va = $verified_at ? "'" . $db->real_escape_string((string) $verified_at) . "'" : 'NULL';
	$vb = $verified_by ? "'" . $db->real_escape_string((string) $verified_by) . "'" : 'NULL';
	$actor_e = $db->real_escape_string($actor);

	$ok = @$db->query(
		"INSERT INTO tessera_tenant_payouts
		 (tenant_id, preferred_method, bank_name, account_name, account_number, bank_branch,
		  momo_network, momo_msisdn, momo_account_name, notes, verified_status, verified_at, verified_by, updated_by)
		 VALUES ({$tid}, '{$pref_e}', {$bn}, {$an}, {$ano}, {$bb}, {$mn}, {$mm}, {$man}, {$nt}, '{$vs_e}', {$va}, {$vb}, '{$actor_e}')
		 ON DUPLICATE KEY UPDATE
		  preferred_method='{$pref_e}', bank_name={$bn}, account_name={$an}, account_number={$ano}, bank_branch={$bb},
		  momo_network={$mn}, momo_msisdn={$mm}, momo_account_name={$man}, notes={$nt},
		  verified_status='{$vs_e}', verified_at={$va}, verified_by={$vb}, updated_by='{$actor_e}'"
	);
	if ($ok) {
		control_audit($db, $db_ok, $actor, 'tenant.payout.save', $tenant_id, ['preferred' => $pref, 'verified' => $verified]);
	}
	return (bool) $ok;
}

function control_ensure_platform_payment_method(?mysqli $db, bool $db_ok, int $tenant_id): void {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenant_payment_methods')) {
		return;
	}
	$tid = (int) $tenant_id;
	@$db->query(
		"INSERT IGNORE INTO tessera_tenant_payment_methods (tenant_id, mode, gateway_type, status)
		 VALUES ({$tid}, 'platform', NULL, 'active')"
	);
}

function control_list_settlements(?mysqli $db, bool $db_ok, int $tenant_id, int $limit = 50): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_settlements')) {
		return [];
	}
	$limit = max(1, min(200, $limit));
	$q = @$db->query(
		'SELECT * FROM tessera_settlements WHERE tenant_id=' . (int) $tenant_id
		. ' ORDER BY id DESC LIMIT ' . $limit
	);
	$rows = [];
	if ($q) {
		while ($r = $q->fetch_assoc()) {
			$rows[] = $r;
		}
	}
	return $rows;
}

function control_create_settlement(?mysqli $db, bool $db_ok, int $tenant_id, array $input, string $actor): ?array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_settlements')) {
		return null;
	}
	$amount = (float) ($input['amount_tzs'] ?? 0);
	if ($amount <= 0) {
		return null;
	}
	$period = trim((string) ($input['period_label'] ?? ''));
	$notes = trim((string) ($input['notes'] ?? ''));
	$tid = (int) $tenant_id;
	$period_e = $db->real_escape_string($period);
	$notes_e = $notes !== '' ? "'" . $db->real_escape_string($notes) . "'" : 'NULL';
	$actor_e = $db->real_escape_string($actor);
	$ok = @$db->query(
		"INSERT INTO tessera_settlements (tenant_id, period_label, amount_tzs, status, notes, created_by)
		 VALUES ({$tid}, '{$period_e}', {$amount}, 'owed', {$notes_e}, '{$actor_e}')"
	);
	if (!$ok) {
		return null;
	}
	$id = (int) $db->insert_id;
	control_audit($db, $db_ok, $actor, 'settlement.create', $tenant_id, ['id' => $id, 'amount' => $amount]);
	$q = @$db->query('SELECT * FROM tessera_settlements WHERE id=' . $id . ' LIMIT 1');
	return $q ? $q->fetch_assoc() : null;
}

function control_mark_settlement_paid(?mysqli $db, bool $db_ok, int $id, string $actor, string $reference = ''): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_settlements')) {
		return false;
	}
	$ref_e = $reference !== '' ? "'" . $db->real_escape_string($reference) . "'" : 'NULL';
	$ok = @$db->query(
		"UPDATE tessera_settlements SET status='paid', paid_at=NOW(), reference={$ref_e}
		 WHERE id=" . (int) $id . " AND status IN ('owed','processing') LIMIT 1"
	);
	if ($ok && $db->affected_rows > 0) {
		control_audit($db, $db_ok, $actor, 'settlement.paid', null, ['id' => $id]);
		return true;
	}
	return false;
}
