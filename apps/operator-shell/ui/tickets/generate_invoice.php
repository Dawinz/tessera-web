<?php
/**
 * T-P3-01 Hire invoice preview (TDS). Does not write a booking.
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/generate_invoice.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Hire invoice</title>
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
		<div class="container-fluid tds-page" id="tds-tk-hire" data-ready="0" data-ui-slice="T-P3-01">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Hire</span></p>
					<h1 class="tds-page-title">Hire invoice</h1>
					<p class="tds-page-lead">Preview a private-hire invoice. Printing this sheet does not create a booking.</p>
				</div>
				<div class="tds-row">
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-tk-hire-print">Print</button>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=generate_invoice.php&amp;ui_tickets_generate_invoice=legacy">Use classic</a>
				</div>
			</div>
			<div id="tds-tk-hire-body"></div>
			<div class="tds-alert tds-alert-danger" id="tds-tk-hire-error" hidden></div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/generate_invoice.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
