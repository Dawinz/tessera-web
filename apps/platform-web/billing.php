<?php
require_once __DIR__ . '/includes/auth_guard.php';

$error = null;
$canBilling = control_can('manage_billing');
$tenantsPack = control_list_tenants($db ?? null, !empty($control_db_ok));
$tenants = $tenantsPack['rows'];
$catalog = control_plan_catalog();
$usage = control_tenant_usage_month($db ?? null, !empty($control_db_ok));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canBilling) {
	$action = (string) ($_POST['action'] ?? '');
	if ($action === 'change_plan') {
		$tid = (int) ($_POST['tenant_id'] ?? 0);
		$plan = (string) ($_POST['plan'] ?? '');
		$eff = (string) ($_POST['effective_date'] ?? date('Y-m-d'));
		$notes = (string) ($_POST['notes'] ?? '');
		if (control_change_tenant_plan($db ?? null, !empty($control_db_ok), $tid, $plan, $eff, $control_user['email'], $notes)) {
			header('Location: billing.php?plan=1');
			exit;
		}
		$error = 'Plan change failed.';
	} elseif ($action === 'create_invoice') {
		$tid = (int) ($_POST['tenant_id'] ?? 0);
		$inv = control_create_invoice_stub($db ?? null, !empty($control_db_ok), $tid, [
			'period_label' => $_POST['period_label'] ?? date('Y-m'),
			'amount_tzs' => $_POST['amount_tzs'] ?? null,
			'status' => $_POST['status'] ?? 'draft',
			'due_date' => $_POST['due_date'] ?? date('Y-m-d', strtotime('+14 days')),
			'notes' => $_POST['notes'] ?? '',
		], $control_user['email']);
		if ($inv) {
			header('Location: billing.php?invoice=1');
			exit;
		}
		$error = 'Invoice create failed (migration 005/010?).';
	} elseif ($action === 'invoice_status') {
		$iid = (int) ($_POST['invoice_id'] ?? 0);
		$status = (string) ($_POST['status'] ?? '');
		if (control_update_invoice_status($db ?? null, !empty($control_db_ok), $iid, $status, $control_user['email'])) {
			header('Location: billing.php?status=1');
			exit;
		}
		$error = 'Invoice status update failed.';
	}
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$error = 'Only owners can change billing.';
}

$changesAll = control_list_plan_changes($db ?? null, !empty($control_db_ok), null, 30);
$invoicesAll = control_list_invoices($db ?? null, !empty($control_db_ok), null, 30);
$overdueCount = count(array_filter($invoicesAll, static function ($inv) {
	$st = (string) ($inv['status'] ?? '');
	$due = (string) ($inv['due_date'] ?? '');
	return $st === 'sent' && $due !== '' && $due < date('Y-m-d');
}));

$invPage = control_paginate($invoicesAll, control_page_int('page', 1), 8);
$chartPayload = ['plans' => control_plan_donut_payload($tenants)];

$__page_title = 'Billing | Tessera Control';
$control_nav = 'billing';
$control_extra_head = control_chart_head_tags();
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="control-page">
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<h4 class="mb-1">Billing & plans</h4>
			<p class="text-muted mb-0 font-size-13">SaaS fees · plans · invoices</p>
		</div>
	</div>
</div>

