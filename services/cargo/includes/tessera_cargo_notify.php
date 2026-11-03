<?php
/**
 * Cargo -> Tessera notification bridge (ticket DB messaging vault).
 */
if (!function_exists('tessera_cargo_ticket_db')) {
	function tessera_cargo_ticket_db(): ?mysqli {
		static $cached = null;
		if ($cached instanceof mysqli) {
			return $cached;
		}
		$paths = [
			'/var/www/html/tickets/includes',
			dirname(__DIR__, 3) . '/services/tickets/includes',
			dirname(__DIR__, 2) . '/tickets/includes',
		];
		$inc = null;
		foreach ($paths as $p) {
			if (is_file($p . '/tessera_messaging.php')) {
				$inc = $p;
				break;
			}
		}
		if ($inc === null) {
			return null;
		}
		require_once $inc . '/sr_env.php';
		require_once $inc . '/tessera_tenant.php';
		require_once $inc . '/tessera_messaging.php';
		require_once $inc . '/tessera_operator_api.php';
		if (!function_exists('sr_mysqli')) {
			return null;
		}
		$cached = @sr_mysqli(getenv('SR_DB_NAME') ?: 'ticket');
		return ($cached instanceof mysqli && !$cached->connect_error) ? $cached : null;
	}
}

if (!function_exists('tessera_cargo_notify')) {
	function tessera_cargo_notify(string $event, string $phone, string $code, string $message, array $extra = []): void {
		try {
			$db = tessera_cargo_ticket_db();
			if (!$db || $phone === '') {
				return;
			}
			$tid = function_exists('tessera_current_tenant_id') ? (int) tessera_current_tenant_id() : 1;
			tessera_notify_event($db, $tid, $event, $phone, array_merge([
				'message' => $message,
				'code' => $code,
			], $extra));
			if (function_exists('tessera_webhook_dispatch')) {
				tessera_webhook_dispatch($db, $tid, 'cargo.status_changed', array_merge([
					'code' => $code,
					'event' => $event,
				], $extra));
			}
		} catch (Throwable $e) {
			/* never break cargo flow */
		}
	}
}
