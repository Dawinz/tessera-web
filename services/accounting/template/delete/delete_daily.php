<?php
include '../connect/connect.php';
$id = $_POST['id'];
$id_new = 0;
$select=mysqli_query($db,"SELECT amount,open_id,stock_amount FROM   office_expenses  where id='$id'  ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $amount =$get['amount']; 
    	    $sid =$get['open_id']; 
    	    $stock_amount =$get['stock_amount']; 
		
}}
$select_times=mysqli_query($db,"SELECT amount,open_id FROM   office_expenses  where open_id='$sid'  ");
$select_num_times=mysqli_num_rows($select_times);

$select_open_am=mysqli_query($db,"SELECT date,initial_am_remain,id,opening_stock,amount_remain,open_date,initial_am_remain  FROM   cash  where id='$sid'  ");
$select_num_open_am=mysqli_num_rows($select_open_am);
if($select_num_open_am !=0){
   	WHILE($get_open_am=mysqli_fetch_array($select_open_am)){
    	    $amount_remain_current =$get_open_am['amount_remain']; 
    	    $initial_am_remain =$get_open_am['initial_am_remain']; 
		


		
}}

$select_open_edit=mysqli_query($db,"SELECT date,initial_am_remain,id,opening_stock,amount_remain,open_date,initial_am_remain  FROM   cash  where id>='$sid'  ");
$select_num_open_edit=mysqli_num_rows($select_open_edit);
if($select_num_open_edit !=0){
   	WHILE($get_open_edit=mysqli_fetch_array($select_open_edit)){
    	    $amount_remain_current =$get_open_edit['amount_remain']; 
    	    $update_id =$get_open_edit['id']; 
    	    $initial_am_remain =$get_open_edit['amount_remain']; 
		$update_amm_r = $initial_am_remain + $amount;

		$update=mysqli_query($db,"UPDATE  cash SET amount_remain='$update_amm_r' WHERE id='$update_id'");


		
}}
$select_edit=mysqli_query($db,"SELECT amount,open_id,amount_remain,id FROM   office_expenses  where id>='$id'  ");
$select_num_edit=mysqli_num_rows($select_edit);
if($select_num_edit !=0){
   	WHILE($get_edit=mysqli_fetch_array($select_edit)){
    	    $id_edit=$get_edit['id']; 
    	    $amount =$get_edit['amount']; 
    	    $amount_remain =$get_edit['amount_remain']; 
    	    $sid =$get_edit['open_id']; 
		$update_edit_am = $amount_remain + $amount;

	$update=mysqli_query($db,"UPDATE  office_expenses SET amount_remain='$update_edit_am' WHERE id='$id_edit'");

if($select_num_times==1){
	$delete=mysqli_query($db,"DELETE from cash WHERE id = '$sid'");

}

}}

if($stock_amount !=0){
	$select_update_amount=mysqli_query($db,"SELECT id FROM   office_expenses  where id>'$id' limit 1  ");
$select_num_update_amount=mysqli_num_rows($select_update_amount);
if($select_num_update_amount !=0){
   	WHILE($get_update_amount=mysqli_fetch_array($select_update_amount)){
    	  
    	    $id_new =$get_update_amount['id']; 
		
}}
$update=mysqli_query($db,"UPDATE  office_expenses SET stock_amount='$stock_amount' WHERE id='$id_new'");
}


$delete=mysqli_query($db,"DELETE from office_expenses WHERE id = '$id'");


?>