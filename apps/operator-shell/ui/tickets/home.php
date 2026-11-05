<?php
/**
 * T-P0-06 modern Home dashboard (TDS).
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
	<title>Dashboard</title>
<?php if (!$isSpa): ?>
	<base href="/tickets/">
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
		<div class="container-fluid tds-page" id="tds-home-root" data-ui-slice="T-P0-06">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Home</span></p>
					<h1 class="tds-page-title">Dashboard</h1>
					<p class="tds-page-lead" id="tds-home-lead">Ticket sales, trips, and revenue.</p>
				</div>
				<div class="tds-row">
					<div class="tds-field" style="min-width:14rem;margin:0">
						<label class="tds-label" for="tds-home-route">Route</label>
						<select class="tds-select" id="tds-home-route">
							<option value="__all__">All routes</option>
						</select>
					</div>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=home.php&amp;ui_tickets_home=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-row" style="margin-bottom:var(--tds-space-4)">
				<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=schedule.php">Schedule</a>
				<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=tickets.php">Book list</a>
				<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=manifest.php">Manifest</a>
				<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=vehicle_manager.php">Vehicles</a>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-home-error" hidden role="alert"></div>

			<div class="tds-kpi-grid" id="tds-home-kpis">
				<div class="tds-kpi">
					<p class="tds-kpi-label">Ticket sales</p>
					<p class="tds-kpi-value" id="tds-kpi-sales">—</p>
					<p class="tds-kpi-hint">This month</p>
					<div id="tds-spark-sales" class="tds-spark" style="min-height:3.5rem;margin-top:0.5rem"></div>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">Seats booked</p>
					<p class="tds-kpi-value" id="tds-kpi-seats">—</p>
					<p class="tds-kpi-hint">Paid &amp; confirmed</p>
					<div id="tds-spark-seats" class="tds-spark" style="min-height:3.5rem;margin-top:0.5rem"></div>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">Trips run</p>
					<p class="tds-kpi-value" id="tds-kpi-trips">—</p>
					<p class="tds-kpi-hint">Manifests this month</p>
					<div id="tds-spark-trips" class="tds-spark" style="min-height:3.5rem;margin-top:0.5rem"></div>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">Free of charge</p>
					<p class="tds-kpi-value" id="tds-kpi-foc">—</p>
					<p class="tds-kpi-hint">Complimentary seats</p>
					<div id="tds-spark-foc" class="tds-spark" style="min-height:3.5rem;margin-top:0.5rem"></div>
				</div>
			</div>

			<div class="tds-card" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-card-header">
					<h2 class="tds-card-title" id="tds-yearly-title">Yearly ticket sales</h2>
				</div>
				<div class="tds-card-body">
					<div id="tds-chart-yearly" style="min-height:20rem"></div>
				</div>
			</div>

			<div class="tds-row" style="align-items:stretch;margin-bottom:var(--tds-space-4)">
				<div class="tds-card" style="flex:1 1 18rem">
					<div class="tds-card-header"><h2 class="tds-card-title">Sales by route</h2></div>
					<div class="tds-card-body"><div id="tds-chart-routes" style="min-height:18rem"></div></div>
				</div>
				<div class="tds-card" style="flex:1 1 18rem">
					<div class="tds-card-header"><h2 class="tds-card-title">Income per bus</h2></div>
					<div class="tds-card-body"><div id="tds-chart-bus" style="min-height:18rem"></div></div>
				</div>
			</div>

			<div class="tds-card">
				<div class="tds-card-header"><h2 class="tds-card-title">Daily ticket sales</h2></div>
				<div class="tds-card-body"><div id="tds-chart-daily" style="min-height:20rem"></div></div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/tickets/assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="/operator/ui/tickets/home.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
