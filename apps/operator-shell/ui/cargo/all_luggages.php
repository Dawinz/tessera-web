<?php
/**
 * C-P0-02 modern All luggages (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/all_luggages.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>All luggage</title>
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
		<div class="container-fluid tds-page" id="tds-luggages-root" data-ui-slice="C-P0-02">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>All luggage</span></p>
					<h1 class="tds-page-title">All luggage</h1>
					<p class="tds-page-lead">Search parcels across your routes. Print and edit open classic pages when needed.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=register_luggage.php">Register</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=all_luggages.php&amp;ui_cargo_all_luggages=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-filter-chips" id="tds-store-chips" role="tablist" aria-label="Dispatch status">
				<button type="button" class="tds-chip is-active" data-store="">All</button>
				<button type="button" class="tds-chip" data-store="store">Store</button>
				<button type="button" class="tds-chip" data-store="half_out">Half out</button>
				<button type="button" class="tds-chip" data-store="out">Out</button>
			</div>

			<div class="tds-toolbar tds-date-range" id="tds-luggages-filters" aria-label="Luggage filters" style="margin-top:var(--tds-space-4)">
				<div class="tds-field" style="min-width:14rem;flex:1 1 14rem">
					<label class="tds-label" for="tds-luggages-q">Search</label>
					<input class="tds-input" id="tds-luggages-q" type="search" placeholder="Code, sender, receiver, phone…">
				</div>
				<div class="tds-field" style="min-width:14rem">
					<label class="tds-label" for="tds-luggages-route">Route</label>
					<select class="tds-select" id="tds-luggages-route"><option value="">All my routes</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-luggages-from">From date</label>
					<input class="tds-input" id="tds-luggages-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-luggages-to">To date</label>
					<input class="tds-input" id="tds-luggages-to" type="date">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-luggages-apply">Search</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-luggages-reset">Reset</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-luggages-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-luggages-success" hidden role="status"></div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-luggages-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading luggage…</p>
				</div>
				<div id="tds-luggages-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No parcels match</p>
					<p class="tds-empty-text">Try another route, date, or search.</p>
				</div>
				<div id="tds-luggages-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table" id="tds-luggages-table">
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
						<tbody id="tds-luggages-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-luggages-pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-luggages-prev">Previous</button>
					<span class="tds-muted" id="tds-luggages-meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-luggages-next">Next</button>
				</div>
			</div>

			<dialog class="tds-card" id="tds-luggages-delete" style="border:1px solid var(--tds-line-strong);border-radius:var(--tds-radius-md);padding:0;max-width:24rem;width:calc(100% - 2rem)">
				<div class="tds-card-body">
					<div class="tds-row" style="justify-content:space-between;align-items:center;margin-bottom:var(--tds-space-3)">
						<h2 class="tds-card-title" style="margin:0">Delete luggage</h2>
						<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-luggages-delete-close">Close</button>
					</div>
					<p class="tds-muted" id="tds-luggages-delete-label" style="margin-top:0">Confirm delete</p>
					<input type="hidden" id="tds-luggages-delete-id">
					<div class="tds-field" style="margin-bottom:var(--tds-space-3)">
						<label class="tds-label" for="tds-luggages-delete-reason">Reason</label>
						<textarea class="tds-input" id="tds-luggages-delete-reason" rows="3" placeholder="Why are you deleting this parcel?"></textarea>
					</div>
					<div class="tds-field" style="margin-bottom:var(--tds-space-4)">
						<label class="tds-label" for="tds-luggages-delete-password">Your password</label>
						<input class="tds-input" id="tds-luggages-delete-password" type="password" autocomplete="current-password">
					</div>
					<button type="button" class="tds-btn tds-btn-primary" id="tds-luggages-delete-confirm">Delete</button>
				</div>
			</dialog>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/all_luggages.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
