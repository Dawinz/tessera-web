<?php
/**
 * C-P0-05 modern Luggage check-out desk (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/luggage_check_out.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Luggage check-out</title>
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
		<div class="container-fluid tds-page" id="tds-checkout-root" data-ui-slice="C-P0-05">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Check-out</span></p>
					<h1 class="tds-page-title">Luggage check-out</h1>
					<p class="tds-page-lead">Destination desk — find loaded parcels by code and mark them handed over.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=vehicle_view.php">Manifest</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=luggage_check_out.php&amp;ui_cargo_luggage_check_out=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-filter-chips" id="tds-checkout-chips" role="tablist" aria-label="Checkout status">
				<button type="button" class="tds-chip is-active" data-checked="">All</button>
				<button type="button" class="tds-chip" data-checked="pending">Pending</button>
				<button type="button" class="tds-chip" data-checked="out">Checked out</button>
			</div>

			<div class="tds-toolbar" id="tds-checkout-filters" aria-label="Checkout filters" style="margin-top:var(--tds-space-4)">
				<div class="tds-field" style="min-width:14rem;flex:1 1 14rem">
					<label class="tds-label" for="tds-checkout-q">Search code</label>
					<input class="tds-input" id="tds-checkout-q" type="search" placeholder="Receipt code, owner, vehicle…">
				</div>
				<div class="tds-field" style="min-width:14rem">
					<label class="tds-label" for="tds-checkout-route">Route</label>
					<select class="tds-select" id="tds-checkout-route"><option value="">All routes</option></select>
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-checkout-apply">Search</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-checkout-reset">Reset</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-checkout-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-checkout-success" hidden role="status"></div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-checkout-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading parcels…</p>
				</div>
				<div id="tds-checkout-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No parcels match</p>
					<p class="tds-empty-text">Try another route or search code.</p>
				</div>
				<div id="tds-checkout-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead>
							<tr>
								<th>Code</th>
								<th>Owner</th>
								<th>Weight</th>
								<th>Detail</th>
								<th>To pay</th>
								<th>Paid</th>
								<th>Vehicle</th>
								<th>Date</th>
								<th>Qty left</th>
								<th>Status</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tds-checkout-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-checkout-pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-checkout-prev">Previous</button>
					<span class="tds-muted" id="tds-checkout-meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-checkout-next">Next</button>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/luggage_check_out.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
