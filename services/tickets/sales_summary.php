<?php
include'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
error_reporting(E_ERROR | E_PARSE);

	session_start();

$username=$_SESSION['username'];

$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname']; 
			$surname =$get12['surname']; 

		$fullname = "$firstname $surname";
	}
}
?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>PRINT</title>
  <!-- plugins:css -->
 
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">

</head>
<style>

th{
	    padding:15px;
		border:1px solid grey;
		font-weight:bold;

}
td{
	padding:3px;
text-align:center;
		border:1px solid grey;	
}
table{
	width:100%
}
</style>
<?php
$route_day_id = isset($_GET['id']) ? $_GET['id'] : '';
$trip_date = isset($_GET['trip_date']) ? $_GET['trip_date'] : '';
$trip_ts = strtotime((string) $trip_date);
$trip_date_known = $trip_ts !== false && (int) date('Y', $trip_ts) > 1970;
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('l jS \of F Y', time());
$date_time_insert = date(' h:i A', time());

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
			$start_route_r =$get134['start_route'];
			$end_route_r =$get134['end_route'];

		

		
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

$select1456=mysqli_query($db,"SELECT *  FROM  manifest WHERE id='".$_GET['manifest']."' ");
$select_num1456=mysqli_num_rows($select1456);
if($select_num1456 !=0){
   	WHILE($get13456=mysqli_fetch_array($select1456)){
    	    $bus_manifest =$get13456['bus'];
    	    $driver_manifest =$get13456['driver'];
    	    $conductor_manifest =$get13456['conductor'];
			$start_location =$get13456['start_location'];
			$end_location =$get13456['end_location'];

$full_trip = "$start_location to $end_location";

		
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
	<script src="js/vendor/jquery-1.12.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<div class="row gutters">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
									<div class="custom-actions-btns mb-5">
										<a href="#" class="btn btn-primary no-print" style="background:#014e80;border-color:#014e80;color:#fff" onclick="window.print(print);">
											<i class="icon-printer"></i> Print
										</a>
									
									</div>
								</div>
							</div>
     
      
            
				 
				 <div class="container">
	<p style="font-size:25px;display:flex;
     justify-content:center;
     align-items:center;font-weight:bold;"> 
	 <img src ="images/sr.jpg" width='80' height='80' /><br>
	 SR CLASSIC SEAT SALES SUMMARY</p>
	<p style="font-size:18px;font-weight:bold;"> TANZANIA HEAD QUATER  <br>P.O.BOX 4804, Mobile +255 629 777 941<br>Lindi /Iringa  Street ILala Dar Es Salaam<br>Email:classiccoachcoltd@gmail.com<br><br>
	Manfiest Date : <?php
	if (empty($trip_date_known)) {
		echo 'Open this summary from a trip on the Manifest page.';
	} else {
		echo date('l, jS \of F Y', $trip_ts);
	}
	?>
	<br>Vehicle : <?php echo $bus_manifest;?>
	
<br>	
	Driver's Name : <?php echo $driver_manifest;?>
	<br>	
	 Trip : <?php echo $full_trip;?>
	<br>	<br>
	Counductor's Name : <?php echo $conductor_manifest;?>
	<br>
	Prepared by <?php echo $fullname;?>,<br> <span style=" ">On <?php
	echo $date_insert; ?> Around <?php echo $date_time_insert;?></span>
	</p>
	
        <table>
		<th>Insurer</th>
		<th>Price</th>
		<th>Fullname</th>
		<th>Seat</th>
		<th>Ticket no</th>
		<th>Phone no</th>
		<th>Id/Passport</th>
		<th>Route</th>
		<th>Starting Location</th>
		<th> Ending Location</th>
		<th>TRANSIT</th>
		<th>FOC</th>
		
		<?php
		
		$select14501=mysqli_query($db,"SELECT * FROM booking WHERE   route_day_id='$route_day_id' and trip_date='$trip_date'  and status !='booked' and delete_book='' AND " . tessera_sql_tenant_eq() . " group by booker ");
$select_num14501=mysqli_num_rows($select14501);
if($select_num14501 !=0){
   	WHILE($get134501=mysqli_fetch_array($select14501)){
    	    $firstname =$get134501['firstname'];
    	    $lastname =$get134501['lastname'];
			$seat_no =$get134501['seat_no'];
			$phone_no =$get134501['phone_no'];
			$id2 =$get134501['id'];
			$validation =$get134501['validation'];
			$booker =$get134501['booker'];
			$start_location =$get134501['start_location'];
			$ending_location =$get134501['ending_location'];
			$start_route =$get134501['start_route'];
			$end_route =$get134501['end_route'];
			$status =$get134501['status'];

$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$booker'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstnam_booker =$get12['firstname'];  
			$surname_booker =$get12['surname'];
	}
}
if($status=="locked"){
	
}else{
			
    	?>
		<tr style="font-weight:bold;">
		<td colspan="12"><?php echo "$firstnam_booker $surname_booker";?></td>
	

	
		
		</tr>
		
		
		
		<?php
}
	$select1450=mysqli_query($db,"SELECT * FROM booking WHERE   route_day_id='$route_day_id' and trip_date='$trip_date' and status !='booked' and booker='$booker' and delete_book='' AND " . tessera_sql_tenant_eq());
$select_num1450=mysqli_num_rows($select1450);
if($select_num1450 !=0){
   	WHILE($get13450=mysqli_fetch_array($select1450)){
    	    $firstname =$get13450['firstname'];
    	    $lastname =$get13450['lastname'];
			$seat_no =$get13450['seat_no'];
			$cost =$get13450['cost'];
			$phone_no =$get13450['phone_no'];
			$id2 =$get13450['id'];
			$validation =$get13450['validation'];
			$booker =$get13450['booker'];
						$transit =$get13450['transit'];

			$start_location =$get13450['start_location'];
$ending_location =$get13450['ending_location'];
if($status=="locked"){
	
}else{
?>
		<tr>
		<td><?php echo " $firstnam_booker $surname_booker";?></td>
		<td><?php echo number_format($cost,2);?></td>
		<td><?php echo "$firstname $lastname";?></td>
		<td><?php echo "$seat_no";?></td>
		<td><?php echo "$id2";?></td>
		<td><?php echo "$phone_no";?></td>
		<td><?php echo "$validation";?></td>
		<td><?php echo "$start_route_r To $end_route_r";?></td>
		<td><?php echo "$start_route ";?></td>
		<td><?php echo "$end_route ";?></td>
		<td><?php if($transit=="checked"){
			echo "Transit";
		}else{
			
		}?></td>
		<td>
		
		<?php if(($transit!="checked")and ($cost=="0")){
			echo "Foc";
		}else{
			
		}?>
		</td>

		
		</tr>

<?php
}
}

	$sql = $db->query("SELECT  trip_date,bus, SUM(cost) AS  cost FROM booking  WHERE  route_day_id='$route_day_id'and trip_date='$trip_date' and status !='booked' and booker='$booker' and delete_book='' AND " . tessera_sql_tenant_eq());
		$rows=$sql->num_rows;
		
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$cost_total=$data['cost'];
		}}
		
		if($status=="locked"){
	
}else{
		?>
					<tr style="color:black;font-weight:bold;">
		<td>Total</td>
		<td><?php echo number_format($cost_total,2);?></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	
		
		</tr>
		
		<?php
}
}
		

		
	}
}

	$sql = $db->query("SELECT  trip_date,bus, SUM(cost) AS  cost FROM booking  WHERE route_day_id='$route_day_id' and trip_date='$trip_date' and status !='booked' and delete_book='' AND " . tessera_sql_tenant_eq());
		$rows=$sql->num_rows;
		
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$cost_total=$data['cost'];
		}}
?>

				<tr style="color:black;font-weight:bold;">
		<td>Grand total</td>
		<td><?php echo number_format($cost_total,2);?></td>
		
	
		
		</tr>

		
		
		
		</table> 

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
