         <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />


<style>
a{
	color:black;
	
}
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
#terms{
	   width: 100%; 
   text-align: center; 
 font-weight:bold;
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



	<?php
	
	include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';

$seat_id= $_GET['id'];

$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE id='$seat_id' AND " . tessera_sql_tenant_eq() . "");
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
$booker= $get13['booker'];
$issuer = $get13['issuer'];

$origin_status= $get13['status'];
$cost= $get13['cost'];
$international_cost= $get13['international_cost'];

$book_date= $get13['book_date'];
$book_time= $get13['book_time'];
$status2= $get13['status'];
$reason= $get13['reason'];
$transit= $get13['transit'];
$sub_route_id= $get13['sub_route_id'];

}}	

	?>
	
	
	
	
	<?php
	$select158=mysqli_query($db,"SELECT *  FROM  users WHERE username='$booker'");
$select_num158=mysqli_num_rows($select158);
if($select_num158 !=0){
   	WHILE($get1358=mysqli_fetch_array($select158)){
 
 $firstname_booker = $get1358['firstname'];
 $surname_booker = $get1358['surname'];

 

}}	

	?>	
	<?php
	$select158=mysqli_query($db,"SELECT *  FROM  users WHERE username='$issuer'");
$select_num158=mysqli_num_rows($select158);
if($select_num158 !=0){
   	WHILE($get1358=mysqli_fetch_array($select158)){
 
 $firstname_issuer = $get1358['firstname'];
 $surname_issuer = $get1358['surname'];

 

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
 $route_active_time = $get135['route_active_time']; 


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
 $contact_no1 = $get13544['contact_no1']; 
 $contact_no2 = $get13544['contact_no2']; 
 

}}	

$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $status= $get1373['status'];
 $initial= $get1373['initial']; 

}}

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
	
<?php

if($status2=="booked"){
	
}else{
?>

 <button class="no-print btn-soft-primary" onclick="window.print();">Print</button>   
<?php
}
?>
	
	     <table style="border: none;font-size:14px;">
        <thead>
            <tr>
                <th style="border: none"></th>
                <th style="border: none">
                    <P>
					<div >
					<div style="font-size:25px;"></div>
					</div>
					<img src="images/sr.jpg"width="80"height="80"/><br>Address : ilala,lindi and Iringa<br><?php echo $start_route_2;?>  : <?php echo $contact_no1?><br><?php echo $end_route_2;?>  : <?php echo $contact_no2;?><br>email: operations@srclassiccoach.co.tz</P>
                </th>
            </tr>
        </thead>
    </table>
	<br>
	
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
                <td class="la" style="font-size:16px;"><b> <a href ="user_profile.php?u=<?php echo $seat_id;?>"><?php echo "$firstname $lastname";?></a></b></td>
				
            </tr><tr  >
               
                <td class="la" ><strong>Phone No </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php echo $phone_no ;?></b></td>
            </tr><tr  >
               
                <td class="la" ><strong>From  </strong> </td>
             
                <td class="la" style="font-size:16px;"><b><?php if($origin_status=="half_complete"){echo $start_route_sub ;}else{ echo $start_route_2;}?></b></td>
            </tr><tr  >
               
                <td class="la" ><strong>To </strong> </td>
                               <td class="la" style="font-size:16px;"><b><?php if($origin_status=="half_complete"){echo $end_route_sub ;}else{ echo $end_route_2;}?></b></td>

            </tr><tr  >
               
                <td class="la" ><strong>Agent Name   </strong> </td>
                               <td class="la" style="font-size:16px;"><b><?php  if(@$firstname_booker==""){echo "$firstname_issuer $surname_issuer";}else{echo "$firstname_booker $surname_booker";} ?></b></td>

            </tr><tr  >
               
                <td class="la" ><strong>Issued  Date  </strong> </td>
                               <td class="la" style="font-size:16px;"><b><?php if($book_date!=""){echo " ".date('jS M Y', strtotime($book_date))."(".$book_time.") ";}else{} ?></b></td>

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
										$time = strtotime($departure);


										$startTime = date("H:i ", strtotime('-30 minutes', $time));

					echo "$startTime";
				}
				?></b></td>
            </tr>		<tr  >
               
                <td class="la" ><strong>Trip start</strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
				if($status2 =="complete"){
					echo "$departure";
				}else{
					echo "$d_time";
				}
				
				?></b></td>
            </tr>
			
			<tr >
               
                <td class="la" ><strong>Trip end  </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
					if($status2 =="complete"){
                  echo $arrival ;
				}else{
					echo "$a_time";
				}
				
				
				?></b></td>
            </tr><tr  >
               
                <td class="la" ><strong>Board Point   </strong> </td>
         
                <td class="la" style="font-size:16px;"><b><?php echo $start_location ;?></b></td>
            </tr><tr>
               
                <td class="la" ><strong>Drop of  point  </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php echo $ending_location ;?></b></td>
            </tr>
			 <tr>
               
                <td class="la" ><strong>Price  </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
				if($status=="Main"){
					echo ''.number_format($cost,2).' '.$initial.'';
				}else{
				echo ''.number_format($cost,2).' '.$initial.'';
				}
				
				
				?></b></td>
            </tr> 
			
<?php
	if($reason!=""){
		?>
			 <tr>
               
                <td class="la" ><strong>FOC</strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
			echo $reason ;
				
				?></b></td>
            </tr> 
		<?php
	} else{
		
	}
	?>
		<?php
	if($transit=="checked"){
		?>
			 <tr>
               
                <td class="la" ><strong>Transit</strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
			echo "yes" ;
				
				?></b></td>
            </tr> 
		<?php
	} else{
		
	}
	?>	
        </tbody>

    </table>
	
	<table style="border:1px solid black;">
	
	<tr>
	<tr>
	<td colspan=3 id="terms"style="border-bottom:1px solid black;"><span>Terms and Conditions </span></td>
	</tr>
		<td colspan=3>
		<ul>
	<li>Only! passenger's luggage(s) that weigh less than <strong>5kgs</strong> and can fit on the staff carrier, allowed to board with the passenger in the bus</li>
	<li>For every <strong>15</strong> tickets that a passenger has booked, he/she will get <strong>1</strong> ticket free</li>
	</ul>
		</td>
	</tr>
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
		<?php
	
	$insert=mysqli_query($db,"DELETE from printing WHERE attribute='$username'");
	?>
