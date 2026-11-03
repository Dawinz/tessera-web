<?php
/**
 * Resolve Home dashboard route filter (single route vs all routes).
 *
 * @return array{all:bool,route_id:string,route_name:string,sql_route:string,sql_route_plain:string,sql_tenant:string}
 */
require_once __DIR__ . '/tessera_tenant.php';

function tessera_home_route_scope(mysqli $db, string $routeRaw): array {
	$route = trim(preg_replace('/_/', ' ', (string) $routeRaw));
	$all = ($route === '' || $route === '__all__' || strcasecmp($route, 'all routes') === 0);
	$route_id = '';

	if (!$all && $route !== '') {
		$esc = $db->real_escape_string($route);
		$q = @$db->query("SELECT route_id FROM assigned_routes WHERE route_name='{$esc}' LIMIT 1");
		if ($q && ($row = $q->fetch_assoc()) && !empty($row['route_id'])) {
			$route_id = (string) $row['route_id'];
		} else {
			$q2 = @$db->query(
				"SELECT route_id FROM routes
				 WHERE route_name='{$esc}' OR CONCAT(start_route,' to ',end_route)='{$esc}'
				 LIMIT 1"
			);
			if ($q2 && ($row2 = $q2->fetch_assoc()) && !empty($row2['route_id'])) {
				$route_id = (string) $row2['route_id'];
			}
		}
	}

	$sql_route = '';
	$sql_route_plain = '';
	if ($route_id !== '') {
		$rid = $db->real_escape_string($route_id);
		$sql_route = "route_id='{$rid}' AND ";
		$sql_route_plain = "route_id='{$rid}'";
	}

	return [
		'all' => $all,
		'route_id' => $route_id,
		'route_name' => $route,
		'sql_route' => $sql_route,
		'sql_route_plain' => $sql_route_plain,
		'sql_tenant' => tessera_sql_tenant_eq() . ' AND ',
	];
}