<?php if (isset($_GET['plan'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Plan updated.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if (isset($_GET['invoice'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Invoice created.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if (isset($_GET['status'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Invoice status updated.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?php echo control_h($error); ?></div><?php endif; ?>

<div class="row g-3 mb-3">
	<div class="col-md-3">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Tickets (month)</div>
			<div class="control-kpi-value"><?php echo number_format($usage['tickets']); ?></div>
			<div class="control-kpi-meta">Fleet-wide volume</div>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Active seats</div>
			<div class="control-kpi-value"><?php echo number_format($usage['seats']); ?></div>
			<div class="control-kpi-meta">Staff users</div>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Revenue (month)</div>
			<div class="control-kpi-value"><?php echo control_h(control_format_money($usage['revenue'])); ?></div>
			<div class="control-kpi-meta">Ticket bookings</div>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Overdue invoices</div>
			<div class="control-kpi-value text-<?php echo $overdueCount ? 'danger' : 'body'; ?>"><?php echo $overdueCount; ?></div>
			<div class="control-kpi-meta">Sent & past due</div>
		</div></div>
	</div>
</div>

<div class="row g-3">
	<div class="col-xl-8">
		<div class="row g-3 mb-0">
			<div class="col-md-5">
				<div class="card control-chart-card h-100">
					<div class="card-header"><h5 class="control-panel-title mb-0">Plan mix</h5></div>
					<div class="card-body"><div id="chart-billing-plans" class="control-chart-host control-chart-host--donut"></div></div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="card control-table-panel h-100">
					<div class="card-header bg-transparent border-bottom"><h5 class="control-panel-title mb-0">Recent plan changes</h5></div>
					<div class="control-table-scroll" style="max-height:280px">
						<?php if (!$changesAll): ?>
							<p class="text-muted p-3 mb-0 font-size-13">No plan changes yet.</p>
						<?php else: ?>
							<ul class="control-mini-list px-3">
								<?php foreach (array_slice($changesAll, 0, 5) as $ch): ?>
									<li>
										<div class="flex-grow-1 min-w-0">
											<div class="fw-medium"><?php echo control_h($ch['display_name'] ?? '#' . $ch['tenant_id']); ?></div>
											<div class="text-muted font-size-12"><code><?php echo control_h($ch['from_plan'] ?: '—'); ?></code> → <strong><?php echo control_h($ch['to_plan']); ?></strong></div>
										</div>
										<div class="text-muted font-size-11 text-end"><?php echo control_h(substr((string) $ch['created_at'], 0, 10)); ?></div>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="card control-table-panel mt-3">
			<div class="card-header bg-transparent border-bottom">
				<h5 class="control-panel-title mb-0">Invoices</h5>
				<div class="text-muted font-size-12 mt-1">Mark sent / paid manually until live collect.</div>
			</div>
			<div class="control-table-scroll">
				<?php if (!$invPage['items']): ?>
					<p class="text-muted p-3 mb-0">No invoices yet.</p>
				<?php else: ?>
					<table class="table table-hover mb-0 table-control">
						<thead class="table-light sticky-top">
							<tr><th>#</th><th>Operator</th><th>Period</th><th>Amount</th><th>Status</th><th>Due</th><th></th></tr>
						</thead>
						<tbody>
							<?php foreach ($invPage['items'] as $inv):
								$st = (string) ($inv['status'] ?? 'draft');
								$due = (string) ($inv['due_date'] ?? '');
								$isOverdue = ($st === 'sent' && $due !== '' && $due < date('Y-m-d'));
							?>
								<tr>
									<td><?php echo (int) $inv['id']; ?></td>
									<td class="font-size-13"><?php echo control_h($inv['display_name'] ?? '—'); ?></td>
									<td><?php echo control_h($inv['period_label']); ?></td>
									<td><?php echo number_format((int) $inv['amount_tzs']); ?></td>
									<td><span class="badge badge-soft-<?php echo $isOverdue ? 'danger' : 'secondary'; ?>"><?php echo control_h($st); ?></span></td>
									<td class="text-muted font-size-12"><?php echo control_h($due ?: '—'); ?></td>
									<td class="text-nowrap">
										<?php if ($canBilling && ($st === 'draft' || $st === 'sent')): ?>
											<form method="post" class="d-inline">
												<input type="hidden" name="action" value="invoice_status">
												<input type="hidden" name="invoice_id" value="<?php echo (int) $inv['id']; ?>">
												<input type="hidden" name="status" value="<?php echo $st === 'draft' ? 'sent' : 'paid'; ?>">
												<button class="btn btn-sm btn-soft-primary" type="submit"><?php echo $st === 'draft' ? 'Send' : 'Paid'; ?></button>
											</form>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
			<?php if ($invPage['pages'] > 1): ?>
			<div class="card-footer"><?php control_render_pagination($invPage, [], 'billing.php'); ?></div>
			<?php endif; ?>
		</div>
	</div>

	<div class="col-xl-4">
		<div class="card control-chart-card mb-3">
			<div class="card-header"><h5 class="control-panel-title mb-0">Catalog</h5></div>
			<ul class="list-group list-group-flush">
				<?php foreach ($catalog as $key => $c): ?>
					<li class="list-group-item py-2">
						<div class="fw-semibold text-capitalize font-size-13"><?php echo control_h($c['label']); ?></div>
						<div class="text-muted font-size-12"><?php echo (int) $c['tickets_mo'] === 0 ? 'Unlimited tickets' : number_format((int) $c['tickets_mo']) . ' tickets/mo'; ?> · <?php echo (int) $c['amount_tzs'] === 0 ? 'Custom fee' : number_format((int) $c['amount_tzs']) . ' TZS'; ?></div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php if ($canBilling): ?>
		<div class="card control-chart-card mb-3">
			<div class="card-header"><h5 class="control-panel-title mb-0">Change plan</h5></div>
			<div class="card-body">
				<form method="post" class="vstack gap-2">
					<input type="hidden" name="action" value="change_plan">
					<select name="tenant_id" class="form-select form-select-sm" required>
						<option value="">Operator…</option>
						<?php foreach ($tenants as $t): ?>
							<option value="<?php echo (int) $t['id']; ?>"><?php echo control_h($t['display_name']); ?></option>
						<?php endforeach; ?>
					</select>
					<select name="plan" class="form-select form-select-sm" required>
						<?php foreach (array_keys($catalog) as $p): ?>
							<option value="<?php echo $p; ?>"><?php echo $p; ?></option>
						<?php endforeach; ?>
					</select>
					<input type="date" name="effective_date" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" required>
					<button class="btn btn-primary btn-sm" type="submit">Apply plan</button>
				</form>
			</div>
		</div>
		<div class="card control-chart-card">
			<div class="card-header"><h5 class="control-panel-title mb-0">New invoice</h5></div>
			<div class="card-body">
				<form method="post" class="vstack gap-2">
					<input type="hidden" name="action" value="create_invoice">
					<select name="tenant_id" class="form-select form-select-sm" required>
						<option value="">Operator…</option>
						<?php foreach ($tenants as $t): ?>
							<option value="<?php echo (int) $t['id']; ?>"><?php echo control_h($t['display_name']); ?></option>
						<?php endforeach; ?>
					</select>
					<input type="text" name="period_label" class="form-control form-control-sm" value="<?php echo date('Y-m'); ?>">
					<input type="number" name="amount_tzs" class="form-control form-control-sm" placeholder="Amount TZS">
					<select name="status" class="form-select form-select-sm">
						<option value="draft">draft</option>
						<option value="sent">sent</option>
					</select>
					<input type="date" name="due_date" class="form-control form-control-sm" value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>">
					<button class="btn btn-soft-primary btn-sm" type="submit">Create invoice</button>
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
    'chart-billing-plans': { type: 'donut', key: 'plans', opts: { centerLabel: 'Operators' } }
  });
});
</script>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
