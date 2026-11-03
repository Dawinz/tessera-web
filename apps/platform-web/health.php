<?php
require_once __DIR__ . '/includes/auth_guard.php';

$error = null;
$canOps = control_can('manage_ops');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canOps) {
	if (($_POST['action'] ?? '') === 'set_deploy') {
		$status = (string) ($_POST['deploy_status'] ?? 'unknown');
		$allowed = ['unknown', 'healthy', 'degraded', 'deploying', 'failed'];
		if (!in_array($status, $allowed, true)) {
			$status = 'unknown';
		}
		if (control_ops_set($db ?? null, !empty($control_db_ok), 'deploy_status', $status, $control_user['email'])) {
			$note = trim((string) ($_POST['deploy_note'] ?? ''));
			control_ops_set($db ?? null, !empty($control_db_ok), 'deploy_note', $note, $control_user['email']);
			header('Location: health.php?deploy=1');
			exit;
		}
		$error = 'Could not save deploy status.';
	}
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$error = 'Only owners can update deploy status.';
}

$tenantsPack = control_list_tenants($db ?? null, !empty($control_db_ok));
$casesPack = control_list_cases($db ?? null, !empty($control_db_ok));
$tenants = $tenantsPack['rows'];
$cases = $casesPack['rows'];

$active = count(array_filter($tenants, static fn($t) => $t['status'] === 'active'));
$suspended = count(array_filter($tenants, static fn($t) => $t['status'] === 'suspended'));
$provisioning = count(array_filter($tenants, static fn($t) => $t['status'] === 'provisioning'));
$openStages = ['kickoff','provisioning','migration','uat','training','golive','hypercare'];
$onboardingOpen = count(array_filter($cases, static fn($c) => in_array($c['stage'], $openStages, true)));

$adminsOk = !empty($control_db_ok) && isset($db) && control_table_exists($db, 'tessera_platform_admins');
$paymentsOk = !empty($control_db_ok) && isset($db) && control_table_exists($db, 'tessera_tenant_payments');
$adminCount = 0;
$payOkCount = 0;
if ($adminsOk) {
	$aq = @$db->query("SELECT COUNT(*) AS c FROM tessera_platform_admins WHERE status='active'");
	if ($aq && ($ar = $aq->fetch_assoc())) {
		$adminCount = (int) $ar['c'];
	}
}
if ($paymentsOk) {
	$pq = @$db->query("SELECT COUNT(*) AS c FROM tessera_tenant_payments WHERE credentials_status='ok'");
	if ($pq && ($pr = $pq->fetch_assoc())) {
		$payOkCount = (int) $pr['c'];
	}
}

$publicBase = rtrim((string) (getenv('TESSERA_PUBLIC_URL') ?: 'https://tessera.co.tz'), '/');
$localTickets = 'http://localhost/tickets/';
$probes = [
	control_http_probe($publicBase . '/'),
	control_http_probe($publicBase . '/tickets/'),
	control_http_probe($localTickets),
];

$deployStatus = control_ops_get($db ?? null, !empty($control_db_ok), 'deploy_status', 'unknown');
$deployNote = control_ops_get($db ?? null, !empty($control_db_ok), 'deploy_note', '');
$webhookFails = control_webhook_failures($db ?? null, !empty($control_db_ok));
$backupPack = control_list_backups(5);
$latestBackup = $backupPack['rows'][0]['mtime'] ?? null;

$deployTone = [
	'healthy' => 'success',
	'degraded' => 'warning',
	'deploying' => 'info',
	'failed' => 'danger',
	'unknown' => 'secondary',
][$deployStatus] ?? 'secondary';

