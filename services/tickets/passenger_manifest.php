<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';

$username = $_SESSION['username'] ?? '';
$fullname = '';

$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname']; 
			$surname =$get12['surname']; 

		$fullname = "$firstname $surname";
	}
}
$id = isset($_GET['id']) ? (string) $_GET['id'] : '';
$trip_date = isset($_GET['trip_date']) ? (string) $_GET['trip_date'] : '';
$tripTs = $trip_date !== '' ? strtotime($trip_date) : false;
$tripDateLabel = ($tripTs && (int) date('Y', $tripTs) > 1970)
	? date('l, jS \of F Y', $tripTs)
	: '';
$hasTrip = $id !== '' && $tripDateLabel !== '';
$full_trip = '';
$d_time = '';
$a_time = '';
$bus = '';
$driver = '';
$conductor = '';
$bus_manifest = '';
$start_route_r = '';
$end_route_r = '';
$route_id = '';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('l jS \of F Y', time());
$date_time_insert = date(' h:i A', time());

if (!$hasTrip) {
	?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Passenger manifest</title>
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container-fluid py-4">
		<div class="card">
			<div class="card-body">
				<h4 class="mb-2">Passenger manifest</h4>
				<p class="mb-3">Open this list from a trip on the Manifest page. A trip and date are required, so an empty visit does not show 1 January 1970.</p>
				<a class="btn btn-primary" href="manifest.php">Open Manifest</a>
			</div>
		</div>
	</div>
</body>
</html>
	<?php
	return;
}

include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $route_id =$get134['route_id'];
			$bus =$get134['bus'];
	 $a_time= $get134['arrival'];
 $d_time= $get134['departure']; 
		

		
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
    <title>Passenger manifest</title>
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<style>
		th { padding: 15px; border: 1px solid grey; font-weight: bold; }
		td { padding: 3px; text-align: center; border: 1px solid grey; font-size: 12px; }
		table { width: 100%; }
	</style>
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
     
      
            
				 
				 <div class="container">
<div style="margin:10px;"><p style="font-size:25px;display:flex;justify-content:center;align-items:center;font-weight:bold;"> <img src ="images/sr.jpg" width="80" height="120" /><br>SR CLASSIC PASSENGER MANIFEST CHART</p><div class="card-header justify-content-between d-flex align-items-center"><p style="font-size:18px;font-weight:bold;"><br> <br>Route : <?php echo $full_trip;?><br>Trip Depeture : <?php echo $d_time;?> Trip Destination Arraival : <?php echo $a_time;?><br>Trip date : <?php echo $tripDateLabel; ?> <br>Printed by <?php echo $fullname;?></p> 	<a  ><p style="font-size:18px;font-weight:bold;"><br>Vehicle : <?php echo $bus_manifest;?></a><br>Driver : <?php echo $driver;?><br>Conductor : <?php echo  $conductor;?> </p></div></div>
	
        <table>
		<th></th>
		<th>Fullname</th>
		<th>Seat</th>
		<th>Ticket no</th>
		<th>Status</th>
		<th>Phone no</th>
		<th>Id/Passport</th>
		<th>From</th>
		<th>To</th>
		<th>Picking point</th>
		<th>Ending Point</th>
		<th>Cost</th><th>Ammount Remain</th>
		
		<?php
		
		$select1450=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY seat_no ASC 
	) row_num,
    firstname,
    id,
	lastname,
	seat_no,
	phone_no,
	validation,
	start_location,
	ending_location,sub_route_id,amount_left,status,cost
FROM 
	booking WHERE   route_day_id='$id' and trip_date='$trip_date' and status !='locked' and delete_book='' and status !='booked'
ORDER BY 
	seat_no ASC 
		
		");
$select_num1450=mysqli_num_rows($select1450);
if($select_num1450 !=0){
   	WHILE($get13450=mysqli_fetch_array($select1450)){
    	    $firstname =$get13450['firstname'];
    	    $lastname =$get13450['lastname'];
			$seat_no =$get13450['seat_no'];
			$phone_no =$get13450['phone_no'];
			$id2 =$get13450['id'];
			$validation =$get13450['validation'];
			$status =$get13450['status'];
			$cost =$get13450['cost'];
			
			$start_location =$get13450['start_location'];
			$ending_location =$get13450['ending_location'];
			$sub_route_id =$get13450['sub_route_id'];
			$row_num =$get13450['row_num'];
						$amount_left =$get13450['amount_left'];

			
			$select1734=mysqli_query($db,"SELECT *  FROM  sub_route Where sub_route_id='$sub_route_id'");
$select_num1734=mysqli_num_rows($select1734);
if($select_num1734 !=0){
   	WHILE($get13734=mysqli_fetch_array($select1734)){
 
 $start_route_sub= $get13734['start_route'];
 $end_route_sub= $get13734['end_route'];
 $a_time= $get13734['a_time'];
 $d_time= $get13734['d_time']; 


}}
			
    	?>
		<tr>
		<td><?php echo $row_num;?></td>
		<td><?php echo "$firstname $lastname";?></td>
		<td><?php echo "$seat_no";?></td>
				<td><?php if($status=="booked"){ echo "Reserved";}else{echo "Complete";} if($amount_left ==0){}else{echo "<br>(but no cleared)";}?></td>
		<td><?php echo "$id2";?></td>
		<td><?php echo "$phone_no";?></td>
		<td><?php echo "$validation";?></td>
		<td><?php
if($sub_route_id==""){
		echo "$start_route_r ";
}else{
	echo $start_route_sub;
}
	
		
		?></td>
		
		<td>
<?php
if($sub_route_id==""){
		echo "$end_route_r ";
}else{
	echo $end_route_sub;
}
	
		
		?></td>
		<td><?php echo "$start_location ";?></td>
		<td><?php echo "$ending_location ";?></td>
		<td><?php echo number_format($cost,2);?> </td>
		<td><?php echo number_format($amount_left,2);?> </td>
		
		</tr>
		
		
		
		<?php

		

		
	}
}
		?>
		<footer>
		<tr>
		<td></td>
		<td>Total passengers </td> <td><strong><?php echo $select_num1450;?></strong></td>
		</tr><tr>
		<td></td>
		<td>Total Ticket Sales </td> <td><?php
		 $sql = $db->query("SELECT SUM(cost) AS  cost FROM booking WHERE   route_day_id='$id' and trip_date='$trip_date' and status !='booked' and delete_book='' AND " . tessera_sql_tenant_eq() . "");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['cost'];
				  echo '<strong>'.number_format($amount,2).'</strong>';
			
		}}else{
	
		}


		?></td>
		</tr>
		<tr></tr>
		</footer>
		
		
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
