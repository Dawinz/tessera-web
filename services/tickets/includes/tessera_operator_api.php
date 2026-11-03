<?php
/**
 * Wave O - operator API keys + outbound webhooks.
 */
require_once __DIR__ . '/tessera_vault.php';

if (!function_exists('tessera_api_key_create')) {
	function tessera_api_key_create(mysqli $db, int $tenantId, string $name, array $scopes, string $actor): ?array {
		$t = @$db->query("SHOW TABLES LIKE 'tessera_api_keys'");
		if (!$t || $t->num_rows === 0) {
			return null;
		}
		$raw = 'tess_' . bin2hex(random_bytes(24));
		$prefix = substr($raw, 0, 12);
		$hash = hash('sha256', $raw);
		$name_e = $db->real_escape_string(trim($name) ?: 'Default');
		$scopes_e = $db->real_escape_string(json_encode(array_values($scopes)));
		$prefix_e = $db->real_escape_string($prefix);
		$hash_e = $db->real_escape_string($hash);
		$actor_e = $db->real_escape_string($actor);
		$ok = @$db->query(
			"INSERT INTO tessera_api_keys (tenant_id, name, key_prefix, key_hash, scopes_json, created_by)
			 VALUES (" . (int) $tenantId . ", '{$name_e}', '{$prefix_e}', '{$hash_e}', '{$scopes_e}', '{$actor_e}')"
		);
		if (!$ok) {
			return null;
		}
		return [
			'id' => (int) $db->insert_id,
			'name' => $name,
			'key' => $raw,
			'key_prefix' => $prefix,
			'scopes' => $scopes,
		];
	}
}

if (!function_exists('tessera_api_key_list')) {
	function tessera_api_key_list(mysqli $db, int $tenantId): array {
		$t = @$db->query("SHOW TABLES LIKE 'tessera_api_keys'");
		if (!$t || $t->num_rows === 0) {
			return [];
		}
		$q = @$db->query(
			'SELECT id, name, key_prefix, scopes_json, status, last_used_at, created_at
			 FROM tessera_api_keys WHERE tenant_id=' . (int) $tenantId . ' ORDER BY id DESC'
		);
		$rows = [];
		if ($q) {
			while ($r = $q->fetch_assoc()) {
				$r['scopes'] = json_decode((string) ($r['scopes_json'] ?? '[]'), true) ?: [];
				unset($r['scopes_json']);
				$rows[] = $r;
			}
		}
		return $rows;
	}
}

if (!function_exists('tessera_api_key_revoke')) {
	function tessera_api_key_revoke(mysqli $db, int $tenantId, int $id): bool {
		return (bool) @$db->query(
			"UPDATE tessera_api_keys SET status='revoked', revoked_at=NOW()
			 WHERE id=" . (int) $id . ' AND tenant_id=' . (int) $tenantId . ' LIMIT 1'
		);
	}
}

if (!function_exists('tessera_api_key_authenticate')) {
	function tessera_api_key_authenticate(mysqli $db, string $rawKey): ?array {
		$t = @$db->query("SHOW TABLES LIKE 'tessera_api_keys'");
		if (!$t || $t->num_rows === 0 || $rawKey === '') {
			return null;
		}
		$hash = hash('sha256', $rawKey);
		$hash_e = $db->real_escape_string($hash);
		$q = @$db->query(
			"SELECT * FROM tessera_api_keys WHERE key_hash='{$hash_e}' AND status='active' LIMIT 1"
		);
		$row = $q ? $q->fetch_assoc() : null;
		if (!$row) {
			return null;
		}
		@$db->query('UPDATE tessera_api_keys SET last_used_at=NOW() WHERE id=' . (int) $row['id']);
		$row['scopes'] = json_decode((string) ($row['scopes_json'] ?? '[]'), true) ?: [];
		return $row;
	}
}

if (!function_exists('tessera_webhook_list')) {
	function tessera_webhook_list(mysqli $db, int $tenantId): array {
		$t = @$db->query("SHOW TABLES LIKE 'tessera_webhooks'");
		if (!$t || $t->num_rows === 0) {
			return [];
		}
		$q = @$db->query('SELECT id, url, secret_last4, events_json, status, created_at FROM tessera_webhooks WHERE tenant_id=' . (int) $tenantId . ' ORDER BY id DESC');
		$rows = [];
		if ($q) {
			while ($r = $q->fetch_assoc()) {
				$r['events'] = json_decode((string) ($r['events_json'] ?? '[]'), true) ?: [];
				unset($r['events_json']);
				$rows[] = $r;
			}
		}
		return $rows;
	}
}