$checks = [
	['name' => 'Platform database', 'detail' => !empty($control_db_ok) ? 'Connected to ticket DB' : 'Database unavailable', 'status' => !empty($control_db_ok) ? 'Connected' : 'Unavailable', 'tone' => !empty($control_db_ok) ? 'success' : 'danger'],
	['name' => 'Tenant registry', 'detail' => count($tenants) . ' operators · ' . $active . ' active', 'status' => $suspended > 0 ? 'Has suspensions' : 'Healthy', 'tone' => $suspended > 0 ? 'warning' : 'success'],
	['name' => 'Onboarding load', 'detail' => $onboardingOpen . ' open cases', 'status' => $onboardingOpen > 0 ? 'In progress' : 'Clear', 'tone' => $onboardingOpen > 5 ? 'warning' : 'info'],
	['name' => 'Platform admins', 'detail' => $adminsOk ? ($adminCount . ' active admins') : 'Run migration 003', 'status' => $adminsOk ? 'Ready' : 'Missing table', 'tone' => $adminsOk ? 'success' : 'danger'],
	['name' => 'Payment vault', 'detail' => $paymentsOk ? ($payOkCount . ' tenant(s) OK') : 'Run migration 003', 'status' => $paymentsOk ? 'Ready' : 'Missing table', 'tone' => $paymentsOk ? 'success' : 'danger'],
	['name' => 'Latest backup', 'detail' => $latestBackup ? ('Last @ ' . $latestBackup) : 'No backup files', 'status' => $latestBackup ? 'Present' : 'None', 'tone' => $latestBackup ? 'success' : 'warning'],
	['name' => 'Deploy posture', 'detail' => $deployNote !== '' ? $deployNote : 'Manual Railway status', 'status' => $deployStatus, 'tone' => $deployTone],
];

$overdueTenants = 0;
if (!empty($control_db_ok) && isset($db) && function_exists('control_table_exists') && control_table_exists($db, 'tessera_invoices')) {
	$oq = @$db->query(
		"SELECT COUNT(DISTINCT tenant_id) AS c FROM tessera_invoices
		 WHERE status='sent' AND due_date IS NOT NULL AND due_date < CURDATE()"
	);
	if ($oq && ($or = $oq->fetch_assoc())) {
		$overdueTenants = (int) $or['c'];
	}
}
$checks[] = [
	'name' => 'SaaS billing dunning',
	'detail' => $overdueTenants . ' operator(s) overdue',
	'status' => $overdueTenants > 0 ? 'Overdue' : 'Clear',
	'tone' => $overdueTenants > 0 ? 'danger' : 'success',
];

$healthTones = control_health_tone_counts($checks);
$chartPayload = [
	'health' => [
		'labels' => ['Healthy', 'Warning', 'Critical', 'Info'],
		'values' => [$healthTones['success'], $healthTones['warning'], $healthTones['danger'], $healthTones['info']],
	],
];
$probeOk = count(array_filter($probes, static fn($p) => !empty($p['ok'])));

$__page_title = 'Health | Tessera Control';
$control_nav = 'health';
$control_extra_head = control_chart_head_tags();
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="control-page">
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<div>
				<h4 class="mb-1">Platform health</h4>
				<p class="text-muted mb-0 font-size-13">Probes · registry · deploy posture</p>
			</div>
			<a class="btn btn-soft-secondary btn-sm" href="health.php">Re-probe</a>
		</div>
	</div>
</div>

