<?php
include '../connect/connect.php';
require_once __DIR__ . '/../includes/tessera_tenant.php';
include 'includes/vechile_summation.php';
$income_array = array();
$selected_currency = $_POST['selected_currency'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$route_name = $_POST['route_name'] ?? '';
$start_date_adopted = $start_date;
$end_date_adopted = $end_date;
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
////body///////////////////

if ($route_name != "") {
  $route_name_esc = mysqli_real_escape_string($db, $route_name);
  $route_name = " route_name ='{$route_name_esc}' and ";
}
if ($start_date != "") {
  $start_date = date("Y-m-d", strtotime($_POST['start_date']));
  $start_date = " trip_date >='$start_date' and ";
}
if ($end_date != "") {
  $end_date = date("Y-m-d", strtotime($_POST['end_date']));
  $end_date = " trip_date <='$end_date' and ";
}
//////////
$symbol = $selected_currency;
$currency_esc = mysqli_real_escape_string($db, $selected_currency);
$select_route_currency = mysqli_query($db, "SELECT symbol FROM currency WHERE name ='{$currency_esc}'");
$select_num_route_currency = ($select_route_currency instanceof mysqli_result) ? mysqli_num_rows($select_route_currency) : 0;
if ($select_num_route_currency != 0) {
  while ($get = mysqli_fetch_array($select_route_currency)) {
    $symbol = $get['symbol'];
  }
}

$currency_value_array = [];

$select_route_currency_value = mysqli_query($db, "SELECT 
  second_point,value,first_point FROM   currency_value  ");
$select_num_route_currency_value = ($select_route_currency_value instanceof mysqli_result) ? mysqli_num_rows($select_route_currency_value) : 0;
if ($select_num_route_currency_value != 0) {
  while ($get_route_currency_value = mysqli_fetch_array($select_route_currency_value)) {
    $first_point = $get_route_currency_value['first_point'];
    $second_point_c = $get_route_currency_value['second_point'];
    $value_c = $get_route_currency_value['value'];

    $currency_value_array["$second_point_c-$first_point"] = $value_c;
  }
}

?>
<div class="table-responsive sr-report-table-wrap">
<table id="order-listing" class="table table-hover align-middle sr-report-table w-100 mb-0" cellspacing="0">
  <thead>
    <tr class="sr-report-head-primary">
      <th scope="col" rowspan="2" class="sr-report-th-num">No</th>
      <th scope="col" rowspan="2">Bus</th>
      <th scope="col" rowspan="2">Routes</th>
      <th scope="col" colspan="4" class="sr-report-head-group">
        <span class="sr-report-head-group-label">Income</span>
        <span class="badge bg-primary-subtle text-primary sr-report-currency-badge"><?php echo htmlspecialchars((string) $selected_currency, ENT_QUOTES, 'UTF-8'); ?></span>
      </th>
      <th scope="col" rowspan="2">Total</th>
      <th scope="col" rowspan="2">Expenses</th>
      <th scope="col" rowspan="2">Balance</th>
    </tr>
    <tr class="sr-report-head-sub">
      <th scope="col">Ticket</th>
      <th scope="col">Road collection</th>
      <th scope="col">Cargo paid</th>
      <th scope="col">Cargo to be paid</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $grand_total_income = 0;
    $grand_total_expenses = 0;
    /* ONLY_FULL_GROUP_BY: aggregate trip_date; row numbers in PHP */
    $tenant_sql = function_exists('tessera_sql_tenant_eq') ? (tessera_sql_tenant_eq() . ' AND ') : '';
    $select_order_bus = mysqli_query(
      $db,
      "SELECT bus, MAX(trip_date) AS trip_date
       FROM manifest
       WHERE {$tenant_sql}{$start_date} {$end_date} {$route_name} 1
       GROUP BY bus
       ORDER BY bus ASC"
    );
    $select_num_order_bus = ($select_order_bus instanceof mysqli_result) ? mysqli_num_rows($select_order_bus) : 0;
    if ($select_num_order_bus != 0) {
      $row_num = 0;
      while ($get_order_bus = mysqli_fetch_array($select_order_bus)) {
        $row_num++;
        $bus = $get_order_bus['bus'];
        $trip_date = $get_order_bus['trip_date'];

        $ticket_income_array = array();
        $road_collection_income_array = array();
        $cargo_income_array = array();
        $cargo_income_to_be_array = array();
        $routs_array = array();
        $exp_array = array();
        $currency_array = array();
        $summations = summation($bus, $db, $selected_currency, $symbol, $start_date, $end_date, $route_name, $trip_date, $ticket_income_array, $road_collection_income_array, $cargo_income_array, $exp_array, $routs_array, $currency_array, $currency_value_array, $cargo_income_to_be_array);
        $ticket_amount = array_sum($ticket_income_array);
        $road_amount = array_sum($road_collection_income_array);
        $cargo_amount = array_sum($cargo_income_array);
        $exp_amount = array_sum($exp_array);
        $cargo_income_to_be_array = array_sum($cargo_income_to_be_array);
        $total_income = $ticket_amount + $road_amount + $cargo_amount + $cargo_income_to_be_array;
        $balance = $total_income - $exp_amount;
        $available_routes = implode(", ", $routs_array);

        $grand_total_income += $total_income;
        $grand_total_expenses += $exp_amount;

        ?>
        <tr>

          <td><?php echo (int) $row_num; ?></td>
          <td>
            <a class="sr-report-bus-link" href="vehicle_report_vehicle.php?bus=<?php echo urlencode($bus); ?>&amp;start_date=<?php echo urlencode($start_date_adopted); ?>&amp;end_date=<?php echo urlencode($end_date_adopted); ?>">
              <?php echo htmlspecialchars($bus, ENT_QUOTES, 'UTF-8'); ?>
            </a>
          </td>
          <td><?php echo $available_routes; ?></td>
          <td><?php echo ($ticket_amount != 0) ? number_format($ticket_amount) : "-"; ?></td>
          <td><?php echo ($road_amount != 0) ? number_format($road_amount) : "-"; ?></td>
          <td><?php echo ($cargo_amount != 0) ? number_format($cargo_amount) : "-"; ?></td>
          <td><?php echo ($cargo_income_to_be_array != 0) ? number_format($cargo_income_to_be_array) : "-"; ?></td>
          <td><strong><?php echo ($total_income != 0) ? number_format($total_income) : "-"; ?></strong></td>
          <td><strong><?php echo ($exp_amount != 0) ? number_format($exp_amount) : "-"; ?></strong></td>
          <td><span
              class="<?php echo ($balance <= 0) ? "text-danger" : ""; ?>"><strong><?php echo ($balance != 0) ? number_format($balance) : "-"; ?></strong></span>
          </td>
        </tr>
        <?php
      }
    }

    $grand_balance = $grand_total_income - $grand_total_expenses;
    ?>
  </tbody>
  <tfoot class="sr-report-foot">
    <tr>
      <td colspan="7" class="sr-report-foot-label text-end">Total income</td>
      <td class="text-end"><strong><?php echo number_format($grand_total_income); ?></strong></td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td colspan="7" class="sr-report-foot-label text-end">Total expenses</td>
      <td></td>
      <td class="text-end"><strong><?php echo number_format($grand_total_expenses); ?></strong></td>
      <td></td>
    </tr>
    <tr class="sr-report-foot-balance">
      <td colspan="7" class="sr-report-foot-label text-end">Balance</td>
      <td colspan="2"></td>
      <td class="text-end"><strong><?php echo number_format($grand_balance); ?></strong></td>
    </tr>
  </tfoot>
</table>
</div>
<script>

  $(document).ready(function () {
    var table = new DataTable('#order-listing', {
      lengthMenu: [100],
      "language": {
        searchPlaceholder: "Search",
        search: "",

        processing: ''
      },
      ordering: false,
      paging: false,
      info: false,
      "order": [],
      layout: {
        topStart: {
          buttons: [
            {
              extend: 'copy',
              footer: true,
              class: 'buttons-copy',
              init: function (api, node, cofig) {
                $(node).hide();
              }
            },
            {
              extend: 'csv', footer: true, class: 'buttons-csv', init: function (api, node, cofig) {
                $(node).hide();
              }
            },
            {
              extend: 'print', footer: true, class: 'buttons-print', init: function (api, node, cofig) {
                $(node).hide();
              }
            },
            {
              extend: 'pdf', footer: true, class: 'buttons-pdf', init: function (api, node, cofig) {
                $(node).hide();
              }
            },
            {
              extend: 'excel', text: 'Save as Excel', class: 'buttons-excel', init: function (api, node, cofig) {
                $(node).hide();
              }
            }
          ],


        }
      }
    });
    table.buttons('.export').remove();
    $("#print_table").click(function () {
      table.buttons('.buttons-print').trigger();


    });
    $("#copy_table").click(function () {
      table.buttons('.buttons-copy').trigger();

    });

    $("#pdf_table").click(function () {
      table.buttons('.buttons-pdf').trigger();

    });
    $("#excel_table").click(function () {
      table.buttons('.buttons-excel').trigger();

    });
    $("#csv_table").click(function () {
      table.buttons('.buttons-csv').trigger();

    });

    $("#search_row").click(function () {

      $("#myInputTextField").toggle();

    });
    oTable2 = $('#order-listing').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
    $('#myInputTextField').keyup(function () {
      oTable2.search($(this).val()).draw();
    });
  });

</script>