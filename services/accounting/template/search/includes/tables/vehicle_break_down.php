<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
$selected_currency = $_POST['selected_currency'];
$tdata = $_POST['tdata'];
if (!function_exists('modifed_time')) {
  function modifed_time($this_page){
$modification_time = filemtime("$this_page");
$url_time = "v=$modification_time";
return $url_time;
}
}

?>
<table>
    <thead>
        <tr>
            <th style="width:36px;">No.</th>
            <th>Trip date</th>
            <th>Route</th>
            <th>Ticket</th>
            <th>Road Collection</th>
            <th>Cargo</th>
            <th>Total</th>
            <th>Expenses</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $primary_array = json_decode($tdata, true);
        $count = 0;
        $ticket_total = 0;
        $road_collection_total = 0;
        $total_cargo = 0;
        $expenses_total = 0;
        $cargo_total = 0;
        $grand_income = 0;
        $total_balance = 0;
       $results = "";
        foreach ($primary_array as $row) {
            $count++;
            [$origin, $destination] = explode(' to ', $row['route_name'], 2);
            $origin = trim($origin);
            $destination = trim($destination);
            $ticket = ticket($row["details"]);
            $road_collection = road_collection($row["details"]);
            $cargo = cargo($row["details"]);
            $expenses = expenses($row["details"]);
            $ticket_total += $ticket;
            $road_collection_total += $road_collection;
            $cargo_total += $cargo;
            $expenses_total += $expenses;
            $total_income = $ticket + $road_collection + $cargo;
            $balance = $total_income - $expenses;
            $grand_income += $total_income;
            $total_balance += $balance;
            $trip_date = date("d/m/Y", strtotime($row["trip_date"]));

            echo '
          <tr class="vehicle_evalutation_details_tr" data-id="'.$count.'" data-vehicle="'.$row['vehicle'].'" data-trip_date="'.$trip_date.'" data-route_name="' . $origin . ' -> ' . $destination . '"data-href="account_up/template/compose_vehicle.php?route_name='.$row['route_name'].'&buss='.$row['vehicle'].'&trip_date='.$row['trip_date'].'&checked=&main_page=manifest.php">
          <td class="idx">' . $count . '</td>
          <td>' . $trip_date . '</td>
          <td><span class="route-chip">' . $origin . ' -> ' . $destination . '</span></td>
          <td class="price">' . (($ticket != 0) ? number_format($ticket) : "-") . ' </td>
          <td class="price">' . (($road_collection != 0) ? number_format($road_collection) : "-") . ' </td>
          <td class="price"> ' . (($cargo != 0) ? number_format($cargo) : "-") . ' </td>
          <td class="price"> ' . (($total_income != 0) ? number_format($total_income) : "-") . ' </td>
          <td class="price"> ' . (($expenses != 0) ? number_format($expenses) : "-") . '</td>
          <td class="price"> ' . (($balance != 0) ? number_format($balance) : "-") . '</td>
        </tr>
                ';
    $encoded = htmlspecialchars(json_encode($row["details"]));
    $results .= '<div style="display:none;" id="manifest_single_details'.$count.'">';
    $results .= $encoded;
    $results .= '</div>';

        }
        echo '
        <tr class="subtotal-row" data-id="'.$count.'" >
          <td class="idx"></td>
          <td></td>
          <td></td>
          <td class="price">' . (($ticket_total != 0) ? number_format($ticket_total) : "-") . '</td>
          <td class="price">' . (($road_collection_total != 0) ? number_format($road_collection_total) : "-") . ' </td>
          <td class="price"> ' . (($cargo_total != 0) ? number_format($cargo_total) : "-") . '</td>
          <td class="price"> ' . (($total_income != 0) ? number_format($total_income) : "-") . '</td>
          <td class="price"> ' . (($expenses_total != 0) ? number_format($expenses_total) : "-") . '</td>
          <td class="price"> ' . (($total_balance != 0) ? number_format($total_balance) : "-") . '</td>
        </tr>
        ';
        echo  $results;
  
        
        ?>
    </tbody>
</table>
<script>
    $("#expenses_details").text("<?= '' . number_format($total_balance) . ' ' ?>");

</script>
<?php
function ticket($detail)
{
    $total = 0;
    foreach ($detail as $row) {

        if (($row['status'] == "Income") && ($row['category'] == "Passanger") && ($row['name'] == "Ticket")) {
            $total += $row['income'];
        }
    }
    return $total;
}
function road_collection($detail)
{
    $total = 0;
    foreach ($detail as $row) {

        if (($row['status'] == "Income") && ($row['category'] == "Passanger") && ($row['name'] == "Road collection")) {
            $total += $row['income'];
        }
    }
    return $total;
}
function cargo($detail)
{
    $total = 0;
    foreach ($detail as $row) {

        if (($row['status'] == "Income") && ($row['category'] == "Cargo")) {
            $total += $row['income'];
        }
    }
    return $total;
}
function expenses($detail)
{
    $total = 0;
    foreach ($detail as $row) {

        if (($row['status'] == "Expenses") && ($row['affect'] == "Paid")) {
            $total += $row['expenses'];
        }
    }
    return $total;
}

?>
<script src="../js/template/load/vehicle_account/table_controller.js?v<?= modifed_time("../../../../js/template/load/vehicle_account/table_controller.js")?>"></script>
