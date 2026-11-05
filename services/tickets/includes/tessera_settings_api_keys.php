<?php
/**
 * Operator settings — API keys helpers (S-05).
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_operator_api.php';

if (!function_exists('tessera_settings_api_keys_scopes')) {
	/** @return list<array{code:string,label:string}> */
	function tessera_settings_api_keys_scopes(): array
	{
		return [
			['code' => 'tickets:read', 'label' => 'Tickets read'],
			['code' => 'tickets:write', 'label' => 'Tickets write'],
			['code' => 'cargo:read', 'label' => 'Cargo read'],
			['code' => 'cargo:write', 'label' => 'Cargo write'],
			['code' => 'webhooks:manage', 'label' => 'Webhooks manage'],
		];
	}
}

if (!function_exists('tessera_settings_api_keys_base_url')) {
	function tessera_settings_api_keys_base_url(): string
	{
		$base = rtrim((string) (getenv('TESSERA_PUBLIC_URL') ?: ''), '/');
		if ($base === '') {
			$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
				|| ((string) ($_SERVER['SERVER_PORT'] ?? '') === '443');
			$host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
			$base = ($https ? 'https://' : 'http://') . $host;
		}
		return $base;
	}
}

if (!function_exists('tessera_settings_api_keys_public_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_settings_api_keys_public_item(array $row): array
	{
		$scopes = $row['scopes'] ?? [];
		if (!is_array($scopes)) {
			$scopes = [];
		}
		return [
			'id' => (int) ($row['id'] ?? 0),
			'name' => (string) ($row['name'] ?? ''),
			'key_prefix' => (string) ($row['key_prefix'] ?? ''),
			'scopes' => array_values($scopes),
			'status' => (string) ($row['status'] ?? ''),
			'last_used_at' => $row['last_used_at'] ?? null,
			'created_at' => $row['created_at'] ?? null,
		];
	}
}

if (!function_exists('tessera_settings_api_keys_list')) {
	/**
	 * @return array{ok:bool,error?:string,items?:list<array<string,mixed>>,meta?:array<string,mixed>}
	 */
	function tessera_settings_api_keys_list(mysqli $db, int $tenantId): array
	{
		if ($tenantId < 1) {
			return ['ok' => false, 'error' => 'No tenant context'];
		}
		$base = tessera_settings_api_keys_base_url();
		$items = [];
		foreach (tessera_api_key_list($db, $tenantId) as $row) {
			$items[] = tessera_settings_api_keys_public_item($row);
		}
		return [
			'ok' => true,
			'items' => $items,
			'meta' => [
				'api_base' => $base . '/tickets/api/v1/',
				'auth_hint' => 'Authorization: Bearer tess_…',
				'scopes' => tessera_settings_api_keys_scopes(),
				'webhooks_url' => '/settings/webhooks.php',
			],
		];
	}
}

if (!function_exists('tessera_settings_api_keys_create')) {
	/**
	 * @param list<string>|mixed $scopes
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>,plaintext_key?:string,items?:list<array<string,mixed>>,meta?:array<string,mixed>}
	 */
	function tessera_settings_api_keys_create(mysqli $db, int $tenantId, string $name, $scopes, string $actor): array
	{
		if ($tenantId < 1) {
			return ['ok' => false, 'error' => 'No tenant context'];
		}
		$name = trim($name);
		if ($name === '') {
			$name = 'Default';
		}
		$allowed = array_column(tessera_settings_api_keys_scopes(), 'code');
		$clean = [];
		if (is_array($scopes)) {
			foreach ($scopes as $sc) {
				$sc = (string) $sc;
				if (in_array($sc, $allowed, true)) {
					$clean[] = $sc;
				}
			}
		}
		$clean = array_values(array_unique($clean));
		if ($clean === []) {
			$clean = ['tickets:read', 'tickets:write', 'cargo:read'];
		}
		$created = tessera_api_key_create($db, $tenantId, $name, $clean, $actor);
		if (!$created) {
			return ['ok' => false, 'error' => 'Could not create key (migration 014?)'];
		}
		$list = tessera_settings_api_keys_list($db, $tenantId);
		return [
			'ok' => true,
			'item' => [
				'id' => (int) ($created['id'] ?? 0),
				'name' => (string) ($created['name'] ?? $name),
				'key_prefix' => (string) ($created['key_prefix'] ?? ''),
				'scopes' => $created['scopes'] ?? $clean,
				'status' => 'active',
			],
			'plaintext_key' => (string) ($created['key'] ?? ''),
			'items' => $list['items'] ?? [],
			'meta' => $list['meta'] ?? [],
			'message' => 'API key created. Copy it now — it will not be shown again.',
		];
	}
}

if (!function_exists('tessera_settings_api_keys_revoke')) {
	/**
	 * @return array{ok:bool,error?:string,items?:list<array<string,mixed>>,meta?:array<string,mixed>,message?:string}
	 */
	function tessera_settings_api_keys_revoke(mysqli $db, int $tenantId, int $id): array
	{
		if ($tenantId < 1 || $id < 1) {
			return ['ok' => false, 'error' => 'Invalid key'];
		}
		if (!tessera_api_key_revoke($db, $tenantId, $id)) {
			return ['ok' => false, 'error' => 'Revoke failed'];
		}
		$list = tessera_settings_api_keys_list($db, $tenantId);
		return [
			'ok' => true,
			'items' => $list['items'] ?? [],
			'meta' => $list['meta'] ?? [],
			'message' => 'Key revoked.',
		];
	}
}
