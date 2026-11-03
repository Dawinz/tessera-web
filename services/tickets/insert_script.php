<?php
session_start();
?>
<?php
include'connect2.php';

$firstname = $_POST['firstname'];
$s_no = $_POST['s_no'];
$r_firstname = $_POST['r_firstname'];
$r_no = $_POST['r_no'];
$trip = $_POST['trip'];
$amount= $_POST['amount'];
$status_em = $_POST['status_em'];
$detail = $_POST['detail'];
$detail = base64_encode($detail);
$question = $_POST['question'];
$answer = $_POST['answer'];
$loan= @$_POST['loan'];
$answer = preg_replace('/[^0-9.]+/', '', $answer);
$amount = preg_replace('/[^0-9.]+/', '', $amount);
$loan = preg_replace('/[^0-9.]+/', '', $loan);

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$month = date('m-Y', time());
$date_time_insert = date(' h:i A', time());
$rand = (rand(1,10));
//CHECK FOR PAYED STATUS
////////////////////////////
/////////////////////////////

if(!isset($_SESSION['username'])){
	echo "log_out";
}else{


$select12=mysqli_query($db,"SELECT *  FROM  routes WHERE route_full_name='$trip'");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){
    	    $initial =$get132['initial'];	
			$country =$get132['currency'];
			$currency_symbol =$get132['currency_symbol']; 
	

	}
}




$select1=mysqli_query($db,"SELECT code   FROM  code  WHERE location='$trip' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	 
			$code =$get13['code']; 
			$code = $code + 1;
		
$code_plus = "$initial$code";		
	   $insert=mysqli_query($db,"UPDATE code SET code='$code' WHERE location='$trip'");


	}
}else{
	$start_no =1;
	$code_plus = "$initial$start_no";
$insert=mysqli_query($db,"INSERT INTO code (code,location) VALUES
 ('1','$trip')");

}




include 'connect2.php';
$select2=mysqli_query($db,"SELECT *  FROM  users WHERE username='".$_SESSION['username']."'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname_user =$get12['firstname'];
			$surname_user =$get12['surname']; 
			$fullname ="$firstname_user $surname_user"; 
	

	
	}
}









