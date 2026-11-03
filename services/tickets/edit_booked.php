require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
		<script src="js/sweetalert.js"></script>

<?php
$id= $_GET['id_update'];

?>

     <?php
	 $route_id = $_GET['route_update'];

?>
     <?php


//index.php
$connect = sr_mysqli('ticket');
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

include 'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE id='$id' AND " . tessera_sql_tenant_eq() . " ");
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
}}
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
											     <div class="col-md-6">
                                       	<div class="mb-3">
                                                    <label class="form-label" for="formrow-firstname-input">Verification</label>
                                                                      <input type="text" class="form-control verif<?php echo $id;?>" id="verif<?php echo $id;?>"value="<?php echo $validation;?>">

                                                </div>
                                                </div> 											     <div class="col-md-6">
                                               <div class="mb-3">
                                                    <label class="form-label" for="formrow-firstname-input">Age group</label>
                                                   <select class="form-select" id="age_group<?php echo $id;?>"
                                                aria-label="Floating label select example">
                                               <option value="1"><?php echo $age_group?></option>
                                                <option value="1">Adult</option>
												
                                                <option value="2">child</option>
                                               
                                            </select>
                                                </div> 
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
									 <script>
$(document).ready(function(){


$("#save<?php echo $id;?>").click(function(){

	var starting_point = $( "#starting_point<?php echo $id;?> option:selected" ).text();
	var end_point = $("#end_point<?php echo $id;?> option:selected" ).text();
	var firstname = $(".firstname<?php echo $id;?>").val();
	var lastname = $(".lastname<?php echo $id;?>").val();
	var phone_no = $(".phone_no<?php echo $id;?>").val();
	var verif = $(".verif<?php echo $id;?>").val();
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
}
if((foc=="checked") && (reason=="")){
				 swal("", "Please Enter reason for FOC!", "warning");

			 control.disabled="disabled";
}
$("#booktext<?php echo $id;?>").text("changing...");


 $.ajax({
				url:"update_passenger_info.php",
				method:"POST",
				data:{firstname:firstname,lastname:lastname,phone_no:phone_no,gender:gender,seat_id:seat_id,age_group:age_group,starting_point:starting_point,end_point:end_point,reason:reason,foc:foc,trip_date:trip_date,route_id:route_id,verif:verif},
				success:function(response){

						$("#passenger_info").DataTable().ajax.reload();

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