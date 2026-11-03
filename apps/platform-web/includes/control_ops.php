<?php
/**
 * Tessera Control Wave D — billing, backups, health probes, support.
 */

function control_plan_catalog(): array {
	return [
		'standard' => [
			'label' => 'Standard',
			'tickets_mo' => 5000,
			'seats' => 10,
			'amount_tzs' => 150000,
			'modules' => 'Tickets only',
		],
		'flagship' => [
			'label' => 'Flagship',
			'tickets_mo' => 50000,
			'seats' => 50,
			'amount_tzs' => 450000,
			'modules' => 'Tickets · Cargo · Accounts',
		],
		'enterprise' => [
			'label' => 'Enterprise',
			'tickets_mo' => 0, /* unlimited */
			'seats' => 0,
			'amount_tzs' => 0, /* custom */
			'modules' => 'Full suite + custom',
		],
	];
}

function control_tenant_usage_month(?mysqli $db, bool $db_ok, ?int $tenantId = null): array {
	$from = date('Y-m-01');
	$to = date('Y-m-d');
	$tickets = 0;
	$revenue = 0.0;
	$seats = 0;
	if ($db_ok && $db) {
		$tenantFilter = '';
		if ($tenantId !== null && $tenantId > 0 && control_column_exists($db, 'booking', 'tenant_id')) {
			$tenantFilter = ' AND tenant_id=' . (int) $tenantId;
		}
		$q = @$db->query(
			"SELECT COUNT(*) AS c, COALESCE(SUM(CAST(cost AS DECIMAL(14,2))),0) AS rev
			 FROM booking WHERE book_date >= '{$from}' AND book_date <= '{$to}'
			   AND (delete_book='' OR delete_book='0' OR delete_book IS NULL){$tenantFilter}"
		);
		if ($q && ($r = $q->fetch_assoc())) {
			$tickets = (int) $r['c'];
			$revenue = (float) $r['rev'];
		}
		$sq = @$db->query("SELECT COUNT(*) AS c FROM users WHERE status='active' OR status='Active' OR status=1");
		if ($sq && ($sr = $sq->fetch_assoc())) {
			$seats = (int) $sr['c'];
		}
	}
	return [
		'from' => $from,
		'to' => $to,
		'tickets' => $tickets,
		'revenue' => $revenue,
		'seats' => $seats,
		'scoped' => ($tenantId !== null && $tenantId > 0),
	];
}

function control_list_plan_changes(?mysqli $db, bool $db_ok, ?int $tenantId = null, int $limit = 50): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_plan_changes')) {
		return [];
	}
	$limit = max(1, min(200, $limit));
	$where = '1=1';
	if ($tenantId !== null) {
		$where = 'tenant_id=' . (int) $tenantId;
	}
	$rows = [];
	$q = @$db->query(
		"SELECT pc.*, t.display_name
		 FROM tessera_plan_changes pc
		 LEFT JOIN tessera_tenants t ON t.id = pc.tenant_id
		 WHERE {$where}
		 ORDER BY pc.id DESC LIMIT {$limit}"
	);
	if ($q) {
		while ($row = $q->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	return $rows;
}

function control_change_tenant_plan(?mysqli $db, bool $db_ok, int $tenantId, string $toPlan, string $effectiveDate, string $actor, string $notes = ''): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenants')) {
		return false;
	}
	$catalog = control_plan_catalog();
	if (!isset($catalog[$toPlan])) {
		return false;
	}
	if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $effectiveDate)) {
		$effectiveDate = date('Y-m-d');
	}
	$tenant = control_get_tenant($db, $db_ok, $tenantId);
	if (!$tenant) {
		return false;
	}
	$from = (string) ($tenant['plan'] ?? '');
	$defs = control_plan_module_defaults($toPlan);
	$okMods = control_update_tenant_modules($db, $db_ok, $tenantId, [
		'plan' => $toPlan,
		'module_tickets' => $defs['tickets'],
		'module_cargo' => $defs['cargo'],
		'module_accounts' => $defs['accounts'],
	], $actor);
	if (!$okMods) {
		return false;
	}
	if (control_table_exists($db, 'tessera_plan_changes')) {
		$from_e = $db->real_escape_string($from);
		$to_e = $db->real_escape_string($toPlan);
		$eff_e = $db->real_escape_string($effectiveDate);
		$actor_e = $db->real_escape_string($actor);
		$notes_e = $db->real_escape_string($notes);
		@$db->query(
			"INSERT INTO tessera_plan_changes (tenant_id, from_plan, to_plan, effective_date, notes, actor)
			 VALUES ({$tenantId}, '{$from_e}', '{$to_e}', '{$eff_e}', NULLIF('{$notes_e}',''), '{$actor_e}')"
		);
	}
	control_audit($db, $db_ok, $actor, 'billing.plan_change', $tenantId, [
		'from' => $from,
		'to' => $toPlan,
		'effective_date' => $effectiveDate,
	]);
	return true;
}

