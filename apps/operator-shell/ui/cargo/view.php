<?php
/**
 * C-P1-05 modern Luggage status view (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/view.js') ?: time();
$luggageId = (int) ($_GET['u'] ?? $_GET['id'] ?? 0);

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Luggage status</title>
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
		<div class="container-fluid tds-page" id="tds-luggage-status-root" data-ui-slice="C-P1-05" data-luggage-id="<?php echo $luggageId; ?>">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Status</span></p>
					<h1 class="tds-page-title">Luggage status</h1>
					<p class="tds-page-lead">Sender, payment, store, and any manifest legs for one parcel.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=view.php&amp;ui_cargo_view=legacy<?php echo $luggageId ? '&amp;u=' . $luggageId : ''; ?>">Use classic</a>
				</div>
			</div>
			<div class="tds-toolbar">
				<div class="tds-field" style="min-width:12rem">
					<label class="tds-label" for="tds-view-code">Receipt code</label>
					<input class="tds-input" id="tds-view-code" type="search" placeholder="Code">
				</div>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-view-find">Find</button>
			</div>
			<div class="tds-alert tds-alert-danger" id="tds-view-error" hidden role="alert"></div>
			<div id="tds-view-empty" class="tds-empty"><p class="tds-empty-title">Look up a luggage</p><p class="tds-empty-text">Search by receipt code, or open a row from My luggages.</p></div>
			<div id="tds-view-card" hidden>
				<div class="tds-row" id="tds-view-actions" style="margin:var(--tds-space-4) 0"></div>
				<div class="tds-table-wrap">
					<table class="tds-table"><tbody id="tds-view-fields"></tbody></table>
				</div>
				<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
					<h2 class="tds-page-title" style="font-size:1.05rem">Manifest</h2>
					<div id="tds-view-man-empty" class="tds-empty"><p class="tds-empty-title">Not on a manifest</p></div>
					<div id="tds-view-man-wrap" class="tds-table-wrap" hidden>
						<table class="tds-table">
							<thead><tr><th>Vehicle</th><th>Trip date</th><th>Route</th><th>Status</th><th>Checked</th><th>Paid</th><th>To pay</th><th>Qty</th><th>Taken</th></tr></thead>
							<tbody id="tds-view-man"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/view.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
