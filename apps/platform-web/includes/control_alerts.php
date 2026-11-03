<?php
/**
 * Tessera Control Wave E — notifications, prefs, host isolation, alert scans.
 */

function control_notify_prefs_defaults(): array {
	$cfg = control_config();
	$to = $cfg['alert_email'] !== '' ? $cfg['alert_email'] : $cfg['owner_email'];
	return [
		'email_suspend' => true,
		'email_webhook_fail' => true,
		'email_stuck_provisioning' => true,
		'provisioning_days' => 7,
		'email_to' => $to,
	];
}

function control_notify_prefs(?mysqli $db, bool $db_ok): array {
	$defaults = control_notify_prefs_defaults();
	$raw = control_ops_get($db, $db_ok, 'notify_prefs', '');
	if ($raw === '') {
		return $defaults;
	}
	$decoded = json_decode($raw, true);
	if (!is_array($decoded)) {
		return $defaults;
	}
	return array_merge($defaults, $decoded);
}

function control_save_notify_prefs(?mysqli $db, bool $db_ok, array $input, string $actor): bool {
	$prefs = control_notify_prefs($db, $db_ok);
	$prefs['email_suspend'] = !empty($input['email_suspend']);
	$prefs['email_webhook_fail'] = !empty($input['email_webhook_fail']);
	$prefs['email_stuck_provisioning'] = !empty($input['email_stuck_provisioning']);
	$prefs['provisioning_days'] = max(1, min(90, (int) ($input['provisioning_days'] ?? 7)));
	$email = strtolower(trim((string) ($input['email_to'] ?? '')));
	if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$prefs['email_to'] = $email;
	}
	return control_ops_set($db, $db_ok, 'notify_prefs', json_encode($prefs), $actor);
}

function control_branding_get(?mysqli $db, bool $db_ok): array {
	return [
		'subtitle' => control_ops_get($db, $db_ok, 'control_brand_subtitle', 'Control') ?: 'Control',
		'logo_url' => control_ops_get($db, $db_ok, 'control_logo_url', ''),
	];
}

function control_branding_save(?mysqli $db, bool $db_ok, array $input, string $actor): bool {
	$subtitle = trim((string) ($input['subtitle'] ?? 'Control'));
	$logo = trim((string) ($input['logo_url'] ?? ''));
	if ($subtitle === '') {
		$subtitle = 'Control';
	}
	$ok1 = control_ops_set($db, $db_ok, 'control_brand_subtitle', $subtitle, $actor);
	$ok2 = control_ops_set($db, $db_ok, 'control_logo_url', $logo, $actor);
	return $ok1 && $ok2;
}

/**
 * Emit a platform notification (deduped). Optionally email per prefs.
 *
 * @return int|null notification id
 */
function control_notify(?mysqli $db, bool $db_ok, array $payload, bool $forceEmail = false): ?int {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_notifications')) {
		return null;
	}
	$type = substr((string) ($payload['type'] ?? 'info'), 0, 64);
	$title = trim((string) ($payload['title'] ?? ''));
	if ($title === '') {
		return null;
	}
	$severity = (string) ($payload['severity'] ?? 'info');
	if (!in_array($severity, ['info', 'warning', 'danger'], true)) {
		$severity = 'info';
	}
	$body = (string) ($payload['body'] ?? '');
	$href = (string) ($payload['href'] ?? '');
	$tenantId = isset($payload['tenant_id']) && $payload['tenant_id'] !== null && $payload['tenant_id'] !== ''
		? (int) $payload['tenant_id'] : null;
	$dedupe = (string) ($payload['dedupe_key'] ?? '');
	if ($dedupe === '') {
		$dedupe = $type . ':' . md5($title . '|' . $body . '|' . ($tenantId ?? ''));
	}
	$dedupe = substr($dedupe, 0, 191);

	$type_e = $db->real_escape_string($type);
	$sev_e = $db->real_escape_string($severity);
	$title_e = $db->real_escape_string($title);
	$body_e = $db->real_escape_string($body);
	$href_e = $db->real_escape_string($href);
	$dedupe_e = $db->real_escape_string($dedupe);
	$tidSql = $tenantId !== null ? (string) $tenantId : 'NULL';

	$ok = @$db->query(
		"INSERT INTO tessera_notifications (type, severity, title, body, tenant_id, href, dedupe_key)
		 VALUES ('{$type_e}', '{$sev_e}', '{$title_e}', NULLIF('{$body_e}',''), {$tidSql}, NULLIF('{$href_e}',''), '{$dedupe_e}')"
	);
	if (!$ok) {
		/* Duplicate dedupe — fetch existing */
		$q = @$db->query("SELECT id FROM tessera_notifications WHERE dedupe_key='{$dedupe_e}' LIMIT 1");
		$row = $q ? $q->fetch_assoc() : null;
		return $row ? (int) $row['id'] : null;
	}
	$id = (int) $db->insert_id;

	$prefs = control_notify_prefs($db, $db_ok);
	$shouldEmail = $forceEmail;
	if (!$shouldEmail) {
		if ($type === 'tenant.suspend' && !empty($prefs['email_suspend'])) {
			$shouldEmail = true;
		}
		if ($type === 'webhook.fail' && !empty($prefs['email_webhook_fail'])) {
			$shouldEmail = true;
		}
		if ($type === 'tenant.stuck_provisioning' && !empty($prefs['email_stuck_provisioning'])) {
			$shouldEmail = true;
		}
	}
	if ($shouldEmail) {
		control_notify_send_email($db, $db_ok, $id, $prefs, $title, $body);
	}
	return $id;
}

