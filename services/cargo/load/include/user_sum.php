<?php

$route_type = [];
$route_type_main = [];
$grand_total = [];
$currency_total = [];
$grand_total_org=[];
$tbody = '';
foreach ($users as $row) {
  $user_to_be = 0;
  $user_paid = 0;

  $route_group = $row["route_name"];
  $username_per_group = $row["username"];

  $assinged_route_currency = assign_currency($route_currency, $route_group);

  //echo "$username_per_group $route_group $assinged_route_currency<br>";


  foreach ($user_values as $row_val) {
    $username = $row_val["username"];
    $route_name = $row_val["route_name"];

    if (($username_per_group === $username) && ($route_name === $route_group)) {
      $user_to_be += $row_val["to_be"];
      $user_paid += $row_val["paid"];


    }

  }


  $tbody .= '<tr><td>' . $route_group . '</td><td>' . $username_per_group . '</td><td>' . $user_to_be . '</td><td>' . $user_paid . '</td><td>' . $user_to_be + $user_paid . '</td></tr>';


  $user_and_routes[] = [
    "route_name" => $route_group,
    "username" => $username_per_group,
    "paid" => $user_paid,
    "to_be" => $user_to_be,
    "currency" => $assinged_route_currency
  ];

}
foreach ($user_and_routes as $row_total) {
  $username_total = $row_total["username"];
  $route_name_total = $row_total["route_name"];
  $paid_total = $row_total["paid"];
  $to_be_total = $row_total["to_be"];
  $currency = $row_total["currency"];
  
 if (in_array($currency, $currency_total)) {
        } else {
          $currency_total[] = $currency;
        }

  if ($route_name_total != $route_origin) {

    $route_type[] = [
      "route_name" => $route_name_total,
      "username" => $username_total,
      "paid" => $paid_total,
      "to_be" => $to_be_total,
      "currency" => $currency
    ];
  } else {
    $route_type_main[] = [
      "route_name" => $route_name_total,
      "username" => $username_total,
      "paid" => $paid_total,
      "to_be" => $to_be_total,
      "currency" => $currency
    ];
  }

}

foreach ($route_type as $row) {
  $username = $row["username"];
  $route_name = $row["route_name"];
  $paid_total = $row["paid"];
  $to_be_total = $row["to_be"];
  $currency = $row["currency"];

  $exchange_rate = currency_rate($currency_values->rows, $currency, $default_currency);
  $route_currency_og  =  assign_currency($route_currency, $route_name);

  $grand_total[] = [
    "route_name" => $route_name,
    "username" => $username,
    "paid" => $paid_total * $exchange_rate,
    "to_be" => $to_be_total * $exchange_rate,
    "currency" => $default_currency
  ];
  $grand_total_org[] = [
    "route_name" => $route_name,
    "username" => $username,
    "paid" => $paid_total ,
    "to_be" => $to_be_total ,
    "currency" => $route_currency_og
  ];


  //echo "$to_be_total $paid_total" . $exchange_rate . "<br>";
}
foreach ($route_type_main as $row) {
  $username = $row["username"];
  $route_name = $row["route_name"];
  $paid_total = $row["paid"];
  $to_be_total = $row["to_be"];
  $currency = $row["currency"];
  $exchange_rate = currency_rate($currency_values->rows, $currency, $default_currency);
  $route_currency_og  =  assign_currency($route_currency, $route_name);
  $grand_total[] = [
    "route_name" => $route_name,
    "username" => $username,
    "paid" => $paid_total * $exchange_rate,
    "to_be" => $to_be_total * $exchange_rate,
    "currency" => $default_currency
  ];
   $grand_total_org[] = [
    "route_name" => $route_name,
    "username" => $username,
    "paid" => $paid_total ,
    "to_be" => $to_be_total ,
    "currency" =>   $route_currency_og
  ];

  //echo currency_rate($currency_values->rows, $currency, $default_currency) . "<br>";

}
$grand_to_be = 0;
$grand_paid = 0;

foreach ($grand_total as $row) {
  $username = $row["username"];
  $route_name = $row["route_name"];
  $paid_total = $row["paid"];
  $to_be_total = $row["to_be"];
  $currency = $row["currency"];

  $grand_to_be += $to_be_total;
  $grand_paid += $paid_total;

 // echo "$username $route_name $paid_total $to_be_total $currency <br>..";
}

$grand_total_amount = $grand_to_be + $grand_paid;



function assign_currency($route_currency, $route_name)
{
  $this_route_curreny = "";
  $currency = "";
  foreach ($route_currency as $row) {
    $route_name_select = $row["route_name"];
    $currency = $row["currency"];

    if ($route_name == $route_name_select) {
      $this_route_curreny = $currency;
    }

  }
  return $this_route_curreny;
}
function currency_rate($currnecy_array, $currency, $default_currency)
{
  $exchange_rate = 1;
  if ($currency != $default_currency) {
    foreach ($currnecy_array as $row) {
      $first_point = $row["first_point"];
      $second_point = $row["second_point"];

      if (($second_point == $default_currency) AND ($first_point == $currency)) {
        $exchange_rate = $row["value"];

      }


    }


  }


  return $exchange_rate;


}


function user_html_table($routes,$grand_total_org,$main_route, $parcel_users,$currency_total){
$table = '<table class="table mb-0" id="print-area">
';
$table .= '<tbody >';
$last_username = end($parcel_users);
 foreach ($parcel_users as $users) {
 $is_last_user = ($users === $last_username);
    $table .= '<tr class="black-border"><td></td><td></td><td><strong>'.$users.'</strong></td><td></td><td></td></tr>';
  $table .= '<tr style="border:none;"><th>Route</th><th>Currency</th><th>To be paid</th><th>Paid</th><th>Total</th></tr>
';
    $last_currency = end($currency_total);
    foreach ($currency_total as $currency) {
          $is_last_currency = ($currency === $last_currency);

        $currency_count = 0;
        $to_be = 0;
        $paid = 0;

        // Filter matching rows first
        $filtered = array_filter($grand_total_org, function($row) use ($users, $currency) {
            return ($users == $row["username"]) && ($row["currency"] == $currency);
        });

        $last_row = end($filtered); // get last matching row

        foreach ($filtered as $row) {

            $is_last = ($row === $last_row); // check if current is last

            $total_amount = $row["paid"] + $row["to_be"];
            $table .= '<tr '.($is_last_currency ? 'class="last-row"' : '').'>
            <td>'.$row["route_name"].' </td>
            <td><span class="badge bg-success">'.$row["currency"].'</span></td>
            <td>'.($row["to_be"] != 0 ? number_format($row["to_be"]) : "-").'</td>
            <td>'.($row["paid"] != 0 ? number_format($row["paid"]) : "-").'</td>
            <td>'.($total_amount != 0 ? number_format($total_amount) : "-").'</td>
            </tr>';

            $currency_count += 1;
            $to_be += $row["to_be"];
            $paid += $row["paid"];
        }

        if ($currency_count > 1) {
            $total = $to_be + $paid;
            $table .= '<tr>
            <td></td><td></td>
            <td style="background:#edf2ef;"><strong>'.($to_be != 0 ? number_format($to_be) : "-").'</strong></td>
            <td style="background:#edf2ef;"><strong>'.($paid != 0 ? number_format($paid) : "-").'</strong></td>
            <td style="background:#edf2ef;"><strong>'.($total != 0 ? number_format($total) : "-").'</strong></td>
            </tr>';
        }
    }
}

$table .= '</tbody>';
$table .= "</table>";

return $table;
}