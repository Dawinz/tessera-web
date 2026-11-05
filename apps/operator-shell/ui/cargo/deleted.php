<?php
/**
 * C-P1-03 modern Deletes audit (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/deleted.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Deletes audit</title>
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
		<div class="container-fluid tds-page" id="tds-cargo-deleted-root" data-ui-slice="C-P1-03">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Deletes</span></p>
					<h1 class="tds-page-title">Deletes audit</h1>
					<p class="tds-page-lead">Changes and deletions recorded against luggage.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=deleted.php&amp;ui_cargo_deleted=legacy">Use classic</a>
				</div>
			</div>
			<div class="tds-toolbar">
				<div class="tds-field" style="min-width:14rem;flex:1">
					<label class="tds-label" for="tds-del-q">Search</label>
					<input class="tds-input" id="tds-del-q" type="search" placeholder="Code, name, note…">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-del-apply">Search</button>
			</div>
			<div class="tds-alert tds-alert-danger" id="tds-del-error" hidden role="alert"></div>
			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-del-loading" class="tds-empty" hidden><p class="tds-empty-title">Loading audit…</p></div>
				<div id="tds-del-empty" class="tds-empty" hidden><p class="tds-empty-title">No audit rows</p></div>
				<div id="tds-del-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead>
							<tr>
								<th>Sender</th><th>Receiver</th><th>Code</th><th>Date</th><th>Amount</th><th>Paid</th><th>To pay</th>
								<th>What changed</th><th>Event</th><th>By</th><th>When</th>
							</tr>
						</thead>
						<tbody id="tds-del-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-del-pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-del-prev">Previous</button>
					<span class="tds-muted" id="tds-del-meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-del-next">Next</button>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/deleted.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
