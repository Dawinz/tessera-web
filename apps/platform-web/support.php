<?php
require_once __DIR__ . '/includes/auth_guard.php';

$error = null;
$canSupport = control_can('manage_support');
$tenantsPack = control_list_tenants($db ?? null, !empty($control_db_ok));
$tenants = $tenantsPack['rows'];
$statusFilter = (string) ($_GET['status'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canSupport) {
	$action = (string) ($_POST['action'] ?? '');
	if ($action === 'create') {
		$created = control_create_support_ticket($db ?? null, !empty($control_db_ok), [
			'tenant_id' => $_POST['tenant_id'] ?? '',
			'subject' => $_POST['subject'] ?? '',
			'body' => $_POST['body'] ?? '',
			'priority' => $_POST['priority'] ?? 'normal',
			'requester_email' => $_POST['requester_email'] ?? '',
			'assignee' => $_POST['assignee'] ?? $control_user['email'],
		], $control_user['email']);
		if ($created) {
			header('Location: support.php?created=1');
			exit;
		}
		$error = 'Could not create ticket.';
	} elseif ($action === 'update') {
		$id = (int) ($_POST['id'] ?? 0);
		$patch = [];
		if (isset($_POST['status'])) {
			$patch['status'] = $_POST['status'];
		}
		if (isset($_POST['assignee'])) {
			$patch['assignee'] = $_POST['assignee'];
		}
		if (!empty($_POST['mark_responded'])) {
			$patch['mark_responded'] = true;
		}
		if (control_update_support_ticket($db ?? null, !empty($control_db_ok), $id, $patch, $control_user['email'])) {
			header('Location: support.php?updated=1' . ($statusFilter !== '' ? '&status=' . rawurlencode($statusFilter) : ''));
			exit;
		}
		$error = 'Update failed.';
	} elseif ($action === 'set_hypercare') {
		$caseId = (int) ($_POST['case_id'] ?? 0);
		$rota = (string) ($_POST['hypercare_rota'] ?? '');
		if (control_set_hypercare_rota($db ?? null, !empty($control_db_ok), $caseId, $rota, $control_user['email'])) {
			header('Location: support.php?rota=1');
			exit;
		}
		$error = 'Hypercare rota update failed.';
	}
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$error = 'Your role cannot manage support.';
}

$ticketsAll = control_list_support_tickets($db ?? null, !empty($control_db_ok), [
	'status' => $statusFilter !== '' ? $statusFilter : null,
	'limit' => 100,
]);
$casesPack = control_list_cases($db ?? null, !empty($control_db_ok));
$hypercareCases = array_values(array_filter($casesPack['rows'], static function ($c) {
	return in_array($c['stage'] ?? '', ['golive', 'hypercare'], true);
}));

$openCount = count(array_filter($ticketsAll, static fn($t) => in_array($t['status'], ['open', 'pending'], true)));
$breachCount = 0;
$priorityCounts = ['urgent' => 0, 'high' => 0, 'normal' => 0, 'low' => 0];
foreach ($ticketsAll as $t) {
	$sla = control_support_sla_state($t);
	if ($sla['first_breached'] || $sla['resolve_breached']) {
		$breachCount++;
	}
	$p = (string) ($t['priority'] ?? 'normal');
	if (isset($priorityCounts[$p])) {
		$priorityCounts[$p]++;
	}
}
$chartPayload = [
	'priority' => [
		'labels' => ['Urgent', 'High', 'Normal', 'Low'],
		'values' => [$priorityCounts['urgent'], $priorityCounts['high'], $priorityCounts['normal'], $priorityCounts['low']],
	],
];

$filterQuery = $statusFilter !== '' ? ['status' => $statusFilter] : [];
$page = control_page_int('page', 1);
$pag = control_paginate($ticketsAll, $page, 10);
$tickets = $pag['items'];

$__page_title = 'Support | Tessera Control';
$control_nav = 'support';
$control_extra_head = control_chart_head_tags();
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="control-page">
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
			<div>
				<h4 class="mb-1">Support inbox</h4>
				<p class="text-muted mb-0 font-size-13">SLA-tracked tickets · hypercare rota</p>
			</div>
			<form method="get" class="d-flex gap-2">
				<select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
					<option value="">All statuses</option>
					<?php foreach (['open', 'pending', 'resolved', 'closed'] as $s): ?>
						<option value="<?php echo $s; ?>" <?php echo $statusFilter === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
					<?php endforeach; ?>
				</select>
			</form>
		</div>
	</div>
</div>

<?php if (isset($_GET['created'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Ticket created.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if (isset($_GET['updated'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Ticket updated.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?php echo control_h($error); ?></div><?php endif; ?>

<div class="row g-3 mb-3">
	<div class="col-md-3"><div class="card control-kpi-card h-100"><div class="card-body">
		<div class="control-kpi-label">Open / pending</div>
		<div class="control-kpi-value"><?php echo $openCount; ?></div>
	</div></div></div>
	<div class="col-md-3"><div class="card control-kpi-card h-100"><div class="card-body">
		<div class="control-kpi-label">SLA breaches</div>
		<div class="control-kpi-value text-<?php echo $breachCount ? 'danger' : 'body'; ?>"><?php echo $breachCount; ?></div>
	</div></div></div>
	<div class="col-md-3"><div class="card control-kpi-card h-100"><div class="card-body">
		<div class="control-kpi-label">Hypercare</div>
		<div class="control-kpi-value"><?php echo count($hypercareCases); ?></div>
	</div></div></div>
	<div class="col-md-3">
		<div class="card control-chart-card h-100">
			<div class="card-body p-2"><div id="chart-support-priority" style="min-height:100px"></div></div>
		</div>
	</div>
</div>

<div class="row g-3">
	<div class="col-xl-8">
		<div class="card control-table-panel">
			<div class="card-header bg-transparent border-bottom"><h5 class="control-panel-title mb-0">Tickets</h5></div>
			<div class="control-table-scroll">
				<?php if (!$tickets): ?>
					<p class="text-muted p-3 mb-0">No tickets<?php echo $statusFilter ? ' with this filter' : ''; ?>.</p>
				<?php else: ?>
					<table class="table table-hover mb-0 table-control">
						<thead class="table-light sticky-top">
							<tr><th>#</th><th>Subject</th><th>Priority</th><th>SLA</th><th>Status</th><th></th></tr>
						</thead>
						<tbody>
							<?php foreach ($tickets as $t):
								$sla = control_support_sla_state($t);
							?>
								<tr>
									<td><?php echo (int) $t['id']; ?></td>
									<td>
										<div class="fw-semibold font-size-13"><?php echo control_h($t['subject']); ?></div>
										<div class="text-muted font-size-12"><?php echo control_h($t['display_name'] ?? 'Platform'); ?></div>
									</td>
									<td><span class="badge badge-soft-<?php echo in_array($t['priority'], ['urgent', 'high'], true) ? 'danger' : 'secondary'; ?>"><?php echo control_h($t['priority']); ?></span></td>
									<td class="font-size-12">
										<?php if ($sla['first_breached'] || $sla['resolve_breached']): ?>
											<span class="text-danger">Overdue</span>
										<?php else: ?>
											<span class="text-muted">On track</span>
										<?php endif; ?>
									</td>
									<td><span class="badge badge-soft-<?php echo control_h($sla['tone']); ?>"><?php echo control_h($t['status']); ?></span></td>
									<td class="text-end">
										<?php if ($canSupport && !in_array($t['status'], ['resolved', 'closed'], true)): ?>
											<form method="post" class="d-inline">
												<input type="hidden" name="action" value="update">
												<input type="hidden" name="id" value="<?php echo (int) $t['id']; ?>">
												<input type="hidden" name="status" value="resolved">
												<button class="btn btn-sm btn-soft-success" type="submit">Resolve</button>
											</form>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
			<?php if ($pag['pages'] > 1): ?>
			<div class="card-footer"><?php control_render_pagination($pag, $filterQuery); ?></div>
			<?php endif; ?>
		</div>

		<?php if ($hypercareCases): ?>
		<div class="card control-table-panel mt-3">
			<div class="card-header bg-transparent border-bottom"><h5 class="control-panel-title mb-0">Hypercare rota</h5></div>
			<ul class="list-group list-group-flush">
				<?php foreach (array_slice($hypercareCases, 0, 5) as $c): ?>
					<li class="list-group-item py-2">
						<div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
							<a href="onboarding_case.php?id=<?php echo (int) $c['id']; ?>">#<?php echo (int) $c['id']; ?> · <?php echo control_h($c['company_name']); ?></a>
							<?php if ($canSupport): ?>
								<form method="post" class="d-flex gap-1 m-0">
									<input type="hidden" name="action" value="set_hypercare">
									<input type="hidden" name="case_id" value="<?php echo (int) $c['id']; ?>">
									<input type="text" name="hypercare_rota" class="form-control form-control-sm" style="width:160px" value="<?php echo control_h($c['hypercare_rota'] ?? ''); ?>" placeholder="On-call">
									<button class="btn btn-sm btn-soft-primary" type="submit">Save</button>
								</form>
							<?php else: ?>
								<span class="text-muted font-size-12"><?php echo control_h($c['hypercare_rota'] ?? '—'); ?></span>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>

	<div class="col-xl-4">
		<?php if ($canSupport): ?>
		<div class="card control-chart-card">
			<div class="card-header"><h5 class="control-panel-title mb-0">New ticket</h5></div>
			<div class="card-body">
				<form method="post" class="vstack gap-2">
					<input type="hidden" name="action" value="create">
					<select name="tenant_id" class="form-select form-select-sm">
						<option value="">Platform / unscoped</option>
						<?php foreach ($tenants as $t): ?>
							<option value="<?php echo (int) $t['id']; ?>"><?php echo control_h($t['display_name']); ?></option>
						<?php endforeach; ?>
					</select>
					<input type="text" name="subject" class="form-control form-control-sm" placeholder="Subject" required>
					<textarea name="body" class="form-control form-control-sm" rows="2" placeholder="Details"></textarea>
					<select name="priority" class="form-select form-select-sm">
						<option value="normal" selected>normal</option>
						<option value="high">high</option>
						<option value="urgent">urgent</option>
						<option value="low">low</option>
					</select>
					<button class="btn btn-primary btn-sm" type="submit">Create ticket</button>
				</form>
			</div>
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
    'chart-support-priority': { type: 'donut', key: 'priority', opts: { height: 120, donutSize: '65%', empty: 'No tickets' } }
  });
});
</script>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
