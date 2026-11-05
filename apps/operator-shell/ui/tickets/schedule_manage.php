<?php
/**
 * T-P1-11 modern Manage trip template (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/schedule_manage.js') ?: time();
$routeDayId = (int) ($_GET['route_day_id'] ?? 0);
$tripDate = trim((string) ($_GET['trip_date'] ?? ''));
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tripDate)) {
	$tripDate = '';
}
$routeId = preg_replace('/[^0-9]/', '', (string) ($_GET['route_id'] ?? '')) ?? '';
$classic = '/operator/app.php?module=tickets&page=schedule_manage.php&ui_tickets_schedule_manage=legacy';
if ($routeDayId > 0) {
	$classic .= '&route_day_id=' . $routeDayId;
}
if ($tripDate !== '') {
	$classic .= '&trip_date=' . rawurlencode($tripDate);
}
if ($routeId !== '') {
	$classic .= '&route_id=' . rawurlencode($routeId);
}

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Manage trip template</title>
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
		<div class="container-fluid tds-page" id="tds-manage-root" data-ui-slice="T-P1-11"
			data-route-day-id="<?php echo (int) $routeDayId; ?>"
			data-trip-date="<?php echo htmlspecialchars($tripDate, ENT_QUOTES, 'UTF-8'); ?>"
			data-route-id="<?php echo htmlspecialchars($routeId, ENT_QUOTES, 'UTF-8'); ?>">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Schedule</span></p>
					<h1 class="tds-page-title">Manage trip template</h1>
					<p class="tds-page-lead">Weekday template, bus assignment, and schedule status.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="<?php echo htmlspecialchars($classic, ENT_QUOTES, 'UTF-8'); ?>">Use classic</a>
				</div>
			</div>
			<div id="tds-manage-root-body"><p class="tds-muted">Loading…</p></div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/schedule_manage.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
