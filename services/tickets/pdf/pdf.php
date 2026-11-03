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
  text-align: center;
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
<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';


	session_start();
include '../connect.php';
$username=$_SESSION['username'];

$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname']; 
			$surname =$get12['surname']; 

		$fullname = "$firstname $surname";
	}
}
?>
<?php
$id=$_GET['id'];
$trip_date=$_GET['trip_date'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('l jS \of F Y', time());
$date_time_insert = date(' h:i A', time());
    $trip_date_changed = date("d-m-Y", strtotime($trip_date));  


$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $route_id =$get134['route_id'];
			$bus =$get134['bus'];
	 $a_time= $get134['arrival'];
 $d_time= $get134['departure']; 
		

		
	}
}
$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			$start_route_r =$get134['start_route'];
			$end_route_r =$get134['end_route'];

		

		
	}
}

$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			$start_route_r =$get134['start_route'];
			$end_route_r =$get134['end_route'];

		

		
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

$select1456=mysqli_query($db,"SELECT *  FROM  manifest WHERE id='".$_GET['manifest']."' ");
$select_num1456=mysqli_num_rows($select1456);
if($select_num1456 !=0){
   	WHILE($get13456=mysqli_fetch_array($select1456)){
    	    $bus_manifest =$get13456['bus'];
    	    $driver_manifest =$get13456['driver'];
    	    $conductor_manifest =$get13456['conductor'];
			$start_location =$get13456['start_location'];
			$end_location =$get13456['end_location'];

$full_trip = "$start_location to $end_location";

		
	}
}

$select_seat=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and status !='locked')");
$select_num_seat=mysqli_num_rows($select_seat);