function control_list_invoices(?mysqli $db, bool $db_ok, ?int $tenantId = null, int $limit = 50): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_invoices')) {
		return [];
	}
	$limit = max(1, min(200, $limit));
	$where = $tenantId !== null ? 'i.tenant_id=' . (int) $tenantId : '1=1';
	$rows = [];
	$q = @$db->query(
		"SELECT i.*, t.display_name FROM tessera_invoices i
		 LEFT JOIN tessera_tenants t ON t.id = i.tenant_id
		 WHERE {$where} ORDER BY i.id DESC LIMIT {$limit}"
	);
	if ($q) {
		while ($row = $q->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	return $rows;
}

function control_create_invoice_stub(?mysqli $db, bool $db_ok, int $tenantId, array $input, string $actor): ?array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_invoices')) {
		return null;
	}
	$tenant = control_get_tenant($db, $db_ok, $tenantId);
	if (!$tenant) {
		return null;
	}
	$plan = (string) ($tenant['plan'] ?? 'flagship');
	$catalog = control_plan_catalog();
	$defaultAmount = (int) ($catalog[$plan]['amount_tzs'] ?? 0);
	$period = trim((string) ($input['period_label'] ?? date('Y-m')));
	$amount = isset($input['amount_tzs']) && $input['amount_tzs'] !== '' ? (int) $input['amount_tzs'] : $defaultAmount;
	$status = (string) ($input['status'] ?? 'draft');
	if (!in_array($status, ['draft', 'sent', 'paid', 'void'], true)) {
		$status = 'draft';
	}
	$due = (string) ($input['due_date'] ?? date('Y-m-d', strtotime('+14 days')));
	$period_e = $db->real_escape_string($period);
	$status_e = $db->real_escape_string($status);
	$due_e = $db->real_escape_string($due);
	$notes_e = $db->real_escape_string((string) ($input['notes'] ?? ''));
	$paidSql = ($status === 'paid') ? 'NOW()' : 'NULL';
	$slug = $db->real_escape_string((string) ($tenant['slug'] ?? 'operator'));
	$link = 'https://console.tessera.co.tz/control/billing.php?pay_stub=' . rawurlencode($slug) . '&period=' . rawurlencode($period);
	$link_e = $db->real_escape_string($link);
	$hasLink = control_column_exists($db, 'tessera_invoices', 'payment_link');
	$hasPaid = control_column_exists($db, 'tessera_invoices', 'paid_at');
	if ($hasLink && $hasPaid) {
		$ok = @$db->query(
			"INSERT INTO tessera_invoices (tenant_id, period_label, amount_tzs, status, due_date, notes, payment_link, provider, paid_at)
			 VALUES ({$tenantId}, '{$period_e}', {$amount}, '{$status_e}', '{$due_e}', NULLIF('{$notes_e}',''), '{$link_e}', 'manual', {$paidSql})"
		);
	} elseif ($hasLink) {
		$ok = @$db->query(
			"INSERT INTO tessera_invoices (tenant_id, period_label, amount_tzs, status, due_date, notes, payment_link, provider)
			 VALUES ({$tenantId}, '{$period_e}', {$amount}, '{$status_e}', '{$due_e}', NULLIF('{$notes_e}',''), '{$link_e}', 'manual')"
		);
	} else {
		$ok = @$db->query(
			"INSERT INTO tessera_invoices (tenant_id, period_label, amount_tzs, status, due_date, notes)
			 VALUES ({$tenantId}, '{$period_e}', {$amount}, '{$status_e}', '{$due_e}', NULLIF('{$notes_e}',''))"
		);
	}
	if (!$ok) {
		return null;
	}
	$id = (int) $db->insert_id;
	control_audit($db, $db_ok, $actor, 'billing.invoice_create', $tenantId, [
		'invoice_id' => $id,
		'period' => $period,
		'amount_tzs' => $amount,
		'status' => $status,
	]);
	$rows = control_list_invoices($db, $db_ok, $tenantId, 1);
	return $rows[0] ?? ['id' => $id];
}

