<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<?php
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now_start = date('m/01/Y', time());
$date_today = date('Y-m-d', time());
$start_date_three_date = date("Y-m-01", strtotime("-3 Month"));
$start_date_six_date = date("Y-m-01", strtotime("-6 Month"));
$start_date_twelve_date = date("Y-01-01");
$new_date_now = date("m/d/Y", strtotime($date_today));
?>
<html lang="en">
  <head>
    <?php $__page_title = 'Vehicle Report'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">
<?php
include 'includes/top_header.php';
include 'dependence/fetch_route_name.php';
?>

<span id="month" style="display:none;"><?php echo isset($current_month) ? htmlspecialchars($current_month, ENT_QUOTES, 'UTF-8') : ''; ?></span>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid sr-report-page sr-accounts-page">
      <div class="sr-content-card">
        <div class="sr-report-toolbar">
          <button type="button" class="btn btn-outline-secondary prev" title="Previous month" aria-label="Previous month">
            <i class="bx bx-chevron-left"></i>
          </button>
          <button type="button" class="btn btn-outline-secondary next" disabled title="Next month" aria-label="Next month">
            <i class="bx bx-chevron-right"></i>
          </button>
          <input value="<?php echo htmlspecialchars($date_now_start, ENT_QUOTES, 'UTF-8'); ?>" class="form-control fc-datepicker" id="start_date" placeholder="Start date" autocomplete="off">
          <input value="<?php echo htmlspecialchars($new_date_now, ENT_QUOTES, 'UTF-8'); ?>" class="form-control fc-datepicker" id="end_date" placeholder="End date" autocomplete="off">
          <select class="form-select select2" id="route_name" style="width:220px;">
            <?php echo $route_name; ?>
          </select>
          <div class="sr-period-group" role="group" aria-label="Period">
            <button type="button" class="btn btn-outline-secondary current_month selected_duration active" data-month="0" disabled>Current</button>
            <button type="button" class="btn btn-outline-secondary 3month selected_duration" data-month="3">3 months</button>
            <button type="button" class="btn btn-outline-secondary 6month selected_duration" data-month="6">6 months</button>
            <button type="button" class="btn btn-outline-secondary year selected_duration" data-month="12">Year</button>
          </div>
        </div>

        <div class="sr-report-header">
          <div>
            <h2>Vehicle Report</h2>
            <div class="sr-report-range">
              <span id="start_date_holder"><?php echo date("jS M Y", strtotime($date_now_start)); ?></span>
              <span aria-hidden="true"> - </span>
              <span id="end_date_holder"><?php echo date("jS M Y", strtotime($date_today)); ?></span>
            </div>
          </div>
        </div>

        <div class="sr-currency-pills">
          <nav class="nav" aria-label="Currency">
            <?php
            include 'connect/connect.php';
            $select_currency_value = mysqli_query($db, "SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num, name,symbol FROM currency  ");
            $select_num_currency_value = mysqli_num_rows($select_currency_value);
            if ($select_num_currency_value != 0) {
              while ($get_currency_value = mysqli_fetch_array($select_currency_value)) {
                $curr_name = $get_currency_value['name'];
                $symbol = $get_currency_value['symbol'];
                $row_num = $get_currency_value['row_num'];
                ?>
                <a class="nav-link currency_selected <?php if ($row_num == 1) { echo "active"; } ?>" href="#" id="<?php echo htmlspecialchars($symbol, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($curr_name, ENT_QUOTES, 'UTF-8'); ?></a>
                <?php
              }
            }
            ?>
          </nav>
        </div>

        <div class="sr-report-actions">
          <?php
          require_once __DIR__ . '/includes/sr_export_actions.php';
          sr_render_export_actions([
            'nav_class' => 'nav',
            'aria_label' => 'Report actions',
            'always_labels' => true,
            'include_search' => true,
          ]);
          ?>
        </div>

        <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search report..." autocomplete="off">

        <div class="sr-report-body" id="load_report"></div>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="selected_currency" value="Tsh">

<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/jquery.cookie.js" type="text/javascript"></script>
<script src="/accounts/js/template/addon/currency_multiple.js"></script>
<script src="/accounts/js/template/check_out_jobcard.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>

<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
<script src="/accounts/js/template/dependence/library.js"></script>