?>
<?php
		 $sql = $db->query("SELECT SUM(cost) AS  cost FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and status !='booked')");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['cost'];
		
			
		}}else{
	
		}


		?>

  <header class="clearfix">
      <div id="logo">
        <img src="http://localhost/pdf/srlogo.jpg">
      </div>
      <h1><?php echo $bus_manifest; ?></h1>
      <div id="company" class="clearfix" style="margin:10px;">
       <table style="padding:none;margin:none;">
        <tr >
            <td style="padding:5px ;background:#1C4893; color:white;border:2px solid white;">Total Passangers</td>
            <td style="padding:5px ; background: #F5F5F5;"><?php echo $select_num_seat; ?></td>
        </tr>   
		<tr>
            <td style="padding:5px ;background:#1C4893; color:white;border:2px solid white;">Total Ammount</td>
            <td style="padding:5px ; background: #F5F5F5;border:2px solid white;"><?php echo number_format($amount,2); ?></td>
        </tr>   
       </table>
       
     
        
      </div>
      <div id="project">


        <div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<span>VEHICLE NAME</span> &nbsp <?php echo $bus_manifest;?></div>
        <div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<span>TRIP DATE</span>  &nbsp <?php echo $trip_date;?> </div>
        <div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<span>DRIVERS NAME</span> &nbsp&nbsp <?php echo $driver;?></div>
        <div><span>CONDUCTOR'S NAME</span>  &nbsp&nbsp <?php echo $conductor;?></div>
        <div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<span>ROUTE NAME</span>  &nbsp&nbsp <?php echo $full_trip;?></div>
    
      </div>
    </header>


 <main>
      <table style="position:relative;bottom:30px;">
        <thead>
          <tr style="background:#1C4893;color:white;">
            <th >No</th>
           	<th>Fullname</th>
		<th>Seat</th>
		<th>Ticket no</th>
		<th>Status</th>
		<th>Phone no</th>
		<th>Id/Passport</th>
		<th>From</th>
		<th>To</th>
		<th>Picking point</th>
		<th>Ending Point</th>
		<th>Cost</th>
          </tr>
        </thead>
        <tbody>
       <tr>

      
       </tr>
        <?php
        
        $select1450=mysqli_query($db,"
	SELECT 
	ROW_NUMBER() OVER (
		ORDER BY seat_no ASC 
	) row_num,
    firstname,
    id,
	lastname,
	seat_no,
	phone_no,
	validation,
	start_location,
	ending_location,sub_route_id,amount_left,status,cost
FROM 
	booking WHERE   route_day_id='$id' and trip_date='$trip_date' and status !='locked'
ORDER BY 
	seat_no ASC 
		
		");
$select_num1450=mysqli_num_rows($select1450);
if($select_num1450 !=0){
   	WHILE($get13450=mysqli_fetch_array($select1450)){
        $row_num =$get13450['row_num'];
		 $firstname =$get13450['firstname'];
    	    $lastname =$get13450['lastname'];
			$seat_no =$get13450['seat_no'];
			$phone_no =$get13450['phone_no'];
			$id2 =$get13450['id'];
			$validation =$get13450['validation'];
			$status =$get13450['status'];
			$cost =$get13450['cost'];
			
			$start_location =$get13450['start_location'];
			$ending_location =$get13450['ending_location'];
			$sub_route_id =$get13450['sub_route_id'];
			$row_num =$get13450['row_num'];
						$amount_left =$get13450['amount_left'];
		$select1734=mysqli_query($db,"SELECT *  FROM  sub_route Where sub_route_id='$sub_route_id'");
$select_num1734=mysqli_num_rows($select1734);
if($select_num1734 !=0){
   	WHILE($get13734=mysqli_fetch_array($select1734)){
 
 $start_route_sub= $get13734['start_route'];
 $end_route_sub= $get13734['end_route'];
 $a_time= $get13734['a_time'];
 $d_time= $get13734['d_time']; 
}}
		
?>
		<tr>
		<td><?php echo $row_num;?></td>
		<td><?php echo "$firstname $lastname";?></td>
		<td><?php echo "$seat_no";?></td>
		<td><?php echo "$id2";?></td>
						<td><?php if($status=="booked"){ echo "Reserved";}else{echo "Complete";} if($amount_left ==0){}else{echo "<br>(but no cleared)";}?></td>

		<td><?php echo "<a href=tel:$phone_no'>$phone_no</a>";?></td>
		<td><?php echo "$validation";?></td>
		<td><?php
if($sub_route_id==""){
		echo "$start_route_r ";
}else{
	echo $start_route_sub;
}
	
		
		?></td>
		
		<td>
<?php
if($sub_route_id==""){
		echo "$end_route_r ";
}else{
	echo $end_route_sub;
}
	
		
		?></td>
		<td><?php echo "$start_location ";?></td>
		<td><?php echo "$ending_location ";?></td>
		<td><?php echo number_format($cost,2);?> </td>
	
		
		</tr>
<?php
    }}
	
	

        ?>
        </tbody>
		<?php
		$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			$start_route_r =$get134['start_route'];
			$end_route_r =$get134['end_route'];
			
			$select_full=mysqli_query($db,"SELECT *  FROM  	booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and start_route='$start_route_r' and end_route='$end_route_r')");
$select_num_full=mysqli_num_rows($select_full);
?>

<tr style="border-top:1px solid grey;">
            <td colspan="7"></td>
			<td ><?php echo "$start_route_r to $end_route_r";?></td>
            <td ><?php echo number_format($fare_price,2);?></td>
            <td ><?php echo number_format($fare_price,2);?></td>
            <td ><?php echo $select_num_full;?></td>
            <td ><?php $total_seat = $fare_price*$select_num_full; echo number_format($total_seat,2)?></td>
          </tr>
<?php
		

		
	}
}
		?>	
		<?php
		$select14=mysqli_query($db,"SELECT *  FROM  sub_route WHERE main_route_no='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			$start_route_r =$get134['start_route'];
			$end_route_r =$get134['end_route'];
			
			$select_full=mysqli_query($db,"SELECT *  FROM  	booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and start_route='$start_route_r' and end_route='$end_route_r')");
$select_num_full=mysqli_num_rows($select_full);
if($select_num_full!=0){
?>

<tr style="">
            <td colspan="7"></td>
			<td ><?php echo "$start_route_r to $end_route_r";?></td>
            <td ><?php echo number_format($fare_price,2);?></td>
            <td ><?php echo number_format($fare_price,2);?></td>
            <td ><?php echo $select_num_full;?></td>
            <td ><?php $total_seat = $fare_price*$select_num_full; echo number_format($total_seat,2)?></td>
          </tr>
<?php
		
}
		
	}
}
		?>
        <tr style="border-top:1px solid grey;">
            <td colspan="9"></td>
		
          
            <td ><strong>Total Ammount</strong></td>
            <td ><strong><?php echo $select_num_seat;?></strong></td>
            <td ><strong><?php echo number_format($amount,2); ?></strong></td>
          </tr>
		  
		  
      </table>
      <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
      </div>
    </main>

<!DOCTYPE html>

