<?php
include '../connect/connect.php';
$id = $_POST['id_name'];
$price = $_POST['price_update'];
$price = preg_replace('/[^0-9.]+/', '', $price);	

$group_cat_update = $_POST['group_cat_update'];
$expenses_update = $_POST['expenses_update'];
$vehicle_update = $_POST['vehicle_update'];
$date_update = $_POST['date_update'];
$detail_update = $_POST['detail_update'];
$item = $_POST['item_update'];
$quantity = $_POST['quantity_update'];



$id_new = 0;
	
$select=mysqli_query($db,"SELECT amount,open_id,stock_amount FROM   office_expenses  where id='$id'  ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $amount =$get['amount']; 
    	    $sid =$get['open_id']; 
    	    $stock_amount =$get['stock_amount']; 
		
}}
$new_amount = $amount -	$price;

$select_times=mysqli_query($db,"SELECT amount_remain FROM   office_expenses  where open_id='$sid'  ");
$select_num_times=mysqli_num_rows($select_times);
 WHILE($get_times=mysqli_fetch_array($select_times)){
    	    $cash_remain =$get_times['amount_remain']; 
				
}




$select_open_am=mysqli_query($db,"SELECT amount_remain,id FROM   cash  where id>'$sid'  ");
$select_num_open_am=mysqli_num_rows($select_open_am);
if($select_num_open_am !=0){
   	WHILE($get_open_am=mysqli_fetch_array($select_open_am)){
    	    $cash_id =$get_open_am['id']; 
    	    $cash_remain_all =$get_open_am['amount_remain']; 
    	   
			$new_cash_remain = $cash_remain_all + $new_amount;
			
			
				$update=mysqli_query($db,"UPDATE  cash SET amount_remain='$new_cash_remain'
	 WHERE id='$cash_id'");
			
}}


$select_edit=mysqli_query($db,"SELECT amount,open_id,amount_remain,id FROM   office_expenses  where id>='$id'  ");
$select_num_edit=mysqli_num_rows($select_edit);
if($select_num_edit !=0){
   	WHILE($get_edit=mysqli_fetch_array($select_edit)){
    	    $id_edit=$get_edit['id']; 
    	    $amount =$get_edit['amount']; 
    	    $amount_remain =$get_edit['amount_remain']; 
    	    $sid =$get_edit['open_id']; 
		$update_edit_am = $amount_remain + $new_amount;
//echo "$id_edit --> $update_edit_am and";

	$update=mysqli_query($db,"UPDATE  office_expenses SET amount_remain='$update_edit_am'
	
	 WHERE id='$id_edit'");



}}
if($stock_amount !=0){
	$select_update_amount=mysqli_query($db,"SELECT id FROM   office_expenses  where id>'$id' limit 1  ");
$select_num_update_amount=mysqli_num_rows($select_update_amount);
if($select_num_update_amount !=0){
   	WHILE($get_update_amount=mysqli_fetch_array($select_update_amount)){
    	  
    	    $id_new =$get_update_amount['id']; 
		
}}
//$update=mysqli_query($db,"UPDATE  office_expenses SET stock_amount='$stock_amount' WHERE id='$id_new'");
}

$update=mysqli_query($db,"UPDATE  office_expenses  SET 
	amount='$price',
	cat_group='$group_cat_update',
	name='$expenses_update',
	vehicle='$vehicle_update',
	item='$item',
	quantity='$quantity'
	
	WHERE id='$id'");	
	//include '../update/update_daily_all.php';

?>