<?php
/**
 * T-P0-03 modern Book list (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/tickets.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Book list</title>
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
		<div class="container-fluid tds-page" id="tds-bookings-root" data-ui-slice="T-P0-03">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Book list</span></p>
					<h1 class="tds-page-title">Book list</h1>
					<p class="tds-page-lead">Search bookings, filter by status, print tickets, and clear dues.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=tickets.php&amp;ui_tickets_tickets=legacy">Use classic book list</a>
				</div>
			</div>

			<div class="tds-filter-chips" id="tds-status-chips" role="tablist" aria-label="Booking status">
				<button type="button" class="tds-chip is-active" data-preset="all">All</button>
				<button type="button" class="tds-chip" data-preset="complete">Complete</button>
				<button type="button" class="tds-chip" data-preset="booked">Reserved</button>
				<button type="button" class="tds-chip" data-preset="half_complete">Sub routes</button>
				<button type="button" class="tds-chip" data-preset="due">Due</button>
			</div>

			<div class="tds-toolbar tds-date-range" id="tds-bookings-filters" aria-label="Book list filters" style="margin-top:var(--tds-space-4)">
				<div class="tds-field" style="min-width:14rem;flex:1 1 14rem">
					<label class="tds-label" for="tds-bookings-q">Search</label>
					<input class="tds-input" id="tds-bookings-q" type="search" placeholder="Ticket #, name, phone, seat…">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-bookings-start">Starting location</label>
					<select class="tds-select" id="tds-bookings-start"><option value="">All starts</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-bookings-end">Ending location</label>
					<select class="tds-select" id="tds-bookings-end"><option value="">All ends</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-bookings-bus">Vehicle</label>
					<select class="tds-select" id="tds-bookings-bus"><option value="">All vehicles</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-bookings-issuer">Seller</label>
					<select class="tds-select" id="tds-bookings-issuer"><option value="">All sellers</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-bookings-from">Trip from</label>
					<input class="tds-input" id="tds-bookings-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-bookings-to">Trip to</label>
					<input class="tds-input" id="tds-bookings-to" type="date">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-bookings-apply">Apply</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-bookings-reset">Reset</button>
			</div>

			<div class="tds-row" style="margin-top:var(--tds-space-3)">
				<label class="tds-muted" style="font-size:var(--tds-fs-sm);display:inline-flex;align-items:center;gap:0.4rem">
					<input type="checkbox" id="tds-bookings-foc"> Zero-fare (FOC)
				</label>
				<label class="tds-muted" style="font-size:var(--tds-fs-sm);display:inline-flex;align-items:center;gap:0.4rem">
					<input type="checkbox" id="tds-bookings-transit"> Transit
				</label>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-bookings-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-bookings-success" hidden role="status"></div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-bookings-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading bookings…</p>
					<span class="tds-skeleton" style="width:12rem;margin:0.75rem auto;display:block"></span>
				</div>
				<div id="tds-bookings-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No bookings match</p>
					<p class="tds-muted">Try another status chip or clear filters.</p>
				</div>
				<div class="table-responsive" id="tds-bookings-wrap">
					<table class="tds-table" id="tds-bookings-table">
						<thead>
							<tr>
								<th>Seat</th>
								<th>Ticket</th>
								<th>Passenger</th>
								<th>Trip date</th>
								<th>Vehicle</th>
								<th>Seller</th>
								<th>Cost</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody id="tds-bookings-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-bookings-pager" hidden>
					<p class="tds-pager-meta" id="tds-bookings-meta"></p>
					<div class="tds-row">
						<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-bookings-prev">Previous</button>
						<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-bookings-next">Next</button>
					</div>
				</div>
			</div>

			<dialog class="tds-card" id="tds-booking-detail" style="border:1px solid var(--tds-line-strong);border-radius:var(--tds-radius-md);padding:0;max-width:28rem;width:calc(100% - 2rem)">
				<div class="tds-card-header">
					<h2 class="tds-card-title" id="tds-detail-title">Booking</h2>
					<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-detail-close">Close</button>
				</div>
				<div class="tds-card-body" id="tds-detail-body"></div>
			</dialog>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/tickets.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
