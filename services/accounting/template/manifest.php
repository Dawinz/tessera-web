<?php
require_once __DIR__ . '/includes/auth_bootstrap.php';
require_once __DIR__ . '/dependence/all.php';

$username_selected = $username;
$db_cargo = cargo_db();
$vehicle_name = '';
$vehicle_category = new Vehicles();
$vehicle_category->set_details($vehicle_name, $db_cargo);

$route_query = '';
$routes_array = array();
$select = mysqli_query($db, "SELECT route_name FROM assign_route where username ='" . mysqli_real_escape_string($db, $username_selected) . "' and status='main route'  ");
if ($select && mysqli_num_rows($select) != 0) {
	while ($get = mysqli_fetch_array($select)) {
		$routes_array[] = "'" . $get['route_name'] . "' ";
	}
}
$selected_route = implode(', ', $routes_array);
// No assign_route rows ⇒ show all manifests (empty SQL fragment). Never use route_name='' (matches nothing).
$route_query = $selected_route !== '' ? "route_name IN ($selected_route) and " : '';

$manifest_stats = ['count' => 0, 'income' => 0, 'expenses' => 0, 'balance' => 0];
$stats_where = $selected_route !== '' ? 'WHERE route_name IN (' . $selected_route . ')' : '';
$stats_result = mysqli_query($db, 'SELECT COUNT(*) AS cnt, COALESCE(SUM(income),0) AS income, COALESCE(SUM(expenses),0) AS expenses, COALESCE(SUM(balance),0) AS balance FROM manifest ' . $stats_where);
if ($stats_result && ($stats_row = mysqli_fetch_assoc($stats_result))) {
	$manifest_stats = [
		'count' => (int) ($stats_row['cnt'] ?? 0),
		'income' => (int) ($stats_row['income'] ?? 0),
		'expenses' => (int) ($stats_row['expenses'] ?? 0),
		'balance' => (int) ($stats_row['balance'] ?? 0),
	];
}
if (!function_exists('sr_accounts_format_tsh')) {
	function sr_accounts_format_tsh($amount) {
		$amount = (int) $amount;
		if ($amount >= 1000000) {
			return 'Tsh ' . number_format($amount / 1000000, 1) . 'M';
		}
		if ($amount >= 1000) {
			return 'Tsh ' . number_format($amount / 1000, 0) . 'K';
		}
		return 'Tsh ' . number_format($amount);
	}
}

