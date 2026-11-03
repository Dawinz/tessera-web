<?php
require_once __DIR__ . '/includes/schedule_boot.php';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<?php $tessera_page_label = 'Schedule'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="assets/libs/fullcalendar/main.min.css" rel="stylesheet" type="text/css" />
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
								<h4 class="mb-1">Schedule</h4>
								<p class="text-muted mb-0 small">Browse departures on the calendar, then open a trip to book seats.</p>
							</div>
							<div class="page-title-right">
								<ol class="breadcrumb m-0">
									<li class="breadcrumb-item"><a href="javascript:void(0);">Apps</a></li>
									<li class="breadcrumb-item active">Schedule</li>
								</ol>
							</div>
						</div>
					</div>
				</div>

				<div class="card mb-3">
					<div class="card-body py-3">
						<div class="row g-3 align-items-end">
							<div class="col-md-4">
								<label class="form-label mb-1" for="starting_location">Starting location</label>
								<select class="form-select selectpicker" id="starting_location">
									<?php echo $start_route_first ?? ''; ?>
									<?php echo $start_route2 ?? ''; ?>
								</select>
							</div>
							<div class="col-md-4">
								<label class="form-label mb-1" for="ending_location">Ending location</label>
								<select class="form-select selectpicker" id="ending_location">
									<option>Choose Ending Point</option>
									<?php echo $start_route2 ?? ''; ?>
								</select>
							</div>
							<div class="col-md-4 text-md-end">
								<?php if ($isScheduleAdmin): ?>
								<button type="button" class="btn btn-soft-success btn-sm" id="schedule-add-trip-top">
									<i class="uil uil-plus"></i> Add trip template
								</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>

				<div class="card schedule-calendar-card">
					<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
						<h5 class="card-title mb-0">Departure calendar</h5>
						<span class="text-muted small">Click any trip to open the booking page</span>
					</div>
					<div class="card-body">
						<div id="schedule-calendar"></div>
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
window.SR_SCHEDULE = {
	isAdmin: <?php echo $isScheduleAdmin ? 'true' : 'false'; ?>,
	role: <?php echo json_encode($userRole); ?>
};
</script>
<script src="assets/libs/fullcalendar/main.min.js"></script>
<script src="assets/js/pages/schedule.init.js?v=<?php echo filemtime(__DIR__ . '/assets/js/pages/schedule.init.js'); ?>"></script>
<script src="assets/js/schedule_book_trip.js?v=<?php echo filemtime(__DIR__ . '/assets/js/schedule_book_trip.js'); ?>"></script>
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
<?php if ($isScheduleAdmin): ?>
<?php include 'modal/route_trip.php'; ?>
<script src="js/insert/route_trip.js"></script>
<?php endif; ?>
<script src="assets/js/bootstrap-jquery-bridge.js"></script>
<script>
$(function () {
	$('.selectpicker').select2();
	$('#schedule-add-trip-top').on('click', function () {
		var day = $(this).data('prefill-day');
		if (day && $('#register_table .day_of_week').length) {
			$('#register_table .day_of_week').first().val(day);
		}
		$('#myModal').modal('show');
	});
});
</script>
</body>
</html>
