
<!DOCTYPE html>
<html>

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
         
            padding: 3px;
        }
		.a{
		}

        .ra {
            text-align: center;
			

        }
		.trd{
			            text-align: center;

			
		}

        .la {
            text-align: center;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }
        }
    </style>
    <table style="border: none">
        <thead>
            <tr>
                <th style="border: none"><img src="/images/llp.jpg" /></th>
                <th style="border: none">
                     <div class="info">
        <h2>Reicepts</h2>
        <p> 
            Address : street city, state ilala</br>
            Email   : operations@srclassiccoach.cotz</br>
            Phone   : 555-555-5555</br>
        </p>
      </div>
                </th>
            </tr>
        </thead>
    </table>
    <table>
    
        <tbody>
                 <thead>
            <tr >
                <th class="trd" colspan=3 >Subject</th>
               
            </tr>
        </thead>
			<tr>
			<td colspan=3 style="border-style: dashed solid;">sdfsdf</td>
			</tr>
         
                 <thead>
            <tr >
                <th class="trd" colspan=3 >Ammout</th>
               
            </tr>
        </thead>
			<tr>
			<td colspan=3 style="border-style: dashed solid;">tsh 500,000</td>
			</tr>
                   <thead>
            <tr >
                <th class="trd" colspan=3 >Detail</th>
               
            </tr>
        </thead>
			<tr>
			<td colspan=3 style="border-style: dashed solid;">industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley</td>
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
