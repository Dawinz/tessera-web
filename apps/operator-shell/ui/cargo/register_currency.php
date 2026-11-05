<?php
/**
 * C-P1-13 modern Cargo currency (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsCrud = @filemtime(__DIR__ . '/setup_crud.js') ?: time();
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
	<title>Register currency</title>
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
		<div class="container-fluid tds-page" id="tds-cargo-currency-root" data-ui-slice="C-P1-13">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Setup</span></p>
					<h1 class="tds-page-title">Register currency</h1>
					<p class="tds-page-lead">Exchange rates printed on cargo receipts. The main currency stays as it is.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=register_currency.php&amp;ui_cargo_register_currency=legacy">Use classic</a>
				</div>
			</div>
			<div id="tds-cargo-currency-root-body"><p class="tds-muted">Loading…</p></div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/setup_crud.js?v=<?php echo (int) $jsCrud; ?>"></script>
<script src="/operator/ui/cargo/register_currency.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
