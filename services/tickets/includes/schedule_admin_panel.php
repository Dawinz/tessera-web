<?php
/** Admin trip management panel in Schedule drawer. */
?>
<div id="schedule-admin-panel" class="d-none">
	<div class="card">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-2">Trip template</h6>
			<dl class="row mb-3 small">
				<dt class="col-sm-4">Route</dt>
				<dd class="col-sm-8" id="admin-route-name">-</dd>
				<dt class="col-sm-4">Bus</dt>
				<dd class="col-sm-8" id="admin-bus">-</dd>
				<dt class="col-sm-4">Weekday</dt>
				<dd class="col-sm-8" id="admin-weekday">-</dd>
				<dt class="col-sm-4">Departure</dt>
				<dd class="col-sm-8" id="admin-departure">-</dd>
				<dt class="col-sm-4">Arrival</dt>
				<dd class="col-sm-8" id="admin-arrival">-</dd>
				<dt class="col-sm-4">Status</dt>
				<dd class="col-sm-8" id="admin-status">-</dd>
				<dt class="col-sm-4">Seats</dt>
				<dd class="col-sm-8" id="admin-seats">-</dd>
			</dl>
			<div class="d-flex flex-wrap gap-2">
				<button type="button" class="btn btn-soft-warning btn-sm" id="schedule-toggle-status">Toggle active</button>
				<button type="button" class="btn btn-soft-danger btn-sm" id="schedule-delete-trip">Delete template</button>
				<a href="register_trip.php" class="btn btn-soft-info btn-sm" id="schedule-edit-trip-link">Full trip admin</a>
				<button type="button" class="btn btn-soft-success btn-sm" id="schedule-add-trip-btn">Add trip template</button>
			</div>
		</div>
	</div>
</div>
