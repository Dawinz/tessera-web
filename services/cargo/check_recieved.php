<?php
session_start();
?>
<?php
include 'connect.php';
$id=$_POST['id'];
$date_insert = date('Y-m-d', time());
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
	

	
	}
}
	echo "out";   
	

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


$select_tick=mysqli_query($db,"SELECT *  FROM  checked_manifest where name='$bus' and trip_date='$date'");
$select_num_tick=mysqli_num_rows($select_tick);
if($select_num_tick !=0){
   	WHILE($get=mysqli_fetch_array($select_tick)){
    	    $times =$get['times']; 
		$times = $times+1;
		
		if($times >= 5){
			$img= "PHN0cm9uZz48c3ZnIHN0eWxlPSJjb2xvcjojYjgxMDA0InhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjE2IiBoZWlnaHQ9IjE2IiBmaWxsPSJjdXJyZW50Q29sb3IiIGNsYXNzPSJiaSBiaS13aGF0c2FwcCIgdmlld0JveD0iMCAwIDE2IDE2Ij4NCiAgPHBhdGggZD0iTTEzLjYwMSAyLjMyNkE3Ljg1NCA3Ljg1NCAwIDAgMCA3Ljk5NCAwQzMuNjI3IDAgLjA2OCAzLjU1OC4wNjQgNy45MjZjMCAxLjM5OS4zNjYgMi43NiAxLjA1NyAzLjk2NUwwIDE2bDQuMjA0LTEuMTAyYTcuOTMzIDcuOTMzIDAgMCAwIDMuNzkuOTY1aC4wMDRjNC4zNjggMCA3LjkyNi0zLjU1OCA3LjkzLTcuOTNBNy44OTggNy44OTggMCAwIDAgMTMuNiAyLjMyNnpNNy45OTQgMTQuNTIxYTYuNTczIDYuNTczIDAgMCAxLTMuMzU2LS45MmwtLjI0LS4xNDQtMi40OTQuNjU0LjY2Ni0yLjQzMy0uMTU2LS4yNTFhNi41NiA2LjU2IDAgMCAxLTEuMDA3LTMuNTA1YzAtMy42MjYgMi45NTctNi41ODQgNi41OTEtNi41ODRhNi41NiA2LjU2IDAgMCAxIDQuNjYgMS45MzEgNi41NTcgNi41NTcgMCAwIDEgMS45MjggNC42NmMtLjAwNCAzLjYzOS0yLjk2MSA2LjU5Mi02LjU5MiA2LjU5MnptMy42MTUtNC45MzRjLS4xOTctLjA5OS0xLjE3LS41NzgtMS4zNTMtLjY0Ni0uMTgyLS4wNjUtLjMxNS0uMDk5LS40NDUuMDk5LS4xMzMuMTk3LS41MTMuNjQ2LS42MjcuNzc1LS4xMTQuMTMzLS4yMzIuMTQ4LS40My4wNS0uMTk3LS4xLS44MzYtLjMwOC0xLjU5Mi0uOTg1LS41OS0uNTI1LS45ODUtMS4xNzUtMS4xMDMtMS4zNzItLjExNC0uMTk4LS4wMTEtLjMwNC4wODgtLjQwMy4wODctLjA4OC4xOTctLjIzMi4yOTYtLjM0Ni4xLS4xMTQuMTMzLS4xOTguMTk4LS4zMy4wNjUtLjEzNC4wMzQtLjI0OC0uMDE1LS4zNDctLjA1LS4wOTktLjQ0NS0xLjA3Ni0uNjEyLTEuNDctLjE2LS4zODktLjMyMy0uMzM1LS40NDUtLjM0LS4xMTQtLjAwNy0uMjQ3LS4wMDctLjM4LS4wMDdhLjcyOS43MjkgMCAwIDAtLjUyOS4yNDdjLS4xODIuMTk4LS42OTEuNjc3LS42OTEgMS42NTQgMCAuOTc3LjcxIDEuOTE2LjgxIDIuMDQ5LjA5OC4xMzMgMS4zOTQgMi4xMzIgMy4zODMgMi45OTIuNDcuMjA1Ljg0LjMyNiAxLjEyOS40MTguNDc1LjE1Mi45MDQuMTI5IDEuMjQ2LjA4LjM4LS4wNTggMS4xNzEtLjQ4IDEuMzM4LS45NDMuMTY0LS40NjQuMTY0LS44Ni4xMTQtLjk0My0uMDQ5LS4wODQtLjE4Mi0uMTMzLS4zOC0uMjMyeiIvPg0KPC9zdmc+PC9zdHJvbmc+";
	$update=mysqli_query($db,"UPDATE vehicle_name SET img='$img',disable='disabled' WHERE date ='$date' and Name='$bus'");
			echo "great";
		}else{
					$update=mysqli_query($db,"UPDATE checked_manifest SET times='$times' WHERE  name='$bus' and trip_date='$date'");
	echo "not great";
		}

	}
	}else{
		$insert=mysqli_query($db,"INSERT INTO checked_manifest VALUES('','$bus','$date','','')");

	}

?>