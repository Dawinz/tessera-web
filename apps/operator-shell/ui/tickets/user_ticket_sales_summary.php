<?php
/**
 * T-P1-03 modern User ticket sales (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/user_ticket_sales_summary.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>User ticket sales</title>
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
		<div class="container-fluid tds-page" id="tds-user-sales-root" data-ui-slice="T-P1-03">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Reports</span></p>
					<h1 class="tds-page-title">User ticket sales</h1>
					<p class="tds-page-lead">Paid bookings for a seller, route, and trip-date range.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=user_ticket_sales_summary.php&amp;ui_tickets_user_ticket_sales_summary=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-toolbar tds-date-range" aria-label="User sales filters">
				<div class="tds-field" id="tds-user-sales-booker-wrap" style="min-width:12rem">
					<label class="tds-label" for="tds-user-sales-booker">Seller</label>
					<select class="tds-select" id="tds-user-sales-booker"></select>
				</div>
				<div class="tds-field" style="min-width:14rem;flex:1 1 14rem">
					<label class="tds-label" for="tds-user-sales-route">Route</label>
					<select class="tds-select" id="tds-user-sales-route"><option value="0">All routes</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-user-sales-from">From trip date</label>
					<input class="tds-input" id="tds-user-sales-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-user-sales-to">To trip date</label>
					<input class="tds-input" id="tds-user-sales-to" type="date">
				</div>
				<div class="tds-field" style="min-width:10rem;flex:1 1 10rem">
					<label class="tds-label" for="tds-user-sales-q">Search</label>
					<input class="tds-input" id="tds-user-sales-q" type="search" placeholder="Seat, passenger…">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-user-sales-apply">Search</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-user-sales-error" hidden role="alert"></div>
			<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)">
				<div class="tds-kpi"><p class="tds-kpi-label">Seller</p><p class="tds-kpi-value" id="tds-user-sales-seller" style="font-size:1.1rem">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Tickets</p><p class="tds-kpi-value" id="tds-user-sales-count">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Total sales</p><p class="tds-kpi-value tds-mono" id="tds-user-sales-total">—</p></div>
			</div>

			<div class="tds-table-panel">
				<div id="tds-user-sales-loading" class="tds-empty" hidden><p class="tds-empty-title">Loading sales…</p></div>
				<div id="tds-user-sales-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No ticket sales in this range</p>
					<p class="tds-muted">Try another seller, route, or trip dates.</p>
				</div>
				<div class="table-responsive" id="tds-user-sales-wrap">
					<table class="tds-table">
						<thead>
							<tr>
								<th>#</th><th>Ticket</th><th>Vehicle</th><th>Seat</th><th>Passenger</th>
								<th>Cost</th><th>Trip date</th><th>From</th><th>To</th><th>Issuer</th>
							</tr>
						</thead>
						<tbody id="tds-user-sales-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-user-sales-pager" hidden>
					<p class="tds-pager-meta" id="tds-user-sales-meta"></p>
					<div class="tds-row">
						<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-user-sales-prev">Previous</button>
						<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-user-sales-next">Next</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/user_ticket_sales_summary.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
