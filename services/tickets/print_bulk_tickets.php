<button class="no-print" onclick="window.print();">Print</button>   
<style>
h4 {
   width: 100%; 
   text-align: center; 
   border-bottom: 1px solid #000; 
   line-height: 0.1em;
   margin: 10px 0 20px; 
} 

h4 span { 
    background:#fff; 
    padding:0 10px; 
}
</style>
    <style>
        body {
            text-align: center;
			
        }

        table {
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
        }

        table td,
        th {
            padding: 3px;
			
        }

        .ra {
            text-align: right;
			border-left: none;
        }

        .la {
            text-align: left;
			border: none;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }
			.tr{
		
			}
        }
    </style>

     <table style="border: none;font-size:14px;">
        <thead>
            <tr>
                <th style="border: none"></th>
                <th style="border: none">
                    <P>
					<div >
					<div style="font-size:25px;"></div>
					</div>
					<img src="images/sr.jpg"width="80"height="80"/><br>Address : ilala,lindi and Iringa<br>Lubumbashi  : +243 826 038 458<br>Dar es salaam  : +255 682 756 699<br>email: operations@srclassiccoach.co.tz</P>
                </th>
            </tr>
        </thead>
    </table>
	<br>

	<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

	
	include 'connect.php';
session_start();
$username=$_SESSION['username'];


$select12=mysqli_query($db,"SELECT *  FROM  printing  WHERE attribute='$username'");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
WHILE($get132=mysqli_fetch_array($select12)){
	 $seat_id= $get132['detail']; 
	?>
	<?php
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id' and issuer='$username')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 
 $trip_date= $get13['trip_date']; 
 $bus= $get13['bus'];
 $cost= $get13['cost'];
 $route_id= $get13['route_id']; 
 $seat_no= $get13['seat_no']; 
 $route_day_id= $get13['route_day_id'];

$start_location= $get13['start_location']; 
 $ending_location= $get13['ending_location'];
$firstname= $get13['firstname'];
$lastname= $get13['lastname'];
$phone_no= $get13['phone_no'];
$bus= $get13['bus'];
$route_day_id= $get13['route_day_id'];

$origin_status= $get13['origin_status'];
$cost= $get13['cost'];
$international_cost= $get13['international_cost'];

$book_date= $get13['book_date'];
$book_time= $get13['book_time'];
$status= $get13['status'];

}}	

	?>
	
	
	
	
	<?php
	$select15=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$route_day_id'");
$select_num15=mysqli_num_rows($select15);
if($select_num15 !=0){
   	WHILE($get135=mysqli_fetch_array($select15)){
 
 $hours_arrival = $get135['12hours'];
 $arrival = $get135['arrival']; 
 $departure = $get135['departure']; 
 

}}	

	?>	
	
	<?php
	$select154=mysqli_query($db,"SELECT *  FROM  qrcodes WHERE id3='$seat_id'");
$select_num154=mysqli_num_rows($select154);
if($select_num154 !=0){
   	WHILE($get1354=mysqli_fetch_array($select154)){
 
 $qrImg = $get1354['qrImg'];


}}	

	?>	
	<?php
	$select1544=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'");
$select_num1544=mysqli_num_rows($select1544);
if($select_num1544 !=0){
   	WHILE($get13544=mysqli_fetch_array($select1544)){
 
 $start_route_2 = $get13544['start_route'];
 $end_route_2 = $get13544['end_route']; 
 $currency = $get13544['currency'];


}}	

$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $status= $get1373['status'];
 $initial= $get1373['initial']; 

}}

