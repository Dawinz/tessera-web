<?php
require_once __DIR__ . '/includes/auth_guard.php';

$period = (string) ($_GET['period'] ?? '7d');
$metrics = control_platform_metrics($db ?? null, !empty($control_db_ok), $period);
$chartSeries = control_platform_chart_series($db ?? null, !empty($control_db_ok), $period);

$tenantsPack = control_list_tenants($db ?? null, !empty($control_db_ok));
$casesPack = control_list_cases($db ?? null, !empty($control_db_ok));
$tenants = $tenantsPack['rows'];
$cases = $casesPack['rows'];
$audit = control_list_audit($db ?? null, !empty($control_db_ok), 5);

$active = 0;
$provisioning = 0;
$suspended = 0;
$otherStatus = 0;
foreach ($tenants as $t) {
	if ($t['status'] === 'active') {
		$active++;
	} elseif ($t['status'] === 'provisioning') {
		$provisioning++;
	} elseif ($t['status'] === 'suspended') {
		$suspended++;
	} else {
		$otherStatus++;
	}
}

$openStages = ['kickoff', 'provisioning', 'migration', 'uat', 'training', 'golive', 'hypercare'];
$onboardingOpen = 0;
foreach ($cases as $c) {
	if (in_array($c['stage'], $openStages, true)) {
		$onboardingOpen++;
	}
}

$attention = [];
foreach ($tenants as $t) {
	if ($t['status'] === 'provisioning') {
		$attention[] = ['tone' => 'warning', 'icon' => 'alert-triangle', 'title' => $t['display_name'] . ' still provisioning', 'detail' => 'Slug ' . $t['slug'], 'href' => 'operator.php?id=' . (int) $t['id']];
	}
	if ($t['status'] === 'suspended') {
		$attention[] = ['tone' => 'danger', 'icon' => 'slash', 'title' => $t['display_name'] . ' suspended', 'detail' => 'Staff login blocked on all modules.', 'href' => 'operator.php?id=' . (int) $t['id']];
	}
}
if (!empty($control_db_ok) && isset($db) && function_exists('control_table_exists') && control_table_exists($db, 'tessera_invoices')) {
	$oq = @$db->query(
		"SELECT COUNT(DISTINCT tenant_id) AS c FROM tessera_invoices
		 WHERE status='sent' AND due_date IS NOT NULL AND due_date < CURDATE()"
	);
	if ($oq && ($or = $oq->fetch_assoc()) && (int) $or['c'] > 0) {
		$attention[] = [
			'tone' => 'danger',
			'icon' => 'file-text',
			'title' => (int) $or['c'] . ' operator(s) with overdue invoices',
			'detail' => 'Review on Billing.',
			'href' => 'billing.php',
		];
	}
}
foreach ($cases as $c) {
	if (in_array($c['stage'], $openStages, true) && !in_array($c['stage'], ['done', 'cancelled'], true)) {
		$attention[] = ['tone' => 'info', 'icon' => 'git-pull-request', 'title' => 'Onboarding: ' . $c['company_name'], 'detail' => 'Stage · ' . $c['stage'], 'href' => 'onboarding_case.php?id=' . (int) $c['id']];
	}
}
$attention = array_slice($attention, 0, 4);

$topTenants = control_top_tenants_by_bookings($db ?? null, !empty($control_db_ok), $tenants, $period, 5);
$onboardingPipeline = control_onboarding_pipeline_counts($cases);
$payLabel = $metrics['payment_success_pct'] === null ? '—' : $metrics['payment_success_pct'] . '%';

$operatorChartLabels = [];
$operatorChartValues = [];
if ($active > 0) {
	$operatorChartLabels[] = 'Active';
	$operatorChartValues[] = $active;
}
if ($provisioning > 0) {
	$operatorChartLabels[] = 'Provisioning';
	$operatorChartValues[] = $provisioning;
}
if ($suspended > 0) {
	$operatorChartLabels[] = 'Suspended';
	$operatorChartValues[] = $suspended;
}
if ($otherStatus > 0) {
	$operatorChartLabels[] = 'Other';
	$operatorChartValues[] = $otherStatus;
}

