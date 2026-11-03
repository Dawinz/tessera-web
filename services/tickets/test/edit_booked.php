<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';

$id= $_GET['id_update'];

?>

     <?php
	 $route_id = $_GET['route_id2'];

//index.php
$connect = mysqli_connect("localhost", "root", "Teccross@360", "ticket");
$start_route2 = '';
$query = "
SELECT detail
FROM  sub_route_points Where route_id='$route_id' and point ='starting' 
UNION DISTINCT
SELECT detail
FROM route_points where route_id='$route_id' and point ='starting' 
";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["detail"].'">'.$row["detail"].'</option>';
}
?>     <?php
//index.php

$end_route_point = '';
$query = "
SELECT detail
FROM  sub_route_points Where route_id='$route_id' and point ='end' 
UNION DISTINCT
SELECT detail
FROM route_points where route_id='$route_id' and point ='end' 
";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $end_route_point .= '<option value="'.$row["detail"].'">'.$row["detail"].'</option>';
}
?>
	

         <style>
		   /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
		   </style> 
		   	  <?php
include 'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $seat_no =$get13['seat_no']; 
 $cost =$get13['cost']; 
 $id= $get13['id']; 
 $trip_date= $get13['trip_date']; 
 $status= $get13['status'];
 $firstname= $get13['firstname'];
 $lastname= $get13['lastname'];
 $phone_no= $get13['phone_no'];
$validation= $get13['validation'];
$gender= $get13['gender'];
$age_group= $get13['age_group'];
$reason= $get13['reason'];
 $start_location= $get13['start_location']; 
 $ending_location= $get13['ending_location'];
 $route_id = $get13['route_id'];
 $sub_route_id = $get13['sub_route_id'];
 $route_day = $get13['route_day_id'];
 $bus = $get13['bus'];
 
 
 if($status=="half_complete"){
$select145=mysqli_query($db,"SELECT *  FROM  sub_route  WHERE sub_route_id='$sub_route_id' ");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
			$start_route =$get1345['start_route'];
			$end_route =$get1345['end_route'];
		$fare_price =$get1345['fare_price']; 
		$sub_route_id =$get1345['sub_route_id']; 
		$main_route_no =$get1345['main_route_no']; 
		
		
		
	
}}	
	
$start_route5 = '';
$query = "SELECT * FROM  sub_route WHERE main_route_no='$main_route_no'  group by start_route   ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route5 .= '<option value="'.$row["start_route"].'">'.$row["start_route"].'</option>';
}

$end_route2 = '';
$query = "SELECT * FROM sub_route WHERE main_route_no='$main_route_no'   group by end_route ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $end_route2 .= '<option value="'.$row["end_route"].'">'.$row["end_route"].'</option>';
}

	
 }else{
			$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
		
		$fare_price =$get134['fare_price']; 
		$main_route_no =$get134['route_id']; 


		
		
		
	
}
 
 }
 $start_route5 = '';
$query = "SELECT * FROM  sub_route WHERE main_route_no='$main_route_no'  group by start_route   ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route5 .= '<option value="'.$row["start_route"].'">'.$row["start_route"].'</option>';
}

$end_route2 = '';
$query = "SELECT * FROM sub_route WHERE main_route_no='$main_route_no'   group by end_route ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $end_route2 .= '<option value="'.$row["end_route"].'">'.$row["end_route"].'</option>';
}

 }
 
?>

<span id="seat_id<?php echo $id;?>" style="display:none;"><?php echo $id;?></span>
    <div class="card-header justify-content-between d-flex align-items-center">
<h4 class="card-title">Seat no : <?php echo $seat_no;?></h4>


                                    </div>
  

  <div class="row">                                                            
  <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">Pick Up Point</label>
                                                            <select class="form-select" id="starting_point<?php echo $id;?>"
                                                aria-label="Floating label select example">
												<option><?php echo $start_location;?></option>
                                                    <?php echo $start_route2; ?>

                                            </select>
                                                        </div>
                                                    </div><!-- end col -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Droping Point</label>
                                                          <select class="form-select" id="end_point<?php echo $id;?>"
                                                aria-label="Floating label select example">
												<option><?php echo $ending_location;?></option>
                                                  <?php echo $end_route_point; ?>
                                            </select>
                                                        </div>
                                                    </div><!-- end col -->
</div>  <div class="row">                                                            
  <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">Firstname</label>
															
                                                            <input type="text" value="<?php echo $firstname;?>"class="form-control firstname<?php echo $id;?>"id="firstname<?php echo $id;?>">
                                                        </div>
                                                    </div><!-- end col -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Last name</label>
                                                            <input type="text" value="<?php echo $lastname;?>"class="form-control lastname<?php echo $id;?>" id="lastname<?php echo $id;?>">
                                                        
														
														</div>
                                                    </div><!-- end col -->
</div>  
 <div class="row">                                                            
  <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">Phone number</label>
                                                            <input type="text" class="form-control phone_no<?php echo $id;?>" id="phone_no<?php echo $id;?>"value="<?php echo $phone_no;?>">
                                                        </div>
                                                    </div><!-- end col -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Gender</label>
                                                                    <select class="form-select" id="gender<?php echo $id;?>"
                                                aria-label="Floating label select example">
                                                <option value="1"><?php echo $gender;?></option>
												<option value="1">Male</option>
                                                <option value="2">Female</option>
                                            </select>
                                                        </div>
                                                    </div><!-- end col -->
