
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
                <th style="border: none"><img src="/images/llp.jpg" /></th>
                <th style="border: none">
                    <P>Address : ilala,boma,3500<br>Contact : +255715511420<br>email: operations@srclassiccoach.co.tz<br>website:www.srclassiccoach.co.tz</P>
                </th>
            </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <th colspan=3>WAYBILL:DAR ES SALAAM TO NAIROBI</th>
           
            </tr>
        </thead>
        <tbody style="border-left: 1px solid black;border-right: 1px solid black;">
            <tr  >
               
                <td class="la" >Item 1</td>
                <td class="la" ></td>
                <td class="la"><?php
                echo "tsh 4000000";
                ?></td>
            </tr>
            <tr>
                <td class="la">Item qw1</td> <td class="la"></td>
                <td class="la">₹451.00</td>
            </tr>
            <tr>
                <td class="la">Item 1</td>  <td class="la"></td>
                <td class="la">₹1,545.00</td>
            </tr>
        </tbody>   <tbody style="border-top: 1px solid black;border-bottom:1px solid black">
            <tr  >
               
                <td class="la" colspan=2>issued by &nbsp&nbsp&nbsp  winston    church</td>
                <td class="la" style="border-left: 1px solid black;">issued by ydfgdfgdfdfggf</td>
               
            </tr>
            <tr>
                <td class="la"colspan=2>Item qw1</td> 
                <td class="la" style="border-left: 1px solid black;">₹451.00</td>
            </tr>
           
        </tbody>
        
    </table>
    <br>
    <button class="no-print" onclick="window.print();">Print via Browser</button>
    <br><br><hr class="no-print" ><br>
    <button class="no-print" onclick="btPrintBillInline();">App Link Print Inline - Bluetooth</button>
    <br><br><br>
    <button class="no-print" onclick="usbPrintBillInline();">App Link Print Inline - USB</button>
    <br><br><br>
    <button class="no-print" onclick="netPrintBillInline();">App Link Print Inline - Net / WiFi</button>
    <br><br><hr class="no-print" ><br>
    <button class="no-print" onclick="btPrintBillUrl();">App Link Print URL - Bluetooth</button>
    <br><br><br>
    <button class="no-print" onclick="usbPrintBillUrl();">App Link Print URL - USB</button>
    <br><br><br>
    <button class="no-print" onclick="netPrintBillUrl();">App Link Print URL - Net / WiFi</button>

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