// Route options for Add / Update Manifest modals (canvas + modal rely on these).
$route_full_name = '';
$select_route_num = 0;
$routeResult = mysqli_query($db, 'SELECT fullname, name FROM route ORDER BY fullname ASC');
if ($routeResult) {
	$select_route_num = (int) mysqli_num_rows($routeResult);
	while ($row = mysqli_fetch_array($routeResult)) {
		$fullname = (string) ($row['fullname'] ?? '');
		$label = (string) ($row['name'] ?? '');
		$decoded = base64_decode($label, true);
		if ($decoded !== false && $decoded !== '' && preg_match('/^[\w\s.\/\-]+$/u', $decoded)) {
			$label = $decoded;
		}
		if ($label === '') {
			$label = $fullname;
		}
		$route_full_name .= '<option value="' . htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8') . '">'
			. htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</option>';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Manifest'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
    <style>
.error_class { border: 1px solid red; }
td.highlight { font-weight: bold; color: #b42318; }
td.highlight_normal { font-weight: bold; color: #1b2834; }

.select2-dropdown { z-index: 10060 !important; }
.select2-selection__rendered { line-height: 31px !important; }
.select2-container .select2-selection--single {
  height: 36px !important;
  border-radius: 0.25rem;
}
.select2-selection__arrow { height: 34px !important; }

.sr-manifest-page { padding: 0 1.25rem 1.5rem !important; }
.sr-manifest-page .sr-content-card {
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 0.5rem;
  padding: 1.25rem;
}
.sr-manifest-page .sr-filter-bar {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  gap: .65rem;
  align-items: center;
  margin-bottom: 1rem;
  position: relative;
  z-index: 2;
  overflow-x: auto;
}
.sr-manifest-page .sr-filter-bar .form-control,
.sr-manifest-page .sr-filter-bar .form-select { min-width: 150px; flex: 0 0 auto; }
.sr-manifest-page .sr-filter-bar #vehicle_name + .select2-container {
  min-width: 200px !important;
  width: 200px !important;
  flex: 0 0 auto !important;
}
.sr-manifest-page .sr-filter-bar .dt-buttons {
  display: inline-flex;
  flex-wrap: nowrap;
  gap: .35rem;
  margin: 0 !important;
  flex: 0 0 auto;
}
.sr-manifest-page .sr-filter-search { margin-left: auto; }
.sr-manifest-page .cname_route {
  font-size: .8125rem;
  color: #6c757d;
  text-transform: uppercase;
  letter-spacing: .04em;
  margin-bottom: .75rem;
}
.sr-manifest-page #example1_wrapper {
  position: relative;
  z-index: 1;
  clear: both;
}
.sr-manifest-page table.dataTable thead th,
.sr-manifest-page table.table thead th {
  white-space: nowrap;
  vertical-align: middle;
}
.sr-act-btn {
  display: inline-flex !important;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  padding: 0 !important;
  line-height: 1;
}
.sr-act-btn i { font-size: 16px; line-height: 1; }
.sr-manifest-page .btn-sm.sr-act-btn,
.sr-manifest-page .btn.btn-sm {
  min-width: 32px;
  min-height: 32px;
  padding: 0.35rem 0.5rem;
}
.sr-manifest-page .btn.btn-sm i {
  font-size: 15px;
  vertical-align: middle;
}
.offcanvas-backdrop:not(.show) { display: none !important; }
    </style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">

<?php
include 'includes/top_header.php';
include 'canvas/manifest.php';
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid sr-manifest-page sr-manifest-workspace">
      <div class="row sr-manifest-layout">
        <div class="col-lg-3 col-xl-2 sr-manifest-sidebar-col">
          <div class="sr-side-panel">
            <div class="sr-side-panel-body">
            <button class="btn btn-indigo btn-with-icon" data-bs-toggle="modal" data-bs-target="#modaldemo1">
              <i class="bx bx-plus"></i> Add Manifest
            </button>
            <label class="d-block mt-3 mb-2">Select Route Option</label>
            <div class="sr-side-panel-routes">
            <button class="btn btn-indigo change_r first_b" id="<?php echo htmlspecialchars($route_query, ENT_COMPAT, 'UTF-8'); ?>" data=""><i class="mdi mdi-view-grid-outline"></i><span>All Routes</span></button>
            <?php
            $select_route_name = mysqli_query($db, "SELECT route_name,route FROM assign_route where username ='" . mysqli_real_escape_string($db, $username_selected) . "' and status='main route'  ");
            if ($select_route_name && mysqli_num_rows($select_route_name) != 0) {
            	while ($get_route_name = mysqli_fetch_array($select_route_name)) {
            		$route_name = $get_route_name['route_name'];
            		$route_name_int = $get_route_name['route'];
            		$route_label = base64_decode($route_name_int);
            		if ($route_label === false || $route_label === '') {
            			$route_label = $route_name;
            		}
            		?>
            		<button class="btn btn-dark change_r first_b" id="route_name='<?php echo $route_name; ?>' and" data="<?php echo htmlspecialchars($route_name, ENT_QUOTES, 'UTF-8'); ?>"><i class="mdi mdi-bus-marker"></i><span><?php echo htmlspecialchars($route_label, ENT_QUOTES, 'UTF-8'); ?></span></button>
            		<?php
            	}
            }
            ?>
            </div>
            </div>
            <div class="sr-side-panel-summary">
              <div class="sr-summary-card">
                <div class="sr-side-panel-summary-title">
                  <span class="sr-summary-title-icon"><i class="mdi mdi-chart-box-outline"></i></span>
                  <span>Quick Summary</span>
                </div>

                <div class="sr-summary-feature">
                  <span class="sr-summary-feature-label">Total Manifest</span>
                  <span class="sr-summary-feature-value" id="srSummaryCount"><?php echo number_format($manifest_stats['count']); ?></span>
                </div>

                <div class="sr-summary-stats">
                  <div class="sr-summary-stat sr-summary-stat-income">
                    <div class="sr-summary-stat-icon"><i class="mdi mdi-trending-up"></i></div>
                    <div class="sr-summary-stat-copy">
                      <span class="sr-summary-stat-label">Total Income</span>
                      <span class="sr-summary-stat-value" id="srSummaryIncome"><?php echo htmlspecialchars(sr_accounts_format_tsh($manifest_stats['income']), ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                  </div>
                  <div class="sr-summary-stat sr-summary-stat-expense">
                    <div class="sr-summary-stat-icon"><i class="mdi mdi-trending-down"></i></div>
                    <div class="sr-summary-stat-copy">
                      <span class="sr-summary-stat-label">Total Expenses</span>
                      <span class="sr-summary-stat-value" id="srSummaryExpenses"><?php echo htmlspecialchars(sr_accounts_format_tsh($manifest_stats['expenses']), ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                  </div>
                  <div class="sr-summary-stat sr-summary-stat-balance">
                    <div class="sr-summary-stat-icon"><i class="mdi mdi-wallet-outline"></i></div>
                    <div class="sr-summary-stat-copy">
                      <span class="sr-summary-stat-label">Total Balance</span>
                      <span class="sr-summary-stat-value" id="srSummaryBalance"><?php echo htmlspecialchars(sr_accounts_format_tsh($manifest_stats['balance']), ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-9 col-xl-10 sr-manifest-main-col">
          <div class="sr-content-card sr-table-panel">
            <div class="cname_route">All Routes</div>
            <?php include 'modal/add_route.php'; ?>

            <div class="sr-filter-bar sr-filter-bar-horizontal" id="manifestToolbar">
              <select class="form-select select_2" id="vehicle_name" aria-label="Vehicle">
                <option value="">All Vehicle</option>
                <?php $vehicle_category->get_details(); ?>
              </select>
              <input type="text" class="form-control fc-datepicker" id="start_date" placeholder="Start Date" autocomplete="off">
              <input type="text" class="form-control fc-datepicker" id="end_date" placeholder="End Date" autocomplete="off">
              <div class="sr-filter-search ms-auto">
                <input type="search" class="sr-table-search form-control" id="manifest_search" placeholder="Search..." autocomplete="off">
              </div>
            </div>

            <div class="table-responsive">
              <table id="example1" class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th data-orderable="false">Code</th>
                    <th>Route</th>
                    <th>Vehicle</th>
                    <th>Trip Date</th>
                    <th>Income</th>
                    <th>Expenses</th>
                    <th>Balance</th>
                    <th>Insert</th>
                    <th>View</th>
                    <th>Update</th>
                    <th>Delete</th>
                    <th>Lock</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<input id="route_query" type="hidden" value="<?php echo htmlspecialchars($route_query, ENT_COMPAT, 'UTF-8'); ?>">

<div class="modal fade" id="lockmodal" tabindex="-1" aria-labelledby="lockmodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="lockmodalLabel">Lock manifest</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-1">Vehicle <strong id="bus_manifest"></strong></p>
        <p>Trip date <strong id="trip_date_manifest"></strong></p>
        <label class="form-label" for="locked_manifest">Password</label>
        <input type="password" class="form-control" id="locked_manifest" autocomplete="off">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-warning" id="lock_save"><span id="lock_save_text">Save changes</span></button>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
<script src="/accounts/js/template/insert/manifest.js<?php echo $url_time; ?>"></script>
<script src="/accounts/js/template/update/update_manifest.js<?php echo $url_time; ?>"></script>
<script src="/accounts/js/template/insert/lock.js"></script>
<script src="/accounts/js/template/dependence/manifest_dependency.js"></script>
<script src="/accounts/js/template/dependence/manifest_trip_date.js"></script>
<script src="/accounts/js/template/dependence/manifest_dependency_update.js"></script>
<script src="/accounts/js/template/dependence/manifest_trip_date_update.js<?php echo $url_time; ?>"></script>
<script src="/accounts/js/template/fetch/fetch_manifest.js<?php echo $url_time; ?>"></script>
<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
<script>
  $(document).ready(function () {
    $('.offcanvas-backdrop').remove();
    $('body').removeClass('modal-open').css({ overflow: '', paddingRight: '' });

    $('.select_2').select2({ width: 'style' });
    if (typeof window.srInitAccountsDatepickers === 'function') {
      window.srInitAccountsDatepickers(document);
    }

    document.querySelectorAll('.offcanvas').forEach(function (el) {
      el.addEventListener('hidden.bs.offcanvas', function () {
        $('.offcanvas-backdrop').remove();
        $('body').removeClass('modal-open').css({ overflow: '', paddingRight: '' });
      });
    });
  });
</script>
</body>
</html>
