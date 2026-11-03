<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

 $route_day_id = $_GET['id'];	
$trip_date = $_GET['trip_date'];	
$route_id = $_GET['route_id'];	
include 'connect.php';
$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$route_day_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $route_id =$get134['route_id'];
			$bus =$get134['bus'];
	
		

		
	}
}

$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];

		

		
	}
}

$select145=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$bus' ");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	    $driver =$get1345['driver'];
    	    $conductor =$get1345['conductor'];

		

		
	}
}
$select1356=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $seats= $get13356['seats'];



}}
 ?>
 
 <style>
#seat_array {
  spanst-style-type: none;
  margin-top: 8;
  padding: 0;
  overflow: hidden;
  
}

.seat_col {
  float: left;
  color:black;
  
}


.seat_holder {
	border:2px solid green;
	width:70px;
	height:73px;
	margin:5px;
}
.blank_seat_holder {

	width:70px;
	height:73px;
	margin:5px;
}

#seat_number_ar{

	border:1px solid black;
}
#name_passenger{
		border:1px solid black;
width:300px;
}
#seat_chart{
	font-size:30px;
	padding:5px;
	font-family: "Times New Roman", Times, serif;
}
th{
	padding:15px;
		border-bottom:1px solid grey;

}
#pname{
	margin:5px;
}
.table-responsive{
	margin:10px;
}


</style>

 <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
 <div class="custom-actions-btns mb-5">
										<a href="#" class="btn btn-primary no-print"onclick="window.print(print);">
											<i class="icon-printer"></i> Print
										</a>
									
									</div>
									 <div id="logo_area"style="text-align:center;">
							<img id="log_img"src="images/sr.jpg"width="110"height="110"/>
							<div id ="seat_chart">SR CLASSIC COACH - SEAT CHART</div><hr>
										</div>
										
										<div class="row">
										<div class="col-8">
										
							
<br>
DRIVER'S NAME : <?php echo strtoupper($driver);?> <br><br>
CONDUCTOR'S NAME : <?php echo strtoupper($conductor);?> 
<br>
										</div>
										<div class="col-4">
										Date : <?php 
$trip_date_word = date('F dS Y', strtotime($trip_date));
echo strtoupper($trip_date_word);
?> <br><br>
Bus : <?php echo strtoupper($bus);?>
										
										</div>
										</div>
										
										
 <div class="row">
 	
									
									   
 <div class="col-8">
 
 

 
 
<?php







$var_56 = function($seats) {
 $alphabet = range('A', 'Z');


 
 $add_number = 0;
 if( $seats==49 ){
	 $seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "iiiii");

 }else if ( $seats==47 ){
		 $seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii",  "iiiii");
 
 }else if ( $seats==57 ){
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii","ii-ii","ii-ii", "iiiii"); 
 }else if ($seats==59 ){
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii","ii-ii","ii-ii", "iiiii");
 
 }else if ($seats==45 ){
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii",  "iiiii");
 
 }
$arrLength = count($seat);


for($i = 0; $i < $arrLength; $i++) {
	?>
	<div id="seat_array">
	<?php
	
	$value = $seat[$i];
	$my_array1 = str_split($value);
$sub_string_total = strlen($value);

$str = $value; 
$sub_string_no= substr_count($str, "i"); 

$new_no = 1;
for($x = 0; $x < $sub_string_total; $x++) {	

	$new_number = $value[$x];
if($new_number=="-"){
	echo "<span class='first center seat_col blank_seat_holder'></span>";
}else{
$new_no = $new_no +1;	
$new_minus = $new_no - 1;
$new_again = $new_minus + $add_number;
$let_no = $new_minus;
$new_seat_row=$i+1;
$letter = $alphabet[$i];
$seat_no = ''.$letter.''.$let_no.'';
$route_day_id = $_GET['id'];	
$trip_date = $_GET['trip_date'];	
$route_id = $_GET['route_id'];	

?>
			<span id='test'class='first seat_holder seat_col 'style='text-align:center;'><span style="font-size:17px;padding-bottom:5px;"><?php echo $seat_no;?></span><br><span  id='seat_no'style='position:relative;bottom:4px;'><?php
	include 'connect.php';
						 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' AND route_day_id='$route_day_id'  and seat_no='$seat_no' and status !='booked')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $status =$get13['status'];
 $firstname =$get13['firstname']; 
 $lastname =$get13['lastname']; 
 $transit =$get13['transit']; 
 $reason =$get13['reason']; 
 $id =$get13['id']; 
 echo '
 



<strong style="padding:3px; style="color:black;"" >'.$id.'</strong>,


 ';
}}
			?></span></span>
<?php
						
}
}

$add_number=$add_number + $sub_string_no;
$add_number = $add_number  ;
echo "<br>";
?>
</div>
<?php

}

};
$var_56($seats);
?>
</div>
 
 
 <div class="col-4">
 <table>
