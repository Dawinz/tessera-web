<?php
/**
 * Tessera Control data helpers (tenants, onboarding, audit).
 */

function control_default_checklist(): array {
	return [
		['id' => 'kickoff', 'label' => 'Sales kickoff — routes, agents, fares, MID, brand kit', 'done' => false],
		['id' => 'tenant', 'label' => 'Provision tenant + slug subdomain', 'done' => false],
		['id' => 'dns', 'label' => 'DNS / SSL for {slug}.tessera.co.tz (+ custom domain)', 'done' => false],
		['id' => 'console', 'label' => 'Operator console live (entitled modules)', 'done' => false],
		['id' => 'branding', 'label' => 'White-label logo + primary color applied', 'done' => false],
		['id' => 'payments', 'label' => 'Platform collection rail configured (owner-only)', 'done' => false],
		['id' => 'payouts', 'label' => 'Operator payout profile collected + verified', 'done' => false],
		['id' => 'mobiles', 'label' => 'Operator customer + agent apps scoped', 'done' => false],
		['id' => 'uat', 'label' => 'UAT signed off (QA playbooks)', 'done' => false],
		['id' => 'training', 'label' => 'Staff training complete', 'done' => false],
		['id' => 'golive', 'label' => 'Go-live + hypercare rota set', 'done' => false],
	];
}

function control_stage_labels(): array {
	return [
		'kickoff' => 'Kickoff',
		'provisioning' => 'Provisioning',
		'migration' => 'Migration',
		'uat' => 'UAT',
		'training' => 'Training',
		'golive' => 'Go-live',
		'hypercare' => 'Hypercare',
		'done' => 'Done',
		'cancelled' => 'Cancelled',
	];
}

function control_table_exists(mysqli $db, string $table): bool {
	$t = $db->real_escape_string($table);
	$r = @$db->query("SHOW TABLES LIKE '{$t}'");
	return $r && $r->num_rows > 0;
}

function control_demo_tenants(): array {
	return [[
		'id' => 1,
		'slug' => 'sr-classic',
		'legal_name' => 'SR Classic Coach',
		'display_name' => 'SR Classic Coach',
		'status' => 'active',
		'plan' => 'flagship',
		'primary_domain' => 'tessera.co.tz',
		'logo_url' => null,
		'primary_color' => '#E53E3E',
		'created_at' => '2025-01-12 10:00:00',
		'updated_at' => date('Y-m-d H:i:s'),
	]];
}

function control_list_tenants(?mysqli $db, bool $db_ok): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenants')) {
		return ['rows' => control_demo_tenants(), 'source' => 'demo'];
	}
	$rows = [];
	$q = $db->query('SELECT * FROM tessera_tenants ORDER BY id ASC');
	if ($q) {
		while ($row = $q->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	if (!$rows) {
		return ['rows' => control_demo_tenants(), 'source' => 'demo'];
	}
	return ['rows' => $rows, 'source' => 'mysql'];
}

function control_get_tenant(?mysqli $db, bool $db_ok, int $id): ?array {
	$all = control_list_tenants($db, $db_ok);
	foreach ($all['rows'] as $t) {
		if ((int) $t['id'] === $id) {
			return $t;
		}
	}
	return null;
}

function control_audit(?mysqli $db, bool $db_ok, string $actor, string $action, ?int $tenant_id, ?array $meta): void {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_platform_audit')) {
		return;
	}
	$actor_e = $db->real_escape_string($actor);
	$action_e = $db->real_escape_string($action);
	$tid = $tenant_id !== null ? (string) (int) $tenant_id : 'NULL';
	$meta_e = $meta ? "'" . $db->real_escape_string(json_encode($meta)) . "'" : 'NULL';
	@$db->query(
		"INSERT INTO tessera_platform_audit (actor, action, tenant_id, meta_json)
		 VALUES ('{$actor_e}', '{$action_e}', {$tid}, {$meta_e})"
	);
}

function control_list_audit(?mysqli $db, bool $db_ok, int $limit = 12): array {
	return control_list_audit_filtered($db, $db_ok, ['limit' => $limit]);
}

