hvhj
<?php
if (!function_exists('modifed_time')) {
  function modifed_time($this_page){
$modification_time = filemtime("$this_page");
$url_time = "v=$modification_time";
return $url_time;
}
}
$tdata = $_POST['tdata'] ?? '';
$rows = json_decode($tdata, true);
if (!is_array($rows)) {
    http_response_code(400);
    echo 'Invalid data.';
    exit;
}

$rows = json_decode($tdata, true);
$count = 0;
$total_income = 0;
$total_expenses = 0;


?>
<style>
  thead th { background: #F5F5FB; border-bottom: 1px solid #E9E9F2; padding: 10px 18px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #6B7184; text-align: left; }
</style>
<table>
    <thead>
        <tr>
            <th style="width:36px;">No.</th>
            <th>Particular</th>
            <th>Category</th>
            <th>Point</th>
            <th>Username</th>
            <th>income</th>
            <th>Expenses</th>

        </tr>
    </thead>
    <tbody>
        <?php

        foreach ($rows as $row) {
            $count++;

            [$origin, $destination] = explode(' to ', $row['route_name'], 2);
            $origin = trim($origin);
            $destination = trim($destination);
     $income = (float) ($row['income'] ?? 0);
    $expenses = (float) ($row['expenses'] ?? 0);
            $total_income += $income;
            $total_expenses += $expenses;
            $total_balance = $total_income - $total_expenses;
            echo '
        <tr class="tr_link"style="cursor: pointer;"data-href="account_up/template/compose_vehicle.php?route_name='.$row['route_name'].'&buss='.$row['vehicle'].'&trip_date='.$row['trip_date'].'&checked=&main_page=manifest.php">
          <td class="idx">' . $count . '</td>
          <td>' . htmlspecialchars($row["name"]) . '</td>
          <td>' . htmlspecialchars($row["category"]) . '</td>
          <td>' . htmlspecialchars($row["point"]) . '</td>
          <td>' . htmlspecialchars($row["username"]) . '</td>
          <td class="price">' . (($income != 0) ? number_format($income) : "-") . ' </td>
          <td class="price">' . (($expenses != 0) ? number_format($expenses) : "-") . ' </td>
          </tr>
              ';
        }

        echo '
          <tr class="subtotal-row" >
          <td class="idx"></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="price">' . (($total_income != 0) ? number_format($total_income) : "-") . ' </td>
          <td class="price">' . (($total_expenses != 0) ? number_format($total_expenses) : "-") . ' </td>
          <td class="price">' . (($total_balance != 0) ? number_format($total_balance) : "-") . '</td>

          </tr>
        ';
        ?>
    </tbody>
</table>
<script>
    $("#expenses_details").text("<?= '' . number_format($total_balance) . ' ' ?>");
</script>