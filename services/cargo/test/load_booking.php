       

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
		   	<script src="js/sweetalert.js"></script>
     <?php
require_once __DIR__ . '/../includes/tessera_tenant.php';

	 
	 session_start();
$username=$_SESSION['username'];
	 
	 $route_id = $_GET['route_id'];

//index.php
$connect = mysqli_connect("localhost", "root", "Teccross@360", "ticket");
$start_route2 = '';
$query = "SELECT detail FROM route_points WHERE point ='starting' and route_id='$route_id' ORDER BY route_id ASC";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["detail"].'">'.$row["detail"].'</option>';
}
?>     <?php
//index.php

$end_route = '';
$query = "SELECT detail FROM route_points WHERE point ='end' and route_id='$route_id'   ORDER BY route_id ASC";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $end_route .= '<option value="'.$row["detail"].'">'.$row["detail"].'</option>';
}
?>
		 <?php
		 $id = $_GET['id'];
		 $trip_date = $_GET['trip_date'];
		 $bus = $_GET['bus'];
		 
		
		
	
		 
		 ?>
				  <?php
				  
$username=$_SESSION['username'];
include 'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' AND bus='$bus' and status='booked' and issuer='".$_SESSION['username']."') ORDER by id DESC ");
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
												 <option >Choose pickup point</option>
                                                    <?php echo $start_route2; ?>

                                            </select>
                                                        </div>
                                                    </div><!-- end col -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Droping Point</label>
                                                          <select class="form-select" id="end_point<?php echo $id;?>"
                                                aria-label="Floating label select example">
                                                  <?php echo $end_route; ?>
                                            </select>
                                                        </div>
                                                    </div><!-- end col -->
</div>  <div class="row">                                                            
  <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">Firstname</label>
															
                                                            <input type="text" class="form-control firstname<?php echo $id;?>"id="firstname<?php echo $id;?>">
                                                        </div>
                                                    </div><!-- end col -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Last name</label>
                                                            <input type="text" class="form-control lastname<?php echo $id;?>" id="lastname<?php echo $id;?>">
                                                        
														
														</div>
                                                    </div><!-- end col -->
</div>  
 <div class="row">                                                            
  <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">Phone number</label>
                                                            <input type="number" class="form-control phone_no<?php echo $id;?>" id="phone_no<?php echo $id;?>"value="+255">
                                                        
														
														</div>
                                                    </div><!-- end col -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Gender</label>
                                                                    <select class="form-select" id="gender<?php echo $id;?>"
                                                aria-label="Floating label select example">
                                                <option id="gender_first<?php echo $id;?>">Gender</option>
												<option value="1">Male</option>
                                                <option value="2">Female</option>
                                            </select>
                                                        </div>
                                                    </div><!-- end col -->
													<div style="display:none;" class="card response_card"style="position:absolute;"id="response<?php echo $id;?>"></div>
					
												
</div> 
										<div class="mb-3">    
                                                    <label class="form-label" for="formrow-firstname-input">Passport or Id</label>
                        <input type="text" class="form-control verification<?php echo $id;?>" id="verification<?php echo $id;?>" >

                                                </div>
  <div class="mb-3">    
                                                    <label class="form-label" for="formrow-firstname-input">Age group</label>
                                                   <select class="form-select" id="age_group<?php echo $id;?>"
                                                aria-label="Floating label select example">
                                               <option id="age_group_first<?php echo $id;?>">Age group</option>
                                                <option value="1">Adult</option>
												
                                                <option value="2">child</option>
                                               
                                            </select>
                                                </div> 
										
												                    <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">FOC </label>
                                                                                         <input type="checkbox" class="form-check-input "  id="foc<?php echo $id;?>">
<span>
<label class="form-label" for="formrow-password-input">TRANSIST </label>
<input type="checkbox" class="form-check-input " id="trans<?php echo $id;?>">
</span>
                                                        </div>
															 <div class="mb-3" style="display:none;" id="focarea_one<?php echo $id;?>">
                                                    <label class="form-label" for="formrow-firstname-input">FOC Reasons</label>
                                                                                                   <input type="text" value="" class="form-control reason<?php echo $id;?>"id="reason<?php echo $id;?>">

                                                </div>
                                    <button type="button" class="btn btn-success"id="save<?php echo $id;?>"><i class="uil uil-check me-2"></i> <span id="booktext<?php echo $id;?>">Book</span></button>
 
 
  <?php
		 $id3 = $_GET['id'];
		 $trip_date3 = $_GET['trip_date'];
		 $bus3 = $_GET['bus'];
		 
		
		
	
		 
		 ?>
 <script>
