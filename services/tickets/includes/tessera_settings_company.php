<?php
/**
 * Operator settings — company profile helpers (S-01).
 */
declare(strict_types=1);

if (!function_exists('tessera_settings_company_get')) {
	/**
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_settings_company_get(): array
	{
		if (!function_exists('tessera_registry_mysqli')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		$reg = tessera_registry_mysqli();
		if (!($reg instanceof mysqli)) {
			return ['ok' => false, 'error' => 'Tenant registry unavailable'];
		}
		$tid = (int) (function_exists('tessera_current_tenant_id') ? tessera_current_tenant_id() : 0);
		if ($tid < 1) {
			$row = function_exists('tessera_tenant_row') ? tessera_tenant_row() : null;
			$tid = (int) ($row['id'] ?? 0);
		}
		if ($tid < 1) {
			return ['ok' => false, 'error' => 'No tenant context'];
		}
		$q = $reg->query(
			"SELECT id, slug, display_name, legal_name, status, plan, primary_domain, custom_domain,
				logo_url, primary_color, module_tickets, module_cargo, module_accounts
			 FROM tessera_tenants WHERE id={$tid} LIMIT 1"
		);
		$row = $q ? $q->fetch_assoc() : null;
		if (!$row) {
			return ['ok' => false, 'error' => 'Company not found'];
		}
		return [
			'ok' => true,
			'item' => [
				'id' => (int) ($row['id'] ?? 0),
				'slug' => (string) ($row['slug'] ?? ''),
				'display_name' => (string) ($row['display_name'] ?? ''),
				'legal_name' => (string) ($row['legal_name'] ?? ''),
				'status' => (string) ($row['status'] ?? ''),
				'plan' => (string) ($row['plan'] ?? ''),
				'primary_domain' => (string) ($row['primary_domain'] ?? ''),
				'custom_domain' => (string) ($row['custom_domain'] ?? ''),
				'logo_url' => (string) ($row['logo_url'] ?? ''),
				'primary_color' => (string) ($row['primary_color'] ?? ''),
				'modules' => [
					'tickets' => !empty($row['module_tickets']),
					'cargo' => !empty($row['module_cargo']),
					'accounts' => !empty($row['module_accounts']),
				],
			],
		];
	}
}

if (!function_exists('tessera_settings_company_update')) {
	/**
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_settings_company_update(array $input): array
	{
		if (!function_exists('tessera_registry_mysqli')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		$reg = tessera_registry_mysqli();
		if (!($reg instanceof mysqli)) {
			return ['ok' => false, 'error' => 'Tenant registry unavailable'];
		}
		$tid = (int) (function_exists('tessera_current_tenant_id') ? tessera_current_tenant_id() : 0);
		if ($tid < 1) {
			$row = function_exists('tessera_tenant_row') ? tessera_tenant_row() : null;
			$tid = (int) ($row['id'] ?? 0);
		}
		if ($tid < 1) {
			return ['ok' => false, 'error' => 'No tenant context'];
		}

		$display = trim((string) ($input['display_name'] ?? ''));
		$legal = trim((string) ($input['legal_name'] ?? ''));
		$color = trim((string) ($input['primary_color'] ?? ''));
		$logo = trim((string) ($input['logo_url'] ?? ''));

		if ($display === '' || $legal === '') {
			return ['ok' => false, 'error' => 'Display name and legal name are required'];
		}
		if ($color !== '' && !preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
			return ['ok' => false, 'error' => 'Primary colour must be a hex value like #E53E3E'];
		}

		$d = $reg->real_escape_string($display);
		$l = $reg->real_escape_string($legal);
		$colorSql = $color === '' ? 'NULL' : "'" . $reg->real_escape_string($color) . "'";
		$logoSql = $logo === '' ? 'NULL' : "'" . $reg->real_escape_string($logo) . "'";

		$ok = $reg->query(
			"UPDATE tessera_tenants SET
				display_name='{$d}',
				legal_name='{$l}',
				primary_color={$colorSql},
				logo_url={$logoSql}
			 WHERE id={$tid} LIMIT 1"
		);
		if (!$ok) {
			return ['ok' => false, 'error' => 'Could not save company profile'];
		}

		return tessera_settings_company_get();
	}
}
