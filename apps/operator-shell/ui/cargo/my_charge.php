<?php
/**
 * C-P0-06 modern My charges (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/my_charge.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>My charges</title>
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
		<div class="container-fluid tds-page" id="tds-charges-root" data-ui-slice="C-P0-06">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>My charges</span></p>
					<h1 class="tds-page-title">My charges</h1>
					<p class="tds-page-lead">Parcels you registered for a date range, with paid / to-be-paid totals.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=my_charge.php&amp;ui_cargo_my_charge=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-toolbar tds-date-range" id="tds-charges-filters" aria-label="Charge filters">
				<div class="tds-field" id="tds-charges-user-wrap" hidden>
					<label class="tds-label" for="tds-charges-user">Seller</label>
					<select class="tds-select" id="tds-charges-user"></select>
				</div>
				<div class="tds-field" style="min-width:12rem">
					<label class="tds-label" for="tds-charges-route">Route</label>
					<select class="tds-select" id="tds-charges-route"><option value="">All my routes</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-charges-from">From date</label>
					<input class="tds-input" id="tds-charges-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-charges-to">To date</label>
					<input class="tds-input" id="tds-charges-to" type="date">
				</div>
				<div class="tds-field" style="min-width:10rem;flex:1">
					<label class="tds-label" for="tds-charges-q">Search code</label>
					<input class="tds-input" id="tds-charges-q" type="search" placeholder="Code, sender, receiver…">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-charges-apply">Search</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-charges-reset">Reset</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-charges-error" hidden role="alert"></div>

			<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)">
				<div class="tds-kpi">
					<p class="tds-kpi-label">Seller</p>
					<p class="tds-kpi-value" id="tds-charges-seller" style="font-size:1.15rem">—</p>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">Parcels</p>
					<p class="tds-kpi-value" id="tds-charges-count">—</p>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">Paid</p>
					<p class="tds-kpi-value" id="tds-charges-paid">—</p>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">To be paid</p>
					<p class="tds-kpi-value" id="tds-charges-tobe">—</p>
				</div>
			</div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-charges-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading charges…</p>
				</div>
				<div id="tds-charges-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No charges in this range</p>
					<p class="tds-empty-text">Try another route or date window.</p>
				</div>
				<div id="tds-charges-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead>
							<tr>
								<th>Sender</th>
								<th>Receiver</th>
								<th>Route</th>
								<th>Date</th>
								<th>Detail</th>
								<th>Code</th>
								<th>Amount</th>
								<th>Loan</th>
								<th>Paid</th>
								<th>To pay</th>
								<th>Qty</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tds-charges-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-charges-pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-charges-prev">Previous</button>
					<span class="tds-muted" id="tds-charges-meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-charges-next">Next</button>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/my_charge.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
