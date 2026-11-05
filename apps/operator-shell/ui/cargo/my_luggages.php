<?php
/**
 * C-P1-02 modern My luggages (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/my_luggages.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>My luggages</title>
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
		<div class="container-fluid tds-page" id="tds-my-luggages-root" data-ui-slice="C-P1-02">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>My luggages</span></p>
					<h1 class="tds-page-title">My luggages</h1>
					<p class="tds-page-lead">Parcels you registered. Open a row to see status or update it.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=register_luggage.php">Register</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=my_luggages.php&amp;ui_cargo_my_luggages=legacy">Use classic</a>
				</div>
			</div>
			<div class="tds-filter-chips" id="tds-my-store-chips" role="tablist" aria-label="Dispatch status">
				<button type="button" class="tds-chip is-active" data-store="">All</button>
				<button type="button" class="tds-chip" data-store="store">Store</button>
				<button type="button" class="tds-chip" data-store="half_out">Half out</button>
				<button type="button" class="tds-chip" data-store="out">Out</button>
			</div>
			<div class="tds-toolbar" aria-label="Luggage filters">
				<div class="tds-field" style="min-width:12rem">
					<label class="tds-label" for="tds-my-route">Route</label>
					<select class="tds-select" id="tds-my-route"><option value="">All my routes</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-my-from">From</label>
					<input class="tds-input" id="tds-my-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-my-to">To</label>
					<input class="tds-input" id="tds-my-to" type="date">
				</div>
				<div class="tds-field" style="min-width:10rem;flex:1">
					<label class="tds-label" for="tds-my-q">Search</label>
					<input class="tds-input" id="tds-my-q" type="search" placeholder="Code, sender, receiver…">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-my-apply">Search</button>
			</div>
			<div class="tds-alert tds-alert-danger" id="tds-my-error" hidden role="alert"></div>
			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-my-loading" class="tds-empty" hidden><p class="tds-empty-title">Loading luggages…</p></div>
				<div id="tds-my-empty" class="tds-empty" hidden><p class="tds-empty-title">No luggages yet</p><p class="tds-empty-text">Register a parcel or widen the dates.</p></div>
				<div id="tds-my-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead><tr><th>Code</th><th>Sender</th><th>Receiver</th><th>Route</th><th>Date</th><th>Store</th><th>Payment</th><th>Qty</th><th></th></tr></thead>
						<tbody id="tds-my-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-my-pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-my-prev">Previous</button>
					<span class="tds-muted" id="tds-my-meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-my-next">Next</button>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/my_luggages.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