/** Wave J2 — mark invoice sent / paid / void. */
function control_update_invoice_status(?mysqli $db, bool $db_ok, int $invoiceId, string $status, string $actor): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_invoices')) {
		return false;
	}
	if (!in_array($status, ['draft', 'sent', 'paid', 'void'], true)) {
		return false;
	}
	$inv = null;
	$q = @$db->query('SELECT * FROM tessera_invoices WHERE id=' . (int) $invoiceId . ' LIMIT 1');
	if ($q) {
		$inv = $q->fetch_assoc();
	}
	if (!$inv) {
		return false;
	}
	$status_e = $db->real_escape_string($status);
	$paidSql = '';
	if (control_column_exists($db, 'tessera_invoices', 'paid_at')) {
		$paidSql = ($status === 'paid') ? ', paid_at=COALESCE(paid_at, NOW())' : (($status === 'void' || $status === 'draft') ? ', paid_at=NULL' : '');
	}
	$ok = @$db->query("UPDATE tessera_invoices SET status='{$status_e}'{$paidSql} WHERE id=" . (int) $invoiceId . ' LIMIT 1');
	if (!$ok) {
		return false;
	}
	control_audit($db, $db_ok, $actor, 'billing.invoice_status', (int) $inv['tenant_id'], [
		'invoice_id' => $invoiceId,
		'from' => $inv['status'],
		'to' => $status,
	]);
	if ($status === 'paid' && function_exists('control_dunning_clear_if_paid')) {
		control_dunning_clear_if_paid($db, $db_ok, (int) $inv['tenant_id'], $actor);
	}
	return true;
}

function control_column_exists(mysqli $db, string $table, string $column): bool {
	static $cache = [];
	$key = $table . '.' . $column;
	if (array_key_exists($key, $cache)) {
		return $cache[$key];
	}
	$t = $db->real_escape_string($table);
	$c = $db->real_escape_string($column);
	$q = @$db->query("SHOW COLUMNS FROM `{$t}` LIKE '{$c}'");
	$cache[$key] = ($q && $q->num_rows > 0);
	return $cache[$key];
}

function control_backup_dir(): string {
	$env = getenv('BACKUP_DIR');
	if ($env !== false && $env !== '') {
		return rtrim($env, '/');
	}
	return dirname(__DIR__, 3) . '/database/backups';
}

function control_list_backups(int $limit = 40): array {
	$dir = control_backup_dir();
	if (!is_dir($dir)) {
		return ['dir' => $dir, 'rows' => [], 'exists' => false];
	}
	$files = array_merge(
		glob($dir . '/*.sql') ?: [],
		glob($dir . '/*.sql.gz') ?: [],
		glob($dir . '/*.gz') ?: []
	);
	$files = array_values(array_unique($files));
	usort($files, static function ($a, $b) {
		return filemtime($b) <=> filemtime($a);
	});
	$rows = [];
	foreach (array_slice($files, 0, $limit) as $path) {
		$rows[] = [
			'name' => basename($path),
			'path' => $path,
			'size' => filesize($path) ?: 0,
			'mtime' => date('Y-m-d H:i:s', filemtime($path) ?: time()),
		];
	}
	return ['dir' => $dir, 'rows' => $rows, 'exists' => true];
}

/**
 * Trigger local backup script. Owner-only; audited by caller.
 *
 * @return array{ok:bool,output?:string,error?:string}
 */
function control_run_backup(): array {
	$root = dirname(__DIR__, 3);
	$script = $root . '/scripts/backup-mysql.sh';
	if (!is_file($script)) {
		return ['ok' => false, 'error' => 'script_missing'];
	}
	$mysqldump = '/Applications/XAMPP/xamppfiles/bin/mysqldump';
	$pathPrefix = '';
	if (is_file($mysqldump)) {
		$pathPrefix = 'PATH="/Applications/XAMPP/xamppfiles/bin:$PATH" ';
	}
	$cmd = $pathPrefix . 'bash ' . escapeshellarg($script) . ' 2>&1';
	$output = [];
	$code = 0;
	exec($cmd, $output, $code);
	$text = implode("\n", $output);
	if ($code !== 0) {
		return ['ok' => false, 'error' => 'exit_' . $code, 'output' => $text];
	}
	return ['ok' => true, 'output' => $text];
}

function control_http_probe(string $url, int $timeout = 8): array {
	$ch = curl_init($url);
	if (!$ch) {
		return ['ok' => false, 'url' => $url, 'status' => 0, 'ms' => 0, 'error' => 'curl_init'];
	}
	curl_setopt_array($ch, [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_NOBODY => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_TIMEOUT => $timeout,
		CURLOPT_CONNECTTIMEOUT => 4,
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_USERAGENT => 'TesseraControlHealth/1.0',
	]);
	$start = microtime(true);
	curl_exec($ch);
	$ms = (int) round((microtime(true) - $start) * 1000);
	$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$err = curl_error($ch);
	curl_close($ch);
	$ok = $err === '' && $code >= 200 && $code < 400;
	return [
		'ok' => $ok,
		'url' => $url,
		'status' => $code,
		'ms' => $ms,
		'error' => $err !== '' ? $err : null,
	];
}

