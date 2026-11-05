<?php
/**
 * T-P0-02 modern Book trip (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/schedule_book.js') ?: time();

$routeDayId = (int) ($_GET['route_day_id'] ?? 0);
$tripDate = htmlspecialchars((string) ($_GET['trip_date'] ?? ''), ENT_QUOTES, 'UTF-8');
$routeId = htmlspecialchars((string) ($_GET['route_id'] ?? ''), ENT_QUOTES, 'UTF-8');

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Book trip</title>
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
		<div class="container-fluid tds-page" id="tds-book-root"
			data-ui-slice="T-P0-02"
			data-route-day-id="<?php echo $routeDayId; ?>"
			data-trip-date="<?php echo $tripDate; ?>"
			data-route-id="<?php echo $routeId; ?>">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Schedule</span><span>·</span><span>Book</span></p>
					<h1 class="tds-page-title">Book trip</h1>
					<p class="tds-page-lead">Select seats and enter passenger details.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=schedule.php">Back to calendar</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-book-classic" href="#">Classic book</a>
				</div>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-book-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-book-success" hidden role="status"></div>

			<div class="tds-card" id="tds-book-hero" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-card-body">
					<p class="tds-skeleton" style="width:60%;height:1.25rem"></p>
					<p class="tds-skeleton" style="width:40%;height:0.9rem;margin-top:0.75rem"></p>
				</div>
			</div>

			<div class="tds-card" id="tds-book-segment" style="margin-bottom:var(--tds-space-4)" hidden>
				<div class="tds-card-body">
					<div class="tds-field">
						<label class="tds-label" for="tds-book-sub-route">Travel segment</label>
						<select class="tds-select" id="tds-book-sub-route">
							<option value="">Full route (main fare)</option>
						</select>
						<p class="tds-muted" style="margin-top:0.35rem;font-size:var(--tds-fs-sm)">Choose a sub-route when the passenger only rides part of the trip.</p>
					</div>
				</div>
			</div>

			<div class="tds-row" style="align-items:stretch;gap:var(--tds-space-4)">
				<div style="flex:1 1 18rem;min-width:16rem">
					<div class="tds-card">
						<div class="tds-card-header">
							<h2 class="tds-card-title">Seat chart</h2>
							<span class="tds-muted" id="tds-seat-summary" style="font-size:var(--tds-fs-sm)"></span>
						</div>
						<div class="tds-card-body">
							<div class="tds-seat-chart" id="tds-seat-chart">
								<div class="tds-empty">
									<p class="tds-empty-title">Loading seats…</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="flex:1 1 22rem;min-width:18rem">
					<div class="tds-card" style="height:100%">
						<div class="tds-card-header">
							<h2 class="tds-card-title">Passenger details</h2>
						</div>
						<div class="tds-card-body">
							<div id="tds-passenger-empty" class="tds-empty">
								<p class="tds-empty-title">No seats selected</p>
								<p class="tds-muted">Click available seats on the chart.</p>
							</div>
							<div id="tds-passenger-list" class="tds-stack" hidden></div>
							<div class="tds-row" style="margin-top:var(--tds-space-4);justify-content:space-between">
								<p class="tds-muted" id="tds-book-total">Total: —</p>
								<button type="button" class="tds-btn tds-btn-primary" id="tds-book-submit" disabled>Confirm booking</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/schedule_book.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
