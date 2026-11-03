<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
include 'config2.php';

$draw = isset($_POST['draw']) ? (int) $_POST['draw'] : 0;
$row = isset($_POST['start']) ? (int) $_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? (int) $_POST['length'] : 10;
if ($rowperpage < 1) {
	$rowperpage = 10;
}
$searchValue = isset($_POST['search']['value']) ? (string) $_POST['search']['value'] : '';
$route = isset($_POST['route']) ? (string) $_POST['route'] : '';
$vecihle = isset($_POST['vecihle']) ? (string) $_POST['vecihle'] : '';
$start_date = isset($_POST['start_date']) ? (string) $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? (string) $_POST['end_date'] : '';

if ($start_date !== '') {
	$start_date = "trip_date >= '$start_date' and";
}
if ($end_date !== '') {
	$end_date = "trip_date <= '$end_date' and";
}

$searchArray = [];
$searchQuery = ' ';
if ($searchValue !== '') {
	$searchQuery = ' AND (trip_date LIKE :trip_date OR bus LIKE :bus OR start_location LIKE :start_location OR end_location LIKE :end_location) ';
	$searchArray = [
		'trip_date'       => "%$searchValue%",
		'bus'             => "%$searchValue%",
		'start_location'  => "%$searchValue%",
		'end_location'    => "%$searchValue%",
	];
}

$filterWhere = " $route $vecihle $start_date $end_date 1 " . $searchQuery;

$stmt = $conn->prepare('SELECT COUNT(*) AS allcount FROM manifest');
$stmt->execute();
$totalRecords = (int) $stmt->fetchColumn();

$stmt = $conn->prepare('SELECT COUNT(*) AS allcount FROM manifest WHERE ' . $filterWhere);
$stmt->execute($searchArray);
$totalRecordwithFilter = (int) $stmt->fetchColumn();

$summary = ['trips' => 0, 'buses' => 0, 'seats' => 0, 'sales' => 0.0, 'road' => 0.0];
$stmt = $conn->prepare(
	'SELECT COUNT(*) AS trips, COUNT(DISTINCT bus) AS buses, COALESCE(SUM(seats),0) AS seats,
	        COALESCE(SUM(amount),0) AS sales, COALESCE(SUM(road_collection),0) AS road
	 FROM manifest WHERE ' . $filterWhere
);
$stmt->execute($searchArray);
if ($rowSummary = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$summary = [
		'trips' => (int) $rowSummary['trips'],
		'buses' => (int) $rowSummary['buses'],
		'seats' => (int) $rowSummary['seats'],
		'sales' => (float) $rowSummary['sales'],
		'road'  => (float) $rowSummary['road'],
	];
}

$stmt = $conn->prepare(
	'SELECT * FROM manifest WHERE ' . $filterWhere . ' ORDER BY trip_date DESC LIMIT :limit,:offset'
);
foreach ($searchArray as $key => $search) {
	$stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $row, PDO::PARAM_INT);
