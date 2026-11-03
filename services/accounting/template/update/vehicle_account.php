<?php
require_once __DIR__ . '/../includes/tessera_staff_boot.php';
include '../connect/connect.php';
$row_num = $_POST['row_number'] ?? '';
$end_point = $_POST['end_point_edite'] ?? '';
$zero = 0;
$new_part_category = '';
$affect = 'Paid';
$currency_symbol = '';
$select_currency=mysqli_query($db,"SELECT symbol FROM   currency   where name ='".$_POST['part_Currency']."'  ");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $currency_symbol =$get_currency['symbol']; 
	}
}


$select_status=mysqli_query($db,"SELECT status,month,trip_date,bus,point,route_name,current_route,income,expenses FROM   vehicle_account   where id ='".$_POST['part_id']."'  ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	    $status =$get_status['status']; 
    	    $month =$get_status['month']; 
    	    $trip_date =$get_status['trip_date']; 
    	    $bus =$get_status['bus']; 
    	    $point_value =$get_status['point']; 
    	    $route_name =$get_status['route_name']; 
    	    $current_route =$get_status['current_route']; 
    	    $income =$get_status['income']; 
    	    $expenses =$get_status['expenses']; 
    	    $status =$get_status['status']; 
	}
}
$select_category=mysqli_query($db,"SELECT category FROM   particular_category   where name ='".$_POST['part_Category_update']."' and category='$status'  ");
$select_num_category=mysqli_num_rows($select_category);
if($select_num_category !=0){
   	WHILE($get_category=mysqli_fetch_array($select_category)){
    	    $new_part_category =$get_category['category']; 
	}
}
$select_category_par=mysqli_query($db,"SELECT affect FROM   particulars   where category ='".$_POST['part_Category_update']."' and name ='".$_POST['part_detail_update']."'  ");
$select_num_category_par=mysqli_num_rows($select_category_par);
if($select_num_category_par !=0){
   	WHILE($get_category_par=mysqli_fetch_array($select_category_par)){
    	    $affect =$get_category_par['affect']; 
	}
}

if($new_part_category != $status){
if($new_part_category =="Income"){
	$insert=mysqli_query($db,"UPDATE  vehicle_account  SET 
	category='".$_POST['part_Category_update']."',
	name='".$_POST['part_detail_update']."',
	currency='".$_POST['part_Currency']."',
	currency_sign='$currency_symbol',
	income='".preg_replace('/[^0-9.]+/', '',$_POST['amount'])."',
	price='".preg_replace('/[^0-9.]+/', '',$_POST['amount'])."',
	expenses='$zero',
	status='$new_part_category',
	detail='".$_POST['detail']."'
	WHERE id='".$_POST['part_id']."'");
	include '../includes/update/add_income.php';
}else{
	$insert=mysqli_query($db,"UPDATE  vehicle_account  SET 
	category='".$_POST['part_Category_update']."',
	name='".$_POST['part_detail_update']."',
	currency='".$_POST['part_Currency']."',
	currency_sign='$currency_symbol',
	income='$zero',
	expenses='".preg_replace('/[^0-9.]+/', '',$_POST['amount'])."',
	price='".preg_replace('/[^0-9.]+/', '',$_POST['amount'])."',
	status='$new_part_category',
	detail='".$_POST['detail']."'
	WHERE id='".$_POST['part_id']."'");
	include '../includes/update/add_expenses.php';

}	
		
}else{
	if($new_part_category=="Income"){
$insert=mysqli_query($db,"UPDATE  vehicle_account  SET 
	category='".$_POST['part_Category_update']."',
	name='".$_POST['part_detail_update']."',
	currency_sign='$currency_symbol',
	status='$new_part_category',
		expenses='$zero',
			currency='".$_POST['part_Currency']."',

			income='".preg_replace('/[^0-9.]+/', '',$_POST['amount'])."',
		price='".preg_replace('/[^0-9.]+/', '',$_POST['amount'])."',
	affect='".$affect."'
	WHERE id='".$_POST['part_id']."'");	
		include '../includes/update/add_income.php';

	}else{
		$insert=mysqli_query($db,"UPDATE  vehicle_account  SET 
	category='".$_POST['part_Category_update']."',
	name='".$_POST['part_detail_update']."',
	currency_sign='$currency_symbol',
	status='$new_part_category',
	income='$zero',
		currency='".$_POST['part_Currency']."',

	expenses='".preg_replace('/[^0-9.]+/', '',$_POST['amount'])."',
		price='".preg_replace('/[^0-9.]+/', '',$_POST['amount'])."',
	detail='".$_POST['detail']."',
	affect='".$affect."'
	WHERE id='".$_POST['part_id']."'");	
	include '../includes/update/add_expenses.php';
	}
}
$busname = $bus;


$select_acc=mysqli_query($db,"SELECT income,expenses FROM    vehicle_account    where bus ='$bus' and trip_date='$trip_date' ");
$select_num_acc=mysqli_num_rows($select_acc);
if($select_num_acc !=0){
   	WHILE($get_acc=mysqli_fetch_array($select_acc)){
    	    $income =$get_acc['income']; 
    	    $expenses =$get_acc['expenses']; 
    	
    	  
    	  if($income !=0){
			  $amount =  $income;
			 // include '../insert/includes/combine_income.php';
	//include '../insert/includes/combine_account_monthly.php';
		  }else{
			  			   $amount =  $expenses;

			  //include '../insert/includes/combine_account_exp.php';
	//include '../insert/includes/combine_account_monthly_exp.php';
		  }

	}
}



include '../update/update_total.php';




 ?>