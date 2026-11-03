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
        }

        .la {
            text-align: left;
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
					<img src="images/sr.jpg"width="80"height="80"/><br>Address : ilala,lindi and Iringa<br>Dar es salaam  : +255 682 756 699<br>email: operations@srclassiccoach.co.tz</P>
                </th>
            </tr>
        </thead>
    </table>
	<br>

<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

$route_day_id = $_GET['id'];	
$trip_date=$_GET['trip_date'];

include 'connect.php';
$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$route_day_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
			$bus =$get134['bus'];
	
		

		
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
	
	
	    <table >
    <td colspan=3><h4><span>Manifest</span></h4></td>
        <tbody >
	<tr>
		 
<td colspan=3 style="font-weight:bold; padding-bottom:15px;" >Trip date : <?php echo $trip_date;?><br>bus  : <?php echo $bus;?><br>Driver  : <?php echo $driver;?><br>Conductor: <?php echo $conductor;?> </td>
          
		
		</tr>
		
		    <tr >
                <th style="border: none">Seat no </th>
				<th style="border: none">Name</th>
				<th style="border: none">Route</th>
             </tr>
		<tr>
	
	</tr>
     <?php
	 								include 'connect.php';
				 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' AND route_day_id='$route_day_id' and status !='booked' and delete_book='')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $status =$get13['status'];
 $firstname =$get13['firstname']; 
 $lastname =$get13['lastname']; 
 $id =$get13['id']; 
  $seat_no= $get13['seat_no']; 
 $route_id= $get13['route_id']; 
$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	  
			$route_name =$get134['route_name'];
		

		

		
	}
}
?>
	 
           
          <tr >
		  <td >(<?php echo $seat_no;?>)</td>
		  <td><?php echo  $firstname;?> .<?php echo  mb_substr($lastname, 0, 1);?>.</td>
		  <td>(<?php echo $route_name;?>)</td>
		  
		  </tr>
		  <?php
}}
		  ?>
        </tbody>

    </table>
	
	
		    <table>
    
        <tbody >
 
		
        </tbody>

    </table>
	

   <button class="no-print" onclick="window.print();">Print via Browser</button>   
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