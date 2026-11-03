LAYOUT]
-- General layout
<EC>
<BMP>C:\Users\Rick\Documents\Logo1.png
<F>-
<div style="font-size:25px;font-weight:bold;text-align:center;font-family:'Calibri'">SALES RECEIPT</div>
<F>-
<div style="font-size:13px;font-weight:bold;text-align:left;font-family:'Calibri'">
<table>
<tr>
<td width="25">Qty</td>
<td width="196">Item Desription</td>
<td width=45 align=right>Price</td>
</tr>
</table>
</div>
<F>-
{ORDERS}
<div style="font-size:15px;font-weight:bold;text-align:center;font-family:'Calibri'"><br/>{TICKET QUANTITY SUM}x Items Sold</div>
<F>-
<div style="font-size:15px;font-weight:bold;text-align:justify;font-family:'Calibri'">
<table>
<tr>
<td align="left">Sub Total:</td><td align="right">£[=F(TN('{TICKET TOTAL}')+TN('{ORDER TAG TOTAL:Discount Item Custom Value}')*-1+TN('{ORDER TAG TOTAL:Discount Item Custom Percent}')*-1+TN('{ORDER TAG TOTAL:Price Override}')*-1+TN('{CALCULATION TOTAL:Discount Ticket Total Percent}')*-1+TN('{CALCULATION TOTAL:Staff Discount}')*-1+TN('{CALCULATION TOTAL:Manager Discount}')*-1+TN('{CALCULATION TOTAL:Loyalty Discount}')*-1,'0.00')]</td>
</tr>
</table>
</div>
<F>-
{DISCOUNTS}

[<div style="font-size:15px;font-weight:normal;text-align:justify;font-family:'Calibri'">
<table>
<tr>
<td align="left">Line Discount:</td><td align "right">-£[=F(TN('{ORDER TAG TOTAL:Discount Item Custom Value}')*-1+TN('{ORDER TAG TOTAL:Price Override}')*-1+TN('{ORDER TAG TOTAL:Discount Item Custom Percent}')*-1,'0.00')]</td>
</tr>
</table>
</div>]

[<div style="font-size:15px;font-weight:normal;text-align:justify;font-family:'Calibri'">
<table>
<tr>
<td align="left">Transaction Discount:</td><td align "right">-£[=F(TN('{CALCULATION TOTAL:Discount Ticket Total Percent}')*-1,'0.00')]</td>
</tr>
</table>
</div>]

[<div style="font-size:15px;font-weight:normal;text-align:justify;font-family:'Calibri'">
<table>
<tr>
<td align="left">Staff Discount 10%:</td><td align "right">-£[=F(TN('{CALCULATION TOTAL:Staff Discount}')*-1,'0.00')]</td>
</tr>
</table>
</div>]

[<div style="font-size:15px;font-weight:normal;text-align:justify;font-family:'Calibri'">
<table>
<tr>
<td align="left">Owner Discount 100%:</td><td align "right">-£[=F(TN('{CALCULATION TOTAL:Manager Discount}')*-1,'0.00')]</td>
</tr>
</table>
</div>]

[<div style="font-size:15px;font-weight:normal;text-align:justify;font-family:'Calibri'">
<table>
<tr>
<td align="left">Loyalty Discount 10%:</td><td align "right">-£[=F(TN('{CALCULATION TOTAL:Loyalty Discount}')*-1,'0.00')]</td>
</tr>
</table>
</div>]

[<div style="font-size:15px;font-weight:bold;text-align:justify;font-family:'Calibri'">
<table>
<tr>
<td align="left">Discount Total:</td><td align "right">-£[=F(TN('{ORDER TAG TOTAL:Discount Item Custom Value}')*-1+TN('{ORDER TAG TOTAL:Discount Item Custom Percent}')*-1+TN('{ORDER TAG TOTAL:Price Override}')*-1+TN('{CALCULATION TOTAL:Discount Ticket Total Percent}')*-1+TN('{CALCULATION TOTAL:Staff Discount}')*-1+TN('{CALCULATION TOTAL:Manager Discount}')*-1+TN('{CALCULATION TOTAL:Loyalty Discount}')*-1,'0.00')]</td>
</tr>
</table>
</div>
<F>-]
<div style="font-size:22px;font-weight:bold;text-align:justify;font-family:'Calibri'">
<table>
<tr>
<td align="left">Total:</td><td align "right">£{TICKET TOTAL}</td>
</tr>
</table>
</div>
{PAYMENTS}
<F>-
<div style="font-size:15px;font-weight:bold;text-align:justify;font-family:'Calibri'">
<table>
<tr>
<td align="left">Tendered Total:</td><td align="right">£{TENDERED TOTAL}</td>
</tr>
</table>
</div>
<div style="font-size:15px;font-weight:bold;text-align:justify;font-family:'Calibri'">
<table>
<tr>
<td align="left">Change:</td><td align="right">£{CHANGE TOTAL}</td>
</tr>
</table>
</div>
<F>-
</div>
<F>
<F>
<div style="font-size:15px;font-weight:bold;text-align:center;font-family:'Calibri'">
<L00>THANK YOU<br/>
</div>
<EC>
<BAR>{TICKET TAG:Barcode}
<EL>
<F>-
<div style=font-size:15px;font-weight:bold;text-align:center;font-family:'Calibri'">
<table>
<tr>
<td width=60 align=center>{TICKET ID}</td>
<td width=80 align=center>{TICKET DATE}</td>
<td width=60 align=center>{TIME}</td>
<td width=60 align=center>{USER NAME}</td>
</tr>
</table>
</div>
<F>-
<br/>
<br/>
<br/>
<L>-
{ENTITIES}

[DISCOUNTS]

[PAYMENTS]
<div style="font-size:15px;font-weight:bold;text-align:justify;font-family:'Calibri'">
<table>
<tr>
<td align="left">{PAYMENT NAME}</td>
<td align="right">£{TENDERED}</td>
</tr>
</table>
</div>
[ORDERS]
-- Default format for orders
<div style="font-size:13px;text-align:left;font-family:'Calibri'">
<table>
<tr>
<td width="25">{QUANTITY}x</td>
<td width="196">{ITEM TAG:Receipt Name} {PORTION: }</td>
<td width=45 align=right>£{TOTAL PRICE}</td>
</tr>
</table>
</div>
{ORDER TAGS}
[ORDERS:Gift]
-- Format for gifted orders
<J00>{QUANTITY} {NAME}|**FREE ITEM**


[ORDERS:Void]
-- Nothing will print for void lines

[ORDER TAGS]
-- Format for order tags
[<div style="font-size:10px;text-align:left;font-family:'Calibri'">
<table>
<tr>
<td width="25">***</td>
<td width="196">Line Discount: {ORDER STATE:DStatus}</td>
<td width=45 align=right>-£[=F(TN('{ORDER TAG TOTAL:Discount Item Custom Value}')+TN('{ORDER TAG TOTAL:Discount Item Custom Percent}')*-1),'0.00']</td>
</tr>
</table>
</div>]

[<div style="font-size:10px;text-align:left;font-family:'Calibri'">
<table>
<tr>
<td width="25">***</td>
<td width="196">Price Override: {ORDER STATE:PStatus}</td>
<td width=45 align=right>-£[=F(TN('{ORDER TAG TOTAL:Price Override}')*-1),'0.00']</td>
</tr>
</table>
</div>]


[ENTITIES:Customers]
-- Customer entity format
<F>
<F>
[ENTITIES:Table]
-- Table entity format
<L00>Table: {ENTITY NAME}
<F>
<F>