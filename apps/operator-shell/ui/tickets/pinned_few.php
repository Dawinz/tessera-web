<?php
/**
 * T-P3-06 Pinned seats, latest five (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/pinned.js') ?: time();
if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Pinned seats</title>
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
		<div class="container-fluid tds-page" id="tds-tk-pinned-few" data-ready="0" data-limit="5" data-ui-slice="T-P3-06">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Booking</span></p>
					<h1 class="tds-page-title">Pinned seats</h1>
					<p class="tds-page-lead">The latest five trips you pinned.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=pinned.php">All pins</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=pinned_few.php&amp;ui_tickets_pinned_few=legacy">Use classic</a>
				</div>
			</div>
			<div id="tds-tk-pinned-few-body"></div>
			<div class="tds-alert tds-alert-danger" id="tds-tk-pinned-few-error" hidden></div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/pinned.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