function control_notify_send_email(?mysqli $db, bool $db_ok, int $id, array $prefs, string $title, string $body): void {
	$to = trim((string) ($prefs['email_to'] ?? ''));
	if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
		return;
	}
	$subject = '[Tessera Control] ' . $title;
	$headers = 'From: Tessera Control <noreply@tessera.co.tz>' . "\r\n" .
		'Content-Type: text/plain; charset=UTF-8';
	$sent = @mail($to, $subject, $body !== '' ? $body : $title, $headers);
	if ($sent && $db_ok && $db) {
		$id = (int) $id;
		@$db->query("UPDATE tessera_notifications SET email_sent_at=NOW() WHERE id={$id} LIMIT 1");
	}
}

function control_list_notifications(?mysqli $db, bool $db_ok, array $filters = []): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_notifications')) {
		return [];
	}
	$limit = max(1, min(100, (int) ($filters['limit'] ?? 20)));
	$where = ['1=1'];
	if (!empty($filters['unread_only'])) {
		$where[] = 'read_at IS NULL';
	}
	$sql = 'SELECT * FROM tessera_notifications WHERE ' . implode(' AND ', $where)
		. " ORDER BY id DESC LIMIT {$limit}";
	$rows = [];
	$q = @$db->query($sql);
	if ($q) {
		while ($row = $q->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	return $rows;
}

function control_unread_notification_count(?mysqli $db, bool $db_ok): int {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_notifications')) {
		return 0;
	}
	$q = @$db->query('SELECT COUNT(*) AS c FROM tessera_notifications WHERE read_at IS NULL');
	if ($q && ($r = $q->fetch_assoc())) {
		return (int) $r['c'];
	}
	return 0;
}

function control_mark_notifications_read(?mysqli $db, bool $db_ok, ?int $id = null): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_notifications')) {
		return false;
	}
	if ($id !== null) {
		$id = (int) $id;
		return (bool) @$db->query("UPDATE tessera_notifications SET read_at=NOW() WHERE id={$id} AND read_at IS NULL LIMIT 1");
	}
	return (bool) @$db->query('UPDATE tessera_notifications SET read_at=NOW() WHERE read_at IS NULL');
}

/**
 * Scan platform for alert conditions (throttled).
 */