<?php if (isset($_GET['deploy'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Deploy status saved.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?php echo control_h($error); ?></div><?php endif; ?>

<div class="row g-3 mb-3">
	<div class="col-md-3"><div class="card control-kpi-card h-100"><div class="card-body">
		<div class="control-kpi-label">HTTP probes</div>
		<div class="control-kpi-value"><?php echo $probeOk; ?>/<?php echo count($probes); ?></div>
		<div class="control-kpi-meta">Endpoints up</div>
	</div></div></div>
	<div class="col-md-3"><div class="card control-kpi-card h-100"><div class="card-body">
		<div class="control-kpi-label">Active operators</div>
		<div class="control-kpi-value"><?php echo $active; ?></div>
		<div class="control-kpi-meta"><?php echo $suspended; ?> suspended</div>
	</div></div></div>
	<div class="col-md-3"><div class="card control-kpi-card h-100"><div class="card-body">
		<div class="control-kpi-label">Checks passing</div>
		<div class="control-kpi-value text-success"><?php echo $healthTones['success']; ?></div>
		<div class="control-kpi-meta">Of <?php echo count($checks); ?> signals</div>
	</div></div></div>
	<div class="col-md-3"><div class="card control-kpi-card h-100"><div class="card-body">
		<div class="control-kpi-label">Webhook issues</div>
		<div class="control-kpi-value text-<?php echo count($webhookFails) ? 'danger' : 'body'; ?>"><?php echo count($webhookFails); ?></div>
		<div class="control-kpi-meta">Payment vault</div>
	</div></div></div>
</div>

<div class="row g-3">
	<div class="col-xl-4">
		<div class="card control-chart-card mb-3">
			<div class="card-header"><h5 class="control-panel-title mb-0">Check summary</h5></div>
			<div class="card-body"><div id="chart-health-summary" class="control-chart-host control-chart-host--donut"></div></div>
		</div>
		<div class="card control-chart-card">
			<div class="card-header"><h5 class="control-panel-title mb-0">Deploy status</h5></div>
			<div class="card-body">
				<?php if ($canOps): ?>
					<form method="post" class="vstack gap-2">
						<input type="hidden" name="action" value="set_deploy">
						<select name="deploy_status" class="form-select form-select-sm">
							<?php foreach (['unknown', 'healthy', 'degraded', 'deploying', 'failed'] as $s): ?>
								<option value="<?php echo $s; ?>" <?php echo $deployStatus === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
							<?php endforeach; ?>
						</select>
						<input type="text" name="deploy_note" class="form-control form-control-sm" value="<?php echo control_h($deployNote); ?>" placeholder="Deploy note">
						<button class="btn btn-primary btn-sm" type="submit">Save</button>
					</form>
				<?php else: ?>
					<span class="badge badge-soft-<?php echo control_h($deployTone); ?>"><?php echo control_h($deployStatus); ?></span>
					<p class="text-muted font-size-12 mt-2 mb-0"><?php echo control_h($deployNote); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="col-xl-8">
		<div class="card control-chart-card mb-3">
			<div class="card-header"><h5 class="control-panel-title mb-0">Live HTTP probes</h5></div>
			<div class="card-body py-2">
				<div class="control-check-grid">
					<?php foreach ($probes as $p): ?>
						<div class="control-check-tile">
							<div class="d-flex justify-content-between align-items-start gap-2">
								<div class="title text-break font-size-12"><?php echo control_h(parse_url($p['url'], PHP_URL_HOST) ?: $p['url']); ?></div>
								<span class="badge badge-soft-<?php echo $p['ok'] ? 'success' : 'danger'; ?>"><?php echo $p['ok'] ? (int) $p['status'] : 'Fail'; ?></span>
							</div>
							<div class="detail"><?php echo $p['ms']; ?> ms</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="card control-chart-card mb-3">
			<div class="card-header"><h5 class="control-panel-title mb-0">Registry & ops</h5></div>
			<div class="card-body py-2">
				<div class="control-check-grid">
					<?php foreach ($checks as $c): ?>
						<div class="control-check-tile">
							<div class="d-flex justify-content-between align-items-start gap-2">
								<div class="title"><?php echo control_h($c['name']); ?></div>
								<span class="badge badge-soft-<?php echo control_h($c['tone']); ?>"><?php echo control_h($c['status']); ?></span>
							</div>
							<div class="detail"><?php echo control_h($c['detail']); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php if ($webhookFails): ?>
		<div class="card control-table-panel">
			<div class="card-header bg-transparent border-bottom"><h5 class="control-panel-title mb-0">Webhook failures</h5></div>
			<ul class="list-group list-group-flush">
				<?php foreach (array_slice($webhookFails, 0, 5) as $w): ?>
					<li class="list-group-item py-2">
						<div class="fw-semibold font-size-13"><?php echo control_h($w['display_name'] ?? 'Tenant #' . $w['tenant_id']); ?></div>
						<div class="text-muted font-size-12"><?php echo control_h($w['webhook_last_status']); ?> · <?php echo control_h($w['webhook_last_at'] ?: '—'); ?></div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</div>
</div>

<script type="application/json" id="control-page-charts"><?php echo json_encode($chartPayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
<script src="/control/assets/js/control-charts.js?v=20260906b"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  ControlCharts.bootFromPayload('control-page-charts', {
    'chart-health-summary': { type: 'donut', key: 'health', opts: { centerLabel: 'Checks', colors: ['#34c38f', '#f1b44c', '#f46a6a', '#50a5f1'] } }
  });
});
</script>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
