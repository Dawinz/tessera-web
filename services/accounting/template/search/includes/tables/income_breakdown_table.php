<?php
/*
error_reporting(E_ALL);


ini_set('display_errors', '1');

ini_set('display_startup_errors', '1');
*/
if (!function_exists('modifed_time')) {
  function modifed_time($this_page){
$modification_time = filemtime("$this_page");
$url_time = "v=$modification_time";
return $url_time;
}
}
$tdata = $_POST['tdata'];
$selected_currency = $_POST['selected_currency'];
?>
<table>
    <thead>
        <tr>
            <th style="width:36px;">No.</th>
            <th>Item</th>
            <th>Instance</th>
            <th class="right">Price (<?= $selected_currency ?>)</th>
        </tr>
    </thead>
    <tbody></tbody>
    <?php
    $php_array = json_decode($tdata, true);
    $count = 0;
    $total_amount = 0;
    $instances = 0;
    foreach ($php_array as $row) {
        $count++;
    $instances += count($row["details"]);
        $value = income($row['details']);
        $total_amount += $value;
        $id = $row['id']."".$count;
        echo '
        <tr class="evaluation_details_tr_income"id="evaluation_tr_details' . $id. '"data-id="' . $id. '"  data-category="'.$row["category"].'" data-name="'.$row["name"].'">
          <td class="idx">' . $count . '</td>
          <td>' . ucfirst($row["name"]) . '</td>
          <td>' . count($row["details"]). '</td>
          <td class="price">' . number_format($value) . ' </td>
        </tr>
          ';


    $encoded = htmlspecialchars(json_encode($row["details"]));
    $results .= '<div style="display:none;" id="item_name_selected'.$id.'">';
    $results .= $encoded;
    $results .= '</div>';

    }
     echo '
          <tr class="subtotal-row" >
          <td class="idx"></td>
          <td></td>
          <td>'.$instances.'</td>
          <td class="price"> '.number_format($total_amount).'</td>

          </tr>
        ';
     echo  $results;
    ?>
    </tbody>
</table>
<?php
function income($expneses)
{
    $total = 0;
    foreach ($expneses as $exp) {
        $total += $exp['income'];
    }
    return $total;
}

function breakdown($expneses)
{
    $php_array = json_encode($expneses, true);

    return $php_array;
}
?>
<script>
    $("#expenses_details").text("<?= '' . number_format($total_amount) . ' ' ?>");
</script>
<script src="../js/template/load/vehicle_account/table_controller.js?v<?= modifed_time("../../../../js/template/load/vehicle_account/table_controller.js")?>"></script>

