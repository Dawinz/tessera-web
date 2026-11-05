<?php
require_once __DIR__ . '/includes/schedule_boot.php';
require_once __DIR__ . '/includes/schedule_trip_from_request.php';

if (!$isScheduleAdmin) {
	if (!empty($GLOBALS['tessera_operator_spa_embed'])) {
		require __DIR__ . '/schedule.php';
		return;
	}
	header('Location: /tickets/schedule.php', true, 302);
	exit;
}

$scheduleTrip = schedule_trip_from_request($db);
if ($scheduleTrip === null) {
	if (!empty($GLOBALS['tessera_operator_spa_embed'])) {
		require __DIR__ . '/schedule.php';
		return;
	}
	header('Location: /tickets/schedule.php', true, 302);
	exit;
}

$bookQuery = http_build_query([
	'route_day_id' => $scheduleTrip['route_day_id'],
	'trip_date' => $scheduleTrip['trip_date'],
	'route_id' => $scheduleTrip['route_id'],
]);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<?php $tessera_page_label = 'Manage trip'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include 'include_scripts.php'; ?>
	<link rel="stylesheet" href="assets/css/schedule.css">
</head>
<body data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark">

<div id="layout-wrapper">
	<?php include 'top_header.php'; ?>

	<div class="main-content">
		<div class="page-content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
							<div>
								<h4 class="mb-1">Manage trip template</h4>
								<p class="text-muted mb-0 small">Weekday template, bus assignment, and schedule status.</p>
							</div>
							<div class="page-title-right">
								<ol class="breadcrumb m-0">
									<li class="breadcrumb-item"><a href="schedule.php">Schedule</a></li>
									<li class="breadcrumb-item"><a href="schedule_book.php?<?php echo htmlspecialchars($bookQuery, ENT_QUOTES, 'UTF-8'); ?>">Book trip</a></li>
									<li class="breadcrumb-item active">Manage</li>
								</ol>
							</div>
						</div>
					</div>
				</div>

				<div class="row g-3">
					<div class="col-lg-8">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title mb-0"><?php echo htmlspecialchars($scheduleTrip['route_name'], ENT_QUOTES, 'UTF-8'); ?></h5>
							</div>
							<div class="card-body">
								<dl class="row mb-0 schedule-manage-dl">
									<dt class="col-sm-4">Trip date</dt>
									<dd class="col-sm-8"><?php echo htmlspecialchars($scheduleTrip['trip_date_display'] . ' - ' . $scheduleTrip['weekday'], ENT_QUOTES, 'UTF-8'); ?></dd>
									<dt class="col-sm-4">Bus</dt>
									<dd class="col-sm-8" id="admin-bus"><?php echo htmlspecialchars($scheduleTrip['bus'], ENT_QUOTES, 'UTF-8'); ?></dd>
									<dt class="col-sm-4">Weekday template</dt>
									<dd class="col-sm-8" id="admin-weekday"><?php echo htmlspecialchars($scheduleTrip['weekday'], ENT_QUOTES, 'UTF-8'); ?></dd>
									<dt class="col-sm-4">Departure</dt>
									<dd class="col-sm-8" id="admin-departure"><?php echo htmlspecialchars($scheduleTrip['departure'] ?: ' - ', ENT_QUOTES, 'UTF-8'); ?></dd>
									<dt class="col-sm-4">Arrival</dt>
									<dd class="col-sm-8" id="admin-arrival"><?php echo htmlspecialchars($scheduleTrip['arrival'] ?: ' - ', ENT_QUOTES, 'UTF-8'); ?></dd>
									<dt class="col-sm-4">Status</dt>
									<dd class="col-sm-8" id="admin-status"><?php echo htmlspecialchars($scheduleTrip['status'] ?: ' - ', ENT_QUOTES, 'UTF-8'); ?></dd>
									<dt class="col-sm-4">Seats booked</dt>
									<dd class="col-sm-8" id="admin-seats"><?php echo (int) $scheduleTrip['seats_booked']; ?> / <?php echo (int) $scheduleTrip['seats_total']; ?></dd>
									<dt class="col-sm-4">Route</dt>
									<dd class="col-sm-8" id="admin-route-name"><?php echo htmlspecialchars($scheduleTrip['route_name'], ENT_QUOTES, 'UTF-8'); ?></dd>
								</dl>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title mb-0">Actions</h5>
							</div>
							<div class="card-body d-grid gap-2">
								<button type="button" class="btn btn-soft-warning" id="schedule-toggle-status" data-id="<?php echo (int) $scheduleTrip['route_day_id']; ?>">Toggle active status</button>
								<button type="button" class="btn btn-soft-danger" id="schedule-delete-trip" data-id="<?php echo (int) $scheduleTrip['route_day_id']; ?>">Delete trip template</button>
								<a href="register_trip.php" class="btn btn-soft-info">Open trip registration</a>
								<button type="button" class="btn btn-soft-success" id="schedule-add-trip-btn">Add another template</button>
								<a href="schedule_book.php?<?php echo htmlspecialchars($bookQuery, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary">Book seats for this date</a>
								<a href="schedule.php" class="btn btn-light">Back to calendar</a>
							</div>
						</div>
					</div>
				</div>
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

<script src="assets/libs/metismenujs/metismenujs.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/js/app.js"></script>
<script>
window.SR_SCHEDULE = { isAdmin: true, role: <?php echo json_encode($userRole); ?> };
</script>
<script src="assets/js/schedule_book_trip.js?v=<?php echo filemtime(__DIR__ . '/assets/js/schedule_book_trip.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include 'modal/route_trip.php'; ?>
<script src="js/insert/route_trip.js"></script>
<script src="assets/js/bootstrap-jquery-bridge.js"></script>
<script>
$(function () {
	$('#schedule-add-trip-btn').on('click', function () {
		$('#myModal').modal('show');
	});
});
</script>
</body>
</html>
