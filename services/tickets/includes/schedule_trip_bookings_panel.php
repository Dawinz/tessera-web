<?php
/**
 * Trip bookings table for schedule_book.php - full-width footer workspace.
 */
if (empty($isPageLayout)) {
	return;
}
?>
<section class="schedule-trip-bookings-fullwidth" aria-labelledby="schedule-trip-bookings-title">
	<div class="card mb-0 schedule-panel-card schedule-panel-card--bookings schedule-panel-card--bookings-full">
		<div class="card-header schedule-trip-bookings-header">
			<div class="schedule-trip-bookings-header__main">
				<div class="schedule-trip-bookings-header__icon" aria-hidden="true">
					<i class="uil uil-ticket"></i>
				</div>
				<div>
					<h5 class="schedule-trip-bookings-header__title mb-1" id="schedule-trip-bookings-title">Bookings, reserved &amp; locks</h5>
					<p class="schedule-trip-bookings-header__sub text-muted mb-0">All tickets, reservations, and seat locks on this departure.</p>
				</div>
			</div>
			<div class="schedule-trip-bookings-header__tools">
				<div class="schedule-trip-bookings-stat" aria-live="polite">
					<span class="schedule-trip-bookings-stat__label">On this trip</span>
					<strong class="schedule-trip-bookings-stat__value" id="schedule-trip-bookings-total">-</strong>
				</div>
				<div class="schedule-trip-bookings-search">
					<label class="visually-hidden" for="schedule_trip_bookings_search">Search bookings</label>
					<div class="input-group input-group-sm">
						<span class="input-group-text"><i class="uil uil-search"></i></span>
						<input type="search" id="schedule_trip_bookings_search" class="form-control" placeholder="Search seat, ticket, passenger..." autocomplete="off">
					</div>
				</div>
			</div>
		</div>
		<div class="card-body p-0 schedule-trip-bookings-body">
			<div class="table-responsive schedule-trip-bookings-table-wrap">
				<table class="table table-hover table-nowrap align-middle mb-0 schedule-trip-bookings-table" id="passenger_info" style="width:100%;">
					<thead class="table-light">
						<tr>
							<th>Seat</th>
							<th>Ticket</th>
							<th>Passenger</th>
							<th>Cost</th>
							<th>View</th>
							<th>Edit</th>
							<th>Reserved</th>
							<th>Clear</th>
							<th>Print</th>
							<th>Unlock</th>
							<th>Transfer</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</section>