$chartPayload = [
	'volume' => [
		'labels' => $chartSeries['labels'],
		'series' => [
			['name' => 'Bookings', 'type' => 'column', 'data' => $chartSeries['tickets']],
			['name' => 'Parcels', 'type' => 'column', 'data' => $chartSeries['parcels']],
			['name' => 'Revenue', 'type' => 'line', 'data' => $chartSeries['revenue']],
		],
	],
	'operators' => [
		'labels' => $operatorChartLabels,
		'values' => $operatorChartValues,
	],
	'onboarding' => [
		'labels' => array_column($onboardingPipeline, 'label'),
		'values' => array_column($onboardingPipeline, 'count'),
	],
];

$__page_title = 'Overview';
$control_nav = 'overview';
$control_extra_head = '<script src="/tickets/assets/libs/apexcharts/apexcharts.min.js"></script>';
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="control-dashboard">
	<div class="row">
		<div class="col-12">
			<div class="page-title-box d-flex align-items-center justify-content-between">
				<div>
					<h4 class="mb-1">Platform overview</h4>
					<p class="text-muted mb-0 font-size-13"><?php echo control_h($metrics['period']['label']); ?> · <?php echo control_h($metrics['source']); ?> data</p>
				</div>
				<div class="d-flex flex-wrap gap-2 align-items-center">
					<div class="btn-group">
						<?php foreach (['today' => 'Today', '7d' => '7 days', '30d' => '30 days'] as $k => $lab): ?>
							<a href="overview.php?period=<?php echo $k; ?>" class="btn btn-sm btn-<?php echo $period === $k ? 'primary' : 'soft-secondary'; ?>"><?php echo $lab; ?></a>
						<?php endforeach; ?>
					</div>
					<a href="operator_new.php" class="btn btn-primary btn-sm"><i class="mdi mdi-account-plus me-1"></i> Add operator</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mb-3">
		<div class="col-xl-3 col-sm-6">
			<div class="card control-kpi-card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-start">
						<div>
							<div class="control-kpi-label">Tickets booked</div>
							<div class="control-kpi-value"><?php echo number_format($metrics['tickets']); ?></div>
							<div class="control-kpi-meta">Revenue <?php echo control_format_money($metrics['ticket_revenue']); ?></div>
						</div>
						<span class="control-kpi-icon control-kpi-icon--tickets"><i class="mdi mdi-ticket-outline"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6">
			<div class="card control-kpi-card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-start">
						<div>
							<div class="control-kpi-label">Parcels</div>
							<div class="control-kpi-value"><?php echo number_format($metrics['parcels']); ?></div>
							<div class="control-kpi-meta">Cargo volume</div>
						</div>
						<span class="control-kpi-icon control-kpi-icon--parcels"><i class="mdi mdi-package-variant"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6">
			<div class="card control-kpi-card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-start">
						<div>
							<div class="control-kpi-label">Payment success</div>
							<div class="control-kpi-value"><?php echo control_h($payLabel); ?></div>
							<div class="control-kpi-meta"><?php echo (int) $metrics['payment_paid']; ?>/<?php echo (int) $metrics['payment_total']; ?> mobile bookings</div>
						</div>
						<span class="control-kpi-icon control-kpi-icon--payments"><i class="mdi mdi-credit-card-outline"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6">
			<div class="card control-kpi-card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-start">
						<div>
							<div class="control-kpi-label">Operators</div>
							<div class="control-kpi-value"><?php echo count($tenants); ?></div>
							<div class="control-kpi-meta"><?php echo $active; ?> active · <?php echo $onboardingOpen; ?> onboarding</div>
						</div>
						<span class="control-kpi-icon control-kpi-icon--operators"><i class="mdi mdi-account-group-outline"></i></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mb-3">
		<div class="col-xl-8">
			<div class="card control-chart-card h-100">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="control-panel-title mb-0">Platform volume</h5>
					<span class="text-muted font-size-12">Bookings, parcels &amp; revenue</span>
				</div>
				<div class="card-body">
					<div id="control-chart-volume" class="control-chart-host"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="card control-chart-card h-100">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="control-panel-title mb-0">Operator fleet</h5>
					<a href="operators.php" class="btn btn-sm btn-soft-primary">All operators</a>
				</div>
				<div class="card-body">
					<div id="control-chart-operators" class="control-chart-host control-chart-host--donut"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3">
		<div class="col-xl-3 col-md-6">
			<div class="card control-chart-card mb-0 h-100">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="control-panel-title mb-0">Needs attention</h5>
					<span class="badge badge-soft-<?php echo count($attention) ? 'warning' : 'success'; ?>"><?php echo count($attention); ?></span>
				</div>
				<div class="card-body pt-2">
					<?php if (!$attention): ?>
						<div class="control-empty"><i class="mdi mdi-check-circle-outline me-1"></i> All clear — no urgent items.</div>
					<?php else: ?>
						<ul class="control-mini-list">
							<?php foreach ($attention as $item): ?>
								<li>
									<?php
									$mdiMap = [
										'alert-triangle' => 'mdi-alert-circle-outline',
										'slash' => 'mdi-cancel',
										'file-text' => 'mdi-file-document-outline',
										'git-pull-request' => 'mdi-source-branch',
									];
									$mdi = $mdiMap[$item['icon']] ?? 'mdi-information-outline';
									$toneClass = $item['tone'] === 'danger' ? 'danger' : ($item['tone'] === 'warning' ? 'warning' : 'info');
									?>
									<span class="control-stat-icon is-<?php echo $toneClass; ?>" style="width:32px;height:32px;flex-shrink:0">
										<i class="mdi <?php echo control_h($mdi); ?> font-size-16"></i>
									</span>
									<div class="flex-grow-1 min-w-0">
										<a href="<?php echo control_h($item['href']); ?>"><?php echo control_h($item['title']); ?></a>
										<div class="text-muted font-size-12 text-truncate"><?php echo control_h($item['detail']); ?></div>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6">
			<div class="card control-chart-card mb-0 h-100">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="control-panel-title mb-0">Onboarding pipeline</h5>
					<a href="onboarding.php" class="btn btn-sm btn-soft-secondary"><?php echo $onboardingOpen; ?> open</a>
				</div>
				<div class="card-body">
					<div id="control-chart-onboarding" class="control-chart-host control-chart-host--donut"></div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6">
			<div class="card control-chart-card mb-0 h-100">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="control-panel-title mb-0">Top operators</h5>
					<a href="operators.php" class="btn btn-sm btn-soft-primary">View all</a>
				</div>
				<div class="card-body pt-2">
					<?php if (!$topTenants): ?>
						<div class="control-empty">No booking volume in this period.</div>
					<?php else: ?>
						<ul class="control-mini-list">
							<?php foreach ($topTenants as $row): ?>
								<li>
									<div class="flex-grow-1 min-w-0">
										<a href="operator.php?id=<?php echo (int) $row['id']; ?>"><?php echo control_h($row['name']); ?></a>
										<div class="text-muted font-size-12"><code><?php echo control_h($row['slug']); ?></code></div>
									</div>
									<div class="text-end">
										<div class="fw-semibold"><?php echo number_format($row['bookings']); ?></div>
										<div class="text-muted font-size-11"><?php echo control_format_money($row['revenue']); ?></div>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6">
			<div class="card control-chart-card mb-0 h-100">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="control-panel-title mb-0">Recent audit</h5>
					<a href="audit.php" class="btn btn-sm btn-soft-primary">Full log</a>
				</div>
				<div class="card-body pt-2">
					<?php if (!$audit): ?>
						<div class="control-empty">No audited actions yet.</div>
					<?php else: ?>
						<ul class="control-mini-list">
							<?php foreach ($audit as $row): ?>
								<li>
									<div class="flex-grow-1 min-w-0">
										<div class="fw-medium text-truncate"><?php echo control_h($row['action']); ?></div>
										<div class="text-muted font-size-12"><?php echo control_h($row['actor']); ?> · <?php echo control_h($row['created_at']); ?></div>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="application/json" id="control-overview-data"><?php echo json_encode($chartPayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
<script src="/control/assets/js/control-overview.js?v=20260906"></script>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
