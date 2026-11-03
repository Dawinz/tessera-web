<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Daily Expenses'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">
<?php
include 'includes/top_header.php';

$transdate = date('m-d-Y', time());
$current_month = date("F");
$name_daily_year = '';
$query_account = "SELECT name FROM daily_year ORDER BY ID DESC ";
$result_account = mysqli_query($db, $query_account);
while ($row_account = mysqli_fetch_array($result_account)) {
  $name_daily_year .= '<option value="' . htmlspecialchars($row_account["name"], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row_account["name"], ENT_QUOTES, 'UTF-8') . '</option>';
}

$cat_group = '';
$query = "SELECT front_name,name FROM  particular_category  ";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_array($result)) {
  $cat_group .= '<option value="' . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . '</option>';
}

$select_first = mysqli_query($db, "SELECT fullname,name FROM route order by id asc limit 1");
$fullname_first = '';
$select_num_first = mysqli_num_rows($select_first);
if ($select_num_first != 0) {
  while ($get_first = mysqli_fetch_array($select_first)) {
    $fullname_first = $get_first['fullname'];
  }
}

$route_full_name_first = '';
$query = "SELECT id,open_date FROM cash WHERE route_name ='$fullname_first' order by id desc";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_array($result)) {
  $route_full_name_first .= '<option value="' . htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row["open_date"], ENT_QUOTES, 'UTF-8') . '</option>';
}

$route_full_name = '';
$first_route = '';
$query = "SELECT route_name FROM assign_route where username='" . mysqli_real_escape_string($db, $username) . "' ORDER BY id ASC";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_array($result)) {
  $rn = (string) $row['route_name'];
  if ($first_route === '') {
    $first_route = $rn;
  }
  $sel = ($first_route === $rn) ? ' selected' : '';
  $route_full_name .= '<option value="' . htmlspecialchars($rn, ENT_QUOTES, 'UTF-8') . '"' . $sel . '>' . htmlspecialchars($rn, ENT_QUOTES, 'UTF-8') . '</option>';
}
include 'modal/daily_exp.php';
?>
<span id="month" style="display:none;"><?php echo htmlspecialchars($current_month, ENT_QUOTES, 'UTF-8'); ?></span>

<?php include 'canvas/canvas_daily.php'; ?>
<div id="srOffcanvasMount"></div>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid sr-accounts-page">
      <div class="sr-content-card">
        <div class="sr-month-nav" id="navComplex" role="tablist" aria-label="Months">
          <?php
          $select_month = mysqli_query($db, "SELECT name FROM   months    ");
          $select_num_month = mysqli_num_rows($select_month);
          if ($select_num_month != 0) {
            while ($get_month = mysqli_fetch_array($select_month)) {
              $month_name = $get_month['name'];
              ?>
              <a href="#azTab12" class="tab-link <?php if ($current_month == $month_name) { echo "active"; } ?>"><?php echo htmlspecialchars($month_name, ENT_QUOTES, 'UTF-8'); ?></a>
              <?php
            }
          }
          ?>
        </div>

        <div class="sr-filter-bar sr-filter-bar-horizontal mb-3">
          <select class="form-select current_route select2" id="current_route" style="width:260px;" aria-label="Route">
            <?php echo $route_full_name; ?>
          </select>
          <button class="btn btn-indigo btn-with-icon add_exp" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="bx bx-plus"></i> Add Expenses
          </button>
        </div>

        <div id="load_daily_account"></div>
      </div>
    </div>
  </div>
</div>

<span style="display:none;" id="route_name_update"><?php echo isset($_GET['route_name']) ? htmlspecialchars($_GET['route_name'], ENT_QUOTES, 'UTF-8') : ''; ?></span>
<span style="display:none;" id="id_update"><?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') : ''; ?></span>
<span style="display:none;" id="date_update"><?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date'], ENT_QUOTES, 'UTF-8') : ''; ?></span>

<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/jquery.cookie.js" type="text/javascript"></script>
<script src="/accounts/js/template/addon/currency_multiple.js"></script>
<script src="/accounts/js/template/check_out_jobcard.js"></script>

<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
<script src="/accounts/js/template/update/update_daily.js"></script>
<script src="/accounts/js/template/reload/daily_load.js"></script>
<script>
$(document).ready(function () {
  var month = $("#month").text() || "<?php echo htmlspecialchars($current_month, ENT_QUOTES, 'UTF-8'); ?>";
  var year = new Date().getFullYear();
  var route = $("#current_route").val() || "<?php echo htmlspecialchars($first_route ?? '', ENT_QUOTES, 'UTF-8'); ?>";
  if (route && !$("#current_route").val()) {
    $("#current_route").val(route).trigger('change.select2');
  }

  $('.select2').select2({ width: 'style' });

  (function () {
    var oc = document.getElementById('offcanvasTop');
    var mount = document.getElementById('srOffcanvasMount');
    if (oc && mount && oc.parentElement !== mount) {
      oc.classList.remove('show');
      mount.appendChild(oc);
    }
    if (window.srCleanupOffcanvasBackdrop) {
      window.srCleanupOffcanvasBackdrop();
    }
  })();

  $(".add_exp").click(function () {
    $("#route_name").val($("#current_route").val());
  });

  // Initial load (tab/route changes handled by daily_load.js)
  $.ajax({
    url: 'search/daily.php',
    method: 'POST',
    data: { month: month, year: year, route: route },
    beforeSend: function () {
      $("#load_daily_account").html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading</span></div></div>');
    },
    success: function (data) {
      if (window.srMountAjaxHtml) {
        window.srMountAjaxHtml('load_daily_account', data);
      } else {
        $("#load_daily_account").html(data);
      }
    },
    dataType: 'text'
  });
});
</script>
</body>
</html>
