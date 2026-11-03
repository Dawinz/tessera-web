<?php
  include '../connect/connect.php';

$route  = $_POST['route'];
$current_route  = $_POST['current_route'];
$date  = $_POST['trip_date'];
$name  = $_POST['bus'];
$og_trip_date= "";
$veiw_date= "";
$select=mysqli_query($db,"SELECT og_trip_date  FROM   route_manifest WHERE  main_location='$route' and location='$current_route' and trip_date='$date' and bus='$name'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
	
   	WHILE($get=mysqli_fetch_array($select)){
    	    $og_trip_date =$get['og_trip_date']; 
    	
			
}}

if($og_trip_date==""){
	$veiw_date = $date;

}else{
		$veiw_date = $og_trip_date;

}
?>
<?php
session_start();
$_SESSION['username'];


	?>
	
<!DOCTYPE html>
<html>
   <style>
        body {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
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
<head>
  <link rel="stylesheet" href="./pos.css">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Print</title>
</head>
<body style="text-align: center;">
    <style>

    </style>
    <table style="border: none">
        <thead>
            <tr>
              
                <th style="border: none">
                    <P><h3 style="border-style: dotted;">&nbsp USER MANIFEST ANALYSIS &nbsp </h3></P>
                </th>
            </tr>
        </thead>
    </table>
	 <table style="font-size:25px;">
	<?php
	
	include '../connect/connect_cargo.php';
	$to_pay = 0;
	$paid = 0;
	$due = 0;
	$select2=mysqli_query($db_up,"SELECT *  FROM  vehicle_name WHERE  date ='$date' and Name ='$name' and Route='$current_route' ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $to_pay =$get12['to_pay']; 
			$paid=$get12['paid'];
			$due=$get12['due'];
}}
	
$select2=mysqli_query($db_up,"SELECT *  FROM  manifest WHERE  trip_date ='$date' and bus ='$name' and parcel_route ='$current_route' GROUP BY (parcel_uploader)");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $id =$get12['id']; 
			$uploader=$get12['parcel_uploader'];

								$sql = $db_up->query("SELECT SUM(paid) AS  paid FROM manifest   where  parcel_route ='$current_route'  and parcel_uploader='$uploader'and trip_date ='$date' and bus ='$name'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['paid'];
			
		}}

									$sql = $db_up->query("SELECT SUM(to_be) AS  to_be FROM manifest   where  parcel_route ='$current_route' and parcel_uploader='$uploader'and trip_date ='$date' and bus ='$name'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_to_be=$data['to_be'];
		
			
		}}else{
			echo "Nothing";
		}	

	?>
	
   
        <thead>
            <tr style="background:black;color:white;">
               <a href='man_income.php'><th colspan=3><strong><?php echo $uploader; ?> </strong></th></a>
           
            </tr>
        </thead>
        <tbody style="border-left: 1px solid black;border-right: 1px solid black;">
       <tr  >
                <td class="la"><strong>Paid</strong></td>
                <td class="la" ></td>
                               <td class="la" ><strong>To be paid &nbsp&nbsp&nbsp </strong> </td>

            </tr>     
           
           
        </tbody>   <tbody style="border-top: 1px solid black;border-bottom:1px solid black">
            <tr  >
               
                <td class="la" colspan=2></td>
                <td class="la" style="border-left: 1px solid black;"></td>
               
            </tr>
            <tr>
                <td class="la"colspan=2><?php echo number_format($amount,2);?> </td> 
                <td class="la" style="border-left: 1px solid black;"><?php echo number_format($amount_to_be,2);?></td>
            </tr>
           
        </tbody>
        
   
	<?php
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
