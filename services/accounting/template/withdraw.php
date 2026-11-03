<?php
require_once __DIR__ . '/includes/auth_bootstrap.php';

$route_full_name = '';
$query = "SELECT fullname, name FROM route WHERE status='main' ORDER BY id ASC";
$result = mysqli_query($db, $query);
if ($result) {
	while ($row = mysqli_fetch_array($result)) {
		$label = base64_decode((string) $row['name']);
		if ($label === false || $label === '') {
			$label = (string) $row['fullname'];
		}
		$route_full_name .= '<option value="' . htmlspecialchars($row['fullname'], ENT_QUOTES, 'UTF-8') . '">'
			. htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</option>';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Withdraw'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
    <style>
.error_class { border: 1px solid red; }
.select2-dropdown { z-index: 10060 !important; }
.sr-withdraw-page .sr-side-panel {
  background: #03314d;
  border-radius: 0.5rem;
  padding: 1.25rem;
  color: rgba(255,255,255,.88);
}
.sr-withdraw-page .sr-side-panel label {
  color: rgba(255,255,255,.65);
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: .04em;
  margin-bottom: .5rem;
}
.sr-withdraw-page .sr-side-panel .btn { margin-bottom: .5rem; width: 100%; }
.sr-withdraw-page .sr-side-panel .btn-indigo {
  background: #5b73e8; border-color: #5b73e8; color: #fff;
}
.sr-withdraw-page .sr-side-panel .btn-dark {
  background: rgba(255,255,255,.08);
  border-color: rgba(255,255,255,.12);
  color: #fff;
}
.sr-withdraw-page .sr-content-card {
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 0.5rem;
  padding: 1.25rem;
}
.sr-withdraw-page .cname_route {
  font-weight: 600;
  color: #495057;
  margin-bottom: 1rem;
}
.sr-withdraw-page table.dataTable thead th {
  background: #f5f6f8 !important;
  color: #495057 !important;
  white-space: nowrap;
}
    </style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" data-layout-scrollable="true" class="sr-platform-body">

<?php include 'includes/top_header.php'; ?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid sr-accounts-page sr-withdraw-page">
      <div class="row">
        <div class="col-lg-3 col-xl-2 mb-3">
          <div class="sr-side-panel">
            <button class="btn btn-indigo btn-with-icon" data-bs-toggle="modal" data-bs-target="#modaldemo1">
              <i class="bx bx-plus"></i> Add Cash
            </button>
            <label class="d-block mt-3">Select Route Option</label>
            <button class="btn btn-indigo change_r first_b" id="" data="">All Routes</button>
            <?php
            $select = mysqli_query($db, "SELECT fullname,name FROM route WHERE status='main'");
            if ($select && mysqli_num_rows($select) != 0) {
            	while ($get = mysqli_fetch_array($select)) {
            		$route_name = $get['fullname'];
            		$route_name_int = $get['name'];
            		$label = base64_decode((string) $route_name_int);
            		if ($label === false || $label === '') {
            			$label = (string) $route_name;
            		}
            		?>
            		<button class="btn btn-dark change_r first_b" id="route_name='<?php echo htmlspecialchars($route_name, ENT_QUOTES, 'UTF-8'); ?>' and" data="<?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></button>
            		<?php
            	}
            }
            ?>
          </div>
        </div>

        <div class="col-lg-9 col-xl-10">
          <div class="sr-content-card">
            <div class="cname_route">All Routes</div>
            <?php include 'modal/add_cash.php'; ?>

            <div class="table-responsive">
              <table id="example1" class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th data-orderable="false">Authorizer</th>
                    <th>Effective Date</th>
                    <th>Cash</th>
                    <th>V no</th>
                    <th>Balance</th>
                    <th>Date insert</th>
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

<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
<script src="/accounts/js/template/insert/cash.js<?php echo $url_time; ?>"></script>
<script src="/accounts/js/template/fetch/fetch_cash.js<?php echo $url_time; ?>"></script>
<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
<script>
  $(document).ready(function () {
    if (typeof window.srInitAccountsDatepickers === 'function') {
      window.srInitAccountsDatepickers(document);
    }
  });
</script>
</body>
</html>
