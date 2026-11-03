<?php
/**
 * Map import route-manager queries onto Tessera routes / sub_route (acronym keys).
 */
require_once __DIR__ . '/tessera_tenant.php';

if (!function_exists('tessera_rm_company_id')) {
	function tessera_rm_company_id(): int {
		return 1;
	}
}

if (!function_exists('tessera_rm_route_key_sql')) {
	/** SQL expression: stable varchar route key (import route_id). */
	function tessera_rm_route_key_sql(string $alias = 'r'): string {
		$alias = preg_replace('/[^a-zA-Z0-9_]/', '', $alias) ?: 'r';
		return "COALESCE(NULLIF({$alias}.route_acronym,''), CONCAT('R', {$alias}.id))";
	}
}

if (!function_exists('tessera_rm_sub_route_key_sql')) {
	function tessera_rm_sub_route_key_sql(string $alias = 's'): string {
		$alias = preg_replace('/[^a-zA-Z0-9_]/', '', $alias) ?: 's';
		return "COALESCE(NULLIF({$alias}.route_id,''), CONCAT('SR', {$alias}.id))";
	}
}

if (!function_exists('tessera_rm_tenant_id')) {
	function tessera_rm_tenant_id(): int {
		return (int) tessera_current_tenant_id();
	}
}

if (!function_exists('tessera_rm_find_route')) {
	/**
	 * @return array{route_id:string,route_name:string,start_route:string,end_route:string}|null
	 */
	function tessera_rm_find_route(PDO $pdo, string $route_id, int $company_id = 1): ?array {
		$route_id = trim($route_id);
		if ($route_id !== '') {
			$key = tessera_rm_route_key_sql('r');
			$sql = "SELECT {$key} AS route_id, r.route_name, r.start_route, r.end_route
				FROM routes r
				WHERE ({$key} = :route_id OR CAST(r.route_id AS CHAR) = :route_id_alt)
				  AND r.tenant_id = :tenant_id
				LIMIT 1";
			$stmt = $pdo->prepare($sql);
			$stmt->execute([
				'route_id'     => $route_id,
				'route_id_alt' => $route_id,
				'tenant_id'    => tessera_rm_tenant_id(),
			]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row) {
				return $row;
			}

			$subKey = tessera_rm_sub_route_key_sql('s');
			$subSql = "SELECT {$subKey} AS route_id, s.route_name,
					s.starting_point AS start_route, s.ending_point AS end_route
				FROM sub_route s
				INNER JOIN routes r ON r.id = s.main_route_no
				WHERE ({$subKey} = :route_id OR s.route_id = :route_id_alt)
				  AND r.tenant_id = :tenant_id
				LIMIT 1";
			$sub = $pdo->prepare($subSql);
			$sub->execute([
				'route_id'     => $route_id,
				'route_id_alt' => $route_id,
				'tenant_id'    => tessera_rm_tenant_id(),
			]);
			$subRow = $sub->fetch(PDO::FETCH_ASSOC);
			if ($subRow) {
				return $subRow;
			}
		}

		return tessera_rm_first_route($pdo, $company_id);
	}
}

if (!function_exists('tessera_rm_first_route')) {
	function tessera_rm_first_route(PDO $pdo, int $company_id = 1): ?array {
		$key = tessera_rm_route_key_sql('r');
		$sql = "SELECT {$key} AS route_id, r.route_name, r.start_route, r.end_route
			FROM routes r
			WHERE r.tenant_id = :tenant_id
			  AND (r.route_type = 'Main Route' OR r.route_type IS NULL OR r.route_type = '')
			ORDER BY r.id ASC
			LIMIT 1";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['tenant_id' => tessera_rm_tenant_id()]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ?: null;
	}
}