function control_list_audit_filtered(?mysqli $db, bool $db_ok, array $filters = []): array {
	$limit = max(1, min(500, (int) ($filters['limit'] ?? 100)));
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_platform_audit')) {
		return [];
	}
	$where = ['1=1'];
	if (!empty($filters['actor'])) {
		$a = $db->real_escape_string((string) $filters['actor']);
		$where[] = "actor LIKE '%{$a}%'";
	}
	if (!empty($filters['action'])) {
		$a = $db->real_escape_string((string) $filters['action']);
		$where[] = "action LIKE '%{$a}%'";
	}
	if (isset($filters['tenant_id']) && $filters['tenant_id'] !== '' && $filters['tenant_id'] !== null) {
		$tid = (int) $filters['tenant_id'];
		$where[] = "tenant_id={$tid}";
	}
	if (!empty($filters['from'])) {
		$f = $db->real_escape_string((string) $filters['from']);
		$where[] = "DATE(created_at) >= '{$f}'";
	}
	if (!empty($filters['to'])) {
		$t = $db->real_escape_string((string) $filters['to']);
		$where[] = "DATE(created_at) <= '{$t}'";
	}
	$sql = 'SELECT * FROM tessera_platform_audit WHERE ' . implode(' AND ', $where)
		. " ORDER BY id DESC LIMIT {$limit}";
	$rows = [];
	$q = @$db->query($sql);
	if ($q) {
		while ($row = $q->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	return $rows;
}

function control_set_tenant_status(?mysqli $db, bool $db_ok, int $id, string $status, string $actor): bool {
	$allowed = ['active', 'suspended', 'provisioning'];
	if (!in_array($status, $allowed, true)) {
		return false;
	}
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenants')) {
		return false;
	}
	$status_e = $db->real_escape_string($status);
	$id = (int) $id;
	$ok = @$db->query("UPDATE tessera_tenants SET status='{$status_e}' WHERE id={$id} LIMIT 1");
	if ($ok) {
		control_audit($db, $db_ok, $actor, 'tenant.status', $id, ['status' => $status]);
		if ($status === 'suspended' && function_exists('control_notify_suspend')) {
			$t = control_get_tenant($db, $db_ok, $id);
			control_notify_suspend($db, $db_ok, $id, (string) ($t['display_name'] ?? ('#' . $id)));
		}
	}
	return (bool) $ok;
}

function control_create_tenant(?mysqli $db, bool $db_ok, array $input, string $actor): ?array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenants')) {
		return null;
	}
	$slug = strtolower(trim((string) ($input['slug'] ?? '')));
	$name = trim((string) ($input['name'] ?? ''));
	$plan = trim((string) ($input['plan'] ?? 'standard')) ?: 'standard';
	if ($slug === '' || $name === '' || !preg_match('/^[a-z0-9-]+$/', $slug)) {
		return null;
	}
	$domain = $slug === 'sr-classic' ? 'tessera.co.tz' : "{$slug}.tessera.co.tz";
	$mods = control_plan_module_defaults($plan);
	$slug_e = $db->real_escape_string($slug);
	$name_e = $db->real_escape_string($name);
	$plan_e = $db->real_escape_string($plan);
	$domain_e = $db->real_escape_string($domain);
	$mt = (int) $mods['tickets'];
	$mc = (int) $mods['cargo'];
	$ma = (int) $mods['accounts'];
	$dedicated = ($slug !== 'sr-classic');
	$dbMode = $dedicated ? 'dedicated' : 'shared';
	$ok = @$db->query(
		"INSERT INTO tessera_tenants (slug, legal_name, display_name, status, plan, db_mode, primary_domain, domain_status, module_tickets, module_cargo, module_accounts)
		 VALUES ('{$slug_e}', '{$name_e}', '{$name_e}', 'provisioning', '{$plan_e}', '{$dbMode}', '{$domain_e}', 'pending', {$mt}, {$mc}, {$ma})"
	);
	if (!$ok) {
		/* Pre-017 schema without db_mode */
		$ok = @$db->query(
			"INSERT INTO tessera_tenants (slug, legal_name, display_name, status, plan, primary_domain, domain_status, module_tickets, module_cargo, module_accounts)
			 VALUES ('{$slug_e}', '{$name_e}', '{$name_e}', 'provisioning', '{$plan_e}', '{$domain_e}', 'pending', {$mt}, {$mc}, {$ma})"
		);
	}
	if (!$ok) {
		return null;
	}
	$id = (int) $db->insert_id;
	control_audit($db, $db_ok, $actor, 'tenant.create', $id, ['slug' => $slug]);

	/* Hybrid: create dedicated ticket/cargo/account databases for this operator. */
	if ($dedicated) {
		$provCandidates = [
			'/var/www/html/tickets/includes/tessera_db_provision.php',
			dirname(__DIR__, 3) . '/services/tickets/includes/tessera_db_provision.php',
			dirname(__DIR__, 2) . '/services/tickets/includes/tessera_db_provision.php',
		];
		$provFile = '';
		foreach ($provCandidates as $cand) {
			if (is_file($cand)) {
				$provFile = $cand;
				break;
			}
		}
		if ($provFile !== '') {
			require_once $provFile;
			if (function_exists('tessera_provision_tenant_databases')) {
				$prov = tessera_provision_tenant_databases($slug, ['copy_users' => false]);
				control_audit($db, $db_ok, $actor, 'tenant.db_provision', $id, $prov);
			}
		}
	}

	return control_get_tenant($db, $db_ok, $id);
}

