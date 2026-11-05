<?php
/**
 * C-P0-07 modern Check / load luggages (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/check_luggages.js') ?: time();

$bus = (string) ($_GET['bus'] ?? '');
$date = (string) ($_GET['date'] ?? '');

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Load cargo</title>
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
		<div class="container-fluid tds-page" id="tds-load-root" data-ui-slice="C-P0-07"
			data-bus="<?php echo htmlspecialchars($bus, ENT_QUOTES, 'UTF-8'); ?>"
			data-date="<?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?>">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Load</span></p>
					<h1 class="tds-page-title">Load cargo</h1>
					<p class="tds-page-lead">Scan or enter a store receipt code and load it onto this trip’s manifest. Remove lines or add a trip remark here.</p>
				</div>
				<div class="tds-row">
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-load-remark-open">Remark</button>
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=vehicle_view.php">Manifest</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-load-classic" href="/cargo/check_luggages.php">Use classic</a>
				</div>
			</div>

			<div class="tds-kpi-grid" id="tds-load-context" aria-label="Manifest context" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-kpi"><span class="tds-kpi-label">Manifest</span><strong class="tds-kpi-value tds-mono" id="tds-load-code">—</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Vehicle</span><strong class="tds-kpi-value" id="tds-load-vehicle">—</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Trip date</span><strong class="tds-kpi-value" id="tds-load-date">—</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Driver</span><strong class="tds-kpi-value" id="tds-load-driver">—</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Conductor</span><strong class="tds-kpi-value" id="tds-load-conductor">—</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Route</span><strong class="tds-kpi-value" id="tds-load-route">—</strong></div>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-load-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-load-success" hidden role="status"></div>
			<div class="tds-alert tds-alert-warning" id="tds-load-warn" hidden role="status"></div>

			<div class="tds-row" style="align-items:stretch;gap:var(--tds-space-4);flex-wrap:wrap">
				<div style="flex:1 1 22rem;min-width:18rem">
					<div class="tds-table-panel">
						<div class="tds-toolbar" style="justify-content:space-between">
							<strong>Load cargo</strong>
							<span class="tds-muted" id="tds-load-locked" hidden>Locked</span>
						</div>

						<section id="tds-load-search-panel">
							<div class="tds-kpi-grid" style="margin-bottom:var(--tds-space-4)">
								<div class="tds-kpi"><span class="tds-kpi-label">Total paid</span><strong class="tds-kpi-value tds-mono" id="tds-load-sum-paid">0</strong></div>
								<div class="tds-kpi"><span class="tds-kpi-label">Total to be paid</span><strong class="tds-kpi-value tds-mono" id="tds-load-sum-tobe">0</strong></div>
								<div class="tds-kpi"><span class="tds-kpi-label">Total amount</span><strong class="tds-kpi-value tds-mono" id="tds-load-sum-amount">0</strong></div>
							</div>
							<div class="tds-field">
								<label class="tds-label" for="tds-load-code-input">Luggage code</label>
								<input class="tds-input" id="tds-load-code-input" type="text" autocomplete="off" placeholder="Enter or scan code" autofocus>
							</div>
							<div class="tds-row" style="margin-top:var(--tds-space-3)">
								<button type="button" class="tds-btn tds-btn-primary" id="tds-load-search" style="flex:1">Search</button>
							</div>
						</section>

						<section id="tds-load-confirm-panel" hidden>
							<div class="tds-toolbar" style="justify-content:space-between;margin-bottom:var(--tds-space-3)">
								<div>
									<p class="tds-muted" style="margin:0">Reference code</p>
									<strong class="tds-mono" id="tds-load-parcel-code">—</strong>
								</div>
								<span class="tds-badge" id="tds-load-parcel-status">—</span>
							</div>
							<p class="tds-muted" id="tds-load-parcel-route" style="margin-top:0"></p>
							<div class="tds-kpi-grid" style="margin-bottom:var(--tds-space-4)">
								<div class="tds-kpi"><span class="tds-kpi-label">Quantity</span><strong class="tds-kpi-value" id="tds-load-parcel-qty">—</strong></div>
								<div class="tds-kpi"><span class="tds-kpi-label">To be paid</span><strong class="tds-kpi-value tds-mono" id="tds-load-parcel-tobe">—</strong></div>
								<div class="tds-kpi"><span class="tds-kpi-label">Paid</span><strong class="tds-kpi-value tds-mono" id="tds-load-parcel-paid">—</strong></div>
								<div class="tds-kpi"><span class="tds-kpi-label">Amount</span><strong class="tds-kpi-value tds-mono" id="tds-load-parcel-amount">—</strong></div>
							</div>
							<p><span class="tds-badge tds-badge-warn" id="tds-load-qty-store">0</span> luggage(s) in store</p>
							<div class="tds-field">
								<label class="tds-label" for="tds-load-qty">Quantity to load</label>
								<input class="tds-input" id="tds-load-qty" type="text" inputmode="numeric">
							</div>
							<div class="tds-field" id="tds-load-tobe-wrap">
								<label class="tds-label" for="tds-load-tobe">To be paid</label>
								<input class="tds-input" id="tds-load-tobe" type="text" inputmode="decimal">
							</div>
							<div class="tds-field" id="tds-load-paid-wrap">
								<label class="tds-label" for="tds-load-paid">Paid</label>
								<input class="tds-input" id="tds-load-paid" type="text" inputmode="decimal">
							</div>
							<div class="tds-row" style="margin-top:var(--tds-space-3);gap:var(--tds-space-2)">
								<button type="button" class="tds-btn tds-btn-secondary" id="tds-load-back" style="flex:0 0 auto">Back</button>
								<button type="button" class="tds-btn tds-btn-primary" id="tds-load-commit" style="flex:1">Load item</button>
							</div>
						</section>
					</div>
				</div>

				<div style="flex:1 1 26rem;min-width:18rem">
					<div class="tds-table-panel">
						<div class="tds-toolbar" style="justify-content:space-between;flex-wrap:wrap;gap:var(--tds-space-2)">
							<strong>Cargo on this trip</strong>
							<a class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-load-view-more" href="#" target="_blank" rel="noopener">View report</a>
						</div>
						<div class="tds-field" style="margin-bottom:var(--tds-space-3)">
							<label class="tds-label" for="tds-load-q">Search loaded</label>
							<input class="tds-input" id="tds-load-q" type="search" placeholder="Code or receiver…">
						</div>
						<div id="tds-load-loading" class="tds-empty" hidden>
							<p class="tds-empty-title">Loading manifest lines…</p>
						</div>
						<div id="tds-load-empty" class="tds-empty" hidden>
							<p class="tds-empty-title">Nothing loaded yet</p>
							<p class="tds-empty-text">Search a store code on the left to start loading.</p>
						</div>
						<div id="tds-load-wrap" class="tds-table-wrap" hidden>
							<table class="tds-table">
								<thead>
									<tr>
										<th>Code</th>
										<th>Qty</th>
										<th>To paid</th>
										<th>Paid</th>
										<th>Status</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="tds-load-tbody"></tbody>
							</table>
						</div>
						<div class="tds-pager" id="tds-load-pager" hidden>
							<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-load-prev">Previous</button>
							<span class="tds-muted" id="tds-load-meta"></span>
							<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-load-next">Next</button>
						</div>
					</div>
				</div>
			</div>

			<dialog class="tds-card" id="tds-load-remark" style="border:1px solid var(--tds-line-strong);border-radius:var(--tds-radius-md);padding:0;max-width:26rem;width:calc(100% - 2rem)">
				<div class="tds-card-body">
					<div class="tds-row" style="justify-content:space-between;align-items:center;margin-bottom:var(--tds-space-3)">
						<h2 class="tds-card-title" style="margin:0">Trip remark</h2>
						<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-load-remark-close">Close</button>
					</div>
					<p class="tds-muted" id="tds-load-remark-label" style="margin-top:0">Notes for this manifest</p>
					<div class="tds-field" style="margin-bottom:var(--tds-space-4)">
						<label class="tds-label" for="tds-load-remark-text">Remark</label>
						<textarea class="tds-input" id="tds-load-remark-text" rows="4" placeholder="Notes for this trip…"></textarea>
					</div>
					<button type="button" class="tds-btn tds-btn-primary" id="tds-load-remark-save">Save remark</button>
				</div>
			</dialog>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/check_luggages.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
