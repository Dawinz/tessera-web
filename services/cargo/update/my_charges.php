<?php

$daily_query = "daily_charges";

update_charges ($db,$date_insert,$trip,$username_original,$status,$tobe,$paid,$daily_query);



$daily_query = "monthly_charges";
$date_insert = $month;

update_charges ($db,$date_insert,$trip,$username_original,$status,$tobe,$paid,$daily_query);

function update_charges ($db,$date_insert,$trip,$username_original,$status,$tobe,$paid,$daily_query){

    $select_charges=mysqli_query($db,"SELECT to_be,paid,no_to_be,no_paid,no_due   FROM  $daily_query  WHERE date_insert = '$date_insert' and  location='$trip' and username='$username_original' ");
$select_num_charges=mysqli_num_rows($select_charges);
if($select_num_charges !=0){
   	WHILE($get_charges=mysqli_fetch_array($select_charges)){
    	 
					$to_be_og =$get_charges['to_be']; 
					$paid_og =$get_charges['paid']; 
					$no_to_be_og =$get_charges['no_to_be']; 
					$no_paid_og =$get_charges['no_paid']; 
					$no_due_og =$get_charges['no_due']; 
    }}

    $new_to_be = $to_be_og - $tobe;
    $new_paid = $paid_og - $paid;

   if($status=="To be Payed"){
    $no_to_be_og = $no_to_be_og - 1;
   }else if($status=="paid"){
    $no_paid_og = $no_paid_og - 1;

   }else if($status=="Due payment"){
    $no_due_og = $no_due_og - 1;

   }


mysqli_query($db,"UPDATE $daily_query SET to_be='$new_to_be',paid='$new_paid',no_to_be='$no_to_be_og',no_paid='$no_paid_og',no_due='$no_due_og' WHERE date_insert = '$date_insert' and  location='$trip' and username='$username_original'");

}
 
?>