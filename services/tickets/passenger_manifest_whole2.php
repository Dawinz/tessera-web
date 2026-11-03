<style>
#myInputTextField2
{
	
	 position: absolute;
right:40px;
  z-index: 4;
	margin-bottom:85px;
	
}
#manifest_table{
	position:relative;
	top:10px;
}
</style>

<?php
	session_start();
$username=$_SESSION['username'];
include 'connect.php';
$select2=mysqli_query($db,"SELECT *  FROM  users  where username='".$_SESSION['username']."'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname']; 
			$surname =$get12['surname']; 

		$fullname = "$firstname $surname";
	}
}


$route_day_id = $_GET['id'];
$trip_date = $_GET['trip_date'];
$route_id = $_GET['route_id'];
$bus = $_GET['bus'];


date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('l jS \of F Y', time());
$date_time_insert = date(' h:i A', time());


$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$route_day_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $route_id =$get134['route_id'];
			$bus =$get134['bus'];
	
		

		
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


$select1456=mysqli_query($db,"SELECT *  FROM  manifest WHERE route_day_id='$route_day_id' and trip_date='$trip_date' and route_id='$route_id'  ");
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



?>
<div id="route_day_id_modal_manifest"class="display_non"><?php echo $route_day_id;?></div>
<div id="route_id_modal_manifest"class="display_non"><?php echo $route_id;?></div>
<div id="start_route_modal_manifest"class="display_non"><?php echo $start_route_r;?></div>
<div id="end_route_modal_manifest"class="display_non"><?php echo $end_route_r;?></div>
<div id="trip_date_modal_manifest"class="display_none"><?php echo $trip_date;?></div>
<div id="bus_modal_manifest"class="display_none"><?php echo $bus;?></div>
<head>

  <title><?php echo "$bus - $trip_date";?> </title>
  <!-- plugins:css -->
 

</head>
<div class="alert alert-danger display_none" id="alert_display"role="alert"><h6 style="color:black">Seat selected</h6>
<span id="seat_array_list"></span>   <span class="display_none"id="ticket_array_list"></span>                                            </div>
		 <div class="container"style="overflow-y: scroll; max-height:700px;">
		<table id="myInputTextField2">
		<tr>
		<td><button type="button" class="btn btn-soft-success" id="transfer_m_p"><i class="uil uil-arrow-random"></i> Transfter</button></td>
		<td><button type="button" class="btn btn-soft-warning"id="reservation_id"><i class="uil uil-registered"></i> Complete Reservation</button></td>
		<td><button type="button" class="btn btn-soft-primary"><i class="uil uil-print"></i> print</button></td>
				<td><button type="button" class="btn btn-soft-danger"><i class="uil uil-ban"></i> Cancel Ticket</button></td>

		</tr>
		</table>
        <table class="table mb-0" id="manifest_table">
		<thead class="table-light">
		<th><input type="checkbox" id="select_all" name="vehicle1" class="checkbox form-check-input font-size-16"></th>
		<th></th>
		<th>Fullname</th>
		<th>Seat</th>
		<th>Status</th>
		<th>Ticket no</th>
		<th>Phone no</th>
		<th>Id/Passport</th>
		<th>From</th>
		<th>To</th>
		<th>Picking point</th>
		
		<th>Price</th>
		</thead>
		<?php
		$seat_no_array = array(); 
		$seat_no_array_id = array();  
		
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
	ending_location,sub_route_id,cost,status
FROM 
	booking WHERE   route_day_id='$route_day_id' and trip_date='$trip_date' and status !='booked'
ORDER BY 
	seat_no ASC 
		
		");