$select1=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE code='$code_plus' and account='$trip'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
echo"equal";
echo "$code_plus";
}else{
if($status_em=="paid"){
	if($answer==""){
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','','No','$question','','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','$amount','','$month','','$currency_symbol','$country','','$loan','','Not paid')");
echo $code_plus;	
}else{
	$due_total= $amount-$answer;
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','$due_total','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','','No','$question','','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','$amount','','$month','','$currency_symbol','$country','','$loan','','Not paid')");
echo $code_plus;	
}


$select13=mysqli_query($db,"SELECT *   FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$paid =$get133['paid']; 
$new_paid = $paid + $amount;
		

	   $insert=mysqli_query($db,"UPDATE store SET paid='$new_paid' WHERE location='$trip'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO store (paid,location) 
VALUES
                                            ('$amount','$trip')");

}

$select134=mysqli_query($db,"SELECT *   FROM  daily_charges  WHERE date_insert ='$date_insert' and  location='$trip' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$paid_daily =$get1334['paid']; 
					$no_paid =$get1334['no_paid']; 
					$total_no_paid = $no_paid +1;
$new_new_paid_daily = $paid_daily + $amount;
		

	   $insert=mysqli_query($db,"UPDATE daily_charges SET paid='$new_new_paid_daily',no_paid='$total_no_paid' WHERE  date_insert ='$date_insert' and  location='$trip'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO daily_charges (paid,location,date_insert,no_paid) 
VALUES
                                            ('$amount','$trip','$date_insert','1')");

}

$select1345=mysqli_query($db,"SELECT *   FROM  monthly_charges  WHERE date_insert ='$month' and  location='$trip' ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					$paid_monthly =$get13345['paid'];
					$no_paid =$get13345['no_paid']; 
					$total_no_paid_month = $no_paid +1;
					
$new_paid_monthly = $paid_monthly + $amount;
		

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET paid='$new_paid_monthly',no_paid='$total_no_paid_month' WHERE  date_insert ='$month' and  location='$trip'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO monthly_charges (paid,location,date_insert,no_paid) 
VALUES
                                            ('$amount','$trip','$month','1')");

}




}else if($status_em=="To be Payed"){
	if($answer==""){
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','','No','$question','','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','','$amount','$month','','$currency_symbol','$country','','$loan','','Not paid')");
echo $code_plus;	
}else{
	$due_total= $amount-$answer;
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','$due_total','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','','No','$question','','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','','$amount','$month','','$currency_symbol','$country','','$loan','','Not paid')");
echo $code_plus;	
}	

$select13=mysqli_query($db,"SELECT *   FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 
$new_to_paid = $to_be + $amount;
		

	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid' WHERE location='$trip'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO store (to_be,location) 
VALUES
                                            ('$amount','$trip')");

}

////////////////////////////////////
////////////////////////////////////// daily/////////////////

$select134=mysqli_query($db,"SELECT *   FROM  daily_charges  WHERE date_insert ='$date_insert' and  location='$trip' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$tobe_daily =$get1334['to_be']; 
					$no_to_be =$get1334['no_to_be']; 
					$total_no_to_be =$no_to_be+1;
					
$new_to_be_daily = $tobe_daily + $amount;
		

	   $insert=mysqli_query($db,"UPDATE daily_charges SET to_be='$new_to_be_daily',no_to_be='$total_no_to_be' WHERE  date_insert ='$date_insert' and  location='$trip'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO daily_charges (to_be,location,date_insert,no_to_be) 
VALUES
                                            ('$amount','$trip','$date_insert','1')");

}
//////////////////////////////////////////////////
/////////////////////////////////monthly//////////////

$select1345=mysqli_query($db,"SELECT *   FROM  monthly_charges  WHERE date_insert ='$month' and  location='$trip' ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					$tobe_monthly =$get13345['to_be']; 
					$no_to_be =$get13345['no_to_be']; 
					$total_no_to_be_month = $no_to_be+1;
$new_to_be_monthly = $tobe_monthly + $amount;
		

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET to_be='$new_to_be_monthly',no_to_be='$total_no_to_be_month' WHERE  date_insert ='$month' and  location='$trip'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO monthly_charges (to_be,location,date_insert,no_to_be) 
VALUES
                                            ('$amount','$trip','$month','1')");

}



}else if($status_em=="Due payment"){
	if($answer==""){
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','','No','$question','','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','','','$month','','$currency_symbol','$country','$code','$loan','','Not paid')");
echo $code_plus;	
}else{
	$due_total= $amount-$answer;
	$to_be_paid= $due_total + $answer ;
		  $insert=mysqli_query($db,"INSERT INTO parcel_info VALUES('','$firstname','$due_total','$s_no','$r_firstname','','$r_no','$trip','','$detail','$status_em','$amount','$code_plus','$answer','No','$question','$due_total','store','$answer','','$question','$date_insert','','$date_time_insert','$fullname','".$_SESSION['username']."','','0','$answer','$due_total','$month','','$currency_symbol','$country','$code','$loan','','Not paid')");
echo $code_plus;	
}	

$select13=mysqli_query($db,"SELECT *   FROM  store  WHERE location='$trip' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_be =$get133['to_be']; 

				$paid =$get133['paid']; 
				$new_to_paid = $to_be + $due_total;
$new_paid = $paid + $answer;



	   $insert=mysqli_query($db,"UPDATE store SET to_be='$new_to_paid',paid='$new_paid' WHERE location='$trip'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO store (paid,to_be,location) 
VALUES
 ('$answer','$due_total','$trip')");

}

$select134=mysqli_query($db,"SELECT *   FROM  daily_charges  WHERE date_insert ='$date_insert' and  location='$trip' ");
$select_num134=mysqli_num_rows($select134);
if($select_num134 !=0){
   	WHILE($get1334=mysqli_fetch_array($select134)){
    	 
					$paid_daily =$get1334['paid']; 

		$tobe_daily =$get1334['to_be']; 
		$no_due =$get1334['no_due']; 
		$total_no_due=$no_due+1;
		
$new_to_be_daily = $tobe_daily + $due_total;
$new_new_paid_daily = $paid_daily + $answer;

	   $insert=mysqli_query($db,"UPDATE daily_charges SET paid='$new_new_paid_daily',to_be='$new_to_be_daily',no_due='$total_no_due' WHERE  date_insert ='$date_insert' and  location='$trip'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO daily_charges (paid,to_be,location,date_insert,no_due) 
VALUES
                                            ('$answer','$due_total','$trip','$date_insert','1')");

}

$select1345=mysqli_query($db,"SELECT *   FROM  monthly_charges  WHERE date_insert ='$month' and  location='$trip' ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 !=0){
   	WHILE($get13345=mysqli_fetch_array($select1345)){
    	 
					$paid_monthly =$get13345['paid']; 
					$tobe_monthly =$get13345['to_be']; 
					$no_due_month =$get13345['no_due']; 
					$total_no_due_month=$no_due_month +1;
$new_paid_monthly = $paid_monthly + $answer;
$new_tobe_monthly = $tobe_monthly + $due_total;
		

	   $insert=mysqli_query($db,"UPDATE monthly_charges SET paid='$new_paid_monthly',to_be='$new_tobe_monthly',no_due='$total_no_due_month' WHERE  date_insert ='$month' and  location='$trip'");


	}
}else{

$insert=mysqli_query($db,"INSERT INTO monthly_charges (paid,to_be,location,date_insert,no_due) 
VALUES
                                            ('$answer','$due_total','$trip','$month','1')");

}

}




}
///////////////////////////
//////CLOSING PAY STATUS///
////////////////////////////
}
 
?>