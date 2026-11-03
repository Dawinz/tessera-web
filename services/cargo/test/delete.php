<?php
session_start();
?>
<?php
include 'connect.php';
$id = $_POST['id'];
$code = $_POST['code'];
$password = $_POST['password'];
$username_uploader =$_SESSION['username'];






$select2=mysqli_query($db,"SELECT *  FROM  users  where username='".$_SESSION['username']."' and password='$password'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
		WHILE($get123=mysqli_fetch_array($select2)){
$role=$get123['role'];
}
	
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$date_time_insert = date(' h:i A', time());

$select2=mysqli_query($db,"SELECT *  FROM  manifest WHERE code='$code' ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 ==0){
	
	$select12=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE code='$code'");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
WHILE($get12=mysqli_fetch_array($select12)){
$to_be_orginal=$get12['to_be'];
$paid_original=$get12['paid'];
$status=$get12['status'];
$trip=$get12['account']; 
$date_insert=$get12['date_insert']; 
$month=$get12['month']; 
$username_original=$get12['username']; 

}}

if(($username_original !=$username) and ($role !="Administrator")){
	echo "not_uploader";
	}else{
if($status=="To be Payed"){
		$select13=mysqli_query($db,"SELECT to_be   FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
					
$new_to_paid = $to_be - $to_be_orginal;

		

	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid' WHERE location='$trip'");


	}
}	

$select134=mysqli_query($db,"SELECT to_be,no_to_be   FROM  daily_charges  WHERE date_insert = '$date_insert' and  location='$trip' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$to_be1 =$get1334['to_be'];
					$no_to_be =$get1334['no_to_be']; 
				$total_no_to_be=$no_to_be-1;
$new_to_paid1 = $to_be1 - $to_be_orginal;

		

	   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid1',no_to_be='$total_no_to_be' WHERE date_insert = '$date_insert' and location='$trip'");


	}
}


$select1346=mysqli_query($db,"SELECT to_be,no_to_be  FROM  monthly_charges  WHERE date_insert = '$month' and  location='$trip' ");
$select_num1346=mysqli_num_rows($select1346);
if($select_num1346 !=0){
   	WHILE($get13346=mysqli_fetch_array($select1346)){
    	 
					$to_be2 =$get13346['to_be']; 
				$no_to_be_month =$get13346['no_to_be']; 
				$total_no_to_be_month = $no_to_be_month-1;
$new_to_paid2 = $to_be2 - $to_be_orginal;
		

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid2',no_to_be='$total_no_to_be_month' WHERE date_insert = '$month' and location='$trip'");


	}
}

}
	
if($status=="paid"){
$select13=mysqli_query($db,"SELECT paid   FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
 $paid =$get133['paid']; 
$new_paid = $paid - $paid_original;
$insert=mysqli_query($db,"UPDATE store SET paid='$new_paid' WHERE location='$trip'");


	}
}	
$select134=mysqli_query($db,"SELECT paid,no_paid   FROM  daily_charges  WHERE date_insert = '$date_insert' and  location='$trip' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
$paid1 =$get1334['paid']; 
$new_paid1 = $paid1 - $paid_original;

$no_paid =$get1334['no_paid']; 
$totol_no_paid = $no_paid - 1;

$insert=mysqli_query($db,"UPDATE daily_charges SET paid='$new_paid1',no_paid='$totol_no_paid' WHERE date_insert = '$date_insert' and  location='$trip'");



	}
}

$select1345=mysqli_query($db,"SELECT paid,no_paid   FROM  monthly_charges  WHERE date_insert = '$month' and  location='$trip' ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					
					$paid2 =$get13345['paid']; 

$new_paid2 = $paid2 - $paid_original;
		$no_paid_month =$get13345['no_paid']; 
$totol_no_paid_month = $no_paid_month - 1;

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET paid='$new_paid2',no_paid='$totol_no_paid_month' WHERE date_insert = '$month' and  location='$trip'");


	}
}
	
}	
if($status=="Due payment"){
		$select13=mysqli_query($db,"SELECT to_be,paid  FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
					$paid =$get133['paid']; 
					
$new_to_paid = $to_be - $to_be_orginal;
$new_paid = $paid - $paid_original;
$insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid',paid='$new_paid' WHERE location='$trip'");


	}
}
$select134=mysqli_query($db,"SELECT to_be,paid,no_due   FROM   daily_charges  WHERE date_insert = '$date_insert' and  location='$trip'  ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$to_be1 =$get1334['to_be']; 
					$paid1 =$get1334['paid']; 
					$no_due =$get1334['no_due']; 
					$total_no_due=$no_due-1;
$new_to_paid1 = $to_be1 - $to_be_orginal;
$new_paid1 = $paid1 - $paid_original;

		


	   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_paid1',paid='$new_paid1',no_due='$total_no_due' WHERE date_insert = '$date_insert' and location='$trip'");


	}
}	

$select1345=mysqli_query($db,"SELECT to_be,paid,no_due   FROM   monthly_charges  WHERE date_insert = '$month' and  location='$trip'  ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					$to_be2 =$get13345['to_be']; 
					$paid2 =$get13345['paid'];
					$no_due_month =$get13345['no_due']; 
					$totoal_no_due_motnth = $no_due_month-1;
$new_to_paid2 = $to_be2 - $to_be_orginal;
$new_paid2 = $paid2 - $paid_original;

		


	   $insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_paid2',paid='$new_paid2',no_due='$totoal_no_due_motnth' WHERE date_insert = '$month' and location='$trip'");


	}
}	
	
}	
	
$insert=mysqli_query($db,"DELETE from parcel_info WHERE id = '$id'");

$insert2=mysqli_query($db,"UPDATE notification SET status='deleted' ,deleted='".$_SESSION['username']."',deleted_date='$date_insert at $date_time_insert'  WHERE code = '$code'");
echo "deleted";
}}else{
	echo "found";
}

}else{
echo "wrong ";	
}




?>