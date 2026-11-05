<?php
require_once __DIR__ . '/includes/schedule_boot.php';
require_once __DIR__ . '/includes/schedule_trip_from_request.php';

$scheduleTrip = schedule_trip_from_request($db);
if ($scheduleTrip === null) {
	if (!empty($GLOBALS['tessera_operator_spa_embed'])) {
		require __DIR__ . '/schedule.php';
		return;
	}
	header('Location: /tickets/schedule.php', true, 302);
	exit;
}

$scheduleTripJson = json_encode($scheduleTrip, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$tomorrowmo = $scheduleTrip['trip_date_display'];
$scheduleRouteIdSelected = $scheduleTrip['route_id'];
$bookQuery = http_build_query([
	'route_day_id' => $scheduleTrip['route_day_id'],
	'trip_date' => $scheduleTrip['trip_date'],
	'route_id' => $scheduleTrip['route_id'],
]);
$manageHref = 'schedule_manage.php?' . $bookQuery;
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<?php $tessera_page_label = 'Book trip'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include 'include_scripts.php'; ?>
	<link rel="stylesheet" href="assets/css/schedule.css?v=20260918a">
</head>
<body class="schedule-book-page" data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark">

<div id="layout-wrapper">
	<?php include 'top_header.php'; ?>

	<div class="main-content">
		<div class="page-content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
							<div>
								<h4 class="mb-1">Book trip</h4>
								<p class="text-muted mb-0 small">Select seats and enter passenger details.</p>
							</div>
							<div class="page-title-right">
								<ol class="breadcrumb m-0">
									<li class="breadcrumb-item"><a href="schedule.php">Schedule</a></li>
									<li class="breadcrumb-item active">Book trip</li>
								</ol>
							</div>
						</div>
					</div>
				</div>

				<div class="card schedule-trip-hero mb-3">
					<div class="card-body py-3">
						<div class="row align-items-center g-3">
							<div class="col-lg-8">
								<h5 class="mb-2"><?php echo htmlspecialchars($scheduleTrip['route_name'], ENT_QUOTES, 'UTF-8'); ?></h5>
								<div class="d-flex flex-wrap gap-3 schedule-trip-meta">
									<span><i class="uil uil-calendar-alt me-1"></i><?php echo htmlspecialchars($scheduleTrip['trip_date_display'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlspecialchars($scheduleTrip['weekday'], ENT_QUOTES, 'UTF-8'); ?></span>
									<span><i class="uil uil-bus me-1"></i><?php echo htmlspecialchars($scheduleTrip['bus'], ENT_QUOTES, 'UTF-8'); ?></span>
									<span><i class="uil uil-clock me-1"></i><?php echo htmlspecialchars($scheduleTrip['departure'] ?: ' - ', ENT_QUOTES, 'UTF-8'); ?> -> <?php echo htmlspecialchars($scheduleTrip['arrival'] ?: ' - ', ENT_QUOTES, 'UTF-8'); ?></span>
									<span><i class="uil uil-users-alt me-1"></i><?php echo (int) $scheduleTrip['seats_booked']; ?> / <?php echo (int) $scheduleTrip['seats_total']; ?> seats</span>
								</div>
							</div>
							<div class="col-lg-4 text-lg-end">
								<a href="schedule.php" class="btn btn-soft-secondary btn-sm me-1"><i class="uil uil-arrow-left"></i> Back to calendar</a>
								<?php if ($isScheduleAdmin): ?>
								<a href="<?php echo htmlspecialchars($manageHref, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-soft-info btn-sm">Manage template</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>

				<?php
				$scheduleBookingLayout = 'page';
				include __DIR__ . '/includes/schedule_booking_panel.php';
				?>
			</div>
		</div>

		<footer class="footer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-6">
						<script>document.write(new Date().getFullYear())</script> &copy; Tessera.
					</div>
				</div>
			</div>
		</footer>
	</div>
</div>

<script>
window.SR_SCHEDULE_TRIP = <?php echo $scheduleTripJson; ?>;
</script>
<?php include __DIR__ . '/includes/schedule_booking_footer.php'; ?>
</body>
</html>