</div> 
  <div class="mb-3">
                                                    <label class="form-label" for="formrow-firstname-input">Age group</label>
                                                   <select class="form-select" id="age_group<?php echo $id;?>"
                                                aria-label="Floating label select example">
                                               <option value="1"><?php echo $age_group?></option>
                                                <option value="1">Adult</option>
												
                                                <option value="2">child</option>
                                               
                                            </select>
                                                </div>
												                    <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">FOC </label><?php echo $reason;?>
                                                                                         <input type="checkbox" <?php if($reason==""){}else{echo "checked"; }?> class="form-check-input"  id="foc<?php echo $id;?>">

                                                        </div>
															 <div class="mb-3" style="<?php if($reason !=""){}else{ echo "display:none;";}?>" id="focarea_one<?php echo $id;?>">
                                                    <label class="form-label" for="formrow-firstname-input">FOC Reasons</label>
                                                                                                   <input type="text" class="form-control reason<?php echo $id;?>"id="reason<?php echo $id;?>"value="<?php echo $reason;?>">

                                                </div>
                                    <button type="button" class="btn btn-success"id="save<?php echo $id;?>"><i class="uil uil-check me-2"></i> <span id="booktext<?php echo $id;?>">Change</span></button>
 <div id="seat_id"style="display:none;"><?php echo $id;?></div>

 		<script src="js/vendor/jquery-1.12.4.min.js"></script>

 <script>
$(document).ready(function(){


$("#save<?php echo $id;?>").click(function(){
	var starting_location = $( "#starting_location<?php echo $id;?> option:selected" ).text();
	var ending_location = $( "#ending_location<?php echo $id;?> option:selected" ).text();
	var starting_point = $( "#starting_point<?php echo $id;?> option:selected" ).text();
	var end_point = $("#end_point<?php echo $id;?> option:selected" ).text();
	var firstname = $(".firstname<?php echo $id;?>").val();
	var lastname = $(".lastname<?php echo $id;?>").val();
	var phone_no = $(".phone_no<?php echo $id;?>").val();
	var gender = $( "#gender<?php echo $id;?> option:selected" ).text();
	var age_group = $( "#age_group<?php echo $id;?> option:selected" ).text();
	var seat_id = $( "#seat_id<?php echo $id;?>" ).text();
 var foc = $("#foc<?php echo $id;?>").val();
 var trip_date = "<?php echo $trip_date;?>"; 
 var route_id  = "<?php echo $route_id ;?>";
 
    var reason = $("#reason<?php echo $id;?>").val();
	if(firstname==""){
				 swal("", "Please Enter Firstname!", "warning");

			 control.disabled="disabled";
}if(lastname==""){
				 swal("", "Please Enter Surname!", "warning");

			 control.disabled="disabled";
}if((phone_no=="") || (phone_no=="+255")){
				 swal("", "Please Enter phone number!", "warning");

			 control.disabled="disabled";
}if(gender=="Gender"){
				 swal("", "Please Enter gender!", "warning");

			 control.disabled="disabled";
}if(age_group=="Age group"){
				 swal("", "Please Enter Age group!", "warning");

			 control.disabled="disabled";
}
if((foc=="checked") && (reason=="")){
				 swal("", "Please Enter reason for FOC!", "warning");

			 control.disabled="disabled";
}
$("#booktext<?php echo $id;?>").text("changing...");


 $.ajax({
				url:"update_passenger_info.php",
				method:"POST",
				data:{firstname:firstname,lastname:lastname,phone_no:phone_no,gender:gender,seat_id:seat_id,age_group:age_group,starting_point:starting_point,end_point:end_point,reason:reason,foc:foc,trip_date:trip_date,route_id:route_id,starting_location:starting_location,ending_location:ending_location},
				success:function(response){
					$("#booktext<?php echo $id;?>").text("Change");
if (response=="past"){
				 swal("Past", "Cannot delete past bookings", "warning");
	
			
		}else{
										  swal({
    title: "Update",
    text: "update Succefull.",
    timer: 600,
    showConfirmButton: false
  });
		}
		
	$("#load_table").load("booking_table.php?id=<?php echo $route_day;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	$("#load_seat_table").load("load_seat_table.php?id=<?php echo $route_day;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	$("#load_booking").load("load_booking.php?id=<?php echo $route_day;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");

$("#load_status").load("load_status.php?id=<?php echo $route_day;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");

	}
			
			});

});

$("#foc<?php echo $id;?>").click(function(){
if ($('#foc<?php echo $id;?>').prop('checked')) {
    //blah blah
swal("FOC SEAT", "You have activate FOC Seat the sale amount will be 0.00 please put reasons for fos seat!", "warning");
$("#focarea_one<?php echo $id;?>").show();
$("#foc<?php echo $id;?>").val("checked");
}else{
		$("#focarea_one<?php echo $id;?>").hide();
		$("#foc<?php echo $id;?>").val("");
}
});
	});


</script>
 
<?php
}}

?>