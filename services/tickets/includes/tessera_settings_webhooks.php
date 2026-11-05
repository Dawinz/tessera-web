<?php
/**
 * Operator settings — webhooks helpers (S-06).
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_operator_api.php';

if (!function_exists('tessera_settings_webhooks_event_catalog')) {
	/** @return list<array{code:string,label:string}> */
	function tessera_settings_webhooks_event_catalog(): array
	{
		return [
			['code' => 'booking.confirmed', 'label' => 'Booking confirmed'],
			['code' => 'booking.paid', 'label' => 'Booking paid'],
			['code' => 'booking.cancelled', 'label' => 'Booking cancelled'],
			['code' => 'cargo.status_changed', 'label' => 'Cargo status changed'],
			['code' => 'payout.sent', 'label' => 'Payout sent'],
		];
	}
}

if (!function_exists('tessera_settings_webhooks_public_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_settings_webhooks_public_item(array $row): array
	{
		$events = $row['events'] ?? [];
		if (!is_array($events)) {
			$events = [];
		}
		return [
			'id' => (int) ($row['id'] ?? 0),
			'url' => (string) ($row['url'] ?? ''),
			'events' => array_values($events),
			'status' => (string) ($row['status'] ?? ''),
			'secret_last4' => (string) ($row['secret_last4'] ?? ''),
			'created_at' => $row['created_at'] ?? null,
		];
	}
}

if (!function_exists('tessera_settings_webhooks_list')) {
	/**
	 * @return array{ok:bool,error?:string,items?:list<array<string,mixed>>,meta?:array<string,mixed>}
	 */
	function tessera_settings_webhooks_list(mysqli $db, int $tenantId): array
	{
		if ($tenantId < 1) {
			return ['ok' => false, 'error' => 'No tenant context'];
		}
		$items = [];
		foreach (tessera_webhook_list($db, $tenantId) as $row) {
			$items[] = tessera_settings_webhooks_public_item($row);
		}
		return [
			'ok' => true,
			'items' => $items,
			'meta' => [
				'events' => tessera_settings_webhooks_event_catalog(),
				'signature_header' => 'X-Tessera-Signature: sha256=…',
				'api_keys_url' => '/settings/api.php',
			],
		];
	}
}

if (!function_exists('tessera_settings_webhooks_create')) {
	/**
	 * @param list<string>|mixed $events
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>,plaintext_secret?:string,items?:list<array<string,mixed>>,meta?:array<string,mixed>,message?:string}
	 */
	function tessera_settings_webhooks_create(mysqli $db, int $tenantId, string $url, $events, string $actor): array
	{
		if ($tenantId < 1) {
			return ['ok' => false, 'error' => 'No tenant context'];
		}
		$allowed = array_column(tessera_settings_webhooks_event_catalog(), 'code');
		$clean = [];
		if (is_array($events)) {
			foreach ($events as $ev) {
				$ev = (string) $ev;
				if ($ev === '*' || in_array($ev, $allowed, true)) {
					$clean[] = $ev;
				}
			}
		}
		$clean = array_values(array_unique($clean));
		if ($clean === []) {
			$clean = ['*'];
		}
		$created = tessera_webhook_create($db, $tenantId, $url, $clean, $actor);
		if (!$created) {
			return ['ok' => false, 'error' => 'Invalid HTTPS URL or migration 014 missing'];
		}
		$list = tessera_settings_webhooks_list($db, $tenantId);
		return [
			'ok' => true,
			'item' => [
				'id' => (int) ($created['id'] ?? 0),
				'url' => (string) ($created['url'] ?? $url),
				'events' => $created['events'] ?? $clean,
				'status' => 'active',
			],
			'plaintext_secret' => (string) ($created['secret'] ?? ''),
			'items' => $list['items'] ?? [],
			'meta' => $list['meta'] ?? [],
			'message' => 'Webhook registered. Copy the signing secret once.',
		];
	}
}

if (!function_exists('tessera_settings_webhooks_disable')) {
	/**
	 * @return array{ok:bool,error?:string,items?:list<array<string,mixed>>,meta?:array<string,mixed>,message?:string}
	 */
	function tessera_settings_webhooks_disable(mysqli $db, int $tenantId, int $id): array
	{
		if ($tenantId < 1 || $id < 1) {
			return ['ok' => false, 'error' => 'Invalid webhook'];
		}
		if (!tessera_webhook_disable($db, $tenantId, $id)) {
			return ['ok' => false, 'error' => 'Disable failed'];
		}
		$list = tessera_settings_webhooks_list($db, $tenantId);
		return [
			'ok' => true,
			'items' => $list['items'] ?? [],
			'meta' => $list['meta'] ?? [],
			'message' => 'Webhook disabled.',
		];
	}
}