function control_ops_get(?mysqli $db, bool $db_ok, string $key, string $default = ''): string {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_ops_meta')) {
		return $default;
	}
	$key_e = $db->real_escape_string($key);
	$q = @$db->query("SELECT meta_value FROM tessera_ops_meta WHERE meta_key='{$key_e}' LIMIT 1");
	if ($q && ($r = $q->fetch_assoc())) {
		return (string) ($r['meta_value'] ?? $default);
	}
	return $default;
}

function control_ops_set(?mysqli $db, bool $db_ok, string $key, string $value, string $actor): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_ops_meta')) {
		return false;
	}
	$key_e = $db->real_escape_string($key);
	$val_e = $db->real_escape_string($value);
	$actor_e = $db->real_escape_string($actor);
	$ok = @$db->query(
		"INSERT INTO tessera_ops_meta (meta_key, meta_value, updated_by)
		 VALUES ('{$key_e}', '{$val_e}', '{$actor_e}')
		 ON DUPLICATE KEY UPDATE meta_value='{$val_e}', updated_by='{$actor_e}'"
	);
	if ($ok) {
		control_audit($db, $db_ok, $actor, 'ops.meta', null, ['key' => $key, 'value' => $value]);
	}
	return (bool) $ok;
}

function control_webhook_failures(?mysqli $db, bool $db_ok): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_tenant_payments')) {
		return [];
	}
	$rows = [];
	$q = @$db->query(
		"SELECT p.*, t.display_name FROM tessera_tenant_payments p
		 LEFT JOIN tessera_tenants t ON t.id = p.tenant_id
		 WHERE p.webhook_last_status IS NOT NULL
		   AND LOWER(p.webhook_last_status) NOT IN ('ok','success','paid','completed')
		 ORDER BY p.webhook_last_at DESC LIMIT 20"
	);
	if ($q) {
		while ($row = $q->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	return $rows;
}

function control_sla_deadlines(string $priority): array {
	$priority = strtolower($priority);
	$map = [
		'urgent' => [1, 8],
		'high' => [4, 24],
		'normal' => [8, 72],
		'low' => [24, 120],
	];
	[$frH, $resH] = $map[$priority] ?? $map['normal'];
	return [
		'first' => date('Y-m-d H:i:s', time() + $frH * 3600),
		'resolve' => date('Y-m-d H:i:s', time() + $resH * 3600),
	];
}

function control_list_support_tickets(?mysqli $db, bool $db_ok, array $filters = []): array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_support_tickets')) {
		return [];
	}
	$limit = max(1, min(200, (int) ($filters['limit'] ?? 100)));
	$where = ['1=1'];
	if (!empty($filters['status'])) {
		$s = $db->real_escape_string((string) $filters['status']);
		$where[] = "st.status='{$s}'";
	}
	if (isset($filters['tenant_id']) && $filters['tenant_id'] !== '' && $filters['tenant_id'] !== null) {
		$where[] = 'st.tenant_id=' . (int) $filters['tenant_id'];
	}
	$sql = 'SELECT st.*, t.display_name FROM tessera_support_tickets st
		LEFT JOIN tessera_tenants t ON t.id = st.tenant_id
		WHERE ' . implode(' AND ', $where) . " ORDER BY st.id DESC LIMIT {$limit}";
	$rows = [];
	$q = @$db->query($sql);
	if ($q) {
		while ($row = $q->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	return $rows;
}

function control_create_support_ticket(?mysqli $db, bool $db_ok, array $input, string $actor): ?array {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_support_tickets')) {
		return null;
	}
	$subject = trim((string) ($input['subject'] ?? ''));
	if ($subject === '') {
		return null;
	}
	$priority = (string) ($input['priority'] ?? 'normal');
	if (!in_array($priority, ['low', 'normal', 'high', 'urgent'], true)) {
		$priority = 'normal';
	}
	$sla = control_sla_deadlines($priority);
	$tenantId = isset($input['tenant_id']) && $input['tenant_id'] !== '' ? (int) $input['tenant_id'] : null;
	$tidSql = $tenantId !== null ? (string) $tenantId : 'NULL';
	$subject_e = $db->real_escape_string($subject);
	$body_e = $db->real_escape_string((string) ($input['body'] ?? ''));
	$priority_e = $db->real_escape_string($priority);
	$req_e = $db->real_escape_string((string) ($input['requester_email'] ?? ''));
	$assignee_e = $db->real_escape_string((string) ($input['assignee'] ?? $actor));
	$fr_e = $db->real_escape_string($sla['first']);
	$res_e = $db->real_escape_string($sla['resolve']);
	$ok = @$db->query(
		"INSERT INTO tessera_support_tickets
			(tenant_id, subject, body, status, priority, requester_email, assignee, sla_first_response_due, sla_resolve_due)
		 VALUES ({$tidSql}, '{$subject_e}', NULLIF('{$body_e}',''), 'open', '{$priority_e}', NULLIF('{$req_e}',''), NULLIF('{$assignee_e}',''), '{$fr_e}', '{$res_e}')"
	);
	if (!$ok) {
		return null;
	}
	$id = (int) $db->insert_id;
	control_audit($db, $db_ok, $actor, 'support.ticket_create', $tenantId, ['ticket_id' => $id, 'priority' => $priority]);
	$all = control_list_support_tickets($db, $db_ok, ['limit' => 1]);
	return $all[0] ?? ['id' => $id];
}

