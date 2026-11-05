<?php
/**
 * A-P0-06 modern Accounts Profit & loss (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']) || !empty($GLOBALS['tessera_accounts_spa_embed']);
$jsV = @filemtime(__DIR__ . '/report_profit_loss.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Profit &amp; loss</title>
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
		<div class="container-fluid tds-page" id="tds-acc-pnl-root" data-ui-slice="A-P0-06">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Accounts</span><span>·</span><span>Financial reports</span></p>
					<h1 class="tds-page-title">Profit &amp; loss</h1>
					<p class="tds-page-lead">Tickets and cargo revenue, less trip costs and office expenses.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=accounts&amp;page=report_trips.php">Trip profitability</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=accounts&amp;page=report_profit_loss.php&amp;ui_accounts_report_profit_loss=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-kpi-grid" aria-label="P&amp;L totals" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-kpi"><span class="tds-kpi-label">Total revenue</span><strong class="tds-kpi-value tds-mono" id="tds-acc-pnl-revenue">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Direct trip costs</span><strong class="tds-kpi-value tds-mono" id="tds-acc-pnl-direct">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Gross profit</span><strong class="tds-kpi-value tds-mono" id="tds-acc-pnl-gross">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Net profit</span><strong class="tds-kpi-value tds-mono" id="tds-acc-pnl-net">0</strong></div>
			</div>

			<div class="tds-toolbar tds-date-range" aria-label="P&amp;L filters">
				<div class="tds-field" style="min-width:14rem;flex:1 1 14rem">
					<label class="tds-label" for="tds-acc-pnl-route">Route</label>
					<select class="tds-select" id="tds-acc-pnl-route"><option value="">All routes</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-acc-pnl-from">From date</label>
					<input class="tds-input" id="tds-acc-pnl-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-acc-pnl-to">To date</label>
					<input class="tds-input" id="tds-acc-pnl-to" type="date">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-acc-pnl-apply">Search</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-pnl-reset">Reset</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-acc-pnl-error" hidden role="alert"></div>
			<p class="tds-muted" id="tds-acc-pnl-period" style="margin-top:var(--tds-space-3)"></p>

			<div class="tds-row" style="align-items:flex-start;gap:var(--tds-space-4);flex-wrap:wrap;margin-top:var(--tds-space-3)">
				<section style="flex:1 1 22rem;min-width:18rem">
					<div class="tds-table-panel">
						<div class="tds-toolbar"><strong id="tds-acc-pnl-statement-title">Statement</strong></div>
						<div id="tds-acc-pnl-loading" class="tds-empty" hidden>
							<p class="tds-empty-title">Loading statement…</p>
						</div>
						<div id="tds-acc-pnl-statement-wrap" class="tds-table-wrap" hidden>
							<table class="tds-table">
								<tbody id="tds-acc-pnl-statement"></tbody>
							</table>
						</div>
					</div>
				</section>
				<section style="flex:1 1 22rem;min-width:18rem">
					<div class="tds-table-panel">
						<div class="tds-toolbar">
							<strong>Compared with previous period</strong>
							<span class="tds-muted" id="tds-acc-pnl-prev-label"></span>
						</div>
						<div id="tds-acc-pnl-compare-empty" class="tds-empty" hidden>
							<p class="tds-empty-title">No comparison</p>
						</div>
						<div id="tds-acc-pnl-compare-wrap" class="tds-table-wrap" hidden>
							<table class="tds-table">
								<thead>
									<tr>
										<th>Line</th>
										<th>This period</th>
										<th>Previous</th>
										<th>Change</th>
										<th>%</th>
									</tr>
								</thead>
								<tbody id="tds-acc-pnl-compare"></tbody>
							</table>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/accounts/report_profit_loss.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