function control_alerts_scan(?mysqli $db, bool $db_ok, bool $force = false): array {
	$emitted = [];
	if (!$db_ok || !$db) {
		return $emitted;
	}
	$last = control_ops_get($db, $db_ok, 'alerts_last_scan', '');
	if (!$force && $last !== '' && (time() - (int) strtotime($last)) < 300) {
		return $emitted;
	}
	control_ops_set($db, $db_ok, 'alerts_last_scan', date('Y-m-d H:i:s'), 'system');

	$prefs = control_notify_prefs($db, $db_ok);
	$days = max(1, (int) ($prefs['provisioning_days'] ?? 7));

	/* Stuck provisioning */
	if (control_table_exists($db, 'tessera_tenants')) {
		$q = @$db->query(
			"SELECT id, display_name, updated_at FROM tessera_tenants
			 WHERE status='provisioning'
			   AND updated_at < DATE_SUB(NOW(), INTERVAL {$days} DAY)"
		);
		if ($q) {
			while ($t = $q->fetch_assoc()) {
				$id = control_notify($db, $db_ok, [
					'type' => 'tenant.stuck_provisioning',
					'severity' => 'warning',
					'title' => 'Tenant stuck in provisioning',
					'body' => ($t['display_name'] ?? 'Operator') . ' has been provisioning > ' . $days . ' days.',
					'tenant_id' => (int) $t['id'],
					'href' => 'operator.php?id=' . (int) $t['id'],
					'dedupe_key' => 'stuck_prov:' . (int) $t['id'] . ':' . date('Y-m-d'),
				]);
				if ($id) {
					$emitted[] = $id;
				}
			}
		}
	}

	/* Webhook failures */
	foreach (control_webhook_failures($db, $db_ok) as $w) {
		$tid = (int) ($w['tenant_id'] ?? 0);
		$status = (string) ($w['webhook_last_status'] ?? 'fail');
		$id = control_notify($db, $db_ok, [
			'type' => 'webhook.fail',
			'severity' => 'danger',
			'title' => 'Payment webhook failing',
			'body' => ($w['display_name'] ?? 'Tenant') . ' · status ' . $status,
			'tenant_id' => $tid ?: null,
			'href' => $tid ? ('operator.php?id=' . $tid) : 'health.php',
			'dedupe_key' => 'webhook:' . $tid . ':' . md5($status . ($w['webhook_last_at'] ?? '')),
		]);
		if ($id) {
			$emitted[] = $id;
		}
	}

	/* Wave J4 — overdue SaaS invoices → notify + soft-suspend */
	foreach (control_dunning_scan($db, $db_ok) as $nid) {
		$emitted[] = $nid;
	}

	return $emitted;
}

/**
 * Overdue = status sent AND due_date < today.
 * Soft-locks tenant via suspend (skips flagship id=1 unless DUNNING_INCLUDE_FLAGSHIP=1).
 */
function control_dunning_scan(?mysqli $db, bool $db_ok): array {
	$emitted = [];
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_invoices')) {
		return $emitted;
	}
	$grace = (int) (control_ops_get($db, $db_ok, 'dunning_grace_days', '0') ?: 0);
	$grace = max(0, min(30, $grace));
	$protectFlagship = getenv('DUNNING_INCLUDE_FLAGSHIP') !== '1';

	$q = @$db->query(
		"SELECT t.id AS tenant_id, t.display_name, t.slug, t.status AS tenant_status,
		        COUNT(i.id) AS overdue_n, MIN(i.due_date) AS oldest_due,
		        GROUP_CONCAT(i.id ORDER BY i.id) AS invoice_ids
		 FROM tessera_invoices i
		 JOIN tessera_tenants t ON t.id = i.tenant_id
		 WHERE i.status = 'sent'
		   AND i.due_date IS NOT NULL
		   AND i.due_date < DATE_SUB(CURDATE(), INTERVAL {$grace} DAY)
		 GROUP BY t.id, t.display_name, t.slug, t.status"
	);
	if (!$q) {
		return $emitted;
	}
	while ($row = $q->fetch_assoc()) {
		$tid = (int) $row['tenant_id'];
		if ($protectFlagship && $tid === 1) {
			continue;
		}
		$name = (string) ($row['display_name'] ?? ('#' . $tid));
		$n = (int) $row['overdue_n'];
		$oldest = (string) ($row['oldest_due'] ?? '');
		$nid = control_notify($db, $db_ok, [
			'type' => 'billing.overdue',
			'severity' => 'danger',
			'title' => 'SaaS invoice overdue',
			'body' => "{$name}: {$n} unpaid invoice(s); oldest due {$oldest}. Soft-lock applied if not already suspended.",
			'tenant_id' => $tid,
			'href' => 'billing.php',
			'dedupe_key' => 'dunning:' . $tid . ':' . date('Y-m-d'),
		]);
		if ($nid) {
			$emitted[] = $nid;
		}
		if (($row['tenant_status'] ?? '') !== 'suspended') {
			if (control_set_tenant_status($db, $db_ok, $tid, 'suspended', 'system:dunning')) {
				control_ops_set($db, $db_ok, 'dunning_lock_tenant_' . $tid, date('Y-m-d H:i:s'), 'system:dunning');
				control_audit($db, $db_ok, 'system:dunning', 'billing.dunning_lock', $tid, [
					'invoice_ids' => $row['invoice_ids'],
					'oldest_due' => $oldest,
					'overdue_n' => $n,
				]);
			}
		}
	}
	return $emitted;
}

