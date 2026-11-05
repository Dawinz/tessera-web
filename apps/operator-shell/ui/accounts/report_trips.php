<?php
/**
 * A-P0-05 modern Accounts Trip profitability (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']) || !empty($GLOBALS['tessera_accounts_spa_embed']);
$jsV = @filemtime(__DIR__ . '/report_trips.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Trip profitability</title>
<?php if (!$isSpa): ?>
	<base href="/accounts/">
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
		<div class="container-fluid tds-page" id="tds-acc-trips-root" data-ui-slice="A-P0-05">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Accounts</span><span>·</span><span>Fleet reports</span></p>
					<h1 class="tds-page-title">Trip profitability</h1>
					<p class="tds-page-lead">Revenue, costs and margin for each accounts manifest trip.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=accounts&amp;page=manifest.php">Manifests</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=accounts&amp;page=report_trips.php&amp;ui_accounts_report_trips=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-kpi-grid" aria-label="Trip P&amp;L totals" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-kpi"><span class="tds-kpi-label">Trips</span><strong class="tds-kpi-value" id="tds-acc-trips-count">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Revenue</span><strong class="tds-kpi-value tds-mono" id="tds-acc-trips-revenue">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Trip profit</span><strong class="tds-kpi-value tds-mono" id="tds-acc-trips-net">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Trips to review</span><strong class="tds-kpi-value" id="tds-acc-trips-review">0</strong></div>
			</div>

			<div class="tds-toolbar tds-date-range" aria-label="Trip filters">
				<div class="tds-field" style="min-width:12rem;flex:1 1 12rem">
					<label class="tds-label" for="tds-acc-trips-q">Search</label>
					<input class="tds-input" id="tds-acc-trips-q" type="search" placeholder="Trip code or vehicle…">
				</div>
				<div class="tds-field" style="min-width:14rem">
					<label class="tds-label" for="tds-acc-trips-route">Route</label>
					<select class="tds-select" id="tds-acc-trips-route"><option value="">All routes</option></select>
				</div>
				<div class="tds-field" style="min-width:10rem">
					<label class="tds-label" for="tds-acc-trips-vehicle">Vehicle</label>
					<select class="tds-select" id="tds-acc-trips-vehicle"><option value="">All vehicles</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-acc-trips-from">From date</label>
					<input class="tds-input" id="tds-acc-trips-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-acc-trips-to">To date</label>
					<input class="tds-input" id="tds-acc-trips-to" type="date">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-acc-trips-apply">Search</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-trips-reset">Reset</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-acc-trips-error" hidden role="alert"></div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-acc-trips-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading trips…</p>
				</div>
				<div id="tds-acc-trips-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No trips match</p>
					<p class="tds-empty-text">Widen the date range or clear filters.</p>
				</div>
				<div id="tds-acc-trips-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead>
							<tr>
								<th>Code</th>
								<th>Trip date</th>
								<th>Route</th>
								<th>Vehicle</th>
								<th>Tickets</th>
								<th>Cargo</th>
								<th>Revenue</th>
								<th>Costs</th>
								<th>Net</th>
								<th>Margin</th>
								<th>Status</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tds-acc-trips-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-acc-trips-pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-acc-trips-prev">Previous</button>
					<span class="tds-muted" id="tds-acc-trips-meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-acc-trips-next">Next</button>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/accounts/report_trips.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
