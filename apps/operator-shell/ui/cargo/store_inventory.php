<?php
/**
 * C-P0-03 modern Store inventory (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/store_inventory.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Store inventory</title>
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
		<div class="container-fluid tds-page" id="tds-store-root" data-ui-slice="C-P0-03">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Store inventory</span></p>
					<h1 class="tds-page-title">Store inventory</h1>
					<p class="tds-page-lead">Parcels still in the warehouse — store and half out. Print and edit open classic pages.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=all_luggages.php">All luggage</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=store_inventory.php&amp;ui_cargo_store_inventory=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-filter-chips" id="tds-store-chips" role="tablist" aria-label="Store status">
				<button type="button" class="tds-chip is-active" data-store="not_out">In store</button>
				<button type="button" class="tds-chip" data-store="store">Store</button>
				<button type="button" class="tds-chip" data-store="half_out">Half out</button>
			</div>

			<div class="tds-toolbar tds-date-range" id="tds-store-filters" aria-label="Store inventory filters" style="margin-top:var(--tds-space-4)">
				<div class="tds-field" style="min-width:14rem;flex:1 1 14rem">
					<label class="tds-label" for="tds-store-q">Search</label>
					<input class="tds-input" id="tds-store-q" type="search" placeholder="Code, sender, receiver, phone…">
				</div>
				<div class="tds-field" style="min-width:14rem">
					<label class="tds-label" for="tds-store-route">Route</label>
					<select class="tds-select" id="tds-store-route"><option value="">All my routes</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-store-from">From date</label>
					<input class="tds-input" id="tds-store-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-store-to">To date</label>
					<input class="tds-input" id="tds-store-to" type="date">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-store-apply">Search</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-store-reset">Reset</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-store-error" hidden role="alert"></div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-store-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading store…</p>
				</div>
				<div id="tds-store-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No parcels in store</p>
					<p class="tds-empty-text">Try another route, date, or status.</p>
				</div>
				<div id="tds-store-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table" id="tds-store-table">
						<thead>
							<tr>
								<th>Code</th>
								<th>Dispatch</th>
								<th>Sender</th>
								<th>Receiver</th>
								<th>Date</th>
								<th>Weight</th>
								<th>Detail</th>
								<th>Qty</th>
								<th>To pay</th>
								<th>Paid</th>
								<th>Total</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tds-store-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-store-pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-store-prev">Previous</button>
					<span class="tds-muted" id="tds-store-meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-store-next">Next</button>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/store_inventory.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