$select1734=mysqli_query($db,"SELECT *  FROM  sub_route Where main_route_no='$route_id'");
$select_num1734=mysqli_num_rows($select1734);
if($select_num1734 !=0){
   	WHILE($get13734=mysqli_fetch_array($select1734)){
 
 $a_time= $get13734['a_time'];
 $d_time= $get13734['d_time']; 

}}
	?>
	
	
	
    <table id="border:1px solid black;">
    
        <tbody >
		<tr>
	<td colspan=3><h4><span>Ticket info</span></h4></td>
	</tr>
            <tr  >
               
                <td class="la" ><strong>Ticket no  </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php echo $seat_id ;?></b></td>
            </tr><tr  >
               
                <td class="la" ><strong>Seat no  </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php echo $seat_no ;?></b></td>
            </tr><tr  >
               
                <td class="la" ><strong>Fullname </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php echo "$firstname $lastname";?></b></td>
            </tr><tr  >
               
                <td class="la" ><strong>Phone No </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php echo $phone_no ;?></b></td>
            </tr><tr  >
               
                <td class="la" ><strong>From  </strong> </td>
             
                <td class="la" style="font-size:16px;"><b><?php if($origin_status=="half_complete"){echo $start_location ;}else{ echo $start_route_2;}?></b></td>
            </tr><tr  >
               
                <td class="la" ><strong>To </strong> </td>
                               <td class="la" style="font-size:16px;"><b><?php if($origin_status=="half_complete"){echo $ending_location ;}else{ echo $end_route_2;}?></b></td>

            </tr><tr  >
               
                <td class="la" ><strong>Agent Name   </strong> </td>
                               <td class="la" style="font-size:16px;"><b><?php echo "$firstname_booker $surname_booker";?></b></td>

            </tr><tr  >
               
                <td class="la" ><strong>Issued  time  </strong> </td>
                               <td class="la" style="font-size:16px;"><b><?php echo " ".date('jS M Y', strtotime($book_date))."(".$book_time.") ";?></b></td>

            </tr>
			
		<tr >
		
		<td style="padding:10px;" colspan=3><h4><span>	Trip Details</span></h4></td>
		</tr>
		          <tr  >
               
                <td class="la" ><strong>Bus no : </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php echo $bus ;?></b></td>
            </tr><tr  >
               
                <td class="la" ><strong>Traveling date  </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php echo date('jS F Y', strtotime($trip_date));?></b></td>
            </tr>
			<tr  >
               
                <td class="la" ><strong>Reporting Time</strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
					if($status2 =="complete"){
					$time = strtotime($departure);
					$startTime = date("H:i A ", strtotime('-30 minutes', $time));

					echo "$startTime";
				}else{
											$time = strtotime($d_time);

										$startTime = date("H:i A", strtotime('-30 minutes', $time));

					echo "$startTime";
				}
				?></b></td>
            </tr>	
			<tr>
               
                <td class="la" ><strong>Trip Start   </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
				if($status =="complete"){
					echo "$departure";
				}else{
					echo "$d_time";
				}
				
				?></b></td>
            </tr>
			
			<tr>
               
                <td class="la" ><strong>Trip End  </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
					if($status =="complete"){
                  echo $arrival ;
				}else{
					echo "$a_time";
				}
				
				
				?></b></td>
            </tr><tr  >
               
                <td class="la" ><strong>Board Point   </strong> </td>
         
                <td class="la" style="font-size:16px;"><b><?php echo $start_location ;?></b></td>
            </tr><tr  >
               
                <td class="la" ><strong>Drop of  point  </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php echo $ending_location ;?></b></td>
            </tr>
			  <tr  >
               
                <td class="la" ><strong>Price  </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
				if($status=="Main"){
					echo ''.number_format($cost,2).' '.$initial.'';
				}else{
				echo ''.number_format($international_cost,2).' '.$initial.'';
				}
				
				
				?></b></td>
            </tr> 
        </tbody>

    </table>
	
	
		    <table>
    
        <tbody >
 
		
        </tbody>

    </table>
	
	   <table style="border: none;font-size:14px;">
        <thead>
            <tr>
                <th style="border: none"></th>
                <th style="border: none">
                    <P>
					<div >
					<div style="font-size:25px;"></div>
					</div>
					<a href="www.srclassiccoach.co.tz"><img src="qrcode/userQr/meravi5552389.png"width="80"height="80"/></a>
                </th>
            </tr>
        </thead>
    </table>
	
	<?php

}}
?>

<?php
	
	$insert=mysqli_query($db,"DELETE from printing WHERE attribute='$username'");
	?>

   
   <br> <br>
	
    <br><br><hr class="no-print" ><br>
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

    <script language="JavaScript">
        function netPrintBillInline() {
            let dynHtml = "print://escpos.org/escpos/net/print?srcTp=uri&srcObj=html&src='data:text/html,";
            dynHtml += document.body.innerHTML
            dynHtml += "'";
            window.location.href = dynHtml;
        }
        function btPrintBillInline() {
            let dynHtml = "print://escpos.org/escpos/bt/print?srcTp=uri&srcObj=html&src='data:text/html,";
            dynHtml += document.body.innerHTML
            dynHtml += "'";
            window.location.href = dynHtml;
        }
        function usbPrintBillInline() {
            let dynHtml = "print://escpos.org/escpos/usb/print?srcTp=uri&srcObj=html&src='data:text/html,";
            dynHtml += document.body.innerHTML
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
	