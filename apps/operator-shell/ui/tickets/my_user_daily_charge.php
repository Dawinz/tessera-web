<?php
/**
 * T-P0-07 modern Agent daily charge (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/my_user_daily_charge.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>My daily charges</title>
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
		<div class="container-fluid tds-page" id="tds-charge-root" data-ui-slice="T-P0-07">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Account</span><span>·</span><span>Daily charges</span></p>
					<h1 class="tds-page-title">My daily charges</h1>
					<p class="tds-page-lead">Ticket sales you booked for a date range, with running total.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=my_user_daily_charge.php&amp;ui_tickets_my_user_daily_charge=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-toolbar tds-date-range" id="tds-charge-filters" aria-label="Daily charge filters">
				<div class="tds-field" id="tds-charge-user-wrap" hidden>
					<label class="tds-label" for="tds-charge-booker">Seller</label>
					<select class="tds-select" id="tds-charge-booker"></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-charge-from">From date</label>
					<input class="tds-input" id="tds-charge-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-charge-to">To date</label>
					<input class="tds-input" id="tds-charge-to" type="date">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-charge-apply">Search</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-charge-error" hidden role="alert"></div>

			<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)">
				<div class="tds-kpi">
					<p class="tds-kpi-label">Seller</p>
					<p class="tds-kpi-value" id="tds-charge-seller" style="font-size:1.15rem">—</p>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">Tickets</p>
					<p class="tds-kpi-value" id="tds-charge-count">—</p>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">Total sales</p>
					<p class="tds-kpi-value" id="tds-charge-total">—</p>
				</div>
			</div>

			<div class="tds-table-panel">
				<div id="tds-charge-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading charges…</p>
				</div>
				<div id="tds-charge-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No ticket sales in this range</p>
					<p class="tds-muted">Try another date range or seller.</p>
				</div>
				<div class="table-responsive" id="tds-charge-wrap">
					<table class="tds-table">
						<thead>
							<tr>
								<th>#</th>
								<th>Ticket</th>
								<th>Seat</th>
								<th>Passenger</th>
								<th>Booked</th>
								<th>Route</th>
								<th>Vehicle</th>
								<th>Cost</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tds-charge-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-charge-pager" hidden>
					<p class="tds-pager-meta" id="tds-charge-meta"></p>
					<div class="tds-row">
						<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-charge-prev">Previous</button>
						<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-charge-next">Next</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/my_user_daily_charge.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
