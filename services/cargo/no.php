<?php
if($x==1){

	    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'A1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
		$nid = $get2['nid'];	
		$id_no = $get2['id_no'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:70px;'>A1
			<div >$firstname $surname<br/>$id_no<br>$nid </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>$x A1<br>
			<div ></div>
			</div>"; 
		  }
}else if ($x == 2){
	echo "";
		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'A2,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
					$id_no = $get2['id_no'];
		     $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> A2<br>
			<div >$firstname $surname<br/>$id_no <br> $nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			  	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>A2<br>
			<div ></div>
			</div>"; 
		  
			
		  }
} else if ($x == 3){

			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'A3,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			$id_no = $get2['id_no'];
		  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>A3<br>
			<div >$firstname $surname<br/>$nid <br>$id_no</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			  
			    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>A3<br>
			<div ></div>
			</div>"; 
		  
		  }
} else if ($x == 4){

				    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'A4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

			$firstname = $get2['firstname'];
		$id_no = $get2['id_no'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>A4<br>
			<div >$firstname $surname<br/>$nid <br>$id_no</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			  
			   
			    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>A4<br>
			<div ></div>
			</div>"; 
		  }
}else if ($x == 5){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'B1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			$id_no = $get2['id_no'];
					  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> B1<br>
			<div >$firstname $surname<br/>$id_no<br> $nid </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			 	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>B1<br>
			<div ></div>
			</div>";  
		  }
}else if($x == 6){

			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'B2,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
		
					  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> B2<br>
			<div >$firstname $surname<br/>$id_no<br>$nid </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			   	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>B2<br>
			<div ></div>
			</div>";  
		  }
}else if ($x == 7){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'B3,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> B3<br>
			<div >$firstname $surname<br/>$id_no<br>$nid </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			  		   	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>B3<br>
			<div ></div>
			</div>";  
		  }
} else if ($x == 8){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'B4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> B4<br>
			<div >$firstname $surname<br/>$id_no <br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			  		   	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>B4<br>
			<div ></div>
			</div>"; 
		  }
} else if ($x == 9){
		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'C1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> C1<br>
			<div>$firstname $surname<br/>$nid<br>$id_no</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
					  		   	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>C1<br>
			<div ></div>
			</div>";   
		  }
}else if ($x == 10){
	
			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'C2,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> C2<br>
			<div >$firstname $surname<br/>$id_no<br>$nid </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			 			  		   	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>C2<br>
			<div ></div>
			</div>";   
		  }
}else if($x == 11){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'C3,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> C3<br>
			<div >$firstname $surname<br/>$id_no<br>$nid</div>
			</div>";
			
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
				 			  		   	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>C3<br>
			<div ></div>
			</div>";     
			  
		  }
}else if ($x == 12){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'C4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> C4<br>
			<div >$firstname $surname<br/>$nid <br>$id_no</div>
			</div>";
			
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			  		 			  		   	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>C4<br>
			<div ></div>
			</div>"; 
		  }
} else if ($x == 13){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'D1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

	$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> D1<br>
			<div >$firstname $surname<br/>$id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			  	  		 			  		   	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>D1<br>
			<div ></div>
			</div>"; 
		  }
} else if ($x == 14){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'A4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

	$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> D2<br>
			<div >$firstname $surname<br/>$id_no<br>$nid </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

}}else{
	  	  		 			  		   	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>D2<br>
			<div ></div>
			</div>"; 
}
}else if ($x == 15){
	
			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'D3,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
	$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> D3<br>
			<div >$firstname $surname<br/>$id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			 		 			  		   	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>D3<br>
			<div ></div>
			</div>";  
		  }
}else if($x == 16){
		
	    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'D4,%' and  LEFT(booking_date ,10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

		
			$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> D4<br>
			<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}	else{
			  			 		 			  		   	    	echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>D4<br>
			<div ></div>
			</div>";  
		  }	
}else if ($x == 17){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'E1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
		
			$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> E1<br>
			<div >$firstname $surname<br/>$id_no<br>$nid</div>
			</div>";
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>E1<br>
			<div ></div>
			</div>";  
		  }	
		  }
 else if ($x == 18){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'E2,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

		$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> E2<br>
			<div >$firstname $surname<br/>$id_no<br>$nid </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			  echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>E2<br>
			<div ></div>
			</div>"; 
		  }
} else if ($x == 19){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'E3,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

		$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> E3<br>
			<div >$firstname $surname<br/>$id_no<br>$nid </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			    echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>E3<br>
			<div ></div>
			</div>"; 
		  }
}else if ($x == 20){
		
			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'E4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){

			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];

		$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> E4<br>
			<div >$firstname $surname<br/>$id_no<br>$nid </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			 	    echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>E4<br>
			<div ></div>
			</div>";  
		  }
}else if($x == 21){
	
			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'F1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
	$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> F1<br>
			<div >$firstname $surname<br/>$nid <br>$id_no</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			 	    echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>F1<br>
			<div ></div>
			</div>";   
		  }
}else if ($x == 22){
	
		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'F2,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
	$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> F1<br>
			<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			  			 	    echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>F1<br>
			<div ></div>
			</div>";   
		  }
} else if ($x == 23){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'F3,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>F3<br>
			<div >$firstname $surname<br/>$id_no<br>$nid </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
   echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>F3<br>
			<div ></div>
			</div>";
		  }
} else if ($x == 24){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'F4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> F4<br>
			<div >$firstname $surname<br/>$id_no <br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			   echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>F4<br>
			<div ></div>
			</div>";  
		  }
}else if ($x == 25){

			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'G1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> G1<br>
			<div >$firstname $surname<br/> $nid<br>$id_no </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			 	   echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>G1<br>
			<div ></div>
			</div>";   
		  }
}else if($x == 26){
		
			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'G2,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> G2<br>
			<div >$firstname $surname<br/>$nid <br>$id_no</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

}}else{
	 	   echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>G2<br>
			<div ></div>
			</div>";  	
}
}else if ($x == 27){
	
		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'G3,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> G3<br>
			<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			   echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>G3<br>
			<div ></div>
			</div>";   
		  }
} else if ($x == 28){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'G4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> G4<br>
			<div >$firstname $surname<br/>$id_no<br>$nid </div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			 			   echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>G4<br>
			<div ></div>
			</div>";  
		  }
} else if ($x == 29){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'H1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> H1<br>
			<div >$firstname $surname<br/>$id_no <br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			  		 			   echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>H1<br>
			<div ></div>
			</div>";  
		  }
}else if ($x == 30){

			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'H1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> H2<br>
			<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
	 echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>H2<br>
			<div ></div>
			</div>";
		  }
}else if($x == 31){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'H3,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
$id_no = $get2['id_no'];
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> H3<br>
		<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
					  			  		 			   echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>H3<br>
			<div ></div>
			</div>";	  
		  }
}else if ($x == 32){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'H4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
			$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> H4<br>
<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";

			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
			  					  			  		 			   echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>H4<br>
			<div ></div>
			</div>";	  
		  }
} else if ($x == 33){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'I1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
		$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> 11<br>
<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";

			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
		echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>I1<br>
			<div ></div>
			</div>";	  
		  }
} else if ($x == 34){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'I2,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
		$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> I2<br>
<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";

			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
		echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>I2<br>
			<div ></div>
			</div>";			  
		  }
}else if ($x == 35){
	
			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'I3,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
		$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> I3<br>
<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
					echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>I3<br>
			<div ></div>
			</div>";  
		  }
}else if($x == 36){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'I4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
		$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> I4<br>
<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
							echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>I4<br>
			<div ></div>
			</div>";    
		  }
}else if ($x == 37){
	
		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'J1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
		$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> J1<br>
<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
							echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>J1<br>
			<div ></div>
			</div>";  			  
		  }
} else if ($x == 38){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'J2,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
		$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> J2<br>
			<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
						echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>J2<br>
			<div ></div>
			</div>"; 			  
		  }
} else if ($x == 39){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'J3,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
	$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> J3<br>
<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
							echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>J3<br>
			<div ></div>
			</div>"; 			  
		  }
}else if ($x == 40){

			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'J4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
	$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> J4<br>
			<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
								echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>J4<br>
			<div ></div>
			</div>"; 		  
		  }
}else if($x == 41){

			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'K1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
	$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> K1<br>
			<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
								echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>K1<br>
			<div ></div>
			</div>"; 			  
		  }
}else if ($x == 42){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'A4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
	$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> K2<br>
			<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
									echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>K2<br>
			<div ></div>
			</div>"; 		  
		  }
} else if ($x == 43){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'K2,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
	$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>K3<br>
		<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
									echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>K3<br>
			<div ></div>
			</div>"; 				  
		  }
} else if ($x == 44){

		    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'K3,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
	$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> K4<br>
<div >$firstname $surname<br/> $id_no<br>$nid</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
									echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>K4<br>
			<div ></div>
			</div>"; 			  
		  }
}else if ($x == 45){

			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'K4,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
	$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> B1<br>
			<div >$firstname $surname<br/>$nid <br>$id_no</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
									echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>$x A4<br>
			<div ></div>
			</div>"; 		  
		  }
}else if($x == 46){
		echo "$x L1<br>";
			    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no='$route_id' and  seat_numbers like 'L1,%' and  LEFT(booking_date , 10) = '$date_search'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
$nr++;
			$price = $get1['price'];
			$tkt_passenger_id_no = $get1['tkt_passenger_id_no'];
			
			/////////////////////////////////
			/////////////////////////////////
			    $select2=mysqli_query($db,"SELECT * FROM tkt_passenger where id_no = '$tkt_passenger_id_no'");
$select_num2=mysqli_num_rows($select1);
if($select_num2 !=0){
    	WHILE($get2=mysqli_fetch_array($select2)){
	$firstname = $get2['firstname'];
			$surname = $get2['lastname'];
			  $nid = $get2['nid'];
			echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'> B1<br>
			<div >$firstname $surname<br/>$nid <br>$id_no</div>
			</div>";
			
    	  }}
				
				//////////////////////////////////
				/////////////////////////////////
			
			

    	  }}else{
									echo "<div class='text-center'style='border-radius:5px;border:1px solid black;height:90px;'>$x A4<br>
			<div ></div>
			</div>"; 	  
		  }
} else if ($x == 47){
	echo "$x L2<br>";
}else if ($x == 48){
		echo "$x L3<br>";
}else if($x == 49){
	echo "$x L4<br>";
}else if ($x == 50){
	echo "$x M1<br>";
} else if ($x == 51){
	echo "M2<br>";
} else if ($x == 52){
	echo "M3<br>";
}else if ($x == 53){
		echo "M3<br>";
}else if($x == 54){
		echo "M4<br>";
} 
?>