<script>
$(document).ready(function () {
  var selected_currency = $("#selected_currency").val();
  var route_name = $("#route_name").val();
  var start_date = $("#start_date").val();
  var end_date = $("#end_date").val();

  function setPeriodActive($btn) {
    $(".selected_duration").removeClass("active").prop("disabled", false);
    $btn.addClass("active").prop("disabled", true);
  }

  function loadReport(start_date, end_date, selected_currency, route_name) {
    $.ajax({
      url: 'search/vehicle_report.php',
      method: 'POST',
      data: {
        start_date: start_date,
        end_date: end_date,
        selected_currency: selected_currency,
        route_name: route_name
      },
      beforeSend: function () {
        $("#load_report").html('<div class="sr-report-loading"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading</span></div></div>');
      },
      success: function (data) {
        $("#load_report").html(data);
      },
      dataType: 'text'
    });
  }

  loadReport(start_date, end_date, selected_currency, route_name);

  $(".currency_selected").click(function (e) {
    e.preventDefault();
    $(".currency_selected").removeClass("active");
    $(this).addClass("active");
    selected_currency = $(this).text();
    $("#selected_currency").val(selected_currency);
    start_date = $("#start_date").val();
    end_date = $("#end_date").val();
    route_name = $("#route_name").val();
    loadReport(start_date, end_date, selected_currency, route_name);
  });

  $("#start_date, #end_date").on("change", function () {
    start_date = $("#start_date").val();
    end_date = $("#end_date").val();
    route_name = $("#route_name").val();
    $("#start_date_holder").text(moment(start_date).format('Do MMM YYYY'));
    $("#end_date_holder").text(moment(end_date).format('Do MMM YYYY'));
    loadReport(start_date, end_date, selected_currency, route_name);
  });

  $("#route_name").change(function () {
    start_date = $("#start_date").val();
    end_date = $("#end_date").val();
    route_name = $("#route_name").val();
    loadReport(start_date, end_date, selected_currency, route_name);
  });

  $(".prev").click(function () {
    $(".next").attr("disabled", false);
    start_date = $("#start_date").val();
    var date = moment(start_date).format('YYYY-MM-DD');
    var new_date = new Date(date);
    new_date.setMonth(new_date.getMonth() - 1);
    var new_og_st_date = moment(new_date.toISOString().split('T')[0]).format('MM/DD/YYYY');
    var new_og_end_date = moment(new_og_st_date).endOf('month').format('MM/DD/YYYY');
    $("#start_date").val(new_og_st_date);
    $("#end_date").val(new_og_end_date);
    if ($("#start_date")[0]._flatpickr) $("#start_date")[0]._flatpickr.setDate(new_og_st_date, false);
    if ($("#end_date")[0]._flatpickr) $("#end_date")[0]._flatpickr.setDate(new_og_end_date, false);
    $("#start_date_holder").text(moment(new_og_st_date).format('Do MMM YYYY'));
    $("#end_date_holder").text(moment(new_og_end_date).format('Do MMM YYYY'));
    route_name = $("#route_name").val();
    loadReport(new_og_st_date, new_og_end_date, selected_currency, route_name);
  });

  $(".next").click(function () {
    start_date = $("#start_date").val();
    var date = moment(start_date).format('YYYY-MM-DD');
    var new_date = new Date(date);
    new_date.setMonth(new_date.getMonth() + 1);
    var new_og_st_date = moment(new_date.toISOString().split('T')[0]).format('MM/DD/YYYY');
    var new_og_end_date = moment(new_og_st_date).endOf('month').format('MM/DD/YYYY');
    $("#start_date").val(new_og_st_date);
    $("#end_date").val(new_og_end_date);
    if ($("#start_date")[0]._flatpickr) $("#start_date")[0]._flatpickr.setDate(new_og_st_date, false);
    if ($("#end_date")[0]._flatpickr) $("#end_date")[0]._flatpickr.setDate(new_og_end_date, false);
    $("#start_date_holder").text(moment(new_og_st_date).format('Do MMM YYYY'));
    $("#end_date_holder").text(moment(new_og_end_date).format('Do MMM YYYY'));
    route_name = $("#route_name").val();
    loadReport(new_og_st_date, new_og_end_date, selected_currency, route_name);
  });

  $(".current_month").click(function () {
    start_date = "<?php echo $date_now_start; ?>";
    end_date = "<?php echo $new_date_now; ?>";
    setPeriodActive($(this));
    $("#start_date").val(start_date);
    $("#end_date").val(end_date);
    if ($("#start_date")[0]._flatpickr) $("#start_date")[0]._flatpickr.setDate(start_date, false);
    if ($("#end_date")[0]._flatpickr) $("#end_date")[0]._flatpickr.setDate(end_date, false);
    $("#start_date_holder").text(moment(start_date).format('Do MMM YYYY'));
    $("#end_date_holder").text(moment(end_date).format('Do MMM YYYY'));
    route_name = $("#route_name").val();
    loadReport(start_date, end_date, selected_currency, route_name);
  });

  $(".year").click(function () {
    start_date = "<?php echo date('m/d/Y', strtotime($start_date_twelve_date)); ?>";
    end_date = "<?php echo $new_date_now; ?>";
    setPeriodActive($(this));
    $("#start_date").val(start_date);
    $("#end_date").val(end_date);
    if ($("#start_date")[0]._flatpickr) $("#start_date")[0]._flatpickr.setDate(start_date, false);
    if ($("#end_date")[0]._flatpickr) $("#end_date")[0]._flatpickr.setDate(end_date, false);
    $("#start_date_holder").text(moment(start_date).format('Do MMM YYYY'));
    $("#end_date_holder").text(moment(end_date).format('Do MMM YYYY'));
    route_name = $("#route_name").val();
    loadReport(start_date, end_date, selected_currency, route_name);
  });

  $(".selected_duration").click(function () {
    var duration = parseInt($(this).data("month"), 10);
    if (!duration) return;
    setPeriodActive($(this));
    var currentdate = new Date();
    var month = (currentdate.getMonth() + 1);
    month = month < 10 ? ('0' + month) : ('' + month);
    end_date = month + "/" + currentdate.getDate() + "/" + currentdate.getFullYear();
    var start_month_end_date = month + "/1/" + currentdate.getFullYear();
    var date = moment(start_month_end_date).format('YYYY-MM-DD');
    var new_date = new Date(date);
    new_date.setMonth(new_date.getMonth() - duration);
    var new_og_st_date = moment(new_date.toISOString().split('T')[0]).format('MM/DD/YYYY');
    $("#start_date").val(new_og_st_date);
    $("#end_date").val(end_date);
    if ($("#start_date")[0]._flatpickr) $("#start_date")[0]._flatpickr.setDate(new_og_st_date, false);
    if ($("#end_date")[0]._flatpickr) $("#end_date")[0]._flatpickr.setDate(end_date, false);
    $("#start_date_holder").text(moment(new_og_st_date).format('Do MMM YYYY'));
    $("#end_date_holder").text(moment(end_date).format('Do MMM YYYY'));
    start_date = new_og_st_date;
    route_name = $("#route_name").val();
    loadReport(start_date, end_date, selected_currency, route_name);
  });
});
</script>
</body>
</html>
