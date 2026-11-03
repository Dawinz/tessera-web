<?php
/**
 * Legacy staff booking URL - redirects to Schedule subpages (query string preserved).
 */
$query = $_SERVER['QUERY_STRING'] ?? '';
$bookKeys = ['route_day_id', 'id', 'manifest_id', 'trip_date', 'route_id', 'start_location', 'ending_location', 'seat_no'];
$toBook = false;
foreach ($bookKeys as $key) {
	if (isset($_GET[$key]) && (string) $_GET[$key] !== '') {
		$toBook = true;
		break;
	}
}
$base = $toBook ? 'schedule_book.php' : 'schedule.php';
$target = $base . ($query !== '' ? '?' . $query : '');
header('Location: ' . $target, true, 302);
exit;
