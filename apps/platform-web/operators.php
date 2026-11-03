<?php
require_once __DIR__ . '/includes/auth_guard.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_tenant') {
	$created = control_create_tenant($db ?? null, !empty($control_db_ok), [
		'slug' => $_POST['slug'] ?? '',
		'name' => $_POST['name'] ?? '',
		'plan' => $_POST['plan'] ?? 'standard',
	], $control_user['email']);
	if ($created) {
		header('Location: operator.php?id=' . (int) $created['id'] . '&created=1');
		exit;
	}
	$error = 'Could not create tenant. Check slug uniqueness and migrations.';
}

$pack = control_list_tenants($db ?? null, !empty($control_db_ok));
$allTenants = $pack['rows'];
$q = strtolower(trim((string) ($_GET['q'] ?? '')));
$statusFilter = (string) ($_GET['status'] ?? '');
$tenants = $allTenants;
if ($q !== '') {
	$tenants = array_values(array_filter($tenants, static function ($t) use ($q) {
		return str_contains(strtolower($t['display_name']), $q)
			|| str_contains(strtolower($t['slug']), $q)
			|| str_contains(strtolower($t['plan']), $q);
	}));
}
if (in_array($statusFilter, ['active', 'suspended', 'provisioning'], true)) {
	$tenants = array_values(array_filter($tenants, static fn($t) => $t['status'] === $statusFilter));
}

$statusCounts = control_tenant_status_counts($allTenants);
$planDonut = control_plan_donut_payload($allTenants);
$page = control_page_int('page', 1);
$pagination = control_paginate($tenants, $page, 12);
$tenantsPage = $pagination['items'];
$listQuery = array_filter(['q' => $q, 'status' => $statusFilter], static fn($v) => $v !== '');

$chartPayload = [
	'operators' => control_status_donut_payload($statusCounts),
	'plans' => $planDonut,
];

$__page_title = 'Operators | Tessera Control';
$control_nav = 'operators';
$control_extra_head = control_chart_head_tags();
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="control-page">
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<div>
				<h4 class="mb-1">Operators</h4>
				<p class="text-muted mb-0 font-size-13">Fleet registry · <?php echo $pack['source'] === 'mysql' ? 'live data' : 'demo data'; ?></p>
			</div>
			<div class="d-flex flex-wrap gap-2">
				<button type="button" class="btn btn-soft-primary btn-sm" data-bs-toggle="modal" data-bs-target="#quickAddOperatorModal">Quick add</button>
				<a href="operator_new.php" class="btn btn-primary btn-sm"><i class="mdi mdi-account-plus me-1"></i> Add operator</a>
			</div>
		</div>
	</div>
</div>

<?php if ($error): ?>
	<div class="alert alert-danger"><?php echo control_h($error); ?></div>
<?php endif; ?>

<div class="row g-3 mb-3">
	<div class="col-xl-3 col-sm-6">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Total</div>
			<div class="control-kpi-value"><?php echo $statusCounts['total']; ?></div>
			<div class="control-kpi-meta">Registered operators</div>
		</div></div>
	</div>
	<div class="col-xl-3 col-sm-6">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Active</div>
			<div class="control-kpi-value text-success"><?php echo $statusCounts['active']; ?></div>
			<div class="control-kpi-meta">Staff login allowed</div>
		</div></div>
	</div>
	<div class="col-xl-3 col-sm-6">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Provisioning</div>
			<div class="control-kpi-value"><?php echo $statusCounts['provisioning']; ?></div>
			<div class="control-kpi-meta">Setup in progress</div>
		</div></div>
	</div>
	<div class="col-xl-3 col-sm-6">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Suspended</div>
			<div class="control-kpi-value"><?php echo $statusCounts['suspended']; ?></div>
			<div class="control-kpi-meta">Login blocked</div>
		</div></div>
	</div>
</div>

<div class="row g-3 mb-3">
	<div class="col-md-6">
		<div class="card control-chart-card h-100">
			<div class="card-header"><h5 class="control-panel-title mb-0">Status mix</h5></div>
			<div class="card-body"><div id="chart-operators-status" class="control-chart-host control-chart-host--donut"></div></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card control-chart-card h-100">
			<div class="card-header"><h5 class="control-panel-title mb-0">Plans</h5></div>
			<div class="card-body"><div id="chart-operators-plans" class="control-chart-host control-chart-host--donut"></div></div>
		</div>
	</div>
</div>

