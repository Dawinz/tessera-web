<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>PRINT</title>
  <!-- plugins:css -->
 
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">

</head>
<style>
#seat_no_container{
	border:2px solid green;
	width:110px;
	height:73px;
	margin:5px;
}
#seat_number_id{
font-weight:bold;
text-align:center;
}
#seat_names{
	padding:2px;
	text-align:center;

}
#logo_name{
	padding-left:60px;
	font-size:60px;
	

}
#logo_area{
	padding-left:200px;
	font-family: 'PT Serif', serif;
}
#spacing{
	width:120px;
}
#info_data{
	font-size:14px;
}#seat_number_ar{

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
<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';

$id=$_GET['id'];
$trip_date=$_GET['trip_date'];

include 'connect.php';
$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$id' ");
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>white invoice - Bootdey.com</title>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<div class="row gutters">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
									<div class="custom-actions-btns mb-5">
										<a href="#" class="btn btn-primary no-print"onclick="window.print(print);">
											<i class="icon-printer"></i> Print
										</a>
									
									</div>
								</div>
							</div>
           <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                 <div id="logo_area">
							<img id="log_img"src="images/sr.jpg"width="110"height="110"/>
										<span id="logo_name">Sr classic coach</span>
										</div>
				 
				 <div class="container">

<table>
<th>
<div id ="seat_chart">SEAT CHART</div>
<br>
DRIVER'S NAME : <?php echo strtoupper($driver);?> <br><br>
CONDUCTOR'S NAME : <?php echo strtoupper($conductor);?> 
<br>
</th><th>
<br><br><br>
DATE <BR><BR>
NAME 
<br>
</th><th>
<br><br><br>
<?php 
$trip_date_word = date('F dS Y', strtotime($trip_date));
echo strtoupper($trip_date_word);
?> <BR><BR>
<?php echo strtoupper($bus);?>
<br>
</th>
<tr>
<td><br><br><br>
									<div class="table-responsive">

                    <table style="border:none;">
                      <thead>

                      </thead>
					  <tbody>
<?php
$status = "";





$x3 =0;
$total_seat = 49;
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
	<td style="width: 20.66%;">
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
	<div id="seat_no_container">
<div id="seat_number_id">
<?php echo $seat_no;?>
</div>
<div id="seat_names">
<div>Ticket No<div>
<strong><?php echo $id;?></strong><br>

</div>
<?php

		
}}else{
	?>
	<div id="seat_no_container">
<div id="seat_number_id">
<?php echo $seat_no;?>
</div>
</div>
	<?php
}
	
	}
	?>
	</td>
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
	<div id="seat_no_container">
<div id="seat_number_id">
<?php echo $seat_no;?>
</div>
<div id="seat_names">
<div>Ticket No<div>
<strong><?php echo $id;?></strong><br>

</div>
<?php

		
}}else{
	?>
	<div id="seat_no_container">
<div id="seat_number_id">
<?php echo $seat_no;?>
</div>
</div>
	<?php
}
	

?></td>
 <?php
  $x++;
} 
?> 


</tr>
   </tbody>
                    </table>

									</div>
								</div>
							</div>
							<!-- Row end -->
						</div>
						
</td>
<td id="spacing"></td>

<td>
<table>
<span id="pname"><strong>PASSENGER'S NAME</strong></span><br><br>
<?php






$x3 =0;
$total_seat = 49;
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
</td>
</tr>
</table>
</div>
         
                  </div>
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

<script type="text/javascript">

</script>
</body>
</html>
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
