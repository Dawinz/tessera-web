<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
	exit;
}
$username = $_SESSION['username'];
$monthLabel = date('F Y');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<?php $tessera_page_label = 'Dashboard'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Operator dashboard" name="description" />
	<meta content="Tessera" name="author" />
<?php include 'include_scripts.php'; ?>
	<link rel="stylesheet" href="css/home-dashboard.css?v=20260905">
</head>
<body data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark">
	<div id="layout-wrapper">
		<?php include 'top_header.php'; ?>

		<?php
		$connect = $db;
		$routeOptions = '';
		$query = "SELECT route_name FROM assigned_routes WHERE username='$username' AND route_name != '' ORDER BY route_name ASC";
		$result = mysqli_query($connect, $query);
		while ($result && ($row = mysqli_fetch_array($result))) {
			$routeOptions .= '<option value="' . htmlspecialchars($row['route_name'], ENT_QUOTES, 'UTF-8') . '">'
				. htmlspecialchars($row['route_name'], ENT_QUOTES, 'UTF-8') . '</option>';
		}
		if ($routeOptions === '' && !empty($level) && $level === 'Super Administrator') {
			$rq = mysqli_query($connect, "SELECT DISTINCT route_name FROM assigned_routes WHERE route_name != '' ORDER BY route_name ASC LIMIT 50");
			while ($rq && ($row = mysqli_fetch_array($rq))) {
				$routeOptions .= '<option value="' . htmlspecialchars($row['route_name'], ENT_QUOTES, 'UTF-8') . '">'
					. htmlspecialchars($row['route_name'], ENT_QUOTES, 'UTF-8') . '</option>';
			}
		}
		$allRoutesOption = '';
		if (!empty($level) && $level === 'Super Administrator') {
			$allRoutesOption = '<option value="__all__">All routes (company-wide)</option>';
		}
		?>

		<div class="main-content">
			<div class="page-content">
				<div class="container-fluid sr-home-dashboard">

					<div class="row align-items-end mb-3">
						<div class="col-lg-7">
							<h4 class="mb-1 text-white">Dashboard</h4>
							<p class="text-muted mb-0">Ticket sales, trips, and revenue for <?php echo htmlspecialchars($monthLabel, ENT_QUOTES, 'UTF-8'); ?></p>
						</div>
						<div class="col-lg-5 mt-3 mt-lg-0">
							<label class="form-label text-muted mb-1" for="route">Route</label>
							<select name="route" id="route" class="form-select sr-route-filter" data-live-search="true">
								<?php
								echo $allRoutesOption;
								echo $routeOptions !== '' ? $routeOptions : '<option value="">No routes assigned</option>';
								?>
							</select>
						</div>
					</div>

					<div id="sparkline_charts" class="d-none" aria-hidden="true"></div>

					<div class="row g-3 mb-3">
						<div class="col-xl-3 col-sm-6">
							<div class="card sr-kpi-card">
								<div class="card-body">
									<div class="d-flex justify-content-between align-items-start">
										<div class="flex-grow-1">
											<div class="sr-kpi-label">Ticket sales</div>
											<div id="load_total_sales" class="sr-kpi-value mt-2">
												<div class="placeholder-glow"><span class="placeholder col-10"></span></div>
											</div>
											<div class="sr-kpi-meta mt-1">This month - route filter above</div>
										</div>
										<span class="sr-kpi-icon sr-kpi-icon--sales"><i class="uil uil-chart-line"></i></span>
									</div>
									<div class="apex-charts mt-2" id="sparkline-chart-1"></div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6">
							<div class="card sr-kpi-card">
								<div class="card-body">
									<div class="d-flex justify-content-between align-items-start">
										<div class="flex-grow-1">
											<div class="sr-kpi-label">Seats booked</div>
											<div id="load_total_sales_number" class="sr-kpi-value mt-2">
												<div class="placeholder-glow"><span class="placeholder col-8"></span></div>
											</div>
											<div class="sr-kpi-meta mt-1">Paid &amp; confirmed seats</div>
										</div>
										<span class="sr-kpi-icon sr-kpi-icon--seats"><i class="uil uil-users-alt"></i></span>
									</div>
									<div class="apex-charts mt-2" id="sparkline-chart-2"></div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6">
							<div class="card sr-kpi-card">
								<div class="card-body">
									<div class="d-flex justify-content-between align-items-start">
										<div class="flex-grow-1">
											<div class="sr-kpi-label">Trips run</div>
											<div id="load_total_trips" class="sr-kpi-value mt-2">
												<div class="placeholder-glow"><span class="placeholder col-6"></span></div>
											</div>
											<div class="sr-kpi-meta mt-1">Manifest trips this month</div>
										</div>
										<span class="sr-kpi-icon sr-kpi-icon--trips"><i class="uil uil-bus"></i></span>
									</div>
									<div class="apex-charts mt-2" id="sparkline-chart-3"></div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6">
							<div class="card sr-kpi-card">
								<div class="card-body">
									<div class="d-flex justify-content-between align-items-start">
										<div class="flex-grow-1">
											<div class="sr-kpi-label">Free of charge</div>
											<div id="load_foc" class="sr-kpi-value mt-2">
												<div class="placeholder-glow"><span class="placeholder col-6"></span></div>
											</div>
											<div class="sr-kpi-meta mt-1">Complimentary seats</div>
										</div>
										<span class="sr-kpi-icon sr-kpi-icon--foc"><i class="uil uil-gift"></i></span>
									</div>
									<div class="apex-charts mt-2" id="sparkline-chart-4"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="row g-3 mb-3">
						<div class="col-12">
							<div class="card sr-chart-card">
								<div class="card-header">
									<h5 class="card-title mb-0">Yearly ticket sales</h5>
								</div>
								<div class="card-body sr-chart-host" id="yearly_charts">
									<div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:340px;"></span></div>
								</div>
							</div>
						</div>
					</div>

					<div class="row g-3 mb-3">
						<div class="col-lg-6">
							<div class="card sr-chart-card h-100">
								<div class="card-header">
									<h5 class="card-title mb-0">Sales by route</h5>
								</div>
								<div class="card-body sr-chart-host" id="location_areas">
									<div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:300px;"></span></div>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="card sr-chart-card h-100">
								<div class="card-header">
									<h5 class="card-title mb-0">Income per bus</h5>
								</div>
								<div class="card-body sr-chart-host" id="load_bus_income">
									<div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:300px;"></span></div>
								</div>
							</div>
						</div>
					</div>

					<div class="row g-3">
						<div class="col-12">
							<div class="card sr-chart-card">
								<div class="card-header">
									<h5 class="card-title mb-0">Daily ticket sales</h5>
								</div>
								<div class="card-body sr-chart-host" id="daily_sales">
									<div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:340px;"></span></div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-6">
							<script>document.write(new Date().getFullYear())</script> &copy; Tessera.
						</div>
						<div class="col-sm-6">
							<div class="text-sm-end d-none d-sm-block">
								Crafted with <i class="mdi mdi-heart text-danger"></i> by Tessera
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
	<script src="/tickets/assets/libs/apexcharts/apexcharts.min.js"></script>
	<script src="load_home_route.js?v=20261027b"></script>
</body>
</html>
