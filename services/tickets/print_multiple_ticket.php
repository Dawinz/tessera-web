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
require_once __DIR__ . '/includes/tessera_tenant.php';

	
	include 'connect.php';

function depature_time($route_day_id,$route_id,$start_location,$status,$db){
$select_time=mysqli_query($db,"SELECT deputure  FROM   traveling_time  WHERE route_day_id='$route_day_id' and route_id='$route_id' and name='$start_location' and status='$status' ");
$select_num_time=mysqli_num_rows($select_time);
if($select_num_time !=0){
   	WHILE($get_time=mysqli_fetch_array($select_time)){
 
 $deputure = $get_time['deputure'];

echo  "$deputure";


}}	

}
function reporting_time($route_day_id,$route_id,$start_location,$status,$db){
$select_time=mysqli_query($db,"SELECT reporting_time  FROM   traveling_time  WHERE route_day_id='$route_day_id' and route_id='$route_id' and name='$start_location' and status='$status' ");
$select_num_time=mysqli_num_rows($select_time);
if($select_num_time !=0){
   	WHILE($get_time=mysqli_fetch_array($select_time)){
 
 $reporting_time = $get_time['reporting_time'];

echo  "$reporting_time";
 

}}	
}
function arrival($route_day_id,$route_id,$start_location,$status,$db){
$select_time=mysqli_query($db,"SELECT arrival  FROM   traveling_time  WHERE route_day_id='$route_day_id' and route_id='$route_id' and name='$start_location' and status='$status' ");
$select_num_time=mysqli_num_rows($select_time);
if($select_num_time !=0){
   	WHILE($get_time=mysqli_fetch_array($select_time)){
 
 $arrival = $get_time['arrival'];

echo  "$arrival";
 

}}	
}


if(isset($_GET['id'])){

$id= $_GET['id'];
$query = "id='$id'";

}else{

$print_id= $_GET['print_id'];
$query = "print_id='$print_id'";

}


$select12=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND ($query)");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){
		
		 $seat_id= $get132['id']; 



?>

<?php



$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");
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
$amount_status= $get13['amount_status'];
$amount_left= $get13['amount_left'];
$amount_paid= $get13['amount_paid'];
$foc= $get13['foc'];
$start_route= $get13['start_route'];
$end_route= $get13['end_route'];





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
 $terms = base64_decode( $get13544['terms']); 
 

}}	

$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $status= $get1373['status'];
 $initial= $get1373['initial']; 

}}

$select1734=mysqli_query($db,"SELECT *  FROM  routes Where route_id='$sub_route_id'");
$select_num1734=mysqli_num_rows($select1734);
if($select_num1734 !=0){
   	WHILE($get13734=mysqli_fetch_array($select1734)){
  $start_route_sub= $get13734['start_route'];
 $end_route_sub= $get13734['end_route'];
 $arrival = $get13734['arrival']; 
 $departure = $get13734['departure'];  

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
					<img src="images/sr.jpg"width="80"height="80"/><br> 
                    
                    <?php
                    $select_route_contact=mysqli_query($db,"SELECT *  FROM  route_contact Where route_id='$route_id'");
$select_num_route_contact=mysqli_num_rows($select_route_contact);
if($select_num_route_contact !=0){
   	WHILE($get_route_contact=mysqli_fetch_array($select_route_contact)){
 
 $name= $get_route_contact['name'];
 $contact= $get_route_contact['contact']; 
?>
<?php echo $name;?>  : <?php echo $contact?><br>
<?php

}}
                    ?>
                    email: operations@srclassiccoach.co.tz</P>
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
               
                <td class="la" ><strong>Reporting Time  </strong> </td>
   <td class="la" style="font-size:16px;"><b><?php 
				reporting_time($route_day_id,$route_id,$start_location,'Departure',$db);
				?> (24hrs)</b></td>
            </tr>
			<tr  >
               
                <td class="la" style="font-size:18px;"><strong>Trip Start</strong> </td>
                <td class="la" style="font-size:18px;"><b><?php 
         depature_time($route_day_id,$route_id,$start_location,'Departure',$db);
				?> (24hrs)</b></td>
            </tr><tr >
               
                <td class="la" ><strong>Trip End  </strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
				arrival($route_day_id,$route_id,$ending_location,'arrival',$db);
				?> (24hrs)</b></td>
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
	if($foc=="on"){
		?>
			 <tr>
               
                <td class="la" ><strong>FOC</strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
			echo "Yes"; ;
				
				?></b></td>
            </tr> 
		<?php
	} else{
		
	}
	?>
	<?php
	if($transit=="on"){
		?>
			 <tr>
               
                <td class="la" ><strong>Transit</strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
			echo "Yes"; ;
				
				?></b></td>
            </tr> 
		<?php
	} else{
		
	}
	?>
	
	<?php
	if($foc=="on"){
		?>
			 <tr>
               
                <td class="la" ><strong>Reason</strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
			echo "$reason"; 
				
				?></b></td>
            </tr> 
		<?php
	} else{
		
	}
	?>
	<?php
	if($transit=="on"){
		?>
			 <tr>
               
                <td class="la" ><strong>From</strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
			echo "$reason"; 
				
				?></b></td>
            </tr> 
		<?php
	} else{
		
	}
	?>
	
	
	
	
	
	<?php
	if($amount_status=="yes"){
	?>
		 <tr>
               
                <td class="la" ><strong>Ammount paid</strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
				echo ''.number_format($amount_paid,2).' '.$initial.'';
				?></b></td>
            </tr> 
			<tr style="border:1px solid black;">
               
                <td class="la" ><strong>Remain paid</strong> </td>
                <td class="la" style="font-size:16px;"><b><?php 
				echo ''.number_format($amount_left,2).' '.$initial.'';
				?></b></td>
            </tr> 
	<?php } ?>
        </tbody>

    </table>
	
	
		    <table>
    
        <tbody >
			<?php
	if($amount_status=="yes"){
	?>
 <tr>
  <td>
 <img src ="images/debt.png"width="80"height="80">
 </td>
 </tr>
	<?php } ?>
        </tbody>

    </table>
		<table style="border:1px solid black;">
	
	<tr>
	<tr>
	<td colspan=3 id="terms"style="border-bottom:1px solid black;"><span>Terms and Conditions </span></td>
	</tr>
		<td colspan=3>
		<?php
		if($route_id == 13){
			?>
				<ul>
	<li>Ce billet est delivre pour etre utilise au jour et a la date fixes</li>
	<li>Nous retranchons 30% du billet au detenteur de ce billet qui annule son voyage une heure ou 1,2,3... jour avant le depart du bus</li>
	<li>En cas d’accident SR CLASSIC COACH ne pas le responsable! Merci</li>
	<li>le passenger retardataire ne sera pass dedommage, il ne s'en prendra qu'á lui-meme</li>
	<li>Respectez l'heure</li>
	</ul>
			<?php
		}else{
		echo $terms;
		}
		?>
	
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
	<?php
}}

if(!isset($_GET['noprint'])){
	?>
    <br><br><hr class="no-print" ><br>
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

    <script language="JavaScript">
      
window.addEventListener("afterprint", function(event) {
    setTimeout(function() {
        window.close();
    }, 1000); // Execute on the next tick
});

	window.onload = function () {
    window.print();
}


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
}
