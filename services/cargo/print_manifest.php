<?php
session_start();
$_SESSION['username'];
$date= @$_GET['date'];
$name= @$_GET['name'];

	include 'connect.php';
	
	$select2=mysqli_query($db,"SELECT *  FROM  vehicle_name WHERE  date ='$date' and Name ='$name'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $to_pay =$get12['to_pay']; 
			$paid=$get12['paid'];
			$due=$get12['due'];
			$Driver=$get12['Driver'];
}}
	?>
	
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="./pos.css">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Print</title>
</head>
<body>
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
            border: 1px solid black;
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
				border-left-style: dashed;
			}
        }
    </style>
    <table style="border: none">
        <thead>
            <tr>
              
                <th style="border: none">
                    <P><h4 style="border-style: dotted;">&nbsp USER MANIFEST ANALYSIS &nbsp </h4></P>
                </th>
            </tr> <tr>
              
                <th style="border: none">
                    <h3>Driver : <?php echo "$Driver";?><br>Manifest vehicle : <?php echo $name;?> <br>Manifest Date : <?php echo $date;?></h3>
					
                </th>
            </tr>
        </thead>
	
    </table>
	 <table style="font-size:22px;">
	 
	<?php
	

	
$select2=mysqli_query($db,"SELECT parcel_uploader  FROM  manifest WHERE  trip_date ='$date' and bus ='$name' GROUP BY (parcel_uploader)");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
		$uploader=$get12['parcel_uploader'];
		?>
		  <thead>
            <tr style="background:black;color:white;" >
               <a href='man_income.php'><th colspan=3><strong><?php echo $uploader; ?> </strong></th></a>
           
            </tr>
        </thead>
		
		<?php
		
    	  
			
		
		$sum_paid = 0;
		$sum_to_be = 0;
			
			
			$select23=mysqli_query($db,"SELECT paid,to_be    FROM manifest   where parcel_uploader='$uploader'and trip_date ='$date' and bus ='$name'");
$select_num23=mysqli_num_rows($select23);
if($select_num23 !=0){
   	WHILE($get123=mysqli_fetch_array($select23)){
		$to_be=$get123['to_be'];
		$paid=$get123['paid'];
		 $sum_paid += $paid;
		 $sum_to_be += $to_be;
			
?>
   
	<?php
}
?>
        <tbody style="border-left: 1px solid black;border-right: 1px solid black;">

<tr  >
               
                <td class="la" ><strong>Paid &nbsp&nbsp&nbsp </strong> </td>
                <td class="la" ></td>
                <td class="la"><strong> To be paid</strong></td>
            </tr>  
			</tbody>
			  <tbody style="border-top: 1px solid black;border-bottom:1px solid black">
            <tr  >
               
                <td class="la" colspan=2></td>
                <td class="la" style="border-left: 1px solid black;"></td>
               
            </tr>
            <tr>
                <td class="la"colspan=2><?php echo number_format($sum_paid,2);?> </td> 
                <td class="la" style="border-left: 1px solid black;"><?php echo number_format($sum_to_be,2);?></td>
            </tr>
           
        </tbody>
			
<?php

}
		}
}
	?>
	<thead>
            <tr style="background:black;color:white;">
               <a href='man_income.php'><th colspan=3><strong>GROSS TOTAL</strong></th></a>
           
            </tr>
        </thead>
		   <tr>
                <td class="la"style="border-bottom:1px solid black;"colspan=2>Paid Total </td> 
                <td class="la" style="border-left: 1px solid black;border-bottom:1px solid black;"><?php echo number_format($paid,2);?></td>
            </tr>   <tr>
                <td class="la"style="border-bottom:1px solid black;"colspan=2>To be Paid </td> 
                <td class="la" style="border-left: 1px solid black;border-bottom:1px solid black;"><?php echo number_format($to_pay,2);?></td>
            </tr> <tr>
                <td class="la"style="border-bottom:1px solid black;"colspan=2>Grande total </td> 
                <td class="la" style="border-left: 1px solid black;border-bottom:1px solid black;"><?php echo number_format($due,2);?></td>
            </tr>
	 </table>
	<br>
	
    <br>
    <button class="no-print" onclick="window.print();">Print via Browser</button>
    <br><br><hr class="no-print" ><br>
    

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
</body>

</html>