<span id="pname"><strong>PASSENGER'S NAME</strong></span><br><br>
<?php






$x3 =0;
$total_seat = $seats;
$font_seat = $total_seat-5;

$x = 4;
 

 
while($x <= $font_seat) {
	$x3 += 1;
	$alphabet = range('A', 'Z');

$alphabet = range('A', 'Z');




?>
<tr>

<?php
$textArray = ["A", "B", "C","D","E"];
foreach ($textArray as $key=>$value) {
    $key++;
    
	?>

	<?php 
if($key == 3 ){
		$key = "";
		$value="";
		@$image = "";
		
	}if($key > 3 ){
		@$image = "<img src ='images/armchair2.png'>";
		$key = $key - 1;
		$value = ord($value);
		$value = chr($value - 1);
	}if($key < 3 ){
		@$image = "<img src ='images/armchair2.png'>";
	
	}
	if($key ==""){
		
	}else{
		$seat_no = "".$alphabet[$x3-1]."$key";
		
		
								include 'connect.php';
				 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' AND bus='$bus' and seat_no='$seat_no')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $status =$get13['status'];
 $firstname =$get13['firstname']; 
 $lastname =$get13['lastname']; 
 $id =$get13['id']; 

?>
	

<tr id="info_data">
<td id="seat_number_ar"><strong><?php echo $seat_no;?></strong></td><td id="name_passenger"><strong><?php echo strtoupper($firstname);?> <?php echo strtoupper($lastname);?></strong></td>
</tr>
<?php

		
}}else{
	?>
<tr id="info_data">
<td id="seat_number_ar"><strong><?php echo $seat_no;?></strong></td><td id="name_passenger"></td>
</tr>
	<?php
}
	
	}
	?>
	
	<?php
}

?>


</tr>
<?php
  $x+=4;
}
$x3 = $x3;
	$alphabet = range('A', 'Z');

$alphabet = range('A', 'Z');

?>
<tr>
<?php  
$x = 1;
 
while($x <= 5) {
	$seat_no = ''.$alphabet[$x3].''.$x.'';
	
 ?>
 <td style="width: 20.66%;">
<?php
							include 'connect.php';
				 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' AND bus='$bus' and seat_no='$seat_no')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $status =$get13['status'];
 $firstname =$get13['firstname']; 
 $lastname =$get13['lastname']; 
 $id =$get13['id']; 

?>
	

<tr id="info_data">
<td id="seat_number_ar"><strong><?php echo $seat_no;?></strong></td><td id="name_passenger"><strong><?php echo strtoupper($firstname);?> <?php echo strtoupper($lastname);?></strong></td>
</tr>
<?php

		
}}else{
	?>
<tr id="info_data">
<td id="seat_number_ar"><strong><?php echo $seat_no;?></strong></td><td id="name_passenger"></td>
</tr>
	<?php
}

?></td>
 <?php
  $x++;
} 
?> 


</tr>
</table>
 </div>
</div>
<style type="text/css">


.custom-actions-btns {
    margin: auto;
    display: flex;
    justify-content: flex-end;
}

.custom-actions-btns .btn {
    margin: .3rem 0 .3rem .3rem;
}
 @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }
</style>
  <script language="JavaScript">
        function netPrintBillInline() {
            let dynHtml = "print://escpos.org/escpos/net/print?srcTp=uri&srcObj=html&src='data:text/html,";
            dynHtml += document.getElementById('').innerHTML
            dynHtml += "'";
            window.location.href = dynHtml;
        }
        function btPrintBillInline() {
            let dynHtml = "print://escpos.org/escpos/bt/print?srcTp=uri&srcObj=html&src='data:text/html,";
            dynHtml += document.getElementById('').innerHTML
            dynHtml += "'";
            window.location.href = dynHtml;
        }
        function usbPrintBillInline() {
            let dynHtml = "print://escpos.org/escpos/usb/print?srcTp=uri&srcObj=html&src='data:text/html,";
            dynHtml += document.getElementById('').innerHTML
            dynHtml += "'";
            window.location.href = dynHtml;
        }
        function netPrintBillUrl() {
            let dynHtml = "print://escpos.org/escpos/net/print?srcTp=uri&srcObj=html&src='";
            dynHtml += "https://loopedlabs.com/web-print/bill.html";
            dynHtml += "'";
            window.location.href = dynHtml;
        }
        function btPrintBillUrl() {
            let dynHtml = "print://escpos.org/escpos/bt/print?srcTp=uri&srcObj=html&src='";
            dynHtml += "https://loopedlabs.com/web-print/bill.html";
            dynHtml += "'";
            window.location.href = dynHtml;
        }
        function usbPrintBillUrl() {
            let dynHtml = "print://escpos.org/escpos/usb/print?srcTp=uri&srcObj=html&src='";
            dynHtml += "https://loopedlabs.com/web-print/bill.html";
            dynHtml += "'";
            window.location.href = dynHtml;
        }
    </script>
