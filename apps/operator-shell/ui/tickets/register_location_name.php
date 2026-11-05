<?php
/**
 * T-P1-17 modern Register locations (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsCrud = @filemtime(__DIR__ . '/network_crud.js') ?: time();
$jsV = @filemtime(__DIR__ . '/register_location_name.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Register locations</title>
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
		<div class="container-fluid tds-page" id="tds-loc-root" data-ui-slice="T-P1-17">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Setup</span></p>
					<h1 class="tds-page-title">Register locations</h1>
					<p class="tds-page-lead">Place names used as route starts and ends.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=register_location_name.php&amp;ui_tickets_register_location_name=legacy">Use classic</a>
				</div>
			</div>
			<div id="tds-loc-root-body"><p class="tds-muted">Loading…</p></div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/network_crud.js?v=<?php echo (int) $jsCrud; ?>"></script>
<script src="/operator/ui/tickets/register_location_name.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
