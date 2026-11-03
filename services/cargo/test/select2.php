
<?php



include '../connect.php';


$select=mysqli_query($db,"SELECT *  FROM  parcel_info  WHERE code='25'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	 $r_no=$get['r_no'];  
			$code=$get['code'];
			$account=$get['account'];  
			$r_name=$get['r_name'];   
			$r_no = substr($r_no, 1);
	
			echo $r_no;
	
	}

}
?>