<?php
include 'connect/connect.php';


$id="1";



$select=mysqli_query($db,"SELECT 

		ROW_NUMBER() OVER (
		order by id asc
	) row_num,date,id,opening_stock,amount_remain,open_date,initial_am_remain 
FROM   cash   where id >='$id' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $open_date =$get['open_date']; 
    	    $opening_stock =$get['opening_stock']; 
    	    $open_id =$get['id']; 
    	    $initial_am_remain =$get['initial_am_remain']; 
    	    $row_num =$get['row_num']; 
 if($row_num ==1){
echo "$initial_am_remain";

 $select_open=mysqli_query($db,"SELECT 	ROW_NUMBER() OVER (
		order by id asc
	) row_num,amount,amount_remain,id,cash_hand,cash_id FROM   office_expenses  where cash_id='$open_id'  ");
$select_num_open=mysqli_num_rows($select_open);
if($select_num_open !=0){
   	WHILE($get_open=mysqli_fetch_array($select_open)){
    	    $id_current =$get_open['id']; 
    	    $amount =$get_open['amount']; 
    	    $amount_remain =$get_open['amount_remain']; 
    	    $cash_id =$get_open['cash_id']; 
    	    $row_num_expnes =$get_open['row_num']; 
			
			
$select_open_am=mysqli_query($db,"SELECT date,initial_am_remain,id,opening_stock,amount_remain,open_date,initial_am_remain  FROM   cash  where id='$cash_id'  ");
$select_num_open_am=mysqli_num_rows($select_open_am);
if($select_num_open_am !=0){
   	WHILE($get_open_am=mysqli_fetch_array($select_open_am)){
    	    $amount_remain_current =$get_open_am['amount_remain']; 
    	    $initial_am_remain =$get_open_am['initial_am_remain']; 
		


		
}}
	if($row_num_expnes ==1){
		$new_amount_rmn = $initial_am_remain -$amount;	
			$update=mysqli_query($db,"UPDATE  cash  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$open_id'");
	
	$update=mysqli_query($db,"UPDATE  office_expenses  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$id_current'");
	
	echo "$new_amount_rmn-$amount  $cash_id<br>";
	
	}else{
				$new_amount_rmn = $amount_remain_current -$amount;	
	$update=mysqli_query($db,"UPDATE  cash  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$open_id'");
	
	$update=mysqli_query($db,"UPDATE  office_expenses  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$id_current'");
	
	echo "$new_amount_rmn-$amount  $cash_id<br>";
	}

			
}}

	}else{
		
		
		$select_prv=mysqli_query($db,"SELECT 

		ROW_NUMBER() OVER (
		
	) row_num,date,id,opening_stock,amount_remain,open_date,initial_am_remain 
FROM   cash   where id <'$open_id' limit 1 ");
$select_num_prv=mysqli_num_rows($select_prv);
if($select_num_prv !=0){
   	WHILE($get_prv=mysqli_fetch_array($select_prv)){
    	    $id_prv =$get_prv['id']; 
    	    $amount_remain_prv =$get_prv['amount_remain']; 

		$select_this=mysqli_query($db,"SELECT 

		ROW_NUMBER() OVER (
		
	) row_num,date,id,opening_stock,amount_remain,open_date,initial_am_remain 
FROM   cash   where id ='$open_id' ");
$select_num_this=mysqli_num_rows($select_this);
if($select_num_this !=0){
   	WHILE($get_this=mysqli_fetch_array($select_this)){
    	    $id_this =$get_this['id']; 
    	    $amount_remain_this =$get_this['opening_stock']; 
    	    $id_this =$get_this['id']; 
			
			
$new_amount_rmn = $amount_remain_prv +  $amount_remain_this;
			
		$update=mysqli_query($db,"UPDATE  cash  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$open_id'");	
	
	
	
	echo "$initial_am_remain";

 $select_open=mysqli_query($db,"SELECT 	ROW_NUMBER() OVER (
		order by id asc
	) row_num,amount,amount_remain,id,cash_hand,cash_id FROM   office_expenses  where cash_id='$open_id'  ");
$select_num_open=mysqli_num_rows($select_open);
if($select_num_open !=0){
   	WHILE($get_open=mysqli_fetch_array($select_open)){
    	    $id_current =$get_open['id']; 
    	    $amount =$get_open['amount']; 
    	    $amount_remain =$get_open['amount_remain']; 
    	    $cash_id =$get_open['cash_id']; 
    	    $row_num_expnes =$get_open['row_num']; 
			
			
$select_open_am=mysqli_query($db,"SELECT date,initial_am_remain,id,opening_stock,amount_remain,open_date,initial_am_remain  FROM   cash  where id='$cash_id'  ");
$select_num_open_am=mysqli_num_rows($select_open_am);
if($select_num_open_am !=0){
   	WHILE($get_open_am=mysqli_fetch_array($select_open_am)){
    	    $amount_remain_current =$get_open_am['amount_remain']; 
    	    $initial_am_remain =$get_open_am['initial_am_remain']; 
		


		
}}
	if($row_num_expnes ==1){
		$new_amount_rmn = $amount_remain_current -$amount;	
			$update=mysqli_query($db,"UPDATE  cash  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$open_id'");
	
	$update=mysqli_query($db,"UPDATE  office_expenses  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$id_current'");
	
	echo "$new_amount_rmn-$amount  $cash_id<br>";
	
	}else{
				$new_amount_rmn = $amount_remain_current -$amount;	
	$update=mysqli_query($db,"UPDATE  cash  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$open_id'");
	
	$update=mysqli_query($db,"UPDATE  office_expenses  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$id_current'");
	
	echo "$new_amount_rmn-$amount  $cash_id<br>";
	}

			
}}

	
			
			
			
}}




	


			
}}
		
		
		
		
	}




}

}




/*

		$total_expense = 0;
		 
		 $select_open=mysqli_query($db,"SELECT amount,amount_remain,id,cash_hand,cash_id FROM   office_expenses  where cash_id='$open_id'  ");
$select_num_open=mysqli_num_rows($select_open);
if($select_num_open !=0){
   	WHILE($get_open=mysqli_fetch_array($select_open)){
    	    $id_current =$get_open['id']; 
    	    $amount =$get_open['amount']; 
    	    $amount_remain_single =$get_open['amount_remain']; 
    	    $cash_hand =$get_open['cash_hand']; 
    	    $cash_id =$get_open['cash_id']; 
			
		$total_expense = $total_expense +$amount;
			
			$amount_last ="";
			$amount_remain_single_last =0;
			$id_last =0;
			$cash_hand_last =0;
$select_open_last=mysqli_query($db,"SELECT amount,amount_remain,id,cash_hand FROM   office_expenses  where id <'$id_current'  and cash_id='$open_id'");
$select_num_open_last=mysqli_num_rows($select_open_last);
if($select_num_open_last !=0){
   	WHILE($get_open_last=mysqli_fetch_array($select_open_last)){
    	    $id_last =$get_open_last['id']; 
    	    $amount_last =$get_open_last['amount']; 
    	    $amount_remain_single_last =$get_open_last['amount_remain']; 
    	    $cash_hand_last =$get_open_last['cash_hand']; 
			
}}
if($cash_hand !=0){
}else{
	$new_amount_rmn = $amount_remain_single_last - $amount;	
	echo "$open_id $cash_hand +  $amount_remain_single_last $amount == $new_amount_rmn     $id_current <br>";
/*$update=mysqli_query($db,"UPDATE  office_expenses  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$id_current'");
	
}
	
	
	
	/*	
			if($cash_hand !=0){
				$new_amount_rmn = $amount_remain_single_last + $cash_hand;
				//echo "$open_id $cash_hand +  $amount_remain_single_last $amount <br>";
						$total_amount = 0;		
$select_total=mysqli_query($db,"SELECT amount,amount_remain,id,cash_hand FROM   office_expenses  where cash_id ='$open_id' ");
$select_num_total=mysqli_num_rows($select_total);
if($select_num_total !=0){
   	WHILE($get_total=mysqli_fetch_array($select_total)){
     
    	    $amount_total =$get_total['amount']; 
				$total_amount += $amount_total;
    	 
}}			
						$new_amount_rmn = $amount_remain_single - $total_amount;	
						echo "*$new_amount_rmn $open_id* <br>";	
		$update=mysqli_query($db,"UPDATE  cash  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$open_id'");
			}else{
				
		
				$new_amount_rmn = $amount_remain_single_last -  $amount;
								echo " $amount_remain_single_last -  $amount = $new_amount_rmn ($id_current)<br>";
$update=mysqli_query($db,"UPDATE  office_expenses  SET 
	amount_remain='$new_amount_rmn'
	WHERE id = '$id_current'");



	
			}
			
			 
				 
}
echo "$opening_stock $total_expense $amount_remain_single<br>";
if( $row_num==1){
	
	
	$select_id=mysqli_query($db,"SELECT amount,amount_remain,id,cash_hand FROM   office_expenses  where   cash_id='$open_id' order by id desc limit 1");
$select_num_id=mysqli_num_rows($select_id);
if($select_num_id !=0){
   	WHILE($get_id=mysqli_fetch_array($select_id)){
    	
    	    $amount_id=$get_id['amount_remain']; 
    	   
	/*			$update=mysqli_query($db,"UPDATE  cash  SET 
	amount_remain='$amount_id'
	WHERE id = '$open_id'");
	
}}
echo "($amount_id)";	
}else{
	
}

}
		 */



?>