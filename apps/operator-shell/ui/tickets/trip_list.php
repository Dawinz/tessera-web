<?php
/**
 * T-P1-22 Trip list merges into Schedule (TDS).
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
	<title>Trip list</title>
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
		<div class="container-fluid tds-page" id="tds-triplist-root" data-ui-slice="T-P1-22">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Setup</span></p>
					<h1 class="tds-page-title">Trip list</h1>
					<p class="tds-page-lead">Departures for the day live on the Schedule. Open it to see trips, then book or manage a template.</p>
				</div>
			</div>
			<div class="tds-card">
				<div class="tds-card-body">
					<p>The trip list is the Schedule calendar. Pick a date there to see which buses run.</p>
					<div class="tds-row" style="margin-top:var(--tds-space-4)">
						<a class="tds-btn tds-btn-primary" id="tds-triplist-schedule" href="/operator/app.php?module=tickets&amp;page=schedule.php">Open schedule</a>
						<a class="tds-btn tds-btn-ghost" href="/operator/app.php?module=tickets&amp;page=trip_list.php&amp;ui_tickets_trip_list=legacy">Use classic</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
</body>
</html>