$stmt->bindValue(':offset', $rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

function manifest_trip_date_html($tripDate, $startTime, DateTimeImmutable $today)
{
	$date = DateTimeImmutable::createFromFormat('!Y-m-d', (string) $tripDate, $today->getTimezone());
	if (!$date) {
		return htmlspecialchars((string) $tripDate);
	}
	$diff = (int) $today->diff($date)->format('%r%a');
	$abs = abs($diff);
	if ($diff === 0) {
		$relative = 'Today';
	} elseif ($diff === 1) {
		$relative = 'Tomorrow';
	} elseif ($diff === -1) {
		$relative = 'Yesterday';
	} else {
		if ($abs < 14) {
			$amount = $abs;
			$unit = 'day';
		} elseif ($abs < 60) {
			$amount = (int) floor($abs / 7);
			$unit = 'week';
		} elseif ($abs < 365) {
			$amount = (int) floor($abs / 30);
			$unit = 'month';
		} else {
			$amount = (int) floor($abs / 365);
			$unit = 'year';
		}
		$span = $amount . ' ' . $unit . ($amount === 1 ? '' : 's');
		$relative = $diff > 0 ? 'in ' . $span : $span . ' ago';
	}
	$state = $diff === 0 ? 'is-today' : ($diff > 0 ? 'is-upcoming' : 'is-past');
	$details = [];
	if (trim((string) $startTime) !== '') {
		$details[] = '<i class="mdi mdi-clock-outline"></i> ' . htmlspecialchars(trim($startTime));
	}
	$details[] = '<span class="manifest-date-rel ' . $state . '">' . $relative . '</span>';
	return '<strong>' . $date->format('D, j M Y') . '</strong><div class="booking-table-sub">' . implode(' - ', $details) . '</div>';
}

$today = new DateTimeImmutable('today', new DateTimeZone('Africa/Dar_es_Salaam'));
$data = [];
$remarks = ['On time', 'Delayed departure', 'Fuel top-up on route', 'Full load', 'Cargo pending delivery', 'Checked by supervisor', ''];

foreach ($empRecords as $manifestRow) {
	$busName = (string) ($manifestRow['buss'] ?? $manifestRow['bus'] ?? '');
	$ticketAmount = (float) ($manifestRow['ammount'] ?? $manifestRow['amount'] ?? 0);
	$roadCollection = (float) ($manifestRow['road_collection'] ?? 0);
	$routeId = $manifestRow['rout_id'] ?? $manifestRow['route_id'] ?? '';
	$routeLabel = trim((string) $manifestRow['start_location'] . ' to ' . (string) $manifestRow['end_location']);

	$cargoQuery = http_build_query([
		'date'      => $manifestRow['trip_date'],
		'date2'     => $manifestRow['trip_date'],
		'date3'     => $busName,
		'open'      => 'open',
		'route'     => base64_encode($routeLabel),
		'driver'    => base64_encode((string) $manifestRow['driver']),
		'Conductor' => base64_encode((string) $manifestRow['conductor']),
		'code'      => base64_encode((string) $manifestRow['id']),
	]);
	$cargoHref = htmlspecialchars(tessera_module_url('cargo', 'vehicle_report_new.php?' . $cargoQuery), ENT_QUOTES, 'UTF-8');

	$viewUrl = 'routs.php?id=' . base64_encode($manifestRow['route_day_id'])
		. '&trip_date=' . base64_encode($manifestRow['trip_date'])
		. '&route_id=' . base64_encode($routeId)
		. '&start_location=' . base64_encode($manifestRow['start_location'])
		. '&ending_location=' . base64_encode($manifestRow['end_location'])
		. '&manifest_id=' . base64_encode($manifestRow['id'])
		. '&buss=' . base64_encode($busName);

	$options = '
			<div class="dropdown manifest-options">
				<button type="button" class="btn btn-soft-primary btn-sm manifest-options-toggle" data-bs-toggle="dropdown" aria-expanded="false">
					Options <i class="mdi mdi-chevron-down"></i>
				</button>
				<ul class="dropdown-menu dropdown-menu-end">
					<li><a class="dropdown-item" href="pdf/index.php?id=' . (int) $manifestRow['route_day_id'] . '&trip_date=' . htmlspecialchars($manifestRow['trip_date'], ENT_QUOTES) . '&manifest=' . (int) $manifestRow['id'] . '"><i class="mdi mdi-file-pdf-box text-primary"></i> Manifest PDF</a></li>
					<li><a class="dropdown-item" href="sales_summary.php?id=' . (int) $manifestRow['route_day_id'] . '&trip_date=' . htmlspecialchars($manifestRow['trip_date'], ENT_QUOTES) . '&manifest=' . (int) $manifestRow['id'] . '"><i class="mdi mdi-file-chart-outline text-info"></i> Sales summary</a></li>
					<li><a class="dropdown-item" href="' . htmlspecialchars($viewUrl, ENT_QUOTES) . '"><i class="mdi mdi-eye-outline text-primary"></i> View</a></li>
					<li><a class="dropdown-item" href="' . $cargoHref . '"><i class="mdi mdi-package-variant text-secondary"></i> Cargo</a></li>
					<li><a class="dropdown-item" href="print_pos.php?id=' . (int) $manifestRow['route_day_id'] . '&trip_date=' . htmlspecialchars($manifestRow['trip_date'], ENT_QUOTES) . '"><i class="mdi mdi-printer-pos text-warning"></i> POS / Manifest</a></li>
					<li><button type="button" class="dropdown-item manifest-change" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
						data-id="' . htmlspecialchars((string) $manifestRow['id'], ENT_QUOTES) . '"
						data-route-day-id="' . htmlspecialchars((string) $manifestRow['route_day_id'], ENT_QUOTES) . '"
						data-trip-date="' . htmlspecialchars((string) $manifestRow['trip_date'], ENT_QUOTES) . '"
						data-buss="' . htmlspecialchars($busName, ENT_QUOTES) . '"
						data-driver="' . htmlspecialchars((string) $manifestRow['driver'], ENT_QUOTES) . '"
						data-conductor="' . htmlspecialchars((string) $manifestRow['conductor'], ENT_QUOTES) . '"><i class="mdi mdi-pencil-outline text-success"></i> Change</button></li>
					<li><hr class="dropdown-divider"></li>
					<li><button type="button" class="dropdown-item text-danger manifest-delete" data-id="' . htmlspecialchars((string) $manifestRow['id'], ENT_QUOTES) . '"><i class="mdi mdi-cancel"></i> Delete</button></li>
				</ul>
			</div>';

	$remark = $remarks[(int) $manifestRow['id'] % count($remarks)];

	$data[] = [
		'options'           => $options,
		'remark'            => $remark !== ''
			? '<span class="manifest-remark" title="' . htmlspecialchars($remark, ENT_QUOTES) . '"><i class="mdi mdi-flag manifest-remark-flag"></i> ' . htmlspecialchars($remark) . '</span>'
			: '<span class="text-muted"> - </span>',
		'trip_date_display' => manifest_trip_date_html($manifestRow['trip_date'], $manifestRow['start_time'] ?? '', $today),
		'trip_date'         => $manifestRow['trip_date'],
		'id'                => $manifestRow['id'],
		'buss'              => $busName,
		'bus'               => $busName,
		'route_name'        => htmlspecialchars($manifestRow['start_location'] . ' -> ' . $manifestRow['end_location']),
		'insert_date'       => $manifestRow['insert_date'],
		'driver'            => $manifestRow['driver'],
		'conductor'         => $manifestRow['conductor'],
		'route_day_id'      => $manifestRow['route_day_id'],
		'date_word'         => $manifestRow['date_word'],
		'sort'              => $manifestRow['sort'],
		'seats'             => $manifestRow['seats'],
		'ammount'           => number_format($ticketAmount, 2),
		'amount'            => number_format($ticketAmount, 2),
		't_amount'          => number_format($ticketAmount + $roadCollection, 2),
		'road_collection'   => number_format($roadCollection, 2),
		'end_location'      => $manifestRow['end_location'],
		'start_location'    => $manifestRow['start_location'],
		'status'            => $manifestRow['status'] ?? '',
		'canceled'          => $manifestRow['canceled'],
		'start_time'        => $manifestRow['start_time'],
		'cargo'             => '<a href="' . $cargoHref . '" title="View cargo"><button type="button" class="btn btn-soft-secondary btn-sm"><i class="uil uil-box"></i></button></a>',
	];
}

$response = [
	'draw'                 => $draw,
	'recordsTotal'         => $totalRecords,
	'recordsFiltered'      => $totalRecordwithFilter,
	'data'                 => $data,
	'iTotalRecords'        => $totalRecords,
	'iTotalDisplayRecords' => $totalRecordwithFilter,
	'aaData'               => $data,
	'summary'              => $summary,
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
