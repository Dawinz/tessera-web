<?php
session_start();
?>
<?php
include 'connect.php';
$id=$_POST['id'];
$date_insert = date('m/d/Y', time());
$date_time_insert = date('h:i A', time());

$select2=mysqli_query($db,"SELECT *  FROM  manifest where id='$id'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){

   	WHILE($get12=mysqli_fetch_array($select2)){
$checked =$get12['checked'];
 $date =$get12['trip_date'];
 $bus =$get12['bus']; 
 $paid =$get12['paid']; 
 $to_be =$get12['to_be']; 
 $amount =$get12['amount']; 
 $username =$get12['username']; 
 $code =$get12['code']; 
 $q_left =$get12['q_left'];
 $quantity =$get12['quantity']; 
if($checked == ""){
	   $insert=mysqli_query($db,"UPDATE manifest SET checked='out',username_taken='".$_SESSION['username']."',timetaken='$date_insert $date_time_insert' WHERE id ='$id'");
	   
	   	   $select2=mysqli_query($db,"SELECT *  FROM  vehicle_name  WHERE Name='$bus' and date='$date'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $l_paid =$get12['l_paid']; 
			$l_to_be=$get12['l_to_be'];
			$l_amount=$get12['l_amount'];
			
			$add_to_be = $to_be + $l_to_be;
			$add_paid = $paid + $l_paid;
			$add_amount =  $amount + $l_amount;
			
			
	$update=mysqli_query($db,"UPDATE vehicle_name SET l_paid='$add_paid ',l_to_be='$add_to_be',l_amount='$add_amount' WHERE date ='$date' and Name='$bus'");
	
$select=mysqli_query($db,"SELECT *  FROM  parcel_info  WHERE code='$code' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $s_no=$get['s_no'];  
			$code=$get['code'];  
			$s_name=$get['s_name'];   
			$s_no = substr($s_no, 1);
			$s_no_str = strlen($s_no);
			if($s_no_str ==12){
			
	$sms="
Sr Classic Coach. Ndugu $username Mzigo wako ($q_left)s wenye Nambari $code umechukuliwa Tarehe $date_insert saa $date_time_insert Sr Classic Coach.
";
 $data = [
    'phone' => ''.$s_no.'', // Receivers phone
    'body' => ''.$sms.'', // Message
];
$json = json_encode($data); // Encode data to JSON
// URL for request POST /message
$token = 'ork0odk5fsdmnwjw';
$instanceId = '349661';
$url = 'https://api.chat-api.com/instance'.$instanceId.'/message?token='.$token;
// Make a POST request
$options = stream_context_create(['http' => [
        'method'  => 'POST',
        'header'  => 'Content-type: application/json',
        'content' => $json
    ]
]);
// Send a request
$result = file_get_contents($url, false, $options);
 
			}else{
			
			
			}
			
	
	}

}

	
	}
}

}else{
	   $insert=mysqli_query($db,"UPDATE manifest SET checked='' WHERE id ='$id'");
	   	   	   $select2=mysqli_query($db,"SELECT *  FROM  vehicle_name  WHERE Name='$bus' and date='$date'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $l_paid =$get12['l_paid']; 
			$l_to_be=$get12['l_to_be'];
			$l_amount=$get12['l_amount'];
			
			$add_to_be =  $l_to_be - $to_be;
			$add_paid =   $l_paid - $paid;
			$add_amount = $l_amount - $amount;
			
			
	$update=mysqli_query($db,"UPDATE vehicle_name SET l_paid='$add_paid ',l_to_be='$add_to_be',l_amount='$add_amount' WHERE date ='$date' and Name='$bus'");
	}
}



}


		
	}
	
}


?>