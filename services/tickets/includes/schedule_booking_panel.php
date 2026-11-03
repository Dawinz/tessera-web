<?php
/**
 * Shared booking workspace - used on schedule_book.php (full page).
 * Expects: $tomorrowmo (optional), $country (optional), $scheduleBookingLayout = 'page'
 */
if (!isset($tomorrowmo)) {
	date_default_timezone_set('Africa/Dar_es_Salaam');
	$tomorrowmo = date('m/d/Y', strtotime('+1 day'));
}
$scheduleBookingLayout = $scheduleBookingLayout ?? 'page';
$isPageLayout = ($scheduleBookingLayout === 'page');
$seatColClass = $isPageLayout ? 'col-xl-5 col-lg-6' : 'col-lg-5';
$formColClass = $isPageLayout ? 'col-xl-7 col-lg-6' : 'col-lg-7';
?>
<div id="manifest_id" class="display_none"><?php
if (isset($_GET['manifest_id'])) {
	echo base64_decode((string) $_GET['manifest_id']);
}
?></div>
<div id="manifest_route_day_id" class="display_none"><?php
if (isset($_GET['id'])) {
	echo base64_decode((string) $_GET['id']);
}
?></div>
<div id="manifest_trip_date" class="display_none"><?php
if (isset($_GET['trip_date'])) {
	echo date('m/d/Y', strtotime(base64_decode((string) $_GET['trip_date'])));
}
?></div>
<div id="manifest_route_id" class="display_none"><?php
if (isset($_GET['route_id'])) {
	echo base64_decode((string) $_GET['route_id']);
}
?></div>
<div id="manifest_start_location" class="display_none"><?php
if (isset($_GET['start_location'])) {
	echo base64_decode((string) $_GET['start_location']);
}
?></div>
<div id="manifest_ending_location" class="display_none"><?php
if (isset($_GET['ending_location'])) {
	echo base64_decode((string) $_GET['ending_location']);
}
?></div>
<div id="manifest_seat_no" class="display_none"><?php
if (isset($_GET['seat_no'])) {
	echo base64_decode((string) $_GET['seat_no']);
}
?></div>

<input type="hidden" id="route_id_selected" value="<?php
echo htmlspecialchars(
	isset($scheduleRouteIdSelected) ? (string) $scheduleRouteIdSelected : (isset($_GET['route_id']) ? base64_decode((string) $_GET['route_id']) : ''),
	ENT_QUOTES,
	'UTF-8'
);
?>">
<input type="hidden" id="starting_date" value="<?php echo htmlspecialchars($tomorrowmo, ENT_QUOTES, 'UTF-8'); ?>">

<div id="load_route_content" class="d-none" aria-hidden="true"></div>

<audio id="pop" preload="none" class="sr-sr-only" aria-hidden="true"></audio>
<div id="total_price_ticket" class="display_none">0</div>

