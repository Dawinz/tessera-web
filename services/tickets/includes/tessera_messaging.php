<?php
/**
 * Wave N - messaging vault + outbox notifier.
 */
require_once __DIR__ . '/tessera_vault.php';
require_once __DIR__ . '/tessera_tenant.php';

if (!function_exists('tessera_messaging_default_events')) {
	function tessera_messaging_default_events(): array {
		return [
			'ticket.booked' => ['sms' => true, 'whatsapp' => false, 'push' => true],
			'ticket.paid' => ['sms' => true, 'whatsapp' => false, 'push' => true],
			'ticket.cancelled' => ['sms' => true, 'whatsapp' => false, 'push' => false],
			'cargo.registered' => ['sms' => true, 'whatsapp' => false, 'push' => true],
			'cargo.packed' => ['sms' => true, 'whatsapp' => true, 'push' => true],
			'cargo.arrived' => ['sms' => true, 'whatsapp' => true, 'push' => true],
			'cargo.checkout' => ['sms' => true, 'whatsapp' => false, 'push' => true],
		];
	}
}

if (!function_exists('tessera_messaging_get')) {
	function tessera_messaging_get(mysqli $db, int $tenantId): array {
		$empty = [
			'tenant_id' => $tenantId,
			'sms_enabled' => 0,
			'sms_provider' => '',
			'sms_sender_id' => '',
			'sms_username' => '',
			'sms_api_key_last4' => '',
			'whatsapp_enabled' => 0,
			'whatsapp_provider' => '',
			'whatsapp_instance' => '',
			'whatsapp_api_key_last4' => '',
			'push_enabled' => 0,
			'push_service_url' => '',
			'events' => tessera_messaging_default_events(),
		];
		$t = @$db->query("SHOW TABLES LIKE 'tessera_tenant_messaging'");
		if (!$t || $t->num_rows === 0) {
			return $empty;
		}
		$q = @$db->query('SELECT * FROM tessera_tenant_messaging WHERE tenant_id=' . (int) $tenantId . ' LIMIT 1');
		$row = $q ? $q->fetch_assoc() : null;
		if (!$row) {
			return $empty;
		}
		$events = tessera_messaging_default_events();
		if (!empty($row['events_json'])) {
			$decoded = json_decode((string) $row['events_json'], true);
			if (is_array($decoded)) {
				$events = array_merge($events, $decoded);
			}
		}
		$row['events'] = $events;
		unset($row['sms_api_key_enc'], $row['whatsapp_api_key_enc'], $row['push_api_key_enc'], $row['events_json']);
		return array_merge($empty, $row);
	}
}

