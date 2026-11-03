<?php
require_once __DIR__ . '/includes/auth_guard.php';

$filters = [
	'actor' => trim((string) ($_GET['actor'] ?? '')),
	'action' => trim((string) ($_GET['action'] ?? '')),
	'tenant_id' => ($_GET['tenant_id'] ?? '') === '' ? '' : (int) $_GET['tenant_id'],
	'from' => trim((string) ($_GET['from'] ?? '')),
	'to' => trim((string) ($_GET['to'] ?? '')),
	'limit' => 500,
];

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
	$rows = control_list_audit_filtered($db ?? null, !empty($control_db_ok), $filters + ['limit' => 500]);
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename="tessera-control-audit-' . date('Ymd-His') . '.csv"');
	$out = fopen('php://output', 'w');
	fputcsv($out, ['id', 'created_at', 'actor', 'action', 'tenant_id', 'meta_json']);
	foreach ($rows as $r) {
		fputcsv($out, [
			$r['id'] ?? '',
			$r['created_at'] ?? '',
			$r['actor'] ?? '',
			$r['action'] ?? '',
			$r['tenant_id'] ?? '',
			$r['meta_json'] ?? '',
		]);
	}
	fclose($out);
	exit;
}

$allRows = control_list_audit_filtered($db ?? null, !empty($control_db_ok), $filters);
$tenantsPack = control_list_tenants($db ?? null, !empty($control_db_ok));

$actionCounts = [];
foreach ($allRows as $r) {
	$act = (string) ($r['action'] ?? 'unknown');
	$actionCounts[$act] = ($actionCounts[$act] ?? 0) + 1;
}
arsort($actionCounts);
$topActions = array_slice($actionCounts, 0, 6, true);
$chartLabels = [];
$chartValues = [];
foreach ($topActions as $act => $n) {
	$chartLabels[] = strlen($act) > 22 ? substr($act, 0, 20) . '…' : $act;
	$chartValues[] = (int) $n;
}
$chartPayload = ['actions' => ['labels' => $chartLabels, 'values' => $chartValues]];

$page = control_page_int('page', 1);
$pag = control_paginate($allRows, $page, 20);
$rows = $pag['items'];
$filterQuery = array_filter([
	'actor' => $filters['actor'],
	'action' => $filters['action'],
	'tenant_id' => $filters['tenant_id'] !== '' ? $filters['tenant_id'] : null,
	'from' => $filters['from'],
	'to' => $filters['to'],
], static fn($v) => $v !== '' && $v !== null);

$uniqueActors = count(array_unique(array_column($allRows, 'actor')));

$__page_title = 'Audit | Tessera Control';
$control_nav = 'audit';
$control_extra_head = control_chart_head_tags();
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="control-page">
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
			<div>
				<h4 class="mb-1">Audit log</h4>
				<p class="text-muted mb-0 font-size-13">Platform owner actions · filter and export</p>
			</div>
			<a class="btn btn-soft-primary btn-sm" href="audit.php?<?php echo control_h(http_build_query(array_filter($filters + ['export' => 'csv'], static fn($v) => $v !== '' && $v !== null))); ?>">Export CSV</a>
		</div>
	</div>
</div>

<div class="row g-3 mb-3">
	<div class="col-md-4">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Matching events</div>
			<div class="control-kpi-value"><?php echo $pag['total']; ?></div>
			<div class="control-kpi-meta">Current filter</div>
		</div></div>
	</div>
	<div class="col-md-4">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Unique actors</div>
			<div class="control-kpi-value"><?php echo $uniqueActors; ?></div>
			<div class="control-kpi-meta">In this result set</div>
		</div></div>
	</div>
	<div class="col-md-4">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Top action</div>
			<div class="control-kpi-value font-size-16"><?php echo control_h($chartLabels[0] ?? '—'); ?></div>
			<div class="control-kpi-meta"><?php echo isset($chartValues[0]) ? $chartValues[0] . ' events' : 'No data'; ?></div>
		</div></div>
	</div>
</div>

