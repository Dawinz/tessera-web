<?php
/**
 * T-P1-23 Route manager hub merges into the network screens (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Route manager</title>
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
		<div class="container-fluid tds-page" id="tds-hub-root" data-ui-slice="T-P1-23">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Setup</span></p>
					<h1 class="tds-page-title">Route manager</h1>
					<p class="tds-page-lead">Maintain the network from these screens: routes, stops, trip templates, and the daily schedule.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=route_manager.php&amp;ui_tickets_route_manager=legacy">Use classic</a>
				</div>
			</div>
			<div class="tds-row" id="tds-hub-cards" style="align-items:stretch">
				<a class="tds-card" id="tds-hub-route" href="/operator/app.php?module=tickets&amp;page=register_route.php" style="flex:1 1 16rem;text-decoration:none;color:inherit">
					<div class="tds-card-body">
						<h2 class="tds-card-title">Register route</h2>
						<p class="tds-muted">Main routes, fares, and currency.</p>
					</div>
				</a>
				<a class="tds-card" id="tds-hub-points" href="/operator/app.php?module=tickets&amp;page=register_route_points.php" style="flex:1 1 16rem;text-decoration:none;color:inherit">
					<div class="tds-card-body">
						<h2 class="tds-card-title">Route points</h2>
						<p class="tds-muted">Boarding and drop stops on a route.</p>
					</div>
				</a>
				<a class="tds-card" id="tds-hub-trip" href="/operator/app.php?module=tickets&amp;page=register_trip.php" style="flex:1 1 16rem;text-decoration:none;color:inherit">
					<div class="tds-card-body">
						<h2 class="tds-card-title">Register trip</h2>
						<p class="tds-muted">Weekly templates: day, route, and bus.</p>
					</div>
				</a>
				<a class="tds-card" id="tds-hub-schedule" href="/operator/app.php?module=tickets&amp;page=schedule.php" style="flex:1 1 16rem;text-decoration:none;color:inherit">
					<div class="tds-card-body">
						<h2 class="tds-card-title">Schedule</h2>
						<p class="tds-muted">Departures on the calendar.</p>
					</div>
				</a>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
</body>
</html>
