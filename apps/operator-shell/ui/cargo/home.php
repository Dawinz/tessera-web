<?php
/**
 * C-P1-01 modern Cargo home (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/home.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Cargo home</title>
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
		<div class="container-fluid tds-page" id="tds-cargo-home-root" data-ui-slice="C-P1-01">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Home</span></p>
					<h1 class="tds-page-title">Cargo home</h1>
					<p class="tds-page-lead" id="tds-cargo-home-lead">Monthly charges and receipts for your routes.</p>
				</div>
				<div class="tds-row">
					<div class="tds-field" style="min-width:14rem;margin:0">
						<label class="tds-label" for="tds-cargo-home-route">Route</label>
						<select class="tds-select" id="tds-cargo-home-route"></select>
					</div>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=home.php&amp;ui_cargo_home=legacy">Use classic</a>
				</div>
			</div>
			<div class="tds-row" style="margin-bottom:var(--tds-space-4)">
				<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=register_luggage.php">Register</a>
				<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=my_luggages.php">My luggages</a>
				<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=my_charge.php">My charges</a>
				<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=vehicle_report_new.php">Vehicle report</a>
			</div>
			<div class="tds-alert tds-alert-danger" id="tds-cargo-home-error" hidden role="alert"></div>
			<div class="tds-kpi-grid">
				<div class="tds-kpi"><p class="tds-kpi-label">Total charge</p><p class="tds-kpi-value" id="tds-cargo-kpi-total">—</p><p class="tds-kpi-hint">Paid + outstanding</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Cash paid</p><p class="tds-kpi-value" id="tds-cargo-kpi-paid">—</p><p class="tds-kpi-hint">Collected this month</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">To be paid</p><p class="tds-kpi-value" id="tds-cargo-kpi-tobe">—</p><p class="tds-kpi-hint">Outstanding</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Receipts</p><p class="tds-kpi-value" id="tds-cargo-kpi-receipts">—</p><p class="tds-kpi-hint">Luggage receipts</p></div>
			</div>
			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div class="tds-page-title-box" style="margin-bottom:var(--tds-space-3)"><h2 class="tds-page-title" style="font-size:1.05rem">Routes this month</h2></div>
				<div id="tds-cargo-home-loading" class="tds-empty" hidden><p class="tds-empty-title">Loading dashboard…</p></div>
				<div id="tds-cargo-home-empty" class="tds-empty" hidden><p class="tds-empty-title">No charges this month</p></div>
				<div id="tds-cargo-home-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead><tr><th>Route</th><th>Paid</th><th>To be paid</th><th>Total</th><th>Receipts</th></tr></thead>
						<tbody id="tds-cargo-home-routes"></tbody>
					</table>
				</div>
			</div>
			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div class="tds-page-title-box" style="margin-bottom:var(--tds-space-3)"><h2 class="tds-page-title" style="font-size:1.05rem">Daily receipts</h2></div>
				<div id="tds-cargo-home-daily-empty" class="tds-empty" hidden><p class="tds-empty-title">No daily receipts for this route</p></div>
				<div id="tds-cargo-home-daily-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead><tr><th>Date</th><th>Paid receipts</th><th>To be paid</th><th>Due</th></tr></thead>
						<tbody id="tds-cargo-home-daily"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/home.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
