<?php
/**
 * Legacy URL - unified Vehicle manager (Seat layout tab).
 */
$query = $_SERVER['QUERY_STRING'] ?? '';
$target = 'vehicle_manager.php';
if ($query !== '') {
	$target .= '?' . $query;
}
$target .= '#layout';
header('Location: ' . $target, true, 302);
exit;
