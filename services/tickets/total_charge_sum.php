 <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" /> <link rel="stylesheet" href="css/vertical-layout-light/style.css">
<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

 $route_day_id = $_GET['id'];	
$trip_date = $_GET['trip_date'];	
$route_id = $_GET['route_id'];	
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('l jS \of F Y', time());
$date_time_insert = date(' h:i A', time());

include'connect.php';
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
$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
		
$full_trip = "$start_route to $end_route";
		
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
	<p style="font-size:25px;display:flex;
     justify-content:center;
     align-items:center;font-weight:bold;"> 
	 <img src ="images/sr.jpg" width='80' height='80' /><br>
	 SR CLASSIC SEAT SALES SUMMARY</p>
	<p style="font-size:18px;font-weight:bold;"> TANZANIA HEAD QUATER  <br>P.O.BOX 4804, Mobile +255 629 777 941<br>Lindi /Iringa  Street ILala Dar Es Salaam<br>Email:classiccoachcoltd@gmail.com<br><br>
	Manfiest Date : <?php
	$today_date_word = date('l, jS \of F Y', strtotime($trip_date));
echo $today_date_word;
	?>
	<br>Vehicle : <?php echo $bus;?>
	
<br>	
	Driver's Name : <?php echo $driver;?>
	<br>	
	 Trip : <?php echo $full_trip;?>
	<br>	<br>
	Counductor's Name : <?php echo $conductor;?>
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
		<th>Picking point</th>
		<th>Ending Point</th>
		<th>TRANSIT</th>
		<th>FOC</th>
		
		<?php
		
		$select14501=mysqli_query($db,"SELECT * FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date'  and status !='booked') group by booker ");
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
	$select1450=mysqli_query($db,"SELECT * FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date' and status !='booked' and booker='$booker')");
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
						$start_route =$get13450['start_route'];
						$end_route_r =$get13450['end_route'];

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
		<td><?php echo "$start_route To $end_route_r";?></td>
		<td><?php echo "$start_location ";?></td>
		<td><?php echo "$ending_location ";?></td>
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

	$sql = $db->query("SELECT  trip_date,bus, SUM(cost) AS  cost FROM booking  WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id'and trip_date='$trip_date' and status !='booked' and booker='$booker')");
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

	$sql = $db->query("SELECT  trip_date,bus, SUM(cost) AS  cost FROM booking  WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date' and status !='booked')");
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

</div>
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
