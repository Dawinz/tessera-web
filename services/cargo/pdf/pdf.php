<style>

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
<?php

session_start();
$date_vehicle=$_SESSION['date_vehicle'];
$name_vehicle=$_SESSION['name_vehicle'];


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
?>

</style>
<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title> Manifest Chart</title>
    
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="http://localhost/pdf/srlogo.jpg">
      </div>
      <h1><?php echo $name_vehicle; ?></h1>
      <div id="company" class="clearfix" style="margin:10px;">
       <table style="padding:none;margin:none;">
        <tr >
            <td style="padding:5px ;background:#1C4893; color:white;border:2px solid white;">Total Paid</td>
            <td style="padding:5px ; background: #F5F5F5;"><?php echo number_format($paid_vehicle,2) ?></td>
        </tr>   <tr >
            <td style="padding:5px ;background:#1C4893; color:white;border:2px solid white;">Total To Be Paid</td>
            <td style="padding:5px ;background: #F5F5F5;"><?php echo number_format($to_pay_vehicle,2) ?></td>
        </tr>   <tr style="border-top:2px solid white;" >
            <td style="padding:5px ;background:#1C4893; color:white;border:2px solid white;">Total Ammount</td>
            <td style="padding:5px ; background: #F5F5F5;"><?php echo number_format($due_vehicle,2) ?></td>
        </tr> 
       </table>
       
     
        
      </div>
      <div id="project">


        <div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<span>VEHICLE NAME</span> &nbsp <?php echo $name_vehicle;?></div>
        <div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<span>TRIP DATE</span>  &nbsp <?php echo $date_vehicle;?> </div>
        <div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<span>DRIVERS NAME</span> &nbsp&nbsp <?php echo $driver;?></div>
        <div><span>CONDUCTOR'S NAME</span>  &nbsp&nbsp <?php echo $Conductor;?></div>
        <div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<span>ROUTE NAME</span>  &nbsp&nbsp <?php echo $route;?></div>
    
      </div>
    </header>
    <main>
      <table style="position:relative;bottom:40px;">
        <thead>
          <tr style="background:#1C4893;color:white;">
            <th >No</th>
            <th >Code No</th>
            <th >Reciver's Name</th>
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
        <?php
        
        $select1450=mysqli_query($db,"
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
		
		");
$select_num1450=mysqli_num_rows($select1450);
if($select_num1450 !=0){
   	WHILE($get13450=mysqli_fetch_array($select1450)){
        $row_num =$get13450['row_num'];
?>
<tr>
<td><?php echo $row_num;?></td>
<td><?php echo $get13450['code'];?></td>
<td><?php echo $get13450['username'];?></td>

<td><?php echo base64_decode($get13450['detail']);?></td>
<td><?php echo number_format($get13450['amount']);?></td>
<td><?php echo number_format($get13450['paid']);?></td>
<td><?php echo number_format($get13450['to_be']);?></td>
<td><?php echo ''.$get13450['q_left'].'/'.$get13450['q_left'].'';?></td>
    </tr>
<?php
    }}
        ?>
        </tbody>
        <tr style="border-top:1px solid grey;">
            <td colspan="7">Total Paid</td>
            <td class="total"><?php echo number_format($paid_vehicle,2);?></td>
          </tr><tr>
            <td colspan="7">Total To be Paid</td>
            <td class="total"><?php echo number_format($to_pay_vehicle,2);?></td>
          </tr><tr>
            <td colspan="7">Total Amount</td>
            <td class="total"><?php echo number_format($due_vehicle,2);?></td>
          </tr>
      </table>
      <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
      </div>
    </main>
  
  </body>
</html>