function control_parse_checklist($raw): array {
	if (is_array($raw)) {
		return $raw;
	}
	if (is_string($raw) && $raw !== '') {
		$decoded = json_decode($raw, true);
		if (is_array($decoded)) {
			return $decoded;
		}
	}
	return control_default_checklist();
}

function control_demo_cases(): array {
	$checklist = control_default_checklist();
	foreach ($checklist as &$c) {
		$c['done'] = true;
	}
	unset($c);
	return [[
		'id' => 1,
		'tenant_id' => 1,
		'company_name' => 'SR Classic Coach',
		'slug' => 'sr-classic',
		'contact_name' => 'Operations',
		'contact_email' => 'ops@srclassic.example',
		'contact_phone' => null,
		'stage' => 'hypercare',
		'go_live_date' => '2026-06-01',
		'checklist_json' => json_encode($checklist),
		'notes' => 'Flagship tenant — live on tessera.co.tz.',
		'owner_label' => 'Platform',
		'created_at' => '2025-01-12 10:00:00',
		'updated_at' => date('Y-m-d H:i:s'),
	]];
}

function control_list_cases(?mysqli $db, bool $db_ok): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_onboarding_cases')) {
		return ['rows' => control_demo_cases(), 'source' => 'demo'];
	}
	$rows = [];
	$q = $db->query('SELECT * FROM tessera_onboarding_cases ORDER BY id DESC');
	if ($q) {
		while ($row = $q->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	if (!$rows) {
		return ['rows' => control_demo_cases(), 'source' => 'demo'];
	}
	return ['rows' => $rows, 'source' => 'mysql'];
}

function control_get_case(?mysqli $db, bool $db_ok, int $id): ?array {
	$all = control_list_cases($db, $db_ok);
	foreach ($all['rows'] as $c) {
		if ((int) $c['id'] === $id) {
			return $c;
		}
	}
	return null;
}

function control_create_case(?mysqli $db, bool $db_ok, array $input, string $actor): ?array {
	$company = trim((string) ($input['company_name'] ?? ''));
	if ($company === '') {
		return null;
	}
	$slug = strtolower(trim((string) ($input['slug'] ?? '')));
	$provision = !empty($input['provision_tenant']) && $slug !== '';
	$tenant = null;
	if ($provision) {
		$tenant = control_create_tenant($db, $db_ok, ['slug' => $slug, 'name' => $company, 'plan' => 'standard'], $actor);
	}

	$checklist = control_default_checklist();
	if ($tenant) {
		foreach ($checklist as &$item) {
			if (in_array($item['id'], ['kickoff', 'tenant'], true)) {
				$item['done'] = true;
			}
		}
		unset($item);
	}
	$stage = $tenant ? 'provisioning' : 'kickoff';

	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_onboarding_cases')) {
		return null;
	}

	$company_e = $db->real_escape_string($company);
	$slug_e = $slug !== '' ? "'" . $db->real_escape_string($slug) . "'" : 'NULL';
	$contact_name = trim((string) ($input['contact_name'] ?? ''));
	$contact_email = trim((string) ($input['contact_email'] ?? ''));
	$contact_phone = trim((string) ($input['contact_phone'] ?? ''));
	$go_live = trim((string) ($input['go_live_date'] ?? ''));
	$owner = trim((string) ($input['owner_label'] ?? ''));
	$notes = trim((string) ($input['notes'] ?? ''));
	$tid = $tenant ? (string) (int) $tenant['id'] : 'NULL';
	$checklist_e = $db->real_escape_string(json_encode($checklist));
	$stage_e = $db->real_escape_string($stage);

	$cn = $contact_name !== '' ? "'" . $db->real_escape_string($contact_name) . "'" : 'NULL';
	$ce = $contact_email !== '' ? "'" . $db->real_escape_string($contact_email) . "'" : 'NULL';
	$cp = $contact_phone !== '' ? "'" . $db->real_escape_string($contact_phone) . "'" : 'NULL';
	$gd = $go_live !== '' ? "'" . $db->real_escape_string($go_live) . "'" : 'NULL';
	$ol = $owner !== '' ? "'" . $db->real_escape_string($owner) . "'" : 'NULL';
	$nt = $notes !== '' ? "'" . $db->real_escape_string($notes) . "'" : 'NULL';

	$ok = @$db->query(
		"INSERT INTO tessera_onboarding_cases
		 (tenant_id, company_name, slug, contact_name, contact_email, contact_phone, stage, go_live_date, checklist_json, notes, owner_label)
		 VALUES ({$tid}, '{$company_e}', {$slug_e}, {$cn}, {$ce}, {$cp}, '{$stage_e}', {$gd}, '{$checklist_e}', {$nt}, {$ol})"
	);
	if (!$ok) {
		return null;
	}
	$id = (int) $db->insert_id;
	control_audit($db, $db_ok, $actor, 'onboarding.create', $tenant ? (int) $tenant['id'] : null, ['case_id' => $id]);
	return control_get_case($db, $db_ok, $id);
}