$(document).ready(function(){
	var seat_table = "load_seat_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?> ";
var booking = "load_status.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>";

$("#save<?php echo $id;?>").click(function(){
	var starting_point = $( "#starting_point<?php echo $id;?> option:selected" ).text();
	var end_point = $("#end_point<?php echo $id;?> option:selected" ).text();
	var firstname = $(".firstname<?php echo $id;?>").val();
	var lastname = $(".lastname<?php echo $id;?>").val();
	var phone_no = $(".phone_no<?php echo $id;?>").val();
	var verification = $(".verification<?php echo $id;?>").val();
	var gender = $( "#gender<?php echo $id;?> option:selected" ).text();
	var age_group = $( "#age_group<?php echo $id;?> option:selected" ).text();
	var seat_id = $( "#seat_id<?php echo $id;?>" ).text();
 var foc = $("#foc<?php echo $id;?>").val(); 
 var tran = $("#trans<?php echo $id;?>").val();


    var reason = $("#reason<?php echo $id;?>").val();
	if(starting_point=="Choose pickup point"){
		 swal("", "Choose Pick up point!", "warning");
		 control.disabled="disabled";
	}
if(firstname==""){
				 swal("", "Please Enter Firstname!", "warning");

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
$("#booktext<?php echo $id;?>").text("booking...");

 $.ajax({
				url:"insert_passenger.php",
				method:"POST",
				data:{firstname:firstname,lastname:lastname,phone_no:phone_no,gender:gender,seat_id:seat_id,age_group:age_group,starting_point:starting_point,end_point:end_point,reason:reason,verification:verification,foc:foc,tran:tran},
				success:function(response){
					alert(response);
					$("#booktext<?php echo $id;?>").text("Book");
				$("#load_booking").load("load_booking.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&route_id=<?php echo $route_id;;?> ").fadeIn('1000');
						$("#load_booking_sub").load("load_sub_booking.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&route_id=<?php echo $route_id;;?> ").fadeIn('1000');
	                    $("#load_seat_table").load("load_seat_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?> ");
	                    $("#load_table").load("booking_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>");
		 	$("#load_status").load("load_status.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>");

					if($.trim(response) == "half"){
					
swal("SEAT OCCUPIED", "Please select other seat the one you choose is occupied!", "warning");
						}else if($.trim(response) == "updated"){
					
				
							  swal({
    title: "Update",
    text: "update Succefull.",
    timer: 600,
    showConfirmButton: false
  });
						}else{
												

$('.exampleModalFullscreen<?php echo $id;?>').modal('show');
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print<?php echo $id;?>");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_ticket.php?id=<?php echo $id;?>";
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
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
$("#trans<?php echo $id;?>").click(function(){
if ($('#trans<?php echo $id;?>').prop('checked')) {

$("#trans<?php echo $id;?>").val("checked");
}else{
	
		$("#trans<?php echo $id;?>").val("");
}
});

       $(document).ready(function () {
                $("#phone_no<?php echo $id;?>").keyup(function () {
                    var query = $("#phone_no<?php echo $id;?>").val();
					var seat_id = $( "#seat_id<?php echo $id;?>" ).text();
$('#response<?php echo $id;?>').show();	
                    if (query.length > 0) {
                        $.ajax(
                            {
                                url: 'searching_phone.php',
                                method: 'POST',
                                data: {
									
                                    search: 1,seat_id:seat_id,
                                    q: query
                                },
                                success: function (data) {
                                    $("#response<?php echo $id;?>").html(data);
                                },
                                dataType: 'text'
                            }
                        );
                    }
                });
$( "body" ).click(function() {
	$('#response<?php echo $id;?>').hide();	

										});
            });


	});


</script>
       <div class="modal fade exampleModalFullscreen<?php echo $id;?>" tabindex="-1"
                                            aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalFullscreenLabel">Print reciepts</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
<div id="container_print<?php echo $id;?>"style="background-color:white;"></div>                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                   
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
<?php
}}

?>
<br><br>
<?php
if($select_num1 >1){
?>
<button type="button" class="btn btn-danger"id="save_all"><i class="uil uil-check me-2"></i> <span id="booktext_all">Book All</span></button>
<script>

$("#save_all").click(function(){
	
				  <?php
include 'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' AND bus='$bus' and status='booked' and issuer='".$_SESSION['username']."')  ORDER by id DESC ");
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

 ?>

	var starting_point = $( "#starting_point<?php echo $id;?> option:selected" ).text();
	var end_point = $("#end_point<?php echo $id;?> option:selected" ).text();
	var firstname = $(".firstname<?php echo $id;?>").val();
	var lastname = $(".lastname<?php echo $id;?>").val();
	var phone_no = $(".phone_no<?php echo $id;?>").val();
	var gender = $( "#gender<?php echo $id;?> option:selected" ).text();
	var age_group = $( "#age_group<?php echo $id;?> option:selected" ).text();
	var seat_id = $( "#seat_id<?php echo $id;?>" ).text();
 var foc = $("#foc<?php echo $id;?>").val();
    var reason = $("#reason<?php echo $id;?>").val();
	
		if(starting_point=="Choose pickup point"){
			$("#starting_point<?php echo $id;?>").css('border','1px solid #ff564a');
		 swal("", "Choose Pick up point!", "warning");
		 control.disabled="disabled";
	}
	
if(firstname==""){
				 swal("", "Please Enter Firstname!", "warning");
				  $(".firstname<?php echo $id;?>").css('border','1px solid #ff564a');

			 control.disabled="disabled";
}if(lastname==""){
				 swal("", "Please Enter Surname!", "warning");
 $(".lastname<?php echo $id;?>").css('border','1px solid #ff564a');
			 control.disabled="disabled";
			 
}if((phone_no=="") || (phone_no=="+255")){
				 swal("", "Please Enter phone number!", "warning");
 $(".phone_no<?php echo $id;?>").css('border','1px solid #ff564a');

			 control.disabled="disabled";
}if(gender=="Gender"){
				 swal("", "Please Enter gender!", "warning");
 $("#gender<?php echo $id;?>").css('border','1px solid #ff564a');

			 control.disabled="disabled";
}if(age_group=="Age group"){
				 swal("", "Please Enter Age group!", "warning");
 $("#age_group<?php echo $id;?>").css('border','1px solid #ff564a');
			 control.disabled="disabled";
}
if((foc=="checked") && (reason=="")){
				 swal("", "Please Enter reason for FOC!", "warning");

			 control.disabled="disabled";
}
$("#booktext_all").text("booking...");




 
 <?php

}}?>
	
				  <?php
include 'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' AND bus='$bus' and status='booked' and issuer='".$_SESSION['username']."')  ORDER by id DESC ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){

 $id= $get13['id']; 
 
 
 ?>
	var starting_point = $( "#starting_point<?php echo $id;?> option:selected" ).text();
	var end_point = $("#end_point<?php echo $id;?> option:selected" ).text();
	var firstname = $(".firstname<?php echo $id;?>").val();
	var lastname = $(".lastname<?php echo $id;?>").val();
	var phone_no = $(".phone_no<?php echo $id;?>").val();
	var verification = $(".verification<?php echo $id;?>").val();
	var gender = $( "#gender<?php echo $id;?> option:selected" ).text();
	var age_group = $( "#age_group<?php echo $id;?> option:selected" ).text();
	var seat_id = $( "#seat_id<?php echo $id;?>" ).text();
 var foc = $("#foc<?php echo $id;?>").val();
    var reason = $("#reason<?php echo $id;?>").val();
 $.ajax({
				url:"insert_passenger_bulk.php",
				method:"POST",
				data:{firstname:firstname,lastname:lastname,phone_no:phone_no,gender:gender,seat_id:seat_id,age_group:age_group,starting_point:starting_point,end_point:end_point,reason:reason,verification:verification},
				success:function(response){
					$("#booktext<?php echo $id;?>").text("Book");
				$("#load_booking").load("load_booking.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&route_id=<?php echo $route_id;;?> ").fadeIn('1000');
						$("#load_booking_sub").load("load_sub_booking.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&route_id=<?php echo $route_id;;?> ").fadeIn('1000');
	                    $("#load_seat_table").load("load_seat_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?> ");
	                    $("#load_table").load("booking_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>");
		 	$("#load_status").load("load_status.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>");

					if($.trim(response) == "half"){
					
							  swal({
   
    text: "Seat <?php echo $seat_no;?> occupied please choose other seat.",
    timer: 600,
    showConfirmButton: false
  });
		
						}

	}
			
			});

 
 <?php

}}?>
$('.exampleModalFullscreen').modal('show');	

	
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_bulk_tickets.php";

iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});

});
</script>

<?php
}
?>