				<?php
				include 'connect.php';
								//////////////////////////////////////
								/////////////////////////////////////charges//////////////////////
								//////////////////////////////////////////////////
								
									$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where   status='paid'and date_insert >= ' $date1' and date_insert <= ' $date2' and month='$month'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}
				?>