/** True when tenant has any sent invoice past due (optional grace). */
function control_tenant_has_overdue_invoices(?mysqli $db, bool $db_ok, int $tenantId): bool {
	if (!$db_ok || !$db || $tenantId <= 0 || !control_table_exists($db, 'tessera_invoices')) {
		return false;
	}
	$grace = (int) (control_ops_get($db, $db_ok, 'dunning_grace_days', '0') ?: 0);
	$grace = max(0, min(30, $grace));
	$q = @$db->query(
		"SELECT 1 FROM tessera_invoices
		 WHERE tenant_id=" . (int) $tenantId . "
		   AND status='sent'
		   AND due_date IS NOT NULL
		   AND due_date < DATE_SUB(CURDATE(), INTERVAL {$grace} DAY)
		 LIMIT 1"
	);
	return (bool) ($q && $q->num_rows > 0);
}

function control_dunning_clear_if_paid(?mysqli $db, bool $db_ok, int $tenantId, string $actor): bool {
	if (!$db_ok || !$db || $tenantId <= 0) {
		return false;
	}
	if (control_tenant_has_overdue_invoices($db, $db_ok, $tenantId)) {
		return false;
	}
	$lock = control_ops_get($db, $db_ok, 'dunning_lock_tenant_' . $tenantId, '');
	if ($lock === '') {
		return false; /* not a dunning lock — leave manual suspend alone */
	}
	$t = control_get_tenant($db, $db_ok, $tenantId);
	if (!$t || ($t['status'] ?? '') !== 'suspended') {
		control_ops_set($db, $db_ok, 'dunning_lock_tenant_' . $tenantId, '', $actor);
		return false;
	}
	if (!control_set_tenant_status($db, $db_ok, $tenantId, 'active', $actor)) {
		return false;
	}
	control_ops_set($db, $db_ok, 'dunning_lock_tenant_' . $tenantId, '', $actor);
	control_audit($db, $db_ok, $actor, 'billing.dunning_unlock', $tenantId, [
		'was_locked_at' => $lock,
	]);
	return true;
}

function control_notify_suspend(?mysqli $db, bool $db_ok, int $tenantId, string $displayName): void {
	control_notify($db, $db_ok, [
		'type' => 'tenant.suspend',
		'severity' => 'danger',
		'title' => 'Operator suspended',
		'body' => $displayName . ' staff login is now blocked.',
		'tenant_id' => $tenantId,
		'href' => 'operator.php?id=' . $tenantId,
		'dedupe_key' => 'suspend:' . $tenantId . ':' . date('Y-m-d-H'),
	]);
}

/**
 * Enforce Control host isolation when enabled.
 * CONTROL_ISOLATE=1 + CONTROL_PUBLIC_HOST=console.tessera.co.tz
 * blocks Control on the operator hostname (tessera.co.tz).
 */
function control_enforce_host_isolation(): void {
	$isolate = getenv('CONTROL_ISOLATE');
	if ($isolate === false || $isolate === '' || $isolate === '0') {
		return;
	}
	$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
	$host = preg_replace('/:\\d+$/', '', $host);
	$allowedRaw = (string) (getenv('CONTROL_ALLOWED_HOSTS') ?: getenv('CONTROL_PUBLIC_HOST') ?: 'console.tessera.co.tz,localhost,127.0.0.1');
	$allowed = array_filter(array_map('trim', explode(',', strtolower($allowedRaw))));
	/* Always allow local XAMPP */
	foreach (['localhost', '127.0.0.1'] as $local) {
		if (!in_array($local, $allowed, true)) {
			$allowed[] = $local;
		}
	}
	if (in_array($host, $allowed, true)) {
		return;
	}
	$console = getenv('CONTROL_PUBLIC_HOST') ?: 'console.tessera.co.tz';
	http_response_code(403);
	header('Content-Type: text/plain; charset=utf-8');
	echo "Tessera Control is not available on this host.\n";
	echo "Use https://{$console}/control/\n";
	exit;
}

function control_config_extended(): array {
	$base = control_config();
	$base['control_public_host'] = (string) (getenv('CONTROL_PUBLIC_HOST') ?: 'console.tessera.co.tz');
	$base['control_isolate'] = (string) (getenv('CONTROL_ISOLATE') ?: '0');
	$base['alert_email'] = (string) (getenv('PLATFORM_ALERT_EMAIL') ?: '');
	return $base;
}