if (!function_exists('tessera_webhook_create')) {
	function tessera_webhook_create(mysqli $db, int $tenantId, string $url, array $events, string $actor): ?array {
		$t = @$db->query("SHOW TABLES LIKE 'tessera_webhooks'");
		if (!$t || $t->num_rows === 0) {
			return null;
		}
		$url = trim($url);
		$localHttp = (bool) preg_match('#^http://(127\.0\.0\.1|localhost)(:\d+)?(/|$)#i', $url);
		if ($url === '' || (!preg_match('#^https://#i', $url) && !$localHttp)) {
			return null;
		}
		$secret = bin2hex(random_bytes(16));
		$enc = tessera_encrypt($secret);
		$last4 = substr($secret, -4);
		$url_e = $db->real_escape_string($url);
		$ev = $db->real_escape_string(json_encode(array_values($events)));
		$enc_e = $db->real_escape_string($enc);
		$last_e = $db->real_escape_string($last4);
		$actor_e = $db->real_escape_string($actor);
		$ok = @$db->query(
			"INSERT INTO tessera_webhooks (tenant_id, url, secret_enc, secret_last4, events_json, created_by)
			 VALUES (" . (int) $tenantId . ", '{$url_e}', '{$enc_e}', '{$last_e}', '{$ev}', '{$actor_e}')"
		);
		if (!$ok) {
			return null;
		}
		return ['id' => (int) $db->insert_id, 'url' => $url, 'secret' => $secret, 'events' => $events];
	}
}

if (!function_exists('tessera_webhook_disable')) {
	function tessera_webhook_disable(mysqli $db, int $tenantId, int $id): bool {
		return (bool) @$db->query(
			"UPDATE tessera_webhooks SET status='disabled' WHERE id=" . (int) $id . ' AND tenant_id=' . (int) $tenantId
		);
	}
}

if (!function_exists('tessera_webhook_dispatch')) {
	function tessera_webhook_dispatch(mysqli $db, int $tenantId, string $event, array $payload): int {
		$t = @$db->query("SHOW TABLES LIKE 'tessera_webhooks'");
		if (!$t || $t->num_rows === 0) {
			return 0;
		}
		$q = @$db->query(
			"SELECT * FROM tessera_webhooks WHERE tenant_id=" . (int) $tenantId . " AND status='active'"
		);
		$n = 0;
		if (!$q) {
			return 0;
		}
		$body = json_encode([
			'event' => $event,
			'tenant_id' => $tenantId,
			'created_at' => gmdate('c'),
			'data' => $payload,
		]);
		while ($wh = $q->fetch_assoc()) {
			$events = json_decode((string) ($wh['events_json'] ?? '[]'), true) ?: [];
			if (!in_array($event, $events, true) && !in_array('*', $events, true)) {
				continue;
			}
			$secret = tessera_decrypt((string) ($wh['secret_enc'] ?? ''));
			$sig = hash_hmac('sha256', $body, $secret);
			$ch = curl_init((string) $wh['url']);
			curl_setopt_array($ch, [
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => [
					'Content-Type: application/json',
					'X-Tessera-Event: ' . $event,
					'X-Tessera-Signature: sha256=' . $sig,
				],
				CURLOPT_POSTFIELDS => $body,
				CURLOPT_TIMEOUT => 15,
			]);
			$resp = curl_exec($ch);
			$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			unset($ch);
			$status = ($code >= 200 && $code < 300) ? 'delivered' : 'failed';
			$err = $status === 'failed' ? substr((string) $resp, 0, 200) : '';
			$pj = $db->real_escape_string($body);
			$err_e = $db->real_escape_string($err);
			@$db->query(
				"INSERT INTO tessera_webhook_deliveries
				 (webhook_id, tenant_id, event_code, payload_json, status, attempts, response_code, last_error, delivered_at)
				 VALUES (" . (int) $wh['id'] . ', ' . (int) $tenantId . ", '" . $db->real_escape_string($event) . "', '{$pj}', '{$status}', 1, {$code}, NULLIF('{$err_e}',''), "
				. ($status === 'delivered' ? 'NOW()' : 'NULL') . ')'
			);
			$n++;
		}
		return $n;
	}
}
