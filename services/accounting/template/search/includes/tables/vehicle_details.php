<?php
function vehicle_details($vehicle_array, $trip_details, $selected_currency, $currnecy_value)
{
  $results = "";
  foreach ($vehicle_array as $row_vehicle) {
    $vehicle_array_selected = [];
    


    foreach ($trip_details as $row) {


      if ($row_vehicle["vehicle"] == $row['vehicle']) {

       $vehicle_array_selected[] = [
          'id' => $row_vehicle['vehicle_id'],
          'vehicle' => $row_vehicle['vehicle'],
          'trip_date' => $row['trip_date'],
          'route_name' => $row["route_name"],
          'details' => $row["details"],
        ];
      }




    }
    $encoded = htmlspecialchars(json_encode($vehicle_array_selected));
    $results .= '<div style="display:none;" id="vehicle_detail'.$row_vehicle['vehicle_id'].'">';
    $results .= $encoded;
    $results .= '</div>';
  }
  return $results;
}