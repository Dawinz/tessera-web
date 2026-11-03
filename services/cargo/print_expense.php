<?php
session_start();
$_SESSION['username'];
include 'connect.php';
$v_no = $_GET['v_no'];
$select1=mysqli_query($db,"SELECT *  FROM  manifest_exp where v_no = '$v_no'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $amount=$get13['income_amt']; 
			$detail=$get13['detail']; 
			$time = $get13['time'];
			$insert_date = $get13['insert_date'];
			$date = $get13['date'];	
			$bus = $get13['bus'];
			$trip_date = $get13['trip_date'];
			
		
	}
}

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
                    <P><h3 style="border-style: dotted;">RECEIPT</h3>Address : ilala,boma,3500<br>Contact : +255715511420<br>email: operations@srclassiccoach.co.tz<br>website:www.srclassiccoach.co.tz</P>
                </th>
            </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <a href='man_exp.php'><th colspan=3>EXPENSES RECEIPT</th></a>
           
            </tr>
        </thead>
        <tbody style="border-left: 1px solid black;border-right: 1px solid black;">
       <tr  >
               
                <td class="la" ><strong>Vehicle's Name &nbsp&nbsp&nbsp <strong> </td>
                <td class="la" ></td>
                <td class="la"><?php echo "$bus";?></td>
            </tr>    <tr  >
               
                <td class="la" ><strong>Trip Date &nbsp&nbsp&nbsp <strong> </td>
                <td class="la" ></td>
                <td class="la"><?php echo "$trip_date";?></td>
            </tr><tr  >
               
                <td class="la" ><strong>Detail &nbsp&nbsp&nbsp <strong> </td>
                <td class="la" ></td>
                <td class="la"><?php echo base64_decode($detail);?></td>
            </tr> 
           
           
        </tbody>   <tbody style="border-top: 1px solid black;border-bottom:1px solid black">
            <tr  >
               
                <td class="la" colspan=2>Issued by&nbsp&nbsp&nbsp  <?php echo $_SESSION['username'];?></td>
                <td class="la" style="border-left: 1px solid black;">Total Ammount</td>
               
            </tr>
            <tr>
                <td class="la"colspan=2>Issued date &nbsp<?php echo "$insert_date <br> $time";?></td> 
                <td class="la" style="border-left: 1px solid black;"><?php echo number_format($amount,2)	;;?></td>
            </tr>
           
        </tbody>
        
    </table>
	<br>
	<div><strong><?php echo $date;?></strong></div>
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
