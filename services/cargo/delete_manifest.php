<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
?>
<?php
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$id = $_POST['id'];
$currency_value = $_POST['currency_json'];
$default_currency = base64_decode($_POST['default_currency']);
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$date_time_insert = date('h:i A', time());

$select2=mysqli_query($db,"SELECT currency,code,q_left,to_be,parcel_route,paid,bus,trip_date,quantity_insert FROM  manifest WHERE id='$id' AND " . tessera_sql_tenant_eq() . "");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
 	WHILE($get12=mysqli_fetch_array($select2)){
			$code=$get12['code'];
			$q_left=$get12['q_left'];
	
		$to_be=$get12['to_be'];
		$trip=$get12['parcel_route'];
		$paid=$get12['paid'];
		$bus=$get12['bus'];
		$trip_date=$get12['trip_date'];
		$quantity_insert=$get12['quantity_insert'];
		$currency=$get12['currency'];

		
	}
}

$exchange_rate = currency_rate($currency_value, $currency, $default_currency);

$manifest_paid = $paid * $exchange_rate;
$manifest_to_be_paid= $to_be * $exchange_rate;


$select_parcel=mysqli_query($db,"SELECT initial,paid_remain,to_be_remain,quantity_remain,scanned,question FROM parcel_info WHERE code='$code' AND " . tessera_sql_tenant_eq() . "");
$select_num_parcel=mysqli_num_rows($select_parcel);
if($select_num_parcel !=0){
   	WHILE($get_parcel=mysqli_fetch_array($select_parcel)){
    	    $paid_remain =$get_parcel['paid_remain']; 
    	    $to_be_remain =$get_parcel['to_be_remain']; 
    	    $quantity_remain =$get_parcel['scanned']; 
    	    $qty =$get_parcel['question']; 

	}}

$new_to_paid = $to_be_remain + $to_be;
$new_paid = $paid + $paid_remain;
$new_qty = $quantity_remain + $quantity_insert;

if($qty == $new_qty){
$update=mysqli_query($db,"UPDATE parcel_info SET scanned='$new_qty',quantity_remain='$new_qty',paid_remain='$new_paid',to_be_remain='$new_to_paid',store='store'  WHERE code = '$code' AND " . tessera_sql_tenant_eq() . "");
}else{
$update=mysqli_query($db,"UPDATE parcel_info SET scanned='$new_qty',quantity_remain='$new_qty',paid_remain='$new_paid',to_be_remain='$new_to_paid',store='half_out'  WHERE code = '$code' AND " . tessera_sql_tenant_eq() . "");
}
$exchange_rate = currency_rate($currency_value, $currency, $default_currency);
$select_vehicle=mysqli_query($db,"SELECT *  FROM  vehicle_name WHERE Name='$bus' and date='$trip_date' ");
$select_num_vehicle=mysqli_num_rows($select_vehicle);
if($select_num_vehicle !=0){
 	WHILE($get_vehicle=mysqli_fetch_array($select_vehicle)){
			$to_pay_bus=$get_vehicle['to_pay'];
			$paid_bus=$get_vehicle['paid'];

$total_paid_see= $paid_bus - $manifest_paid;
$total_to_paid_see= $to_pay_bus - $manifest_to_be_paid;
$total_all_add = $total_paid_see +	$total_to_paid_see;	


$update=mysqli_query($db,"UPDATE vehicle_name SET to_pay='$total_to_paid_see',	paid='$total_paid_see',due='$total_all_add'  WHERE Name='$bus' and date='$trip_date'");

}}
$delete=mysqli_query($db,"INSERT INTO manifest_deleted 
(id2,code) VALUES 
('$id','$code')");
$delete=mysqli_query($db,"DELETE from manifest WHERE id = '$id' AND " . tessera_sql_tenant_eq() . "");
function currency_rate($currency_value, $currency, $default_currency){
    $currency_value = json_decode($currency_value, true);
    $exchange_rate = 1;
    if($currency !=""){
    if (($currency != $default_currency) ) {
        foreach ($currency_value as $row) {
            $first_point = $row["first_point"];
            $second_point = $row["second_point"];

            if (($second_point == $default_currency) AND ($first_point == $currency)) {
                $exchange_rate = $row["value"];

            }


        }
    }

    }


    return  $exchange_rate;


}