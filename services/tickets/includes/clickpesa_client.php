<?php
/**
 * ClickPesa collection client (server-side only).
 * Credentials: per-tenant vault (tessera_tenant_payments) with env fallback
 * (CLICKPESA_CLIENT_ID, CLICKPESA_API_KEY, optional CLICKPESA_BASE_URL).
 */
if (!function_exists('clickpesa_vault_crypto_key')) {
	function clickpesa_vault_crypto_key() {
		$s = (string) (getenv('PLATFORM_VAULT_SECRET') ?: getenv('PLATFORM_SESSION_SECRET') ?: 'tessera-local-vault-dev-key');
		return hash('sha256', $s, true);
	}
}

if (!function_exists('clickpesa_vault_decrypt')) {
	function clickpesa_vault_decrypt($encoded) {
		$raw = base64_decode((string) $encoded, true);
		if ($raw === false || strlen($raw) < 17) {
			return '';
		}
		$iv = substr($raw, 0, 16);
		$ct = substr($raw, 16);
		$plain = openssl_decrypt($ct, 'AES-256-CBC', clickpesa_vault_crypto_key(), OPENSSL_RAW_DATA, $iv);
		return $plain === false ? '' : $plain;
	}
}

if (!function_exists('clickpesa_resolve_credentials')) {
	/**
	 * @return array{client_id:string,api_key:string,base_url:string,payment_mock:?int,webhook_url:string}|null
	 */
	function clickpesa_resolve_credentials() {
		static $cached = null;
		if ($cached !== null) {
			return $cached === false ? null : $cached;
		}

		$envId = getenv('CLICKPESA_CLIENT_ID');
		$envKey = getenv('CLICKPESA_API_KEY');
		$envBase = getenv('CLICKPESA_BASE_URL');
		$baseDefault = ($envBase !== false && $envBase !== '') ? rtrim((string) $envBase, '/') : 'https://api.clickpesa.com';
		$slug = getenv('TESSERA_DEFAULT_TENANT_SLUG') ?: 'sr-classic';
		if (function_exists('tessera_resolve_tenant_slug')) {
			$slug = tessera_resolve_tenant_slug();
		} elseif (is_file(__DIR__ . '/tessera_tenant.php')) {
			require_once __DIR__ . '/tessera_tenant.php';
			if (function_exists('tessera_resolve_tenant_slug')) {
				$slug = tessera_resolve_tenant_slug();
			}
		}
		$row = null;

		/* Prefer vault for resolved tenant when DB is available (mysqli or PDO). */
		$mysqli = null;
		if (isset($GLOBALS['db']) && $GLOBALS['db'] instanceof mysqli) {
			$mysqli = $GLOBALS['db'];
		} else {
			$connect = dirname(__DIR__) . '/connect.php';
			if (is_file($connect)) {
				@include_once $connect;
				if (isset($db) && $db instanceof mysqli) {
					$mysqli = $db;
					$GLOBALS['db'] = $db;
				}
			}
		}

		if ($mysqli) {
			$slug_e = $mysqli->real_escape_string($slug);
			$tq = @$mysqli->query("SELECT id FROM tessera_tenants WHERE slug='{$slug_e}' LIMIT 1");
			if ($tq && ($t = $tq->fetch_assoc())) {
				$tid = (int) $t['id'];
				$pq = @$mysqli->query(
					"SELECT client_id, api_key_enc, base_url, payment_mock, webhook_url FROM tessera_tenant_payments
					 WHERE tenant_id={$tid} AND provider='clickpesa' LIMIT 1"
				);
				if ($pq && ($p = $pq->fetch_assoc())) {
					$row = $p;
				}
			}
		} elseif (isset($GLOBALS['pdo']) && $GLOBALS['pdo'] instanceof PDO) {
			try {
				$st = $GLOBALS['pdo']->prepare(
					"SELECT p.client_id, p.api_key_enc, p.base_url, p.payment_mock, p.webhook_url
					 FROM tessera_tenant_payments p
					 JOIN tessera_tenants t ON t.id = p.tenant_id
					 WHERE t.slug = ? AND p.provider = 'clickpesa'
					 LIMIT 1"
				);
				$st->execute([$slug]);
				$fetched = $st->fetch(PDO::FETCH_ASSOC);
				if (is_array($fetched)) {
					$row = $fetched;
				}
			} catch (Throwable $e) {
				$row = null;
			}
		}

		if (is_array($row) && trim((string) ($row['webhook_url'] ?? '')) !== '') {
			$GLOBALS['tessera_clickpesa_webhook_url'] = trim((string) $row['webhook_url']);
		}

		if (is_array($row) && !empty($row['client_id']) && !empty($row['api_key_enc'])) {
			$key = clickpesa_vault_decrypt($row['api_key_enc']);
			if ($key !== '') {
				$base = trim((string) ($row['base_url'] ?? ''));
				$cached = [
					'client_id' => (string) $row['client_id'],
					'api_key' => $key,
					'base_url' => $base !== '' ? rtrim($base, '/') : $baseDefault,
					'payment_mock' => isset($row['payment_mock']) ? (int) $row['payment_mock'] : null,
					'webhook_url' => trim((string) ($row['webhook_url'] ?? '')),
				];
				return $cached;
			}
		}

		if ($envId !== false && $envId !== '' && $envKey !== false && $envKey !== '') {
			$cached = [
				'client_id' => (string) $envId,
				'api_key' => (string) $envKey,
				'base_url' => $baseDefault,
				'payment_mock' => null,
				'webhook_url' => '',
			];
			return $cached;
		}

		$cached = false;
		return null;
	}
}