$select_num1450=mysqli_num_rows($select1450);
if($select_num1450 !=0){
   	WHILE($get13450=mysqli_fetch_array($select1450)){
    	    $firstname =$get13450['firstname'];
    	    $lastname =$get13450['lastname'];
			$seat_no =$get13450['seat_no'];
			$phone_no =$get13450['phone_no'];
			$id2 =$get13450['id'];
			$validation =$get13450['validation'];
			
			$start_location =$get13450['start_location'];
			$ending_location =$get13450['ending_location'];
			$sub_route_id =$get13450['sub_route_id'];
			$cost =$get13450['cost'];
			$status =$get13450['status'];
			$row_num =$get13450['row_num'];
		
			array_push($seat_no_array,$seat_no );
			array_push($seat_no_array_id,$id2 );
			
			
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
		<td><input type="checkbox" id="check_box<?php echo $seat_no;?>" class="checkbox form-check-input font-size-16"name="vehicle1" value="<?php echo $seat_no;?>"></td>
		<td><?php echo $row_num;?></td>
		<td><?php echo "$firstname $lastname";?></td>
		<td><?php echo "$seat_no";?></td>
		<td><?php echo "$status";?></td>
		<td><?php echo "$id2";?></td>
		<td><?php echo "$phone_no";?></td>
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
		
		<td><?php echo $cost;?> </td>
		
		</tr>
		
		<script>
	
				$("#check_box<?php echo $seat_no;?>").click(function(){
					remove_seat = "<?php echo "$seat_no,";?>";
				select_all_seats = $("#seat_array_list").text();
				select_all_seats_ticket = $("#ticket_array_list").text();
function removeCharacter() {
    let originalString = select_all_seats;
    newString = originalString.replace(remove_seat, "");
    $("#seat_array_list").text(newString);
}
 
removeCharacter()
if(this.checked){
if(select_all_seats===""){
 fruits = [];	
 ticket = [];	

}else{
	select_all_seats = $("#seat_array_list").text();
	select_all_seats_ticket = $("#ticket_array_list").text();
 fruits = [select_all_seats];
 ticket = [select_all_seats_ticket];	
}
	
	fruits.push("<?php echo "$seat_no";?>");
	ticket.push("<?php echo "$id2";?>");
	sort_fruits = fruits.sort();
	 $("#alert_display").show();
$("#seat_array_list").text(fruits);
$("#ticket_array_list").text(ticket);
 sort_seat = $("#seat_array_list").text();
}else{
					remove_seat = "<?php echo ",$seat_no";?>";
				select_all_seats = $("#seat_array_list").text();
function removeCharacter() {
    let originalString = select_all_seats;
    newString = originalString.replace(remove_seat, "");
    $("#seat_array_list").text(newString);
}
 
removeCharacter()		

		remove_seat = "<?php echo "$seat_no";?>";
				select_all_seats = $("#seat_array_list").text();
function removeCharacter() {
    let originalString = select_all_seats;
    newString = originalString.replace(remove_seat, "");
    $("#seat_array_list").text(newString);
}
 
removeCharacter()	

	remove_seat_no = "<?php echo ",$id2";?>";
				select_all_seats_no = $("#ticket_array_list").text();
function removeCharacter_no() {
    let originalString = select_all_seats_no;
    newString = originalString.replace(remove_seat_no, "");
    $("#ticket_array_list").text(newString);
}
 
removeCharacter_no()


remove_seat_no = "<?php echo "$id2";?>";
select_all_seats_no = $("#ticket_array_list").text();


function removeCharacter_no2() {
    let originalString = select_all_seats_no;
    newString = originalString.replace(remove_seat_no, "");
    $("#ticket_array_list").text(newString);
}
removeCharacter_no2()



}




	});
	
		</script>
		
		<?php

		

		
	}
}else{
	?>
	<tr>
	<td>sa</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
	</tr>
	
	<?php
}
		?>
	
		
		
		</table> 
		<div class="display_none" id="select_all_seats"><?php $update_area =  implode(",",$seat_no_array);echo $update_area; ?></div>
		<div class="display_none" id="select_all_seats_no_div"><?php $update_area2 =  implode(",",$seat_no_array_id);echo $update_area2; ?></div>
</div>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>
<script src="transfer_manifest_panel.js"></script>

<script>
$(document).ready(function() {



		 $('#manifest_table').DataTable({
			 dom: 'Bfrtip',
			 "lengthMenu": [ 100 ],
			 order: [[8, 'desc']],
			  columnDefs: [
        {
            target: 9,
            visible: false,
            searchable: false
        }
    ],      buttons: [
            'copy', 'csv', 'excel', 'pdf',

 { extend: 'print', footer: true,
       
   autoPrint: true,
                title: '<div style="margin:10px;"><p style="font-size:25px;display:flex;justify-content:center;align-items:center;font-weight:bold;"> <img src ="images/sr.jpg" width="80" height="120" /><br>SR CLASSIC PASSANGER MANIFEST CHART</p><div class="card-header justify-content-between d-flex align-items-center"><p style="font-size:18px;font-weight:bold;"><br> <br>Route : <?php
if($sub_route_id==""){
		echo "$start_route_r ";
}else{
	echo $start_route_sub;
}
	
		
		?> to <?php
if($sub_route_id==""){
		echo "$end_route_r ";
}else{
	echo $end_route_sub;
}
	
		
		?> <br>Date : <?php
	$today_date_word = date('l, jS \of F Y', strtotime($trip_date));
echo $today_date_word;
	?> <br>Printed by <?php echo $fullname;?></p> 	<a  ><p style="font-size:18px;font-weight:bold;"><br>Vehicle : <?php echo $bus_manifest;?> </a><br>Driver : <?php echo $driver;?><br>Conductor : <?php echo $conductor;?></p></div></div>',

                //For repeating heading.
                repeatingHead: {
                    logo: 'images/sr.jpg',
                    logoPosition: 'right',
                    logoStyle: '',
                    title: '<h3>Sample Heading</h3>'
                } },
        ],
	});
  

    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
				select_all_seats = $("#select_all_seats").text();
				select_all_seats_no_div = $("#select_all_seats_no_div").text();
		$("#seat_array_list").text(select_all_seats);
		$("#ticket_array_list").text(select_all_seats_no_div);
		$("#alert_display").show();
		alert(select_all_seats);
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
  $("#seat_array_list").text("");
  	$("#ticket_array_list").text("");
        }
		
		select_all_seats = $("#select_all_seats").text();
	
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
			
        }else{
            $('#select_all').prop('checked',false);
        }
    });

});


</script>

</script>