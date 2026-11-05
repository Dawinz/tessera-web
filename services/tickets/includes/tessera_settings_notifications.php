<?php
/**
 * Operator settings — notifications helpers (S-03).
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_messaging.php';

if (!function_exists('tessera_settings_notifications_event_labels')) {
	/** @return array<string,string> */
	function tessera_settings_notifications_event_labels(): array
	{
		return [
			'ticket.booked' => 'Ticket booked',
			'ticket.paid' => 'Ticket paid',
			'ticket.cancelled' => 'Ticket cancelled',
			'cargo.registered' => 'Cargo registered',
			'cargo.packed' => 'Cargo packed',
			'cargo.arrived' => 'Cargo arrived',
			'cargo.checkout' => 'Cargo checked out',
		];
	}
}

if (!function_exists('tessera_settings_notifications_public')) {
	/**
	 * Safe public shape (no encrypted secrets).
	 *
	 * @param array<string,mixed> $cfg
	 * @return array<string,mixed>
	 */
	function tessera_settings_notifications_public(array $cfg): array
	{
		$defaults = tessera_messaging_default_events();
		$eventsIn = is_array($cfg['events'] ?? null) ? $cfg['events'] : $defaults;
		$labels = tessera_settings_notifications_event_labels();
		$events = [];
		foreach ($defaults as $code => $chs) {
			$cur = is_array($eventsIn[$code] ?? null) ? $eventsIn[$code] : $chs;
			$events[] = [
				'code' => $code,
				'label' => $labels[$code] ?? $code,
				'sms' => !empty($cur['sms']),
				'whatsapp' => !empty($cur['whatsapp']),
				'push' => !empty($cur['push']),
			];
		}
		return [
			'sms_enabled' => !empty($cfg['sms_enabled']),
			'sms_provider' => (string) ($cfg['sms_provider'] ?? ''),
			'sms_sender_id' => (string) ($cfg['sms_sender_id'] ?? ''),
			'sms_username' => (string) ($cfg['sms_username'] ?? ''),
			'sms_api_key_last4' => (string) ($cfg['sms_api_key_last4'] ?? ''),
			'sms_api_key_set' => ((string) ($cfg['sms_api_key_last4'] ?? '')) !== '',
			'whatsapp_enabled' => !empty($cfg['whatsapp_enabled']),
			'whatsapp_provider' => (string) ($cfg['whatsapp_provider'] ?? ''),
			'whatsapp_instance' => (string) ($cfg['whatsapp_instance'] ?? ''),
			'whatsapp_api_key_last4' => (string) ($cfg['whatsapp_api_key_last4'] ?? ''),
			'whatsapp_api_key_set' => ((string) ($cfg['whatsapp_api_key_last4'] ?? '')) !== '',
			'push_enabled' => !empty($cfg['push_enabled']),
			'push_service_url' => (string) ($cfg['push_service_url'] ?? ''),
			'push_api_key_set' => !empty($cfg['push_api_key_enc']) || !empty($cfg['push_api_key_set']),
			'events' => $events,
			'sms_providers' => [
				['code' => 'nextsms', 'label' => 'NextSMS'],
				['code' => 'mshastra', 'label' => 'Mshastra'],
				['code' => 'other', 'label' => 'Other'],
			],
		];
	}
}

if (!function_exists('tessera_settings_notifications_get')) {
	/**
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_settings_notifications_get(mysqli $db, int $tenantId): array
	{
		if ($tenantId < 1) {
			return ['ok' => false, 'error' => 'No tenant context'];
		}
		$cfg = tessera_messaging_get($db, $tenantId);
		return ['ok' => true, 'item' => tessera_settings_notifications_public($cfg)];
	}
}

if (!function_exists('tessera_settings_notifications_save')) {
	/**
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_settings_notifications_save(mysqli $db, int $tenantId, array $input, string $actor): array
	{
		if ($tenantId < 1) {
			return ['ok' => false, 'error' => 'No tenant context'];
		}
		$defaults = tessera_messaging_default_events();
		$events = $defaults;
		$rawEvents = $input['events'] ?? null;
		if (is_array($rawEvents)) {
			/* Support both [{code,sms,...}] and {code:{sms:...}} */
			$isList = array_keys($rawEvents) === range(0, count($rawEvents) - 1);
			if ($isList) {
				foreach ($rawEvents as $row) {
					if (!is_array($row)) {
						continue;
					}
					$code = (string) ($row['code'] ?? '');
					if ($code === '' || !isset($defaults[$code])) {
						continue;
					}
					$events[$code] = [
						'sms' => !empty($row['sms']),
						'whatsapp' => !empty($row['whatsapp']),
						'push' => !empty($row['push']),
					];
				}
			} else {
				foreach ($defaults as $code => $chs) {
					$cur = is_array($rawEvents[$code] ?? null) ? $rawEvents[$code] : $chs;
					$events[$code] = [
						'sms' => !empty($cur['sms']),
						'whatsapp' => !empty($cur['whatsapp']),
						'push' => !empty($cur['push']),
					];
				}
			}
		}

		$payload = [
			'sms_enabled' => !empty($input['sms_enabled']),
			'sms_provider' => (string) ($input['sms_provider'] ?? ''),
			'sms_sender_id' => (string) ($input['sms_sender_id'] ?? ''),
			'sms_username' => (string) ($input['sms_username'] ?? ''),
			'whatsapp_enabled' => !empty($input['whatsapp_enabled']),
			'whatsapp_provider' => (string) ($input['whatsapp_provider'] ?? ''),
			'whatsapp_instance' => (string) ($input['whatsapp_instance'] ?? ''),
			'push_enabled' => !empty($input['push_enabled']),
			'push_service_url' => (string) ($input['push_service_url'] ?? ''),
			'events' => $events,
		];
		if (isset($input['sms_api_key']) && trim((string) $input['sms_api_key']) !== '') {
			$payload['sms_api_key'] = trim((string) $input['sms_api_key']);
		}
		if (isset($input['whatsapp_api_key']) && trim((string) $input['whatsapp_api_key']) !== '') {
			$payload['whatsapp_api_key'] = trim((string) $input['whatsapp_api_key']);
		}
		if (isset($input['push_api_key']) && trim((string) $input['push_api_key']) !== '') {
			$payload['push_api_key'] = trim((string) $input['push_api_key']);
		}

		$ok = tessera_messaging_save($db, $tenantId, $payload, $actor);
		if (!$ok) {
			return ['ok' => false, 'error' => 'Save failed (messaging table missing — run migration 013)'];
		}
		$cfg = tessera_messaging_get($db, $tenantId);
		return ['ok' => true, 'item' => tessera_settings_notifications_public($cfg)];
	}
}
