<?php
/**
 * Operator settings — payout helpers (S-04).
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_payouts.php';

if (!function_exists('tessera_settings_payouts_public')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_settings_payouts_public(array $row): array
	{
		$status = (string) ($row['verified_status'] ?? 'unverified');
		return [
			'preferred_method' => (string) ($row['preferred_method'] ?? 'mobile_money'),
			'bank_name' => (string) ($row['bank_name'] ?? ''),
			'account_name' => (string) ($row['account_name'] ?? ''),
			'account_number' => (string) ($row['account_number'] ?? ''),
			'bank_branch' => (string) ($row['bank_branch'] ?? ''),
			'momo_network' => (string) ($row['momo_network'] ?? ''),
			'momo_msisdn' => (string) ($row['momo_msisdn'] ?? ''),
			'momo_account_name' => (string) ($row['momo_account_name'] ?? ''),
			'notes' => (string) ($row['notes'] ?? ''),
			'verified_status' => $status,
			'verified_label' => $status === 'verified' ? 'Verified' : ($status === 'pending' ? 'Pending verification' : 'Unverified'),
			'methods' => [
				['code' => 'mobile_money', 'label' => 'Mobile money'],
				['code' => 'bank', 'label' => 'Bank transfer'],
			],
		];
	}
}

if (!function_exists('tessera_settings_payouts_settlements_public')) {
	/**
	 * @param list<array<string,mixed>> $rows
	 * @return list<array<string,mixed>>
	 */
	function tessera_settings_payouts_settlements_public(array $rows): array
	{
		$out = [];
		foreach ($rows as $s) {
			$out[] = [
				'id' => (int) ($s['id'] ?? 0),
				'period_label' => (string) ($s['period_label'] ?? ''),
				'amount_tzs' => (float) ($s['amount_tzs'] ?? 0),
				'currency' => (string) ($s['currency'] ?? 'TZS'),
				'status' => (string) ($s['status'] ?? ''),
				'paid_at' => $s['paid_at'] ?? null,
			];
		}
		return $out;
	}
}

if (!function_exists('tessera_settings_payouts_get')) {
	/**
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>,settlements?:list<array<string,mixed>>}
	 */
	function tessera_settings_payouts_get(mysqli $db, int $tenantId): array
	{
		if ($tenantId < 1) {
			return ['ok' => false, 'error' => 'No tenant context'];
		}
		$payout = tessera_get_tenant_payout($db, $tenantId);
		$settlements = tessera_list_settlements($db, $tenantId, 15);
		return [
			'ok' => true,
			'item' => tessera_settings_payouts_public($payout),
			'settlements' => tessera_settings_payouts_settlements_public($settlements),
		];
	}
}

if (!function_exists('tessera_settings_payouts_save')) {
	/**
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>,settlements?:list<array<string,mixed>>,message?:string}
	 */
	function tessera_settings_payouts_save(mysqli $db, int $tenantId, array $input, string $actor): array
	{
		if ($tenantId < 1) {
			return ['ok' => false, 'error' => 'No tenant context'];
		}
		$ok = tessera_save_tenant_payout($db, $tenantId, $input, $actor, true);
		if (!$ok) {
			return ['ok' => false, 'error' => 'Could not save (ensure migration 011 is applied)'];
		}
		$got = tessera_settings_payouts_get($db, $tenantId);
		$got['message'] = 'Payout profile saved. Verification may be required before the next settlement.';
		return $got;
	}
}
