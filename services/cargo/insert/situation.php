<?php
include 'connect.php';
$situation = $_POST['situation'];
$location = $_POST['location'];
$subject = $_POST['subject'];
$detail = $_POST['detail'];
$monthyear = $_POST['monthyear'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('h:i A', time());
$detail = base64_encode($detail);
//////////////////////////////////////
/////////////////////////////////////
            
			$select1=mysqli_query($db,"SELECT *  FROM situation  ORDER BY id DESC LIMIT 1 ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $name2=$get13['ob'];   
		
	}
}
	
			
////////////////////////////////////
///////////////////////////////////
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-t', time());
 
//Last date of current month.
$lastDateOfMonth = date("Y-m-t", strtotime($date_insert));
 
if($date_insert == $lastDateOfMonth && $name2 == 0){
			$ob = $name2 + 1;

			$select2=mysqli_query($db,"SELECT *  FROM situation  Where year = '$date_insert'");
$select_num2=mysqli_num_rows($select2);

if($select_num2 ==0){
	   $insert=mysqli_query($db,"INSERT INTO situation VALUES('','$situation','$subject','$detail','$date_insert','$date_insert','$location','0')");

}else{
		   $insert=mysqli_query($db,"INSERT INTO situation VALUES('','$situation','$subject','$detail','$date_insert','$date_insert','$location','$ob')");
	
}

}else{
		$ob = $name2 + 1;

		   $insert=mysqli_query($db,"INSERT INTO situation VALUES('','$situation','$subject','$detail','$date_insert','$date_insert','$location','$ob')");


}

   

?>