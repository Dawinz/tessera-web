<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';

$route_day_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$trip_date = isset($_GET['trip_date']) ? mysqli_real_escape_string($db, (string) $_GET['trip_date']) : '';
$bus = isset($_GET['bus']) ? mysqli_real_escape_string($db, (string) $_GET['bus']) : '';

$seats_total = 0;
$tenantWhere = tessera_sql_tenant_eq();

if ($bus !== '') {
	$vehicleQ = mysqli_query($db, "SELECT seats FROM vehicle WHERE name='$bus' AND $tenantWhere LIMIT 1");
	if ($vehicleQ && ($vehicleRow = mysqli_fetch_assoc($vehicleQ))) {
		$seats_total = (int) ($vehicleRow['seats'] ?? 0);
	}
}

$tripWhere = "trip_date='$trip_date' AND bus='$bus' AND $tenantWhere AND delete_book=''";
if ($route_day_id > 0) {
	$tripWhere .= " AND route_day_id='$route_day_id'";
}

$countForStatus = static function ($status) use ($db, $tripWhere) {
	$statusEsc = mysqli_real_escape_string($db, $status);
	$q = mysqli_query($db, "SELECT COUNT(*) AS c FROM booking WHERE $tripWhere AND status='$statusEsc'");
	if ($q && ($row = mysqli_fetch_assoc($q))) {
		return (int) ($row['c'] ?? 0);
	}
	return 0;
};

$reserved = $countForStatus('booked');
$occupied = $countForStatus('complete');
$locked = $countForStatus('locked');
$available = max(0, $seats_total - $reserved - $occupied - $locked);
?>
<div class="schedule-seat-legend" aria-label="Seat status summary">
	<div class="schedule-seat-legend__item">
		<img src="images/armchair2.png" alt="" width="18" height="18">
		<span class="schedule-seat-legend__label">Available</span>
		<strong class="schedule-seat-legend__count"><?php echo $available; ?></strong>
	</div>
	<div class="schedule-seat-legend__item">
		<img src="images/seat_red.png" alt="" width="18" height="18">
		<span class="schedule-seat-legend__label">Occupied</span>
		<strong class="schedule-seat-legend__count"><?php echo $occupied; ?></strong>
	</div>
	<div class="schedule-seat-legend__item">
		<img src="images/bus_yellow.png" alt="" width="18" height="18">
		<span class="schedule-seat-legend__label">Reserved</span>
		<strong class="schedule-seat-legend__count"><?php echo $reserved; ?></strong>
	</div>
	<div class="schedule-seat-legend__item">
		<img src="images/lock.png" alt="" width="18" height="18">
		<span class="schedule-seat-legend__label">Locked</span>
		<strong class="schedule-seat-legend__count"><?php echo $locked; ?></strong>
	</div>
</div>
