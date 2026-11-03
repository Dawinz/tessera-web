
<?php
session_start();
?>
<!DOCTYPE html>
<html>
<?php
	$image= $_GET['code'];
	
								include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
			
			
										
									
										$select=mysqli_query($db,"SELECT *  FROM parcel_info WHERE code='$image' AND " . tessera_sql_tenant_eq() . "");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $quantity=$get['question'];
			$date=$get['date']; 
			$trip=$get['account'];
			$s_no=$get['s_no']; 
			$s_name=$get['s_name'];
			$s_surname=$get['s_surname'];
			$r_name=$get['r_name'];
			$r_surname=$get['r_surname']; 	
			$r_no=$get['r_no'];
			$cost=$get['amount'];
			$due=$get['due']; 	
			$date_insert=$get['date_insert']; 
			$quantity=$get['question']; 
			$detail=$get['detail']; 
		
			$detail=base64_decode($detail);
			$code = $get['code'];
		$status=$get['status']; 
		$time=$get['time']; 
		$uploader=$get['uploader']; 
		
	}
}

	
											?>
												<?php
	if($status=="paid"){
		echo "

		";
	}else if($status=="Due payment"){
		echo "
<style>

body {

  -webkit-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Safari 4+ */
  -moz-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Fx 5+ */
  -o-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Opera 12+ */
  animation: NAME-YOUR-ANIMATION 1s infinite;  /* IE 10+, Fx 29+ */
}

@-webkit-keyframes NAME-YOUR-ANIMATION {
  0%, 49% {
    background-color: #9c0312;
  
  }
  50%, 100% {
    background-color: #9c0312;

  }
}
</style>
		";
	}
	?>
<head>
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
	<?php
		$id= $_GET['id'];	
	
								include'connect.php';
			
			
										
										$select=mysqli_query($db,"SELECT *  FROM manifest WHERE id='$id' AND " . tessera_sql_tenant_eq() . "");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
$q_left=$get['q_left'];

}}
	?>
    <table style="border: none;font-size:14px;">
        <thead>
            <tr>
                <th style="border: none"></th>
                <th style="border: none">
                    <P>
					<div >
					<div style="font-size:25px;"><?php echo $code;?></div>
					</div>
					<a href="../employee_update.php"><img src="sr.jpg"width="100"height="100"/></a><br>Address : ilala,lindi and Iringa<br>Lubumbashi  : +243 826 036 458<br>Dar es salaam  : +255620 526 002<br>email: operations@srclassiccoach.co.tz</P>
                </th>
            </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <th style="font-size:14px;"colspan=3><?php echo ucwords($trip)?></th>
           
            </tr>
        </thead>
        <tbody style="border-left: 1px solid black;border-right: 1px solid black;font-size:12px;">
            <tr  >
               
                <td class="la" ><strong>Sender's name &nbsp&nbsp&nbsp </strong> </td>
                <td class="la" ></td>
                <td class="la"><b><?php echo "$s_name";?></b></td>
            </tr>
			
			<tr  >
               
                <td class="la" ><strong>Recivers's name &nbsp&nbsp&nbsp <strong> </td>
                <td class="la" ></td>
                <td class="la"><b><?php echo "$r_name ";?></b></td>
            </tr> 
            <tr>
                <td class="la"><strong>From town</strong></td> <td class="la"></td>
                <td class="la"><b><?php echo $trip?></b></td>
            </tr>
           <tr>
                <td class="la"><strong>Recivers's contacts</strong></td>  <td class="la"></td>
                <td class="la"><b><?php echo $r_no?></b></td>
            </tr>  <tr>
                <td class="la"><strong>Sender's contacts</strong></td>  <td class="la"></td>
                <td class="la"><b><?php echo $s_no?></b></td>
            </tr><tr>
                <td class="la"><strong>Quantity</strong></td>  <td class="la"></td>
                <td class="la"style="font-size:18px;"><b><?php echo "$q_left/$quantity"?><b></td>
            </tr><tr>
                <td class="la"><strong>Details</strong></td> <td class="la"></td>
                <td class="la"><b><?php echo $detail?></b></td>
            </tr>
        </tbody>   <tbody style="border-top: 1px solid black;border-bottom:1px solid black">
            <tr  >
               
                <td class="la" colspan=2><strong>issued by &nbsp&nbsp&nbsp  <?php
			
				echo $uploader;
				?> </strong></td>
                <td class="la" style="border-left: 1px solid black;"> <strong><?php if($status=="Due payment"){
					echo"Total Ammount ";
				}else{
					echo"Cash paid";
				}?> &nbsp&nbsp</strong><?php
				if($status == "To be Payed"){
					echo "0.00";
				}else{
				echo number_format($cost,2)	;
				}
				
				
				?><br> <strong>To Payed &nbsp&nbsp</strong><?php
				if($status == "To be Payed"){
						echo number_format($cost,2)	;
				}else if($status == "Due payment"){
								echo number_format($cost - $due,2)	;

				}else{
					echo "0,00";
				}
				
				
				?><strong><br> Total paid &nbsp&nbsp&nbsp </strong> <?php echo number_format($due ,2)?><br></td>
               
            </tr>
            <tr>
                <td class="la"colspan=2> <strong>issued on &nbsp&nbsp&nbsp <?php echo "$date_insert $time"?></strong></td> 
                <td class="la" style="border-left: 1px solid black;border-top: 1px solid ;"><strong><?php
				if($status == "paid"){
					echo "Total Payed";
				}else if($status == "To be Payed"){
					echo "Total To pay";
				}else if($status == "Due payment"){
					echo "Total To pay";
				}
				?>  </strong> <?php echo  number_format($cost - $due ,2);?></td>
            </tr>
           
        </tbody>
         <footer>
            <tr>
                <th style="font-size:12px;border:none"colspan=3>
				<img width="150"src="paid.png"/>
				</th>
				
           
            </tr>
        </footer>
    </table>
    <br>

    <button class="no-print" onclick="window.print();">Print via Browser</button>    <br> <br>
	
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
