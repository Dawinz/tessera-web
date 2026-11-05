<?php
/**
 * Operator settings — staff helpers (S-02).
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_rbac.php';
require_once __DIR__ . '/tessera_staff_sync.php';

if (!function_exists('tessera_settings_staff_role_meta')) {
	/**
	 * @return array{roles:list<array{code:string,label:string,description:string,capabilities:list<string>}>}
	 */
	function tessera_settings_staff_role_meta(): array
	{
		$labels = [
			'owner' => 'Owner',
			'manager' => 'Manager',
			'tickets_agent' => 'Tickets agent',
			'cargo_agent' => 'Cargo agent',
			'accountant' => 'Accountant',
		];
		$descriptions = [
			'owner' => 'Full access — settings, staff, all modules, API keys, payouts.',
			'manager' => 'Operations lead — staff, tickets, cargo, accounts, reports.',
			'tickets_agent' => 'Booking desk — tickets module only.',
			'cargo_agent' => 'Cargo desk — cargo module only.',
			'accountant' => 'Finance — accounts, reports, and payouts.',
		];
		$defs = tessera_role_defaults();
		$roles = [];
		foreach ($labels as $code => $label) {
			$roles[] = [
				'code' => $code,
				'label' => $label,
				'description' => $descriptions[$code] ?? '',
				'capabilities' => $defs[$code] ?? [],
			];
		}
		return ['roles' => $roles];
	}
}

if (!function_exists('tessera_settings_staff_list')) {
	/**
	 * @return array{items:list<array<string,mixed>>,summary:array{total:int,active:int,owner:int,agent:int}}
	 */
	function tessera_settings_staff_list(mysqli $db, string $q = ''): array
	{
		$hasCol = false;
		$c = @$db->query("SHOW COLUMNS FROM users LIKE 'tessera_role'");
		if ($c && $c->num_rows > 0) {
			$hasCol = true;
		}
		$sql = $hasCol
			? 'SELECT username, firstname, surname, email, number, role, tessera_role, status FROM users'
			: 'SELECT username, firstname, surname, email, number, role, status FROM users';
		$parts = [];
		$q = trim($q);
		if ($q !== '') {
			$like = '%' . $db->real_escape_string($q) . '%';
			$parts[] = "(username LIKE '{$like}' OR firstname LIKE '{$like}' OR surname LIKE '{$like}')";
		}
		if ($parts !== []) {
			$sql .= ' WHERE ' . implode(' AND ', $parts);
		}
		$sql .= ' ORDER BY username ASC LIMIT 300';

		$labels = [
			'owner' => 'Owner',
			'manager' => 'Manager',
			'tickets_agent' => 'Tickets agent',
			'cargo_agent' => 'Cargo agent',
			'accountant' => 'Accountant',
		];
		$items = [];
		$summary = ['total' => 0, 'active' => 0, 'owner' => 0, 'agent' => 0];
		$rq = @$db->query($sql);
		if ($rq) {
			while ($row = $rq->fetch_assoc()) {
				$effective = !empty($row['tessera_role'])
					? (string) $row['tessera_role']
					: tessera_map_legacy_role((string) ($row['role'] ?? ''));
				$item = [
					'username' => (string) ($row['username'] ?? ''),
					'firstname' => (string) ($row['firstname'] ?? ''),
					'surname' => (string) ($row['surname'] ?? ''),
					'fullname' => trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['surname'] ?? '')),
					'email' => (string) ($row['email'] ?? ''),
					'number' => (string) ($row['number'] ?? ''),
					'legacy_role' => (string) ($row['role'] ?? ''),
					'role' => $effective,
					'role_label' => $labels[$effective] ?? $effective,
					'status' => (string) ($row['status'] ?? ''),
					'menus_url' => '/settings/staff.php?tab=menus&user=' . rawurlencode((string) ($row['username'] ?? '')),
				];
				$items[] = $item;
				$summary['total']++;
				if (($item['status'] ?? '') === 'active') {
					$summary['active']++;
				}
				if ($effective === 'owner') {
					$summary['owner']++;
				}
				if (in_array($effective, ['tickets_agent', 'cargo_agent'], true)) {
					$summary['agent']++;
				}
			}
		}
		return ['items' => $items, 'summary' => $summary];
	}
}
