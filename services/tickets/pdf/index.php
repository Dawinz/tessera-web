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

$select_seat=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and status='complete' and delete_book=''  or route_day_id='$id' and trip_date='$trip_date' and status='half_complete' and delete_book='')");
$select_num_seat=mysqli_num_rows($select_seat);

$query = "
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
	ending_location,sub_route_id,amount_left,status,cost,start_route,end_route
FROM 
	booking WHERE   route_day_id='$id' and trip_date='$trip_date' and status='complete' and delete_book=''  or route_day_id='$id' and trip_date='$trip_date' and status='half_complete' and delete_book='' 
ORDER BY 
	seat_no ASC 
";
$result = mysqli_query($db, $query);


?>

	<?php
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
  text-align: center;
  font-size:12px;
  background-color:white;
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
        <img src="/tickets/pdf/srlogo.jpg">
      </div>
      <h1>'.$bus_manifest.'</h1>
      <div id="company" class="clearfix" style="margin:10px;">
       <table style="padding:none;margin:none;font-size:14px;">
        <tr >
            <td style="padding:5px ;background:#1C4893; color:white;border:2px solid white;font-size:14px;">Total Seats</td>
            <td style="padding:5px ; background: #F5F5F5;font-size:14px;">'.$select_num_seat.'</td>
        </tr>      
       </table>
       
     
        
      </div>
      <div id="project">


        <div><span>VEHICLE NAME</span>  '.$bus_manifest.'</div>
        <div><span>TRIP DATE</span>   '. $trip_date_changed.' </div>
        <div><span>DRIVERS NAME</span> '. $driver.'</div>
        <div><span>CONDUCTORS NAME</span>  '. $conductor.'</div>
        <div><span>ROUTE NAME</span>   '. $full_trip.'</div>
    
      </div>
    </header>
     <table style="position:relative;bottom:30px;">
        <thead>
       <tr style="background:#1C4893;color:white;">
            <th >No</th>
           	<th>Fullname</th>
		<th>Seat</th>
		<th>Ticket no</th>
		<th>Phone no</th>
		<th>Id/Passport</th>
		<th>From</th>
		<th>To</th>
		<th>Picking point</th>
		<th>Ending Point</th>
          </tr>

';
while($row = mysqli_fetch_array($result))
{
	
	       $row_num =$row['row_num'];
		 $firstname =$row['firstname'];
    	    $lastname =$row['lastname'];
			$seat_no =$row['seat_no'];
			$phone_no =$row['phone_no'];
			$id2 =$row['id'];
			$validation =$row['validation'];
			$status =$row['status'];
			$cost =$row['cost'];
			
			$start_location =$row['start_location'];
			$ending_location =$row['ending_location'];
			$start_route =$row['start_route'];
			$end_route =$row['end_route'];
			$sub_route_id =$row['sub_route_id'];
						$amount_left =$row['amount_left'];
		$select1734=mysqli_query($db,"SELECT *  FROM  sub_route Where sub_route_id='$sub_route_id'");
$select_num1734=mysqli_num_rows($select1734);
if($select_num1734 !=0){
   	WHILE($get13734=mysqli_fetch_array($select1734)){
 
 $start_route_sub= $get13734['start_route'];
 $end_route_sub= $get13734['end_route'];
 $a_time= $get13734['a_time'];
 $d_time= $get13734['d_time']; 
}}
	
	$output .= '
		<tr>
			<td>'.$row["row_num"].'</td>
			<td>'.$firstname.' '.$lastname.'</td>
			<td>'.$seat_no.'</td>
			<td>'.$id2.'</td>
			<td><a href=tel:'.$phone_no.'>'.$phone_no.'</td>
			<td>'.$validation.'</td>
			<td>'.$start_route.'</td>
			<td>'.$end_route.'</td>
			<td>'.$start_location.'</td>
			<td>'.$ending_location.'</td>
			
		</tr>
	';
}

$select_foc=mysqli_query($db,"SELECT *  FROM  	booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and start_route='$start_route_r' and end_route='$end_route_r' and status='complete' and cost='0' or route_day_id='$id' and trip_date='$trip_date' and start_route='$start_route_r' and end_route='$end_route_r' and status='half_complete' and cost='0')");
$select_num_foc=mysqli_num_rows($select_foc);

		$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			$start_route_r =$get134['start_route'];
			$end_route_r =$get134['end_route'];
			
$select_full=mysqli_query($db,"SELECT *  FROM  	booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and start_route='$start_route_r' and end_route='$end_route_r' and status='complete' and delete_book='' and status !='booked' or route_day_id='$id' and trip_date='$trip_date' and start_route='$start_route_r' and end_route='$end_route_r' and status='half_complete' and delete_book='' and status !='booked')");
$select_num_full=mysqli_num_rows($select_full);
$select_num_full_new = $select_num_full - $select_num_foc;
$total_seat_price = $fare_price*$select_num_full_new; 




$output .='
	<tr>
                 <td colspan="4"></td>
               <td colspan="5"><strong>'.$start_route_r.' to '.$end_route_r.'</strong></td>
		
			
			<td><strong>'.$select_num_full.' </strong></td>
	
		
		</tr>

';
?>


<?php
		

		
	}
}

		$select14=mysqli_query($db,"SELECT *  FROM  sub_route WHERE main_route_no='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			$start_route_r =$get134['start_route'];
			$end_route_r =$get134['end_route'];
			
		$select_foc=mysqli_query($db,"SELECT *  FROM  	booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and start_route='$start_route_r' and end_route='$end_route_r' and status='complete' and cost='0' and delete_book='' and status !='booked' or route_day_id='$id' and trip_date='$trip_date' and start_route='$start_route_r' and end_route='$end_route_r' and status='half_complete' and cost='0' and delete_book='' and status !='booked')");
$select_num_foc=mysqli_num_rows($select_foc);
	
			
			
			$select_full=mysqli_query($db,"SELECT *  FROM  	booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and start_route='$start_route_r' and end_route='$end_route_r' and status='complete' and delete_book='' and status !='booked' or route_day_id='$id' and trip_date='$trip_date' and start_route='$start_route_r' and end_route='$end_route_r' and status='half_complete'and delete_book='' and status !='booked')");
$select_num_full=mysqli_num_rows($select_full);
if($select_num_full!=0){
	$select_num_full_new = $select_num_full - $select_num_foc;
	$total_seat_price = $fare_price*$select_num_full_new;
	if($select_num_foc !=0){
$select_display = "(-$select_num_foc)";
}else{
	$select_display = "";
}

$output .='
	<tr>
                 <td colspan="4"></td>
               <td colspan="5"><strong>'.$start_route_r.' to '.$end_route_r.'</strong></td>
		
			
			<td><strong>'.$select_num_full.' '.$select_display.'</strong></td>
	
		
		</tr>

';
}
		
	}
}

$output .='
	<tr>
                 <td colspan="8"></td>
             
		
			<td><strong><strong>Total seats</strong></strong></td>
			<td><strong>'.$select_num_seat.' </strong></td>
	
		
		</tr>
</table>
';

	$output .= '
   <div id="notices">
      
        <div >This document was prepared by '.$fullname.' on '.$date_insert.' at '.$date_time_insert.'.</div>
      </div>
	';
$output .= '</body>';
require_once "vendor/autoload.php";
 
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$dompdf->set_option('enable_remote', TRUE);
$dompdf->loadHtml($output);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');
 
// Render the HTML as PDF
$dompdf->render();
 
// Output the generated PDF to Browser
$dompdf->stream("TICKET $bus_manifest $trip_date_changed");

?>