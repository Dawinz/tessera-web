<?php
/**
 * Per-tenant payment credentials vault (Control + runtime reader).
 */

function control_get_tenant_payment(?mysqli $db, bool $db_ok, int $tenant_id, string $provider = 'clickpesa'): ?array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenant_payments')) {
		return null;
	}
	$tenant_id = (int) $tenant_id;
	$provider_e = $db->real_escape_string($provider);
	$q = @$db->query(
		"SELECT * FROM tessera_tenant_payments WHERE tenant_id={$tenant_id} AND provider='{$provider_e}' LIMIT 1"
	);
	if ($q && ($row = $q->fetch_assoc())) {
		return $row;
	}
	return null;
}

/**
 * Public-safe view of payment config (never includes decrypted secret).
 */
function control_payment_public_view(?array $row): array {
	if (!$row) {
		return [
			'configured' => false,
			'credentials_status' => 'missing',
			'client_id' => '',
			'api_key_last4' => '',
			'base_url' => '',
			'webhook_url' => '',
			'webhook_last_at' => null,
			'webhook_last_status' => null,
			'payment_mock' => 1,
			'updated_at' => null,
			'updated_by' => null,
		];
	}
	$hasKey = !empty($row['api_key_enc']) && !empty($row['client_id']);
	return [
		'configured' => $hasKey,
		'credentials_status' => $hasKey ? 'ok' : 'missing',
		'client_id' => (string) ($row['client_id'] ?? ''),
		'api_key_last4' => (string) ($row['api_key_last4'] ?? ''),
		'base_url' => (string) ($row['base_url'] ?? ''),
		'webhook_url' => (string) ($row['webhook_url'] ?? ''),
		'webhook_last_at' => $row['webhook_last_at'] ?? null,
		'webhook_last_status' => $row['webhook_last_status'] ?? null,
		'payment_mock' => (int) ($row['payment_mock'] ?? 1),
		'updated_at' => $row['updated_at'] ?? null,
		'updated_by' => $row['updated_by'] ?? null,
	];
}

function control_save_tenant_payment(?mysqli $db, bool $db_ok, int $tenant_id, array $input, string $actor): ?array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenant_payments')) {
		return null;
	}
	$tenant_id = (int) $tenant_id;
	$provider = 'clickpesa';
	$existing = control_get_tenant_payment($db, $db_ok, $tenant_id, $provider);

	$clientId = trim((string) ($input['client_id'] ?? ($existing['client_id'] ?? '')));
	$apiKey = (string) ($input['api_key'] ?? '');
	$baseUrl = trim((string) ($input['base_url'] ?? ($existing['base_url'] ?? '')));
	$webhookUrl = trim((string) ($input['webhook_url'] ?? ($existing['webhook_url'] ?? '')));
	$paymentMock = !empty($input['payment_mock']) ? 1 : 0;

	$apiKeyEnc = $existing['api_key_enc'] ?? null;
	$apiKeyLast4 = $existing['api_key_last4'] ?? null;
	if ($apiKey !== '') {
		$apiKeyEnc = control_encrypt($apiKey);
		$apiKeyLast4 = substr($apiKey, -4);
	}

	$status = ($clientId !== '' && !empty($apiKeyEnc)) ? 'ok' : 'missing';
	$client_e = $db->real_escape_string($clientId);
	$enc_e = $apiKeyEnc !== null ? "'" . $db->real_escape_string($apiKeyEnc) . "'" : 'NULL';
	$last4_e = $apiKeyLast4 !== null ? "'" . $db->real_escape_string($apiKeyLast4) . "'" : 'NULL';
	$base_e = $db->real_escape_string($baseUrl);
	$wh_e = $db->real_escape_string($webhookUrl);
	$status_e = $db->real_escape_string($status);
	$actor_e = $db->real_escape_string($actor);
	$provider_e = $db->real_escape_string($provider);

	if ($existing) {
		$ok = @$db->query(
			"UPDATE tessera_tenant_payments SET
				client_id='{$client_e}',
				api_key_enc={$enc_e},
				api_key_last4={$last4_e},
				base_url=NULLIF('{$base_e}',''),
				webhook_url=NULLIF('{$wh_e}',''),
				payment_mock={$paymentMock},
				credentials_status='{$status_e}',
				updated_by='{$actor_e}'
			 WHERE tenant_id={$tenant_id} AND provider='{$provider_e}' LIMIT 1"
		);
	} else {
		$ok = @$db->query(
			"INSERT INTO tessera_tenant_payments
				(tenant_id, provider, client_id, api_key_enc, api_key_last4, base_url, webhook_url, payment_mock, credentials_status, updated_by)
			 VALUES
				({$tenant_id}, '{$provider_e}', '{$client_e}', {$enc_e}, {$last4_e}, NULLIF('{$base_e}',''), NULLIF('{$wh_e}',''), {$paymentMock}, '{$status_e}', '{$actor_e}')"
		);
	}

	if (!$ok) {
		return null;
	}

	control_audit($db, $db_ok, $actor, $apiKey !== '' ? 'payment.credentials_rotate' : 'payment.credentials_update', $tenant_id, [
		'provider' => $provider,
		'credentials_status' => $status,
		'payment_mock' => $paymentMock,
		'key_rotated' => $apiKey !== '',
	]);

	return control_get_tenant_payment($db, $db_ok, $tenant_id, $provider);
}

/**
 * Resolve live credentials for Tickets runtime (decrypt). Env fallback.
 *
 * @return array{client_id:string,api_key:string,base_url:string,payment_mock:?int}|null
 */
function control_resolve_clickpesa_credentials(?mysqli $db, bool $db_ok, ?int $tenant_id = null): ?array {
	$envId = getenv('CLICKPESA_CLIENT_ID');
	$envKey = getenv('CLICKPESA_API_KEY');
	$envBase = getenv('CLICKPESA_BASE_URL');

	$row = null;
	if ($db_ok && $db && control_table_exists($db, 'tessera_tenant_payments')) {
		if ($tenant_id) {
			$row = control_get_tenant_payment($db, $db_ok, $tenant_id);
		} else {
			$slug = getenv('TESSERA_DEFAULT_TENANT_SLUG') ?: 'sr-classic';
			$slug_e = $db->real_escape_string($slug);
			$tq = @$db->query("SELECT id FROM tessera_tenants WHERE slug='{$slug_e}' LIMIT 1");
			if ($tq && ($t = $tq->fetch_assoc())) {
				$row = control_get_tenant_payment($db, $db_ok, (int) $t['id']);
			}
		}
	}

	if ($row && !empty($row['client_id']) && !empty($row['api_key_enc'])) {
		$key = control_decrypt((string) $row['api_key_enc']);
		if ($key !== '') {
			$base = trim((string) ($row['base_url'] ?? ''));
			if ($base === '' && $envBase !== false && $envBase !== '') {
				$base = $envBase;
			}
			return [
				'client_id' => (string) $row['client_id'],
				'api_key' => $key,
				'base_url' => $base !== '' ? rtrim($base, '/') : 'https://api.clickpesa.com',
				'payment_mock' => isset($row['payment_mock']) ? (int) $row['payment_mock'] : null,
			];
		}
	}

	if ($envId !== false && $envId !== '' && $envKey !== false && $envKey !== '') {
		return [
			'client_id' => (string) $envId,
			'api_key' => (string) $envKey,
			'base_url' => ($envBase !== false && $envBase !== '') ? rtrim((string) $envBase, '/') : 'https://api.clickpesa.com',
			'payment_mock' => null,
		];
	}

	return null;
}