function control_update_support_ticket(?mysqli $db, bool $db_ok, int $id, array $patch, string $actor): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_support_tickets')) {
		return false;
	}
	$id = (int) $id;
	$sets = [];
	if (isset($patch['status']) && in_array($patch['status'], ['open', 'pending', 'resolved', 'closed'], true)) {
		$status_e = $db->real_escape_string($patch['status']);
		$sets[] = "status='{$status_e}'";
		if (in_array($patch['status'], ['resolved', 'closed'], true)) {
			$sets[] = 'resolved_at=COALESCE(resolved_at, NOW())';
		}
	}
	if (isset($patch['assignee'])) {
		$a = $db->real_escape_string((string) $patch['assignee']);
		$sets[] = "assignee=NULLIF('{$a}','')";
	}
	if (!empty($patch['mark_responded'])) {
		$sets[] = 'first_responded_at=COALESCE(first_responded_at, NOW())';
		$sets[] = "status=IF(status='open','pending',status)";
	}
	if (!$sets) {
		return false;
	}
	$ok = @$db->query('UPDATE tessera_support_tickets SET ' . implode(', ', $sets) . " WHERE id={$id} LIMIT 1");
	if ($ok) {
		control_audit($db, $db_ok, $actor, 'support.ticket_update', null, ['ticket_id' => $id, 'patch' => $patch]);
	}
	return (bool) $ok;
}

function control_support_sla_state(array $ticket): array {
	$now = time();
	$frDue = !empty($ticket['sla_first_response_due']) ? strtotime($ticket['sla_first_response_due']) : null;
	$resDue = !empty($ticket['sla_resolve_due']) ? strtotime($ticket['sla_resolve_due']) : null;
	$responded = !empty($ticket['first_responded_at']);
	$resolved = in_array($ticket['status'] ?? '', ['resolved', 'closed'], true);
	$frBreached = !$responded && $frDue && $now > $frDue;
	$resBreached = !$resolved && $resDue && $now > $resDue;
	return [
		'first_breached' => $frBreached,
		'resolve_breached' => $resBreached,
		'tone' => ($frBreached || $resBreached) ? 'danger' : (($ticket['status'] ?? '') === 'open' ? 'warning' : 'success'),
	];
}

function control_set_hypercare_rota(?mysqli $db, bool $db_ok, int $caseId, string $rota, string $actor): bool {
	if (!$db_ok || !$db || !control_table_exists($db, 'tessera_onboarding_cases')) {
		return false;
	}
	/* Column may be missing if migration 005 not applied */
	$caseId = (int) $caseId;
	$rota_e = $db->real_escape_string($rota);
	$ok = @$db->query("UPDATE tessera_onboarding_cases SET hypercare_rota=NULLIF('{$rota_e}','') WHERE id={$caseId} LIMIT 1");
	if ($ok) {
		control_audit($db, $db_ok, $actor, 'onboarding.hypercare', null, ['case_id' => $caseId, 'rota' => $rota]);
	}
	return (bool) $ok;
}

function control_format_bytes(int $bytes): string {
	if ($bytes < 1024) {
		return $bytes . ' B';
	}
	if ($bytes < 1048576) {
		return round($bytes / 1024, 1) . ' KB';
	}
	return round($bytes / 1048576, 1) . ' MB';
}
