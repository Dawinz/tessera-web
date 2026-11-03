<?php
/**
 * Tessera Control Wave C — domain, branding, entitlements, support access.
 */

function control_domain_statuses(): array {
	return ['pending', 'dns_ok', 'ssl_ok', 'failed'];
}

function control_plan_module_defaults(string $plan): array {
	$plan = strtolower(trim($plan));
	if ($plan === 'standard') {
		return ['tickets' => 1, 'cargo' => 0, 'accounts' => 0];
	}
	/* flagship + enterprise + unknown → full suite */
	return ['tickets' => 1, 'cargo' => 1, 'accounts' => 1];
}

function control_expected_subdomain(array $tenant): string {
	$slug = (string) ($tenant['slug'] ?? '');
	if ($slug === 'sr-classic') {
		return 'tessera.co.tz';
	}
	return $slug !== '' ? "{$slug}.tessera.co.tz" : '';
}

function control_update_tenant_domain(?mysqli $db, bool $db_ok, int $id, array $input, string $actor): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenants')) {
		return false;
	}
	$status = (string) ($input['domain_status'] ?? 'pending');
	if (!in_array($status, control_domain_statuses(), true)) {
		return false;
	}
	$primary = trim((string) ($input['primary_domain'] ?? ''));
	$custom = trim((string) ($input['custom_domain'] ?? ''));
	$id = (int) $id;
	$status_e = $db->real_escape_string($status);
	$primary_e = $db->real_escape_string($primary);
	$custom_e = $db->real_escape_string($custom);
	$ok = @$db->query(
		"UPDATE tessera_tenants SET
			domain_status='{$status_e}',
			primary_domain=NULLIF('{$primary_e}',''),
			custom_domain=NULLIF('{$custom_e}','')
		 WHERE id={$id} LIMIT 1"
	);
	if ($ok) {
		control_audit($db, $db_ok, $actor, 'tenant.domain', $id, [
			'domain_status' => $status,
			'primary_domain' => $primary,
			'custom_domain' => $custom,
		]);
	}
	return (bool) $ok;
}

function control_update_tenant_branding(?mysqli $db, bool $db_ok, int $id, array $input, string $actor): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenants')) {
		return false;
	}
	$display = trim((string) ($input['display_name'] ?? ''));
	$legal = trim((string) ($input['legal_name'] ?? ''));
	$logo = trim((string) ($input['logo_url'] ?? ''));
	$color = trim((string) ($input['primary_color'] ?? ''));
	if ($display === '') {
		return false;
	}
	if ($color !== '' && !preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
		return false;
	}
	$id = (int) $id;
	$display_e = $db->real_escape_string($display);
	$legal_e = $db->real_escape_string($legal !== '' ? $legal : $display);
	$logo_e = $db->real_escape_string($logo);
	$color_sql = $color !== '' ? "'" . $db->real_escape_string($color) . "'" : 'NULL';
	$ok = @$db->query(
		"UPDATE tessera_tenants SET
			display_name='{$display_e}',
			legal_name='{$legal_e}',
			logo_url=NULLIF('{$logo_e}',''),
			primary_color={$color_sql}
		 WHERE id={$id} LIMIT 1"
	);
	if ($ok) {
		control_audit($db, $db_ok, $actor, 'tenant.branding', $id, [
			'display_name' => $display,
			'logo_set' => $logo !== '',
			'primary_color' => $color,
		]);
	}
	return (bool) $ok;
}

function control_update_tenant_modules(?mysqli $db, bool $db_ok, int $id, array $input, string $actor): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenants')) {
		return false;
	}
	$tickets = !empty($input['module_tickets']) ? 1 : 0;
	$cargo = !empty($input['module_cargo']) ? 1 : 0;
	$accounts = !empty($input['module_accounts']) ? 1 : 0;
	if ($tickets === 0 && $cargo === 0 && $accounts === 0) {
		$tickets = 1; /* always keep at least Tickets */
	}
	$plan = trim((string) ($input['plan'] ?? ''));
	$id = (int) $id;
	$planSql = '';
	if ($plan !== '' && in_array($plan, ['standard', 'flagship', 'enterprise'], true)) {
		$plan_e = $db->real_escape_string($plan);
		$planSql = ", plan='{$plan_e}'";
	}
	$ok = @$db->query(
		"UPDATE tessera_tenants SET
			module_tickets={$tickets},
			module_cargo={$cargo},
			module_accounts={$accounts}
			{$planSql}
		 WHERE id={$id} LIMIT 1"
	);
	if ($ok) {
		control_audit($db, $db_ok, $actor, 'tenant.modules', $id, [
			'tickets' => $tickets,
			'cargo' => $cargo,
			'accounts' => $accounts,
			'plan' => $plan !== '' ? $plan : null,
		]);
	}
	return (bool) $ok;
}

/**
 * Create one-time support deep-link into Tickets (30 minutes).
 *
 * @return array{ok:bool,url?:string,expires_at?:string,error?:string}
 */
function control_create_support_session(?mysqli $db, bool $db_ok, int $tenant_id, string $targetUsername, string $actor): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_support_tokens')) {
		return ['ok' => false, 'error' => 'tokens_table_missing'];
	}
	$targetUsername = trim($targetUsername);
	if ($targetUsername === '') {
		return ['ok' => false, 'error' => 'no_target'];
	}
	/* Staff live on the operator’s Tickets DB (dedicated when provisioned). Tokens stay on the registry. */
	$tenantRow = function_exists('control_get_tenant') ? control_get_tenant($db, $db_ok, $tenant_id) : null;
	$staffDb = (function_exists('control_operator_app_mysqli') ? control_operator_app_mysqli($tenantRow, 'ticket') : null) ?? $db;
	$u_e = $staffDb->real_escape_string($targetUsername);
	$uq = @$staffDb->query("SELECT username, status FROM users WHERE username='{$u_e}' LIMIT 1");
	$user = $uq ? $uq->fetch_assoc() : null;
	if (!$user) {
		return ['ok' => false, 'error' => 'user_not_found'];
	}
	if (strtolower((string) $user['status']) !== 'active') {
		return ['ok' => false, 'error' => 'user_inactive'];
	}

	$raw = bin2hex(random_bytes(32));
	$hash = hash('sha256', $raw);
	$expires = date('Y-m-d H:i:s', time() + 30 * 60);
	$tenant_id = (int) $tenant_id;
	$hash_e = $db->real_escape_string($hash);
	$actor_e = $db->real_escape_string($actor);
	$user_e = $db->real_escape_string($targetUsername);
	$exp_e = $db->real_escape_string($expires);
	$ok = @$db->query(
		"INSERT INTO tessera_support_tokens (token_hash, tenant_id, actor, target_username, expires_at)
		 VALUES ('{$hash_e}', {$tenant_id}, '{$actor_e}', '{$user_e}', '{$exp_e}')"
	);
	if (!$ok) {
		return ['ok' => false, 'error' => 'insert_failed'];
	}

	$base = rtrim((string) (getenv('TESSERA_PUBLIC_URL') ?: control_config()['public_operator_base']), '/');
	/* Local XAMPP convenience */
	$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
	if ($host === 'localhost' || str_starts_with($host, '127.0.0.1')) {
		$base = 'http://localhost';
	}
	$url = $base . '/tickets/support_enter.php?token=' . urlencode($raw);

	control_audit($db, $db_ok, $actor, 'support.session_create', $tenant_id, [
		'target' => $targetUsername,
		'expires_at' => $expires,
	]);

	return ['ok' => true, 'url' => $url, 'expires_at' => $expires];
}