if (!function_exists('tessera_messaging_save')) {
	function tessera_messaging_save(mysqli $db, int $tenantId, array $input, string $actor): bool {
		$t = @$db->query("SHOW TABLES LIKE 'tessera_tenant_messaging'");
		if (!$t || $t->num_rows === 0) {
			return false;
		}
		$existing = @$db->query('SELECT * FROM tessera_tenant_messaging WHERE tenant_id=' . (int) $tenantId . ' LIMIT 1');
		$cur = $existing ? $existing->fetch_assoc() : null;

		$sms_enabled = !empty($input['sms_enabled']) ? 1 : 0;
		$wa_enabled = !empty($input['whatsapp_enabled']) ? 1 : 0;
		$push_enabled = !empty($input['push_enabled']) ? 1 : 0;
		$sms_provider = trim((string) ($input['sms_provider'] ?? ''));
		$sms_sender = trim((string) ($input['sms_sender_id'] ?? ''));
		$sms_user = trim((string) ($input['sms_username'] ?? ''));
		$wa_provider = trim((string) ($input['whatsapp_provider'] ?? ''));
		$wa_instance = trim((string) ($input['whatsapp_instance'] ?? ''));
		$push_url = trim((string) ($input['push_service_url'] ?? ''));
		$events = $input['events'] ?? tessera_messaging_default_events();
		if (!is_array($events)) {
			$events = tessera_messaging_default_events();
		}
		$events_json = $db->real_escape_string(json_encode($events));

		$sms_enc = $cur['sms_api_key_enc'] ?? null;
		$sms_last4 = $cur['sms_api_key_last4'] ?? null;
		if (isset($input['sms_api_key']) && trim((string) $input['sms_api_key']) !== '') {
			$key = trim((string) $input['sms_api_key']);
			$sms_enc = tessera_encrypt($key);
			$sms_last4 = substr($key, -4);
		}
		$wa_enc = $cur['whatsapp_api_key_enc'] ?? null;
		$wa_last4 = $cur['whatsapp_api_key_last4'] ?? null;
		if (isset($input['whatsapp_api_key']) && trim((string) $input['whatsapp_api_key']) !== '') {
			$key = trim((string) $input['whatsapp_api_key']);
			$wa_enc = tessera_encrypt($key);
			$wa_last4 = substr($key, -4);
		}
		$push_enc = $cur['push_api_key_enc'] ?? null;
		if (isset($input['push_api_key']) && trim((string) $input['push_api_key']) !== '') {
			$push_enc = tessera_encrypt(trim((string) $input['push_api_key']));
		}

		$tid = (int) $tenantId;
		$sp = $sms_provider !== '' ? "'" . $db->real_escape_string($sms_provider) . "'" : 'NULL';
		$ss = $sms_sender !== '' ? "'" . $db->real_escape_string($sms_sender) . "'" : 'NULL';
		$su = $sms_user !== '' ? "'" . $db->real_escape_string($sms_user) . "'" : 'NULL';
		$se = $sms_enc ? "'" . $db->real_escape_string($sms_enc) . "'" : 'NULL';
		$sl = $sms_last4 ? "'" . $db->real_escape_string($sms_last4) . "'" : 'NULL';
		$wp = $wa_provider !== '' ? "'" . $db->real_escape_string($wa_provider) . "'" : 'NULL';
		$wi = $wa_instance !== '' ? "'" . $db->real_escape_string($wa_instance) . "'" : 'NULL';
		$we = $wa_enc ? "'" . $db->real_escape_string($wa_enc) . "'" : 'NULL';
		$wl = $wa_last4 ? "'" . $db->real_escape_string($wa_last4) . "'" : 'NULL';
		$pu = $push_url !== '' ? "'" . $db->real_escape_string($push_url) . "'" : 'NULL';
		$pe = $push_enc ? "'" . $db->real_escape_string($push_enc) . "'" : 'NULL';
		$actor_e = $db->real_escape_string($actor);

		return (bool) @$db->query(
			"INSERT INTO tessera_tenant_messaging
			 (tenant_id, sms_enabled, sms_provider, sms_sender_id, sms_username, sms_api_key_enc, sms_api_key_last4,
			  whatsapp_enabled, whatsapp_provider, whatsapp_api_key_enc, whatsapp_api_key_last4, whatsapp_instance,
			  push_enabled, push_service_url, push_api_key_enc, events_json, updated_by)
			 VALUES ({$tid}, {$sms_enabled}, {$sp}, {$ss}, {$su}, {$se}, {$sl},
			  {$wa_enabled}, {$wp}, {$we}, {$wl}, {$wi},
			  {$push_enabled}, {$pu}, {$pe}, '{$events_json}', '{$actor_e}')
			 ON DUPLICATE KEY UPDATE
			  sms_enabled={$sms_enabled}, sms_provider={$sp}, sms_sender_id={$ss}, sms_username={$su},
			  sms_api_key_enc={$se}, sms_api_key_last4={$sl},
			  whatsapp_enabled={$wa_enabled}, whatsapp_provider={$wp}, whatsapp_api_key_enc={$we},
			  whatsapp_api_key_last4={$wl}, whatsapp_instance={$wi},
			  push_enabled={$push_enabled}, push_service_url={$pu}, push_api_key_enc={$pe},
			  events_json='{$events_json}', updated_by='{$actor_e}'"
		);
	}
}

if (!function_exists('tessera_notify_enqueue')) {
	/**
	 * Queue a notification. Does not send synchronously (worker/cron can drain).
	 */
	function tessera_notify_enqueue(mysqli $db, int $tenantId, string $event, string $channel, string $recipient, array $payload = []): bool {
		$t = @$db->query("SHOW TABLES LIKE 'tessera_notification_outbox'");
		if (!$t || $t->num_rows === 0) {
			return false;
		}
		$cfg = tessera_messaging_get($db, $tenantId);
		$events = $cfg['events'] ?? [];
		$want = !empty($events[$event][$channel]);
		if (!$want) {
			return false;
		}
		if ($channel === 'sms' && empty($cfg['sms_enabled'])) {
			return false;
		}
		if ($channel === 'whatsapp' && empty($cfg['whatsapp_enabled'])) {
			return false;
		}
		if ($channel === 'push' && empty($cfg['push_enabled'])) {
			return false;
		}
		$ch = $db->real_escape_string($channel);
		$ev = $db->real_escape_string($event);
		$rc = $db->real_escape_string($recipient);
		$pj = $db->real_escape_string(json_encode($payload));
		return (bool) @$db->query(
			"INSERT INTO tessera_notification_outbox (tenant_id, channel, event_code, recipient, payload_json, status)
			 VALUES (" . (int) $tenantId . ", '{$ch}', '{$ev}', '{$rc}', '{$pj}', 'pending')"
		);
	}
}

