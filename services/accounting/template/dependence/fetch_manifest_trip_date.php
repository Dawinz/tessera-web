<?php
$route_name = $_POST['route_name'] ?? ($_POST['choose_route'] ?? '');

include __DIR__ . '/../connect/connect_cargo.php';
$output = '';
$routeEsc = mysqli_real_escape_string($db_up, (string) $route_name);
// ONLY_FULL_GROUP_BY: order by the grouped column (not vehicle_name.id).
$query = "SELECT `date` FROM vehicle_name WHERE Route ='$routeEsc' GROUP BY `date` ORDER BY `date` DESC";
$result = mysqli_query($db_up, $query);

$output .= '<option value=" ">Choose trip date</option>';

if ($result) {
	while ($row = mysqli_fetch_array($result)) {
		$output .= '<option value="' . htmlspecialchars((string) $row['date'], ENT_QUOTES, 'UTF-8') . '">'
			. htmlspecialchars(date('l, d-m-Y', strtotime((string) $row['date'])), ENT_QUOTES, 'UTF-8')
			. '</option>';
	}
}
echo $output;
?>