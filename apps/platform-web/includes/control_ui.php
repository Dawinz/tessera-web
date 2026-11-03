<?php
/**
 * Shared Control UI helpers — pagination, KPI payloads, chart head tags.
 */

function control_page_int(string $key = 'page', int $default = 1): int {
	return max(1, (int) ($_GET[$key] ?? $default));
}

/**
 * @return array{items:list,total:int,page:int,per_page:int,pages:int,offset:int}
 */
function control_paginate(array $items, int $page, int $perPage = 15): array {
	$total = count($items);
	$perPage = max(1, $perPage);
	$pages = max(1, (int) ceil($total / $perPage));
	$page = min(max(1, $page), $pages);
	$offset = ($page - 1) * $perPage;
	return [
		'items' => array_slice($items, $offset, $perPage),
		'total' => $total,
		'page' => $page,
		'per_page' => $perPage,
		'pages' => $pages,
		'offset' => $offset,
	];
}

function control_pagination_href(array $query, int $page, string $script = ''): string {
	$query['page'] = $page;
	$qs = http_build_query(array_filter($query, static function ($v) {
		return $v !== '' && $v !== null;
	}));
	$base = $script !== '' ? $script : basename((string) ($_SERVER['PHP_SELF'] ?? 'index.php'));
	return control_h($base . ($qs !== '' ? '?' . $qs : ''));
}

function control_render_pagination(array $pag, array $query = [], string $script = ''): void {
	if ($pag['pages'] <= 1) {
		return;
	}
	$page = (int) $pag['page'];
	$pages = (int) $pag['pages'];
	$prev = max(1, $page - 1);
	$next = min($pages, $page + 1);
	echo '<nav class="control-pagination" aria-label="Page navigation"><ul class="pagination pagination-sm justify-content-end mb-0">';
	echo '<li class="page-item' . ($page <= 1 ? ' disabled' : '') . '"><a class="page-link" href="' . control_pagination_href($query, $prev, $script) . '">Prev</a></li>';
	$start = max(1, $page - 2);
	$end = min($pages, $page + 2);
	for ($i = $start; $i <= $end; $i++) {
		echo '<li class="page-item' . ($i === $page ? ' active' : '') . '"><a class="page-link" href="' . control_pagination_href($query, $i, $script) . '">' . $i . '</a></li>';
	}
	echo '<li class="page-item' . ($page >= $pages ? ' disabled' : '') . '"><a class="page-link" href="' . control_pagination_href($query, $next, $script) . '">Next</a></li>';
	echo '</ul></nav>';
}

/**
 * @return array{active:int,provisioning:int,suspended:int,other:int,total:int}
 */
function control_tenant_status_counts(array $tenants): array {
	$out = ['active' => 0, 'provisioning' => 0, 'suspended' => 0, 'other' => 0, 'total' => count($tenants)];
	foreach ($tenants as $t) {
		$s = (string) ($t['status'] ?? '');
		if (isset($out[$s])) {
			$out[$s]++;
		} else {
			$out['other']++;
		}
	}
	return $out;
}

/**
 * @return array{labels:list<string>,values:list<int>}
 */
function control_status_donut_payload(array $counts, array $keys = ['active', 'provisioning', 'suspended', 'other']): array {
	$labels = [];
	$values = [];
	$labelMap = [
		'active' => 'Active',
		'provisioning' => 'Provisioning',
		'suspended' => 'Suspended',
		'other' => 'Other',
	];
	foreach ($keys as $key) {
		$n = (int) ($counts[$key] ?? 0);
		if ($n <= 0) {
			continue;
		}
		$labels[] = $labelMap[$key] ?? ucfirst($key);
		$values[] = $n;
	}
	return ['labels' => $labels, 'values' => $values];
}

function control_chart_head_tags(string $version = '20260906b'): string {
	return '<script src="/tickets/assets/libs/apexcharts/apexcharts.min.js"></script>';
}

function control_plan_counts(array $tenants): array {
	$counts = [];
	foreach ($tenants as $t) {
		$p = (string) ($t['plan'] ?? 'standard');
		$counts[$p] = ($counts[$p] ?? 0) + 1;
	}
	return $counts;
}

/**
 * @return array{labels:list<string>,values:list<int>}
 */
function control_plan_donut_payload(array $tenants): array {
	$counts = control_plan_counts($tenants);
	$labels = [];
	$values = [];
	foreach ($counts as $plan => $n) {
		if ($n <= 0) {
			continue;
		}
		$labels[] = ucfirst($plan);
		$values[] = (int) $n;
	}
	return ['labels' => $labels, 'values' => $values];
}

function control_health_tone_counts(array $checks): array {
	$counts = ['success' => 0, 'warning' => 0, 'danger' => 0, 'info' => 0];
	foreach ($checks as $c) {
		$t = (string) ($c['tone'] ?? 'info');
		if (isset($counts[$t])) {
			$counts[$t]++;
		}
	}
	return $counts;
}
