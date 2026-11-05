<?php
/**
 * FullCalendar feed - expand route_day weekday templates into concrete trip events.
 */
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
require_once __DIR__ . '/includes/tessera_schedule_events.php';

header('Content-Type: application/json; charset=utf-8');

$events = tessera_schedule_build_events($db, [
	'start' => (string) ($_GET['start'] ?? ''),
	'end' => (string) ($_GET['end'] ?? ''),
	'starting_location' => trim((string) ($_GET['starting_location'] ?? '')),
	'ending_location' => trim((string) ($_GET['ending_location'] ?? '')),
	'username' => (string) ($_SESSION['username'] ?? ''),
]);

echo json_encode($events);