function control_update_case(?mysqli $db, bool $db_ok, int $id, array $patch, string $actor): ?array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_onboarding_cases')) {
		return null;
	}
	$current = control_get_case($db, $db_ok, $id);
	if (!$current) {
		return null;
	}
	$stages = array_keys(control_stage_labels());
	$stage = (string) ($patch['stage'] ?? $current['stage']);
	if (!in_array($stage, $stages, true)) {
		$stage = $current['stage'];
	}
	$checklist = isset($patch['checklist']) ? $patch['checklist'] : control_parse_checklist($current['checklist_json'] ?? null);
	$notes = array_key_exists('notes', $patch) ? $patch['notes'] : ($current['notes'] ?? '');
	$go_live = array_key_exists('go_live_date', $patch) ? $patch['go_live_date'] : ($current['go_live_date'] ?? '');
	$owner = array_key_exists('owner_label', $patch) ? $patch['owner_label'] : ($current['owner_label'] ?? '');
	$rota = array_key_exists('hypercare_rota', $patch) ? $patch['hypercare_rota'] : ($current['hypercare_rota'] ?? '');

	$stage_e = $db->real_escape_string($stage);
	$checklist_e = $db->real_escape_string(json_encode($checklist));
	$nt = $notes !== null && $notes !== '' ? "'" . $db->real_escape_string((string) $notes) . "'" : 'NULL';
	$gd = $go_live !== null && $go_live !== '' ? "'" . $db->real_escape_string((string) $go_live) . "'" : 'NULL';
	$ol = $owner !== null && $owner !== '' ? "'" . $db->real_escape_string((string) $owner) . "'" : 'NULL';
	$hr = $rota !== null && $rota !== '' ? "'" . $db->real_escape_string((string) $rota) . "'" : 'NULL';
	$id = (int) $id;

	$ok = @$db->query(
		"UPDATE tessera_onboarding_cases SET
			stage='{$stage_e}',
			checklist_json='{$checklist_e}',
			notes={$nt},
			go_live_date={$gd},
			owner_label={$ol},
			hypercare_rota={$hr}
		 WHERE id={$id} LIMIT 1"
	);
	if ($ok) {
		$tid = !empty($current['tenant_id']) ? (int) $current['tenant_id'] : null;
		control_audit($db, $db_ok, $actor, 'onboarding.update', $tid, ['case_id' => $id, 'stage' => $stage]);
	}
	return control_get_case($db, $db_ok, $id);
}

function control_operator_console_url(array $tenant): string {
	$domain = trim((string) ($tenant['primary_domain'] ?? ''));
	if ($domain !== '') {
		$host = preg_replace('#^https?://#', '', $domain);
		return 'https://' . $host . '/tickets/';
	}
	$slug = $tenant['slug'] ?? 'sr-classic';
	return 'https://' . $slug . '.tessera.co.tz/tickets/';
}

function control_status_badge(string $status): string {
	$map = [
		'active' => 'success',
		'suspended' => 'danger',
		'provisioning' => 'warning',
	];
	$class = $map[$status] ?? 'secondary';
	$label = htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8');
	return '<span class="badge badge-soft-' . $class . '">' . $label . '</span>';
}

function control_h(?string $v): string {
	return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}
