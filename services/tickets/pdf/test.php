<?php

session_start();

$date_vehicle= @$_GET['date'];
$name_vehicle= @$_GET['date3'];


include '../connect.php';
$select2=mysqli_query($db,"SELECT *  FROM  vehicle_name where date='$date_vehicle' and Name='$name_vehicle'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $vehicle_name =$get12['Name']; 
			$driver =$get12['Driver']; 
			$Conductor =$get12['Conductor']; 
            $to_pay_vehicle =$get12['to_pay']; 
            $paid_vehicle =$get12['paid']; 
            $due_vehicle =$get12['due']; 
            $route =$get12['Route']; 

		
	}
}

$username=$_SESSION['username'];
$select222=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    $firstname =$get122['firstname']; 
    	    $surname =$get122['surname']; 

		$fullname = "$firstname $surname";
	}
}
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('l jS \of F Y', time());
$date_time_insert = date(' h:i A', time());
require_once "vendor/autoload.php";
 
use Dompdf\Dompdf;
$document = new Dompdf();
$query = "
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id ASC 
	) row_num,
    username,
    code,
	username,
	status,
	checked,
	detail,
	to_be,
	paid,
    amount,
    quantity,
    q_left

   
FROM 
manifest WHERE trip_date ='$date_vehicle' and bus = '$vehicle_name'
ORDER BY 
	id ASC 
		
";
$result = mysqli_query($db, $query);

$output = '
  
	<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}




header {
  padding: 10px 0;
  margin-bottom: 30px;
}

#logo {
  text-align: center;
  margin-bottom: 10px;
}

#logo img {
  width: 90px;
}

h1 {

  color: white;
  font-size: 2em;
  line-height: 1.4em;
  font-weight: normal;
  text-align: center;
  margin: 0 0 20px 0;
  background: #CF4242;
}

#project {
  float: left;
}

#project span {
  color: #5D6975;

  font-size: 0.8em;
}

#company {
  float: right;
  text-align: right;
}

#project div,
#company div {
  white-space: nowrap;        
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
 
}

table tr:nth-child(2n-1) td {
  background: #F5F5F5;
}

table th,
table td {
  text-align: center;
}

table th {
  padding: 5px 20px;
  color: white;
  border-bottom: 1px solid #C1CED9;
  white-space: nowrap;        
  font-weight: normal;
}

table .service,
table .desc {
  text-align: left;
}

table td {
  padding: auto;
  text-align: right;
  font-size:12px;
}

table td.service,
table td.desc {
  vertical-align: top;
}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}

table td.grand {
  border-top: 1px solid #5D6975;
}

#notices .notice {
  color: #5D6975;
  font-size: 1.2em;
}

footer {
  color: #5D6975;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #C1CED9;
  padding: 8px 0;
  text-align: center;
}
#td_header{
padding:3px;
font-weight:bold;
border:1px solid grey;
}
#td_header_span{
    background:#1C4893;
    color:white;
font-size:20px;
    border-bottom:1px solid white;
   
padding:6px;
}
body{
      font-family: Arial, Helvetica, sans-serif;
}
</style>
  <body>
  <header class="clearfix">
      <div id="logo">
        <img src="/cargo/pdf/srlogo.jpg">
      </div>
      <h1>'.$name_vehicle.'</h1>
      <div id="company" class="clearfix" style="margin:10px;">
       <table style="padding:none;margin:none;font-size:14px;">
        <tr >
            <td style="padding:5px ;background:#1C4893; color:white;border:2px solid white;">Total Paid</td>
            <td style="padding:5px ; background: #F5F5F5;">'.number_format($paid_vehicle,2).'</td>
        </tr>   <tr >
            <td style="padding:5px ;background:#1C4893; color:white;border:2px solid white;">Total To Be Paid</td>
            <td style="padding:5px ;background: #F5F5F5;">'. number_format($to_pay_vehicle,2).'</td>
        </tr>   <tr style="border-top:2px solid white;" >
            <td style="padding:5px ;background:#1C4893; color:white;border:2px solid white;">Total Ammount</td>
            <td style="padding:5px ; background: #F5F5F5;">'.number_format($due_vehicle,2).'</td>
        </tr> 
       </table>
       
     
        
      </div>
      <div id="project">


        <div><span>VEHICLE NAME</span>  '.$name_vehicle.'</div>
        <div><span>TRIP DATE</span>   '. $date_vehicle.' </div>
        <div><span>DRIVERS NAME</span> '. $driver.'</div>
        <div><span>CONDUCTORS NAME</span>  '. $Conductor.'</div>
        <div><span>ROUTE NAME</span>   '. $route.'</div>
    
      </div>
    </header>
	 <main>
      <table style="position:relative;bottom:40px;">
        <thead>
          <tr style="background:#1C4893;color:white;">
            <th >No</th>
            <th >Code No</th>
            <th >Recivers Name</th>
            <th >DESCRIPTION</th>
            <th>Ammount</th>
            <th>paid</th>
            <th>To be Paid</th>
            <th>Exported Quantity</th>
          </tr>
        </thead>
        <tbody>
       <tr>

      
       </tr>

';

while($row = mysqli_fetch_array($result))
{
	$output .= '
		<tr>
			<td>'.$row["row_num"].'</td>
			<td>'.$row["code"].'</td>
			<td>'.$row["username"].'</td>
			<td>'.base64_decode($row['detail']).'</td>
			<td>'.number_format($row['amount']).'</td>
			<td>'.number_format($row['paid']).'</td>
			<td>'.number_format($row['to_be']).'</td>
			<td>'.$row['q_left'].'/'.$row['q_left'].'</td>
		</tr>
	';
}
	$output .= '
		<tr style="border-top:1px solid grey;">
			<td colspan="7">Total Paid</td>
			<td>'.number_format($paid_vehicle).'</td>
			
		</tr>
		<tr>
			<td colspan="7">Total To be Paid</td>
			<td>'.number_format($to_pay_vehicle).'</td>
			
		</tr>	
		<tr>
			<td colspan="7">Total Amount</td>
			<td>'.number_format($due_vehicle).'</td>
			
		</tr>
		</table>
	';
	$output .= '
   <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">This document was prepared by '.$fullname.' on '.$date_insert.' at '.$date_time_insert.'.</div>
      </div>
	';
$output .= '</main></body>';

//echo $output;
$document->set_option('enable_remote', TRUE);
$document->loadHtml($output);

//set page size and orientation

$document->setPaper('A4', 'landscape');

//Render the HTML as PDF

$document->render();

//Get output of generated pdf in Browser

$document->stream("$name_vehicle - $date_vehicle", array("Attachment"=>0));
//1  = Download
//0 = Preview

?>