if (!function_exists('clickpesa_should_mock')) {
	/**
	 * Mock when: PAYMENT_MOCK=1, or no credentials, or vault payment_mock=1.
	 * PAYMENT_MOCK=0 forces live when credentials exist (ignores vault mock flag).
	 */
	function clickpesa_should_mock() {
		$env = getenv('PAYMENT_MOCK');
		if ($env === '1') {
			return true;
		}
		$creds = clickpesa_resolve_credentials();
		if (!$creds) {
			return true;
		}
		if ($env === '0') {
			return false;
		}
		if (isset($creds['payment_mock']) && (int) $creds['payment_mock'] === 1) {
			return true;
		}
		return false;
	}
}

if (!function_exists('clickpesa_webhook_url')) {
	function clickpesa_webhook_url() {
		clickpesa_resolve_credentials();
		$saved = trim((string) ($GLOBALS['tessera_clickpesa_webhook_url'] ?? ''));
		if ($saved !== '') {
			return rtrim($saved, '/');
		}
		$creds = clickpesa_resolve_credentials();
		if ($creds && !empty($creds['webhook_url'])) {
			return rtrim((string) $creds['webhook_url'], '/');
		}
		return rtrim(getenv('TESSERA_PUBLIC_URL') ?: getenv('SR_PUBLIC_URL') ?: 'https://tessera.co.tz', '/')
			. '/tickets/api/payment_webhook.php';
	}
}


if (!function_exists('clickpesa_base_url')) {
	function clickpesa_base_url() {
		$creds = clickpesa_resolve_credentials();
		if ($creds && !empty($creds['base_url'])) {
			return $creds['base_url'];
		}
		$b = getenv('CLICKPESA_BASE_URL');
		return ($b !== false && $b !== '') ? rtrim($b, '/') : 'https://api.clickpesa.com';
	}
}

if (!function_exists('clickpesa_configured')) {
	function clickpesa_configured() {
		return clickpesa_resolve_credentials() !== null;
	}
}

if (!function_exists('clickpesa_http_json')) {
	function clickpesa_http_json($method, $path, $headers = [], $body = null) {
		$url = clickpesa_base_url() . $path;
		$ch = curl_init($url);
		$hdrs = [];
		foreach ($headers as $k => $v) {
			$hdrs[] = $k . ': ' . $v;
		}
		$hdrs[] = 'Accept: application/json';
		$opts = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => strtoupper($method),
			CURLOPT_HTTPHEADER => $hdrs,
			CURLOPT_TIMEOUT => 30,
		];
		if ($body !== null) {
			$json = is_string($body) ? $body : json_encode($body);
			$opts[CURLOPT_POSTFIELDS] = $json;
			$hdrs[] = 'Content-Type: application/json';
			$opts[CURLOPT_HTTPHEADER] = $hdrs;
		}
		curl_setopt_array($ch, $opts);
		$raw = curl_exec($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$err = curl_error($ch);
		curl_close($ch);
		$data = null;
		if ($raw !== false && $raw !== '') {
			$data = json_decode($raw, true);
		}
		return [
			'ok' => $err === '' && $code >= 200 && $code < 300,
			'status' => $code,
			'error' => $err,
			'data' => $data,
			'raw' => $raw,
		];
	}
}

if (!function_exists('clickpesa_generate_token')) {
	function clickpesa_generate_token() {
		$creds = clickpesa_resolve_credentials();
		if (!$creds) {
			return ['ok' => false, 'error' => 'missing_credentials'];
		}
		$res = clickpesa_http_json('POST', '/third-parties/generate-token', [
			'client-id' => $creds['client_id'],
			'api-key' => $creds['api_key'],
		]);
		if (!$res['ok'] || empty($res['data']['token'])) {
			return ['ok' => false, 'error' => 'token_failed', 'response' => $res];
		}
		$token = $res['data']['token'];
		if (stripos($token, 'Bearer ') !== 0) {
			$token = 'Bearer ' . $token;
		}
		return ['ok' => true, 'token' => $token];
	}
}

