<?php
/**
 * C-P1-17 modern Cargo vehicle registration (TDS).
 * Cargo manifests use this name / driver / conductor list.
 * Passenger seat layouts stay on Tickets vehicle manager.
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsCrud = @filemtime(__DIR__ . '/setup_crud.js') ?: time();
$jsV = @filemtime(__DIR__ . '/vehicle_registration.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Vehicle registration</title>
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
		<div class="container-fluid tds-page" id="tds-cargo-vehicle-root" data-ui-slice="C-P1-17">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Setup</span></p>
					<h1 class="tds-page-title">Vehicle registration</h1>
					<p class="tds-page-lead">Buses used on cargo manifests: name, driver, and conductor. Seat layouts for passenger tickets stay in Tickets.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-cargo-vehicle-tickets" href="/operator/app.php?module=tickets&amp;page=vehicle_manager.php">Tickets vehicle manager</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=vehicle_registration.php&amp;ui_cargo_vehicle_registration=legacy">Use classic</a>
				</div>
			</div>
			<div id="tds-cargo-vehicle-root-body"><p class="tds-muted">Loading…</p></div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/setup_crud.js?v=<?php echo (int) $jsCrud; ?>"></script>
<script src="/operator/ui/cargo/vehicle_registration.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
