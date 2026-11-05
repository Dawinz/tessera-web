<?php
/**
 * C-P3-02 Cash in hand history (TDS). Spending is Accounts daily.
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/cash_hand.js') ?: time();
if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Cash in hand</title>
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
		<div class="container-fluid tds-page" id="tds-cg-cash-hand" data-ready="0" data-ui-slice="C-P3-02">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Money</span></p>
					<h1 class="tds-page-title">Cash in hand</h1>
					<p class="tds-page-lead">Recent cargo floats. Day-to-day spending is recorded on Accounts daily expenses.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-primary tds-btn-sm" id="tds-cg-cash-hand-daily" href="/operator/app.php?module=accounts&amp;page=daily.php">Open daily expenses</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=cash_hand.php&amp;ui_cargo_cash_hand=legacy">Use classic</a>
				</div>
			</div>
			<div id="tds-cg-cash-hand-body"></div>
			<div class="tds-alert tds-alert-danger" id="tds-cg-cash-hand-error" hidden></div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/cash_hand.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
