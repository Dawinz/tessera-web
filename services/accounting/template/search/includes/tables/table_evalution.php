<?php
function evaluation_table($category_evalutation_array, $expenses_array, $selected_currency, $currnecy_value)
{
  $results = "";
  foreach ($expenses_array as $row_expneses) {
    $expenses_item_array = [];
    $counter_cat = 0;
    $counter = 0;
    $total_expneses = 0;


    foreach ($category_evalutation_array as $row) {


      if ($row_expneses["category"] == $row['category']) {

        $counter++;
        
        $expenses_item_array[] = [
          'exp_id' => $row_expneses['exp_id'],
          'exp_detail_id' => $row['exp_detail_id'],
          'name' => $row["name"],
          'category' => $row["category"],
          'trip_date' => $row["trip_date"],
          'vehicle' => $row["vehicle"],
          'route_name' => $row["route_name"],
          'details' => $row["details"],
        ];
      }




    }
    $encoded = htmlspecialchars(json_encode($expenses_item_array));
    $results .= '<div style="display:none;" id="expenses_results' . $row_expneses['exp_id'] . '" data-detail-id="">';
    $results .= $encoded;
    $results .= '</div>';
  }
  return $results;
}

function income_table($category_evalutation_array, $income_array, $selected_currency, $currnecy_value)
{
  $results = "";
  foreach ($income_array as $row_income) {
    $income_item_array = [];
    $counter_cat = 0;
    $counter = 0;
    $total_expneses = 0;


    foreach ($category_evalutation_array as $row) {


      if ($row_income["category"] == $row['category']) {

        $counter++;
        
        $income_item_array[] = [
          'id' => $row_income['inc_id'],
          'exp_detail_id' => $row['exp_detail_id'],
          'name' => $row["name"],
          'category' => $row["category"],
          'trip_date' => $row["trip_date"],
          'vehicle' => $row["vehicle"],
          'route_name' => $row["route_name"],
          'details' => $row["details"],
        ];
      }




    }
    $encoded = htmlspecialchars(json_encode($income_item_array));
    $results .= '<div style="display:none;" id="income_results' . $row_income['inc_id'] . '" data-detail-id="">';
    $results .= $encoded;
    $results .= '</div>';
  }
  return $results;
}
?>