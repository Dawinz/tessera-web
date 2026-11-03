<?php
require_once __DIR__ . '/includes/auth_guard.php';

$pack = control_list_cases($db ?? null, !empty($control_db_ok));
$cases = $pack['rows'];
$labels = control_stage_labels();
$open = [];
$closed = [];
foreach ($cases as $c) {
	if (in_array($c['stage'], ['done', 'cancelled'], true)) {
		$closed[] = $c;
	} else {
		$open[] = $c;
	}
}

$onboardingPipeline = control_onboarding_pipeline_counts($cases);
$chartPayload = [
	'pipeline' => [
		'labels' => array_column($onboardingPipeline, 'label'),
		'values' => array_column($onboardingPipeline, 'count'),
	],
];

$pageOpen = control_page_int('page', 1);
$pagOpen = control_paginate($open, $pageOpen, 8);
$openPage = $pagOpen['items'];

$showClosed = !empty($_GET['closed']);
$closedVisible = $showClosed ? array_slice($closed, 0, 8) : [];

$__page_title = 'Onboarding | Tessera Control';
$control_nav = 'onboarding';
$control_extra_head = control_chart_head_tags();
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="control-page">
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<div>
				<h4 class="mb-1">Onboarding</h4>
				<p class="text-muted mb-0 font-size-13">Lead → go-live pipeline · <?php echo $pack['source'] === 'mysql' ? 'live cases' : 'demo cases'; ?></p>
			</div>
			<a href="onboarding_new.php" class="btn btn-primary btn-sm"><i class="mdi mdi-plus me-1"></i> New case</a>
		</div>
	</div>
</div>

<div class="row g-3 mb-3">
	<div class="col-md-4">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Open</div>
			<div class="control-kpi-value"><?php echo count($open); ?></div>
			<div class="control-kpi-meta">Active pipeline</div>
		</div></div>
	</div>
	<div class="col-md-4">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Closed</div>
			<div class="control-kpi-value"><?php echo count($closed); ?></div>
			<div class="control-kpi-meta">Done or cancelled</div>
		</div></div>
	</div>
	<div class="col-md-4">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Total</div>
			<div class="control-kpi-value"><?php echo count($cases); ?></div>
			<div class="control-kpi-meta">All cases</div>
		</div></div>
	</div>
</div>

<div class="row g-3 mb-3">
	<div class="col-12">
		<div class="card control-chart-card">
			<div class="card-header d-flex justify-content-between align-items-center">
				<h5 class="control-panel-title mb-0">Pipeline by stage</h5>
				<span class="text-muted font-size-12">Open cases only</span>
			</div>
			<div class="card-body"><div id="chart-onboarding-pipeline" class="control-chart-host"></div></div>
		</div>
	</div>
</div>

<div class="card control-table-panel mb-3">
	<div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
		<h5 class="control-panel-title mb-0">Open cases</h5>
		<span class="badge badge-soft-info"><?php echo count($open); ?></span>
	</div>
	<div class="control-table-scroll">
		<table class="table table-hover table-nowrap align-middle mb-0 table-control">
			<thead class="table-light sticky-top">
				<tr>
					<th>Company</th>
					<th>Stage</th>
					<th>Go-live</th>
					<th>Owner</th>
					<th>Checklist</th>
					<th class="text-end">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!$openPage): ?>
					<tr><td colspan="6" class="text-center text-muted py-4">No open cases. <a href="onboarding_new.php">Start one</a></td></tr>
				<?php endif; ?>
				<?php foreach ($openPage as $c):
					$checklist = control_parse_checklist($c['checklist_json'] ?? null);
					$done = count(array_filter($checklist, static fn($i) => !empty($i['done'])));
				?>
					<tr>
						<td>
							<a class="fw-semibold text-reset" href="onboarding_case.php?id=<?php echo (int) $c['id']; ?>"><?php echo control_h($c['company_name']); ?></a>
							<div class="text-muted font-size-12"><?php echo control_h($c['slug'] ?: 'slug pending'); ?></div>
						</td>
						<td><span class="badge badge-soft-info"><?php echo control_h($labels[$c['stage']] ?? $c['stage']); ?></span></td>
						<td class="text-muted font-size-12"><?php echo control_h($c['go_live_date'] ?: '—'); ?></td>
						<td class="text-muted font-size-12"><?php echo control_h($c['owner_label'] ?: '—'); ?></td>
						<td class="text-muted"><?php echo $done; ?>/<?php echo count($checklist); ?></td>
						<td class="text-end"><a class="btn btn-sm btn-soft-primary" href="onboarding_case.php?id=<?php echo (int) $c['id']; ?>">Open</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php if ($pagOpen['pages'] > 1): ?>
	<div class="card-footer"><?php control_render_pagination($pagOpen, []); ?></div>
	<?php endif; ?>
</div>

<?php if ($closed): ?>
<div class="card control-table-panel">
	<div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
		<h5 class="control-panel-title mb-0">Recently closed</h5>
		<?php if (!$showClosed): ?>
			<a href="onboarding.php?closed=1" class="btn btn-sm btn-soft-secondary">Show <?php echo min(8, count($closed)); ?> latest</a>
		<?php else: ?>
			<a href="onboarding.php" class="btn btn-sm btn-soft-secondary">Hide</a>
		<?php endif; ?>
	</div>
	<?php if ($showClosed && $closedVisible): ?>
	<ul class="list-group list-group-flush">
		<?php foreach ($closedVisible as $c): ?>
			<li class="list-group-item d-flex justify-content-between align-items-center py-2">
				<a href="onboarding_case.php?id=<?php echo (int) $c['id']; ?>" class="text-reset fw-medium"><?php echo control_h($c['company_name']); ?></a>
				<span class="badge badge-soft-secondary"><?php echo control_h($labels[$c['stage']] ?? $c['stage']); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
	<div class="card-body py-3 text-muted font-size-13"><?php echo count($closed); ?> closed case(s) — expand to preview the latest.</div>
	<?php endif; ?>
</div>
<?php endif; ?>
</div>

<script type="application/json" id="control-page-charts"><?php echo json_encode($chartPayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
<script src="/control/assets/js/control-charts.js?v=20260906b"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  ControlCharts.bootFromPayload('control-page-charts', {
    'chart-onboarding-pipeline': { type: 'bar', key: 'pipeline', opts: { seriesName: 'Cases', empty: 'No open onboarding cases', height: 220 } }
  });
});
</script>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