<?php if ($chartLabels): ?>
<div class="row g-3 mb-3">
	<div class="col-12">
		<div class="card control-chart-card">
			<div class="card-header"><h5 class="control-panel-title mb-0">Top actions (filtered)</h5></div>
			<div class="card-body"><div id="chart-audit-actions" class="control-chart-host"></div></div>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="card control-table-panel">
	<div class="card-body border-bottom py-3">
		<form class="row g-2 align-items-end" method="get">
			<div class="col-md-3">
				<label class="form-label">Actor</label>
				<input type="text" name="actor" class="form-control form-control-sm" value="<?php echo control_h($filters['actor']); ?>" placeholder="email…">
			</div>
			<div class="col-md-2">
				<label class="form-label">Action</label>
				<input type="text" name="action" class="form-control form-control-sm" value="<?php echo control_h($filters['action']); ?>" placeholder="tenant.status">
			</div>
			<div class="col-md-2">
				<label class="form-label">Tenant</label>
				<select name="tenant_id" class="form-select form-select-sm">
					<option value="">All</option>
					<?php foreach ($tenantsPack['rows'] as $t): ?>
						<option value="<?php echo (int) $t['id']; ?>" <?php echo $filters['tenant_id'] !== '' && (int) $filters['tenant_id'] === (int) $t['id'] ? 'selected' : ''; ?>>
							#<?php echo (int) $t['id']; ?> · <?php echo control_h($t['display_name']); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-2">
				<label class="form-label">From</label>
				<input type="date" name="from" class="form-control form-control-sm" value="<?php echo control_h($filters['from']); ?>">
			</div>
			<div class="col-md-2">
				<label class="form-label">To</label>
				<input type="date" name="to" class="form-control form-control-sm" value="<?php echo control_h($filters['to']); ?>">
			</div>
			<div class="col-md-1">
				<button type="submit" class="btn btn-soft-secondary btn-sm w-100">Go</button>
			</div>
		</form>
	</div>
	<div class="control-table-scroll">
		<table class="table table-hover table-nowrap align-middle mb-0 table-control">
			<thead class="table-light sticky-top">
				<tr>
					<th>When</th>
					<th>Actor</th>
					<th>Action</th>
					<th>Tenant</th>
					<th>Meta</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!$rows): ?>
					<tr><td colspan="5" class="text-center text-muted py-4">No audit rows match.</td></tr>
				<?php endif; ?>
				<?php foreach ($rows as $r):
					$meta = $r['meta_json'] ?? '';
					if (is_string($meta) && $meta !== '') {
						$decoded = json_decode($meta, true);
						$metaShow = is_array($decoded) ? json_encode($decoded, JSON_UNESCAPED_SLASHES) : $meta;
					} else {
						$metaShow = '—';
					}
					if (strlen($metaShow) > 80) {
						$metaShow = substr($metaShow, 0, 78) . '…';
					}
				?>
					<tr>
						<td class="text-muted font-size-12 text-nowrap"><?php echo control_h($r['created_at'] ?? ''); ?></td>
						<td class="font-size-12"><?php echo control_h($r['actor'] ?? ''); ?></td>
						<td class="fw-medium font-size-13"><?php echo control_h($r['action'] ?? ''); ?></td>
						<td class="text-muted"><?php echo !empty($r['tenant_id']) ? '#' . (int) $r['tenant_id'] : '—'; ?></td>
						<td><code class="font-size-11"><?php echo control_h($metaShow); ?></code></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-2">
		<span class="text-muted font-size-13"><?php echo $pag['total']; ?> row(s) · page <?php echo $pag['page']; ?> of <?php echo $pag['pages']; ?></span>
		<?php control_render_pagination($pag, $filterQuery); ?>
	</div>
</div>
</div>

<?php if ($chartLabels): ?>
<script type="application/json" id="control-page-charts"><?php echo json_encode($chartPayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
<script src="/control/assets/js/control-charts.js?v=20260906b"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  ControlCharts.bootFromPayload('control-page-charts', {
    'chart-audit-actions': { type: 'bar', key: 'actions', opts: { seriesName: 'Events', height: 200, rotateLabels: -25 } }
  });
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
