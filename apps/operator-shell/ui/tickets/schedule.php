<?php
/**
 * T-P0-01 modern Schedule (TDS).
 * Always emits a full HTML document so operator spa_capture can parse it like legacy pages.
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/schedule.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Schedule</title>
	<link rel="stylesheet" href="/tickets/assets/libs/fullcalendar/main.min.css">
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
		<div class="container-fluid tds-page" id="tds-schedule-root" data-ui-slice="T-P0-01">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Schedule</span></p>
					<h1 class="tds-page-title">Schedule</h1>
					<p class="tds-page-lead">Browse departures on the calendar, then open a trip to book seats.</p>
				</div>
				<div class="tds-row" id="tds-schedule-actions">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=schedule.php&amp;ui_tickets_schedule=legacy">Use classic schedule</a>
				</div>
			</div>

			<div class="tds-toolbar tds-date-range" id="tds-schedule-filters" aria-label="Schedule filters">
				<div class="tds-field">
					<label class="tds-label" for="tds-start-loc">Starting location</label>
					<select class="tds-select" id="tds-start-loc">
						<option value="">All starts</option>
					</select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-end-loc">Ending location</label>
					<select class="tds-select" id="tds-end-loc">
						<option value="">All ends</option>
					</select>
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-schedule-apply">Apply</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-schedule-add" hidden>
					Add trip template
				</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-schedule-error" hidden role="alert"></div>

			<div class="tds-card tds-calendar-card">
				<div class="tds-card-header">
					<h2 class="tds-card-title">Departure calendar</h2>
					<span class="tds-muted" style="font-size:var(--tds-fs-sm)">Click any trip to open booking</span>
				</div>
				<div class="tds-card-body">
					<div id="tds-schedule-loading" class="tds-empty" hidden>
						<p class="tds-empty-title">Loading trips…</p>
						<span class="tds-skeleton" style="width:12rem;margin:0.75rem auto;display:block"></span>
					</div>
					<div id="tds-schedule-empty" class="tds-empty" hidden>
						<p class="tds-empty-title">No trips in this range</p>
						<p class="tds-muted">Adjust starting or ending location, or add a trip template.</p>
					</div>
					<div id="tds-schedule-calendar" class="tds-calendar-host"></div>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/tickets/assets/libs/fullcalendar/main.min.js"></script>
<script src="/operator/ui/tickets/schedule.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