if (!function_exists('tessera_notify_event')) {
	function tessera_notify_event(mysqli $db, int $tenantId, string $event, string $phone, array $payload = []): void {
		$phone = preg_replace('/\s+/', '', $phone) ?? '';
		if ($phone === '') {
			return;
		}
		foreach (['sms', 'whatsapp', 'push'] as $ch) {
			tessera_notify_enqueue($db, $tenantId, $event, $ch, $phone, $payload);
		}
	}
}

if (!function_exists('tessera_notify_send_sms_nextsms')) {
	function tessera_notify_send_sms_nextsms(string $user, string $pass, string $from, string $to, string $text): bool {
		$auth = base64_encode($user . ':' . $pass);
		$payload = json_encode(['from' => $from, 'to' => $to, 'text' => $text]);
		$ch = curl_init('https://messaging-service.co.tz/api/sms/v1/text/single');
		curl_setopt_array($ch, [
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				'Authorization: Basic ' . $auth,
				'Content-Type: application/json',
				'Accept: application/json',
			],
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_TIMEOUT => 20,
		]);
		$body = curl_exec($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $code >= 200 && $code < 300 && $body !== false;
	}
}

if (!function_exists('tessera_notify_send_sms_mshastra')) {
	/**
	 * Mshastra-style GET send (user/pass/sender/to/message).
	 * @see common Mshastra HTTP API
	 */
	function tessera_notify_send_sms_mshastra(string $user, string $pass, string $from, string $to, string $text): bool {
		$qs = http_build_query([
			'user' => $user,
			'pwd' => $pass,
			'senderid' => $from,
			'mobileno' => $to,
			'msgtext' => $text,
			'CountryCode' => '255',
		]);
		$url = 'https://www.mshastra.com/sendurlcomma.aspx?' . $qs;
		$ch = curl_init($url);
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 20,
		]);
		$body = curl_exec($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $code >= 200 && $code < 400 && $body !== false;
	}
}

