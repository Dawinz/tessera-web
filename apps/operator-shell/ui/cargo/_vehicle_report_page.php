<?php
/**
 * C-P1-11 / C-P1-12 vehicle report shell.
 * Set $vehicleReportMode to "view" or "edit" before include.
 */
declare(strict_types=1);

$mode = ($vehicleReportMode ?? 'view') === 'edit' ? 'edit' : 'view';
$isEdit = $mode === 'edit';
$root = $isEdit ? 'tds-vehicle-report-edit-root' : 'tds-vehicle-report-root';
$slice = $isEdit ? 'C-P1-12' : 'C-P1-11';
$title = $isEdit ? 'Vehicle report checkout' : 'Vehicle report';
$classic = $isEdit ? 'vehicle_report_new_edit.php' : 'vehicle_report_new.php';
$flag = $isEdit ? 'ui_cargo_vehicle_report_new_edit' : 'ui_cargo_vehicle_report_new';
$lead = $isEdit
	? 'Mark parcels checked out for this trip. Uses the same checkout action as Luggage check-out.'
	: 'Paid, outstanding, and route totals for one vehicle trip.';

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/vehicle_report.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
<?php if (!$isSpa): ?>
	<base href="/cargo/">
	<?php include $__roots['accounts'] . '/template/includes/dashonic_head.php'; ?>
	<link href="/operator/css/tessera-design-tokens.css" rel="stylesheet">
	<link href="/operator/css/tessera-design-components.css" rel="stylesheet">
<?php endif; ?>
</head>
<body <?php echo $isSpa ? '' : 'data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark" class="sr-platform-body sr-operator-shell"'; ?>>
<?php if (!$isSpa): ?>
<?php include dirname(__DIR__, 2) . '/includes/shell_header.php'; ?>
<div class="main-content">
<?php endif; ?>
	<div class="page-content">
		<div class="container-fluid tds-page" id="<?php echo $root; ?>" data-mode="<?php echo $mode; ?>" data-ui-slice="<?php echo $slice; ?>">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Vehicle report</span></p>
					<h1 class="tds-page-title"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
					<p class="tds-page-lead"><?php echo htmlspecialchars($lead, ENT_QUOTES, 'UTF-8'); ?></p>
				</div>
				<div class="tds-row">
					<?php if ($isEdit): ?>
					<a class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-vr-back" href="/operator/app.php?module=cargo&amp;page=vehicle_report_new.php">Report</a>
					<?php else: ?>
					<a class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-vr-edit" href="/operator/app.php?module=cargo&amp;page=vehicle_report_new_edit.php">Checkout</a>
					<?php endif; ?>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=<?php echo rawurlencode($classic); ?>&amp;<?php echo $flag; ?>=legacy">Use classic</a>
				</div>
			</div>
			<div class="tds-toolbar">
				<div class="tds-field" style="min-width:18rem">
					<label class="tds-label" for="<?php echo $root; ?>-trip">Trip</label>
					<select class="tds-select" data-role="trip" id="<?php echo $root; ?>-trip"></select>
				</div>
				<button type="button" class="tds-btn tds-btn-primary" data-role="apply">Show</button>
			</div>
			<div class="tds-alert tds-alert-danger" data-role="error" hidden role="alert"></div>
			<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)">
				<div class="tds-kpi"><p class="tds-kpi-label">Vehicle</p><p class="tds-kpi-value" data-role="bus" style="font-size:1.05rem">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Trip date</p><p class="tds-kpi-value" data-role="date" style="font-size:1.05rem">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Crew</p><p class="tds-kpi-value" data-role="crew" style="font-size:1rem">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Parcels</p><p class="tds-kpi-value" data-role="count">—</p><p class="tds-kpi-hint" data-role="checked">—</p></div>
			</div>
			<div class="tds-kpi-grid">
				<div class="tds-kpi"><p class="tds-kpi-label">Paid</p><p class="tds-kpi-value" data-role="paid">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">To be paid</p><p class="tds-kpi-value" data-role="tobe">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Amount</p><p class="tds-kpi-value" data-role="amount">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Manifest</p><p class="tds-kpi-value" data-role="code" style="font-size:1rem">—</p></div>
			</div>
			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<h2 class="tds-page-title" style="font-size:1.05rem">By route</h2>
				<div data-role="route-empty" class="tds-empty" hidden><p class="tds-empty-title">No route totals</p></div>
				<div data-role="route-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead><tr><th>Route</th><th>Parcels</th><th>Paid</th><th>To be paid</th><th>Loan</th></tr></thead>
						<tbody data-role="routes"></tbody>
					</table>
				</div>
			</div>
			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<h2 class="tds-page-title" style="font-size:1.05rem">Parcels</h2>
				<div data-role="loading" class="tds-empty" hidden><p class="tds-empty-title">Loading report…</p></div>
				<div data-role="empty" class="tds-empty" hidden><p class="tds-empty-title">No parcels on this trip</p></div>
				<div data-role="wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead>
							<tr>
								<th>Code</th><th>Owner</th><th>Route</th><th>Detail</th><th>Status</th>
								<th>Paid</th><th>To pay</th><th>Qty</th><th>Checkout</th>
							</tr>
						</thead>
						<tbody data-role="lines"></tbody>
					</table>
				</div>
				<div class="tds-pager" data-role="pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" data-role="prev">Previous</button>
					<span class="tds-muted" data-role="meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" data-role="next">Next</button>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/vehicle_report.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
