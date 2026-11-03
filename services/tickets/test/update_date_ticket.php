<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';

include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  manifest  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $id =$get13['id']; 
			$date =$get13['trip_date']; 
			$book_date =$get13['insert_date']; 
            $trip_date = date("Y-m-d", strtotime($date));  
            $book_date = date("Y-m-d", strtotime($book_date));  
//$update=mysqli_query($db,"UPDATE  booking SET trip_date='$trip_date',book_date='$book_date' WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");
$update=mysqli_query($db,"UPDATE  manifest SET trip_date='$trip_date',insert_date='$book_date' WHERE id='$id'");

		
	}
}

?>