if (!function_exists('tessera_notify_send_whatsapp_ultramsg')) {
	function tessera_notify_send_whatsapp_ultramsg(string $instance, string $token, string $to, string $text): bool {
		$instance = trim($instance);
		$token = trim($token);
		if ($instance === '' || $token === '') {
			return false;
		}
		$to = preg_replace('/\D+/', '', $to) ?? '';
		if ($to === '') {
			return false;
		}
		$url = 'https://api.ultramsg.com/' . rawurlencode($instance) . '/messages/chat';
		$ch = curl_init($url);
		curl_setopt_array($ch, [
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => http_build_query([
				'token' => $token,
				'to' => $to,
				'body' => $text,
			]),
			CURLOPT_TIMEOUT => 20,
		]);
		$body = curl_exec($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $code >= 200 && $code < 300 && $body !== false;
	}
}

if (!function_exists('tessera_notify_send_push')) {
	function tessera_notify_send_push(string $serviceUrl, string $apiKey, string $phone, array $payload): bool {
		$serviceUrl = rtrim($serviceUrl, '/');
		if ($serviceUrl === '') {
			return false;
		}
		$endpoint = $serviceUrl . '/notify';
		$body = json_encode([
			'dialPhone' => $phone,
			'title' => (string) ($payload['title'] ?? 'Update'),
			'body' => (string) ($payload['message'] ?? ''),
			'data' => $payload,
		]);
		$headers = ['Content-Type: application/json', 'Accept: application/json'];
		if ($apiKey !== '') {
			$headers[] = 'X-Api-Key: ' . $apiKey;
		}
		$ch = curl_init($endpoint);
		curl_setopt_array($ch, [
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => $body,
			CURLOPT_TIMEOUT => 15,
		]);
		$resp = curl_exec($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($code >= 200 && $code < 300) {
			return true;
		}
		/* Fallback alternate path some push services use */
		$ch = curl_init($serviceUrl . '/push');
		curl_setopt_array($ch, [
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => $body,
			CURLOPT_TIMEOUT => 15,
		]);
		curl_exec($ch);
		$code2 = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $code2 >= 200 && $code2 < 300;
	}
}

if (!function_exists('tessera_notify_drain')) {
	function tessera_notify_drain(mysqli $db, int $limit = 20): array {
		$t = @$db->query("SHOW TABLES LIKE 'tessera_notification_outbox'");
		if (!$t || $t->num_rows === 0) {
			return ['sent' => 0, 'failed' => 0];
		}
		$limit = max(1, min(100, $limit));
		$q = @$db->query("SELECT * FROM tessera_notification_outbox WHERE status='pending' ORDER BY id ASC LIMIT {$limit}");
		$sent = 0;
		$failed = 0;
		if (!$q) {
			return ['sent' => 0, 'failed' => 0];
		}
		while ($row = $q->fetch_assoc()) {
			$id = (int) $row['id'];
			$tenantId = (int) $row['tenant_id'];
			$full = @$db->query('SELECT * FROM tessera_tenant_messaging WHERE tenant_id=' . $tenantId . ' LIMIT 1');
			$cfg = $full ? $full->fetch_assoc() : null;
			$payload = json_decode((string) ($row['payload_json'] ?? '{}'), true);
			if (!is_array($payload)) {
				$payload = [];
			}
			$text = (string) ($payload['message'] ?? 'Update from your operator');
			$ok = false;
			$err = '';
			if ($row['channel'] === 'sms' && $cfg) {
				$key = tessera_decrypt((string) ($cfg['sms_api_key_enc'] ?? ''));
				$provider = strtolower((string) ($cfg['sms_provider'] ?? 'nextsms'));
				$from = (string) ($cfg['sms_sender_id'] ?: 'TESSERA');
				$user = (string) ($cfg['sms_username'] ?? '');
				if ($key === '' || $user === '') {
					$err = 'sms_not_configured';
				} elseif ($provider === 'mshastra') {
					$ok = tessera_notify_send_sms_mshastra($user, $key, $from, (string) $row['recipient'], $text);
					if (!$ok) {
						$err = 'sms_mshastra_failed';
					}
				} else {
					$ok = tessera_notify_send_sms_nextsms($user, $key, $from, (string) $row['recipient'], $text);
					if (!$ok) {
						$err = 'sms_send_failed';
					}
				}
			} elseif ($row['channel'] === 'whatsapp' && $cfg) {
				$token = tessera_decrypt((string) ($cfg['whatsapp_api_key_enc'] ?? ''));
				$instance = (string) ($cfg['whatsapp_instance'] ?? '');
				$provider = strtolower((string) ($cfg['whatsapp_provider'] ?? 'ultramsg'));
				if ($token === '' || $instance === '') {
					$err = 'whatsapp_not_configured';
				} elseif ($provider === 'ultramsg' || $provider === '' || $provider === 'other') {
					$ok = tessera_notify_send_whatsapp_ultramsg($instance, $token, (string) $row['recipient'], $text);
					if (!$ok) {
						$err = 'whatsapp_send_failed';
					}
				} else {
					$err = 'whatsapp_provider_unsupported';
				}
			} elseif ($row['channel'] === 'push' && $cfg) {
				$url = (string) ($cfg['push_service_url'] ?? '');
				$apiKey = tessera_decrypt((string) ($cfg['push_api_key_enc'] ?? ''));
				if ($url === '') {
					$err = 'push_not_configured';
				} else {
					$ok = tessera_notify_send_push($url, $apiKey, (string) $row['recipient'], $payload + ['title' => 'Cargo / ticket update']);
					if (!$ok) {
						$err = 'push_send_failed';
					}
				}
			} else {
				$err = 'channel_or_config_missing';
			}
			if ($ok) {
				@$db->query("UPDATE tessera_notification_outbox SET status='sent', sent_at=NOW(), attempts=attempts+1 WHERE id={$id}");
				$sent++;
			} else {
				$err_e = $db->real_escape_string($err ?: 'failed');
				@$db->query("UPDATE tessera_notification_outbox SET status='failed', last_error='{$err_e}', attempts=attempts+1 WHERE id={$id}");
				$failed++;
			}
		}
		return ['sent' => $sent, 'failed' => $failed];
	}
}