if (!function_exists('clickpesa_order_reference')) {
	/**
	 * ClickPesa orderReference must be alphanumeric only (no hyphens).
	 */
	function clickpesa_order_reference($ref) {
		return preg_replace('/[^A-Za-z0-9]/', '', (string) $ref);
	}
}

if (!function_exists('clickpesa_payload_checksum')) {
	/**
	 * HMAC-SHA256 hex digest for ClickPesa request payloads (when checksum security is on).
	 */
	function clickpesa_payload_checksum(array $payload, $checksumKey = null) {
		$key = $checksumKey;
		if ($key === null || $key === '') {
			$env = getenv('CLICKPESA_CHECKSUM_KEY');
			$key = ($env !== false && $env !== '') ? (string) $env : '';
		}
		if ($key === '') {
			return '';
		}
		$forHash = $payload;
		unset($forHash['checksum'], $forHash['checksumMethod']);
		$canonical = clickpesa_canonicalize($forHash);
		$json = json_encode($canonical, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		if ($json === false) {
			return '';
		}
		return hash_hmac('sha256', $json, (string) $key);
	}
}

/**
 * Initiate USSD-PUSH collection. Returns provider refs without exposing brand to clients.
 *
 * @param array $opts amount, phone, order_reference, currency (default TZS)
 */
if (!function_exists('clickpesa_initiate_ussd_push')) {
	function clickpesa_initiate_ussd_push(array $opts) {
		$tok = clickpesa_generate_token();
		if (empty($tok['ok'])) {
			return $tok;
		}
		/* Alphanumeric only; append short suffix so retries stay unique */
		$baseRef = clickpesa_order_reference($opts['order_reference'] ?? '');
		if ($baseRef === '') {
			return ['ok' => false, 'error' => 'invalid_order_reference'];
		}
		$orderRef = $baseRef . 'P' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
		$payload = [
			'amount' => (string) ($opts['amount'] ?? ''),
			'currency' => $opts['currency'] ?? 'TZS',
			'orderReference' => $orderRef,
			'phoneNumber' => $opts['phone'] ?? '',
		];
		if (!empty($opts['webhook_url'])) {
			$payload['webhookUrl'] = $opts['webhook_url'];
		}
		$checksum = clickpesa_payload_checksum($payload);
		if ($checksum !== '') {
			$payload['checksum'] = $checksum;
		}
		$res = clickpesa_http_json(
			'POST',
			'/third-parties/payments/initiate-ussd-push-request',
			['Authorization' => $tok['token']],
			$payload
		);
		if (!$res['ok']) {
			error_log('clickpesa_ussd_initiate_failed status=' . ($res['status'] ?? '') . ' body=' . substr((string) ($res['raw'] ?? ''), 0, 500));
			return ['ok' => false, 'error' => 'initiate_failed', 'response' => $res];
		}
		$data = is_array($res['data']) ? $res['data'] : [];
		return [
			'ok' => true,
			'provider_id' => (string) ($data['id'] ?? $data['paymentId'] ?? $data['transactionId'] ?? ''),
			'order_reference' => (string) ($data['orderReference'] ?? $payload['orderReference']),
			'status' => (string) ($data['status'] ?? 'pending'),
			'raw' => $data,
		];
	}
}

if (!function_exists('clickpesa_canonicalize')) {
	function clickpesa_canonicalize($obj) {
		if ($obj === null || !is_array($obj)) {
			return $obj;
		}
		$isList = array_keys($obj) === range(0, count($obj) - 1);
		if ($isList) {
			return array_map('clickpesa_canonicalize', $obj);
		}
		ksort($obj);
		$out = [];
		foreach ($obj as $k => $v) {
			$out[$k] = clickpesa_canonicalize($v);
		}
		return $out;
	}
}

if (!function_exists('clickpesa_verify_webhook_checksum')) {
	/**
	 * Verify ClickPesa webhook HMAC-SHA256 checksum when a key is configured.
	 * Payload must exclude checksum / checksumMethod from the signed body.
	 */
	function clickpesa_verify_webhook_checksum(array $payload, string $checksumKey) {
		$received = (string) ($payload['checksum'] ?? '');
		if ($received === '' || $checksumKey === '') {
			return false;
		}
		$forHash = $payload;
		unset($forHash['checksum'], $forHash['checksumMethod']);
		$canonical = clickpesa_canonicalize($forHash);
		$json = json_encode($canonical, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		if ($json === false) {
			return false;
		}
		$computed = hash_hmac('sha256', $json, $checksumKey);
		return hash_equals(strtolower($computed), strtolower($received));
	}
}
