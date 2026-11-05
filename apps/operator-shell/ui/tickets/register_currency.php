<?php
/**
 * T-P1-18 modern Currency (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsCrud = @filemtime(__DIR__ . '/network_crud.js') ?: time();
$jsV = @filemtime(__DIR__ . '/register_currency.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Currency</title>
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
		<div class="container-fluid tds-page" id="tds-currency-root" data-ui-slice="T-P1-18">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Setup</span></p>
					<h1 class="tds-page-title">Currency</h1>
					<p class="tds-page-lead">Exchange rates used when routes are priced. A shared Settings currency screen can take over later.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=register_currency.php&amp;ui_tickets_register_currency=legacy">Use classic</a>
				</div>
			</div>
			<div id="tds-currency-root-body"><p class="tds-muted">Loading…</p></div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/network_crud.js?v=<?php echo (int) $jsCrud; ?>"></script>
<script src="/operator/ui/tickets/register_currency.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
