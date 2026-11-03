<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
*/
$tdata = $_POST['tdata'];
$selected_currency = $_POST['selected_currency'];
$php_array = json_decode($tdata, true);
$count = 0;
$total_amount = 0;
$detail_array = detail_array($php_array);
if (!function_exists('modifed_time')) {
  function modifed_time($this_page){
$modification_time = filemtime("$this_page");
$url_time = "v=$modification_time";
return $url_time;
}
}
?>
<style>
  thead th { background: #F5F5FB; border-bottom: 1px solid #E9E9F2; padding: 10px 18px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #6B7184; text-align: left; }
</style>
<table>
    <thead>
        <tr>
            <th style="width:36px;">No.</th>
            <th>Trip date</th>
            <th>Vehicle</th>
            <th>Route</th>
            <th>Instance</th>
            <th class="right">Price <?= $selected_currency ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        $total_occurance = 0;
        foreach ($detail_array as $row) {
            $count++;

            [$origin, $destination] = explode(' to ', $row['route_name'], 2);
            $origin = trim($origin);
            $destination = trim($destination);
            $expenses = array_sum(array_column($row['details'], 'expenses'));   
            $total +=$expenses;
            $total_occurance += count($row["details"]);
         echo '
        <tr class="tr_link"style="cursor: pointer;"data-href="account_up/template/manifest_view.php?route_name='.$row['route_name'].'&buss='.$row['vehicle'].'&trip_date='.$row['trip_date'].'&checked=&main_page=manifest.php">
          <td class="idx">' . $count . '</td>
          <td>' . date("d/m/Y", strtotime($row["trip_date"])) . '</td>
          <td><span class="plate"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 13l1.5-5A2 2 0 0 1 6.4 6.5h11.2a2 2 0 0 1 1.9 1.5L21 13"/><rect x="3" y="13" width="18" height="6" rx="1.5"/><circle cx="7.5" cy="19" r="1.5"/><circle cx="16.5" cy="19" r="1.5"/></svg>' . $row["vehicle"] . '</span></td>
          <td><span class="route-chip">' . $origin . ' -> ' . $destination . '</span></td>
          <td>' . count($row["details"]) . '</td>      
          <td class="price">'.number_format($expenses).'</td>
        </tr>
';

        }
         echo '
          <tr class="subtotal-row" >
          <td class="idx"></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="price">'.$total_occurance.'</td>

          <td class="price">' . (($total != 0) ? number_format($total) : "-") . '</td>

          </tr>
        ';
        ?>
    </tbody>
</table>
<?php
function detail_array($php_array){
    $count=0;
    $trips=[];
 foreach ($php_array as $row) {
   $count++;
   $trip_date = $row["trip_date"];
   $route_name = $row["route_name"];
   $name = $row["name"];
   $vehicle = $row["vehicle"];

 $key = $trip_date . '|' . $route_name. '|' .$vehicle . '|' .$name   ;
  if (!isset($trips[$key])) {
    $trips[$key] = [
      'trip_date' => $trip_date,
      'route_name' => $route_name,
      'vehicle' => $vehicle,
      'name' => $name,
      'details' => [],
    ];
  }
 $trips[$key]['details'][] = [
      'id' => $count,
      'expenses' => $row["expenses"],
    ];
 }
 return $trips;
}
?>
<script src="../js/template/load/vehicle_account/table_controller.js?v<?= modifed_time("../../../../js/template/load/vehicle_account/table_controller.js")?>"></script>

<script>
    $("#expenses_details").text("<?= '' . number_format($total) . '' ?>");
</script>