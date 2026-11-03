<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';

include 'connect.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
$username = $_SESSION['username'] ?? '';
include 'page_info_reload.php';

$level = '';
$select2 = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
if ($select2 && mysqli_num_rows($select2) !== 0) {
	while ($get12 = mysqli_fetch_array($select2)) {
		$level = $get12['role'];
	}
}

if ($level !== 'Super Administrator') {
	header('Location: register_luggage.php?' . modified_time('register_luggage'));
	exit;
}

$monthLabel = date('F Y');
$selectedRoute = '';
$routeOptions = '';

if (isset($_GET['route']) && $_GET['route'] !== '') {
	$selectedRoute = base64_decode($_GET['route']);
}

$select12 = mysqli_query($db, "SELECT * FROM assigned_routes WHERE username='$username' ORDER BY preference DESC, id ASC");
if ($select12 && mysqli_num_rows($select12) !== 0) {
	while ($get132 = mysqli_fetch_array($select12)) {
		$routeFullName = $get132['route_full_name'];
		if ($selectedRoute === '') {
			$selectedRoute = $routeFullName;
		}
		$isSelected = ($routeFullName === $selectedRoute) ? ' selected' : '';
		$routeOptions .= '<option' . $isSelected . '>' . htmlspecialchars($routeFullName, ENT_QUOTES, 'UTF-8') . '</option>';
	}
}

$route2 = base64_encode($selectedRoute !== '' ? $selectedRoute : '');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<?php $tessera_page_label = 'Dashboard'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Cargo charges and payment dashboard" name="description" />
	<meta content="Tessera" name="author" />
	<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="css/cargo-dashboard.css?v=20260906">
</head>
<body class="sr-platform-body" data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark">
	<div id="layout-wrapper">
		<?php include 'top_header.php'; ?>

		<div class="main-content">
			<div class="page-content">
				<div class="container-fluid sr-cargo-dashboard">

					<div class="row align-items-end mb-3">
						<div class="col-lg-7">
							<h4 class="mb-1 text-white">Cargo dashboard</h4>
							<p class="text-muted mb-0">Monthly charges and receipts for <?php echo htmlspecialchars($monthLabel, ENT_QUOTES, 'UTF-8'); ?></p>
						</div>
						<div class="col-lg-5 mt-3 mt-lg-0">
							<label class="form-label text-muted mb-1" for="route">Assigned route</label>
							<select name="route" id="route" class="form-select sr-route-filter" data-live-search="true">
								<?php
								echo $routeOptions !== '' ? $routeOptions : '<option value="">No routes assigned</option>';
								?>
							</select>
						</div>
					</div>

					<div class="row g-3 mb-3">
						<div class="col-xl-3 col-sm-6">
							<div class="card sr-kpi-card">
								<div class="card-body">
									<div class="d-flex justify-content-between align-items-start">
										<div class="flex-grow-1">
											<div class="sr-kpi-label">Total monthly charge</div>
											<div id="totol_cash" class="sr-kpi-value mt-2">
												<div class="placeholder-glow"><span class="placeholder col-10"></span></div>
											</div>
											<div class="sr-kpi-meta mt-1">Paid + outstanding this month</div>
										</div>
										<span class="sr-kpi-icon sr-kpi-icon--charge"><i class="uil uil-chart-line"></i></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6">
							<div class="card sr-kpi-card">
								<div class="card-body">
									<div class="d-flex justify-content-between align-items-start">
										<div class="flex-grow-1">
											<div class="sr-kpi-label">Cash paid</div>
											<div id="load_paid_cash" class="sr-kpi-value mt-2">
												<div class="placeholder-glow"><span class="placeholder col-10"></span></div>
											</div>
											<div class="sr-kpi-meta mt-1">Collected this month</div>
										</div>
										<span class="sr-kpi-icon sr-kpi-icon--paid"><i class="uil uil-money-bill"></i></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6">
							<div class="card sr-kpi-card">
								<div class="card-body">
									<div class="d-flex justify-content-between align-items-start">
										<div class="flex-grow-1">
											<div class="sr-kpi-label">To be paid</div>
											<div id="load_to_paid" class="sr-kpi-value mt-2">
												<div class="placeholder-glow"><span class="placeholder col-10"></span></div>
											</div>
											<div class="sr-kpi-meta mt-1">Outstanding balance</div>
										</div>
										<span class="sr-kpi-icon sr-kpi-icon--due"><i class="uil uil-clock"></i></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6">
							<div class="card sr-kpi-card">
								<div class="card-body">
									<div class="d-flex justify-content-between align-items-start">
										<div class="flex-grow-1">
											<div class="sr-kpi-label">Receipts</div>
											<div id="total_number_l" class="sr-kpi-value mt-2">
												<div class="placeholder-glow"><span class="placeholder col-6"></span></div>
											</div>
											<div class="sr-kpi-meta mt-1">Luggage receipts this month</div>
										</div>
										<span class="sr-kpi-icon sr-kpi-icon--receipts"><i class="uil uil-receipt"></i></span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row g-3 mb-3">
						<div class="col-12">
							<div class="card sr-chart-card">
								<div class="card-header">
									<h5 class="card-title mb-0">Assigned routes - monthly charges (TZS)</h5>
									<p class="text-muted mb-0 small mt-1">Paid, outstanding, and total by route for the current month</p>
								</div>
								<div class="card-body sr-chart-host sr-chart-host--tall" id="all_route_monthly">
									<div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:320px;"></span></div>
								</div>
							</div>
						</div>
					</div>

					<div class="row g-3 mb-3">
						<div class="col-lg-6">
							<div class="card sr-chart-card h-100">
								<div class="card-header">
									<h5 class="card-title mb-0">Payment status by month</h5>
									<p class="text-muted mb-0 small mt-1">Paid, to be paid, and total for the selected route</p>
								</div>
								<div class="card-body sr-chart-host" id="mchart">
									<div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:280px;"></span></div>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="card sr-chart-card h-100">
								<div class="card-header">
									<h5 class="card-title mb-0">Daily cargo charges</h5>
									<p class="text-muted mb-0 small mt-1">Last 30 days for the selected route</p>
								</div>
								<div class="card-body sr-chart-host" id="load_daily_inc">
									<div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:280px;"></span></div>
								</div>
							</div>
						</div>
					</div>

					<div class="row g-3">
						<div class="col-12">
							<div class="card sr-chart-card">
								<div class="card-header">
									<h5 class="card-title mb-0">Daily receipts</h5>
									<p class="text-muted mb-0 small mt-1">Receipt count trend for the selected route</p>
								</div>
								<div class="card-body sr-chart-host" id="load_daily_recipt">
									<div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:280px;"></span></div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

			<div id="route_name" class="d-none"><?php echo htmlspecialchars($route2, ENT_QUOTES, 'UTF-8'); ?></div>

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

	<script src="js/vendor/jquery-1.12.4.min.js"></script>
	<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/bootstrap-jquery-bridge.js"></script>
	<script src="assets/libs/metismenujs/metismenujs.min.js"></script>
	<script src="assets/libs/feather-icons/feather.min.js"></script>
	<script src="assets/js/app.js"></script>
	<script src="/cargo/assets/libs/apexcharts/apexcharts.min.js"></script>
	<script src="js/cargo-home.js?v=20261027b"></script>
</body>
</html>
