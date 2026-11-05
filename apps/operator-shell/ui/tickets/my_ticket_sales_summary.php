<?php
/**
 * T-P1-04 modern My ticket sales (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/my_ticket_sales_summary.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>My ticket sales</title>
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
		<div class="container-fluid tds-page" id="tds-my-sales-root" data-ui-slice="T-P1-04">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Reports</span></p>
					<h1 class="tds-page-title">My ticket sales</h1>
					<p class="tds-page-lead">Your issued paid bookings for a booking-date range.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=my_ticket_sales_summary.php&amp;ui_tickets_my_ticket_sales_summary=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-toolbar tds-date-range" aria-label="My sales filters">
				<div class="tds-field" style="min-width:14rem;flex:1 1 14rem">
					<label class="tds-label" for="tds-my-sales-route">Route</label>
					<select class="tds-select" id="tds-my-sales-route"><option value="0">All routes</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-my-sales-from">From booking date</label>
					<input class="tds-input" id="tds-my-sales-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-my-sales-to">To booking date</label>
					<input class="tds-input" id="tds-my-sales-to" type="date">
				</div>
				<div class="tds-field" style="min-width:10rem;flex:1 1 10rem">
					<label class="tds-label" for="tds-my-sales-q">Search</label>
					<input class="tds-input" id="tds-my-sales-q" type="search" placeholder="Seat, passenger…">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-my-sales-apply">Search</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-my-sales-error" hidden role="alert"></div>
			<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)">
				<div class="tds-kpi"><p class="tds-kpi-label">Seller</p><p class="tds-kpi-value" id="tds-my-sales-seller" style="font-size:1.1rem">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Tickets</p><p class="tds-kpi-value" id="tds-my-sales-count">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Total sales</p><p class="tds-kpi-value tds-mono" id="tds-my-sales-total">—</p></div>
			</div>

			<div class="tds-table-panel">
				<div id="tds-my-sales-loading" class="tds-empty" hidden><p class="tds-empty-title">Loading sales…</p></div>
				<div id="tds-my-sales-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No ticket sales in this range</p>
					<p class="tds-muted">Try another route or booking dates.</p>
				</div>
				<div class="table-responsive" id="tds-my-sales-wrap">
					<table class="tds-table">
						<thead>
							<tr>
								<th>#</th><th>Ticket</th><th>Seat</th><th>Vehicle</th><th>Passenger</th>
								<th>Route</th><th>Booked</th><th>Issuer</th><th>Cost</th>
							</tr>
						</thead>
						<tbody id="tds-my-sales-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-my-sales-pager" hidden>
					<p class="tds-pager-meta" id="tds-my-sales-meta"></p>
					<div class="tds-row">
						<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-my-sales-prev">Previous</button>
						<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-my-sales-next">Next</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/my_ticket_sales_summary.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