<div class="schedule-booking-panel schedule-booking-panel--<?php echo $isPageLayout ? 'page' : 'drawer'; ?>">
	<div class="row g-4">
		<div class="<?php echo $seatColClass; ?> schedule-booking-stack">
			<div class="card mb-0 schedule-panel-card schedule-panel-card--seats">
				<div class="card-header py-3">
					<h6 class="mb-0">Seat chart</h6>
				</div>
				<div class="card-body text-center p-3">
					<div style="display:none;" class="spinner-border text-primary m-1 route_selection_spiner" role="status">
						<span class="visually-hidden">Loading...</span>
					</div>
					<div id="load_seat_chart" class="schedule-seat-chart mb-0">
						<div class="py-4 px-2">
							<div class="mb-3">
								<img src="images/no-task3.png" alt="" width="72">
							</div>
							<h6 class="text-uppercase text-muted">Loading seats...</h6>
							<p class="text-muted mb-0 small">The seat map will appear here.</p>
						</div>
					</div>
				</div>
				<?php if ($isPageLayout): ?>
				<div class="card-footer py-2 px-3 schedule-seat-legend-wrap" id="load_status" aria-live="polite"></div>
				<?php endif; ?>
			</div>
		</div>
		<div class="<?php echo $formColClass; ?>">
			<div class="card mb-0 h-100 schedule-panel-card schedule-panel-card--passengers">
				<div class="card-header schedule-passenger-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
					<div>
						<h6 class="mb-0">Passenger details</h6>
						<p class="text-muted small mb-0 mt-1">Enter details for each selected seat before payment.</p>
					</div>
					<div class="schedule-passenger-header-toggle">
						<span class="schedule-passenger-header-toggle__label">Copy first passenger</span>
						<div class="schedule-passenger-header-toggle__switch square-switch mb-0">
							<input type="checkbox" id="square-switch3" switch="bool" class="copy_seat_info" aria-label="Copy first passenger">
							<label for="square-switch3" data-on-label="On" data-off-label="Off" class="mb-0" aria-hidden="true"></label>
						</div>
					</div>
				</div>
				<div class="card-body p-3 p-lg-4">
					<div id="contact_holder" style="display:none;"><?php
					$code = '';
					if (isset($db)) {
						$select14 = mysqli_query($db, 'SELECT DISTINCT code FROM contact_code ORDER BY code ASC');
						if ($select14) {
							while ($get134 = mysqli_fetch_array($select14)) {
								$code .= '<option value="' . $get134['code'] . '">' . $get134['code'] . '</option>';
							}
						}
					}
					echo $code;
					?></div>
					<div id="phone_dial_codes_holder" class="d-none" aria-hidden="true"><?php
					if ($isPageLayout && isset($db)) {
						$phoneDialOptions = '';
						$dialQuery = mysqli_query(
							$db,
							"SELECT phonecode, MIN(nicename) AS nicename
							FROM country
							WHERE phonecode > 0
							GROUP BY phonecode
							ORDER BY
								CASE phonecode
									WHEN 255 THEN 0 WHEN 243 THEN 1 WHEN 256 THEN 2
									WHEN 260 THEN 3 WHEN 27 THEN 4 WHEN 254 THEN 5
									ELSE 100
								END,
								nicename ASC"
						);
						if ($dialQuery) {
							while ($dialRow = mysqli_fetch_assoc($dialQuery)) {
								$dialVal = '+' . (int) $dialRow['phonecode'];
								$dialLabel = $dialVal . ' - ' . $dialRow['nicename'];
								$phoneDialOptions .= '<option value="' . htmlspecialchars($dialVal, ENT_QUOTES, 'UTF-8') . '">'
									. htmlspecialchars($dialLabel, ENT_QUOTES, 'UTF-8') . '</option>';
							}
						}
						echo $phoneDialOptions;
					}
					?></div>

					<?php if ($isPageLayout): ?>
					<div id="schedule-passenger-tabs-wrap" class="schedule-passenger-nav d-none mb-3">
						<div class="schedule-passenger-nav__top">
							<div class="schedule-passenger-nav__identity">
								<span class="schedule-passenger-nav__badge" id="schedule-active-seat-badge"> - </span>
								<div class="schedule-passenger-nav__meta">
									<p class="schedule-passenger-nav__count mb-0" id="schedule-passenger-count"></p>
									<p class="schedule-passenger-nav__position mb-0" id="schedule-passenger-position"></p>
								</div>
							</div>
							<div class="schedule-passenger-nav__controls">
								<label class="visually-hidden" for="schedule-passenger-jump">Jump to seat</label>
								<select id="schedule-passenger-jump" class="form-select form-select-sm schedule-passenger-jump d-none" aria-label="Jump to seat"></select>
								<div class="btn-group btn-group-sm" role="group" aria-label="Switch passenger">
									<button type="button" class="btn btn-outline-secondary" id="schedule-passenger-prev" aria-label="Previous passenger"><i class="uil uil-angle-left"></i></button>
									<button type="button" class="btn btn-outline-secondary" id="schedule-passenger-next" aria-label="Next passenger"><i class="uil uil-angle-right"></i></button>
								</div>
							</div>
						</div>
						<div class="schedule-passenger-tabs-scroll" id="schedule-passenger-pills-wrap">
							<div class="nav nav-pills schedule-passenger-tabs flex-nowrap" id="schedule-passenger-tabs" role="tablist"></div>
						</div>
					</div>
					<div class="row g-2 mb-3 schedule-route-pickers schedule-route-pickers--page">
						<div class="col-md-6">
							<label class="form-label small mb-1" for="trip_start">Boarding point</label>
							<select class="js-example-basic-single picker_select form-select" id="trip_start"></select>
						</div>
						<div class="col-md-6">
							<label class="form-label small mb-1" for="trip_end">Alighting point</label>
							<select class="js-example-basic-single picker_select form-select" id="trip_end"></select>
						</div>
					</div>
					<?php else: ?>
					<div class="row g-2 mb-3">
						<div class="col-md-6">
							<label class="form-label small mb-1" for="trip_start">Boarding point</label>
							<select class="js-example-basic-single picker_select form-select" id="trip_start"></select>
						</div>
						<div class="col-md-6">
							<label class="form-label small mb-1" for="trip_end">Alighting point</label>
							<select class="js-example-basic-single picker_select form-select" id="trip_end"></select>
						</div>
					</div>
					<?php endif; ?>

					<form method="post" id="insert_form" class="schedule-passenger-form">
						<span id="error"></span>
						<?php if ($isPageLayout): ?>
						<div id="schedule-passenger-empty" class="schedule-passenger-empty">
							<div class="schedule-passenger-empty__icon">
								<i class="uil uil-user-plus"></i>
							</div>
							<p class="mb-0">Select seats on the left to add passenger details here.</p>
						</div>
						<?php endif; ?>
						<div class="<?php echo $isPageLayout ? 'schedule-passenger-list' : 'table-responsive schedule-passenger-table-wrap'; ?>">
							<table class="table <?php echo $isPageLayout ? 'schedule-passenger-table' : 'table-bordered table-nowrap'; ?> align-middle mb-3" id="item_table">
								<thead class="<?php echo $isPageLayout ? 'visually-hidden' : 'table-light'; ?>">
								<tr>
									<th>First name</th>
									<th>Surname</th>
									<th>Mobile</th>
									<th>Gender</th>
									<th>Nationality</th>
									<th>Id type</th>
									<th>Id number</th>
									<th></th>
									<th class="display_none">advance</th>
									<th class="display_none">focinfo</th>
									<th class="display_none">tran info</th>
									<th class="display_none">start</th>
									<th class="display_none">end</th>
									<th class="display_none">start</th>
									<th class="display_none">end</th>
									<th class="display_none">route_day_id</th>
									<th class="display_none">trip_date</th>
									<th class="display_none">route_id</th>
									<th class="display_none">Seat_id</th>
									<th class="display_none">adnc</th>
								</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="schedule-passenger-form__actions">
							<p class="schedule-passenger-form__hint text-muted small mb-0">Name and mobile are required for every selected seat.</p>
							<button type="submit" name="submit" id="submit_button" class="btn btn-primary schedule-passenger-form__submit">
								<span id="loader_button">Continue to payment</span>
								<i class="uil uil-arrow-right ms-1"></i>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php if ($isPageLayout) {
		include __DIR__ . '/schedule_trip_bookings_panel.php';
	} ?>
</div>

<div class="display_none country"><?php echo isset($country) ? $country : ''; ?></div>