<div class="card control-table-panel">
	<div class="card-body border-bottom py-3">
		<form class="row g-2 align-items-end" method="get">
			<div class="col-md-5">
				<label class="form-label">Search</label>
				<input type="search" name="q" value="<?php echo control_h($q); ?>" class="form-control form-control-sm" placeholder="Name, slug, or plan…">
			</div>
			<div class="col-md-3">
				<label class="form-label">Status</label>
				<select name="status" class="form-select form-select-sm">
					<option value="">All statuses</option>
					<?php foreach (['active','provisioning','suspended'] as $st): ?>
						<option value="<?php echo $st; ?>" <?php echo $statusFilter === $st ? 'selected' : ''; ?>><?php echo ucfirst($st); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-4">
				<button type="submit" class="btn btn-soft-secondary btn-sm me-1">Filter</button>
				<a href="operators.php" class="btn btn-link btn-sm">Reset</a>
			</div>
		</form>
	</div>
	<div class="control-table-scroll">
		<table class="table table-hover table-nowrap align-middle mb-0 table-control">
			<thead class="table-light sticky-top">
				<tr>
					<th style="width:40px">#</th>
					<th>Operator</th>
					<th>Slug</th>
					<th>Plan</th>
					<th>Status</th>
					<th>Domain</th>
					<th class="text-end">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!$tenantsPage): ?>
					<tr><td colspan="7" class="text-center text-muted py-4">No operators match. <a href="operator_new.php">Add one</a></td></tr>
				<?php endif; ?>
				<?php foreach ($tenantsPage as $t):
					$console = control_operator_console_url($t);
				?>
					<tr>
						<td class="text-muted"><?php echo (int) $t['id']; ?></td>
						<td>
							<a href="operator.php?id=<?php echo (int) $t['id']; ?>" class="fw-semibold text-reset"><?php echo control_h($t['display_name']); ?></a>
							<div class="text-muted font-size-12"><?php echo control_h($t['legal_name']); ?></div>
						</td>
						<td><code><?php echo control_h($t['slug']); ?></code></td>
						<td class="text-capitalize text-muted"><?php echo control_h($t['plan']); ?></td>
						<td><?php echo control_status_badge($t['status']); ?></td>
						<td class="text-muted font-size-12"><?php echo control_h($t['primary_domain'] ?: '—'); ?></td>
						<td class="text-end">
							<div class="dropdown">
								<button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">Actions</button>
								<div class="dropdown-menu dropdown-menu-end">
									<a class="dropdown-item" href="operator.php?id=<?php echo (int) $t['id']; ?>">Manage</a>
									<a class="dropdown-item" href="<?php echo control_h($console); ?>" target="_blank" rel="noopener">Open staff console</a>
									<a class="dropdown-item" href="onboarding_new.php">Start onboarding</a>
								</div>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-2">
		<span class="text-muted font-size-13"><?php echo $pagination['total']; ?> operator(s) · page <?php echo $pagination['page']; ?> of <?php echo $pagination['pages']; ?></span>
		<?php control_render_pagination($pagination, $listQuery); ?>
	</div>
</div>
</div>

<div class="modal fade" id="quickAddOperatorModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form method="post">
				<input type="hidden" name="action" value="create_tenant">
				<div class="modal-header">
					<h5 class="modal-title">Quick add operator</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<p class="text-muted">For the full checklist, use <a href="operator_new.php">Add operator</a>.</p>
					<div class="mb-3">
						<label class="form-label">Name</label>
						<input type="text" name="name" id="operator_name" class="form-control" required placeholder="Abood Express">
					</div>
					<div class="mb-3">
						<label class="form-label">Slug</label>
						<input type="text" name="slug" id="operator_slug" class="form-control" required pattern="[a-z0-9\-]+" placeholder="abood">
						<div class="form-text"><span id="slug_preview">your-slug.tessera.co.tz</span></div>
					</div>
					<div class="mb-0">
						<label class="form-label">Plan</label>
						<select name="plan" class="form-select">
							<option value="standard">Standard</option>
							<option value="flagship">Flagship</option>
							<option value="enterprise">Enterprise</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Create tenant</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="application/json" id="control-page-charts"><?php echo json_encode($chartPayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
<script src="/control/assets/js/control-charts.js?v=20260906b"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  ControlCharts.bootFromPayload('control-page-charts', {
    'chart-operators-status': { type: 'donut', key: 'operators', opts: { centerLabel: 'Operators', empty: 'No operators yet' } },
    'chart-operators-plans': { type: 'donut', key: 'plans', opts: { centerLabel: 'Plans', empty: 'No plan data' } }
  });
});
</script>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
