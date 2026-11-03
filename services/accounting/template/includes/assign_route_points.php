<?php
/**
 * Load boarding points for a main route.
 * Prefer the user's assign_route rows; if none, fall back to any assigned points
 * for that main_route, then to route.start_point / end_point.
 *
 * @return list<array{row_num:int|string,point:string,id:string,main_route:string,route_name:string,end_point:string}>
 */
function tessera_assign_route_points(mysqli $db, string $username, string $mainRoute, string $pointFilterSql = ''): array
{
	$uEsc = mysqli_real_escape_string($db, $username);
	$rEsc = mysqli_real_escape_string($db, $mainRoute);
	$points = [];

	$q = "SELECT ROW_NUMBER() OVER (ORDER BY id ASC) row_num, point, id, main_route, route_name, end_point
		FROM assign_route WHERE username='$uEsc' AND main_route='$rEsc' $pointFilterSql ORDER BY id ASC";
	$res = mysqli_query($db, $q);
	if ($res) {
		while ($row = mysqli_fetch_assoc($res)) {
			$points[] = $row;
		}
	}
	if ($points) {
		return $points;
	}

	// Fallback: points other staff already use on this main route
	$q = "SELECT ROW_NUMBER() OVER (ORDER BY MIN(id) ASC) row_num, point, MIN(id) AS id, main_route, route_name, end_point
		FROM assign_route WHERE main_route='$rEsc' $pointFilterSql
		GROUP BY point, main_route, route_name, end_point
		ORDER BY MIN(id) ASC";
	$res = mysqli_query($db, $q);
	if ($res) {
		while ($row = mysqli_fetch_assoc($res)) {
			$points[] = $row;
		}
	}
	if ($points) {
		return $points;
	}

	// Last resort: route table start/end
	$res = mysqli_query($db, "SELECT start_point, end_point, fullname FROM route WHERE fullname='$rEsc' LIMIT 1");
	if ($res && ($route = mysqli_fetch_assoc($res))) {
		$start = trim((string) ($route['start_point'] ?? ''));
		$end = trim((string) ($route['end_point'] ?? ''));
		if ($start !== '') {
			$points[] = [
				'row_num' => '1',
				'point' => $start,
				'id' => '0',
				'main_route' => $mainRoute,
				'route_name' => $mainRoute,
				'end_point' => $end,
			];
		}
		if ($end !== '' && $end !== $start) {
			$points[] = [
				'row_num' => (string) (count($points) + 1),
				'point' => $end,
				'id' => '0',
				'main_route' => $mainRoute,
				'route_name' => $mainRoute,
				'end_point' => $start,
			];
		}
	}

	return $points;
}
