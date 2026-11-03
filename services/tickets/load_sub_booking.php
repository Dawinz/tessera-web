require_once __DIR__ . '/includes/sr_env.php';
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
require_once __DIR__ . '/includes/tessera_tenant.php';

	 				  session_start();

	 $route_id = $_GET['route_id'];
	 include 'connect.php';
	 $select15=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'");
$select_num15=mysqli_num_rows($select15);
if($select_num15 !=0){
   	WHILE($get135=mysqli_fetch_array($select15)){
 
 $end_route= $get135['end_route']; 



}}

//index.php
$connect = sr_mysqli('ticket');
$start_route2 = '';
$query = "SELECT start_route FROM sub_route WHERE main_route_no ='$route_id'  GROUP BY start_route ORDER BY id ASC";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["start_route"].'">'.$row["start_route"].'</option>';
}
?>  
<span id="last_route"style="display:none"><?php  echo $end_route;?></span>

		 <?php
$route_day_id = $_GET['id'];	
	 $trip_date = $_GET['trip_date'];
		 $bus = $_GET['bus'];
		 
		 $id_session = $_GET['id'];
		
	
		 
		 ?>
				  <?php
$username=$_SESSION['username'];
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date' and status='booked' and issuer='$username') ORDER by id DESC ");
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
$route_id= $get13['route_id'];
?>
<span id="seat_id<?php echo $id;?>" style="display:none;"><?php echo $id;?></span>
<span id="route_id<?php echo $id;?>" style="display:none;"><?php echo $route_id;?></span>
    <div class="card-header justify-content-between d-flex align-items-center">
<h5 class="card-title">Seat no : <?php echo $seat_no;?> <button type="button" data-bs-toggle="modal"
                                        data-bs-target=".bs-example-modal-center" id="transfer2<?php echo $id;?>"class="btn btn-danger"><i class="uil  uil-arrow-random "></i> </button></h5>


                                    </div>
   <div class="row">                                                            
  <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">Starting point</label>
                                                            <select class="form-select" id="starting_point_half<?php echo $id;?>"
                                                aria-label="Floating label select example">
												<option value="">Choose Starting Point</option>
                                          <?php
$select14=mysqli_query($db,"SELECT start_route FROM sub_route WHERE main_route_no ='$route_id'  GROUP BY start_route ORDER BY id ASC ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
WHILE($get14=mysqli_fetch_array($select14)){
	$start_route =$get14['start_route'];
	
		$select13=mysqli_query($db,"SELECT *  FROM  temp WHERE trip_date='$trip_date' AND route_id='$id_session' and route_day_id='$route_id' and  sub_route_name='$start_route'");
$select_num13=mysqli_num_rows($select13);
if($select_num13 ==0){
echo '<option value="'.$get14['start_route'].'">'.$get14['start_route'].'</option>';
}else{
	
}
	
	
}}
									 
									 ?> </select>
                                                        </div>
                                                    </div><!-- end col -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Ending point</label>
                                                          <select class="form-select" id="end_point_half<?php echo $id;?>"
                                                aria-label="Floating label select example">
                                                 
                                            </select>
                                                        </div>
                                                    </div><!-- end col -->
</div>  <div class="row">                                                            
  <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">Firstname</label>
															
                                                            <input type="text" class="form-control firstname_half<?php echo $id;?>"id="firstname_half<?php echo $id;?>">
                                                        </div>
                                                    </div><!-- end col -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Last name</label>
                                                            <input type="text" class="form-control lastname_half<?php echo $id;?>" id="lastname_half<?php echo $id;?>">
                                                        
														
														</div>
                                                    </div><!-- end col -->
</div>  
 <div class="row">                                                            
  <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">Phone no</label>
                                                            <input type="text" class="form-control phone_no_half<?php echo $id;?>" id="phone_no_half<?php echo $id;?>"value="+255">
                                                        </div>
                                                    </div><!-- end col -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Gender</label>
                                                                    <select class="form-select" id="gender_half<?php echo $id;?>"
                                                aria-label="Floating label select example">
                                                <option id="gender_first_half<?php echo $id;?>">Gender</option>
												<option value="1">Male</option>
                                                <option value="2">Female</option>
                                            </select>
                                                        </div>
                                                    </div><!-- end col -->
</div> 

<div class="mb-3">    
                                                    <label class="form-label" for="formrow-firstname-input">Passport or Id</label>
                        <input type="text" class="form-control verification_half<?php echo $id;?>" id="verification_half<?php echo $id;?>" >

                                                </div>
 <div style="display:none;" class="card response_card"style="position:absolute;"id="response_half<?php echo $id;?>"></div>
  <div class="mb-3">
                                                    <label class="form-label" for="formrow-firstname-input">Age group</label>
                                                   <select class="form-select" id="age_group_half<?php echo $id;?>"
                                                aria-label="Floating label select example">
                                               <option id="age_group_first_half<?php echo $id;?>>Age group</option>
                                                <option value="1">Adult</option>
												
                                                <option value="2">child</option>
                                               
                                            </select>
                                                </div> 
												          <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">FOC</label>
                                                                                         <input type="checkbox" class="form-check-input" id="foc_half<?php echo $id;?>" value="">
<span>
<label class="form-label" for="formrow-password-input">TRANSIST </label>
<input type="checkbox" class="form-check-input " id="trans_half<?php echo $id;?>">
</span>
                                                        </div>
														 <div class="mb-3" style="display:none;" id="focarea<?php echo $id;?>">
                                                    <label class="form-label" for="formrow-firstname-input">FOC Reasons</label>
                                                                                                   <input type="text" class="form-control firstname<?php echo $id;?>"id="reason_half<?php echo $id;?>">

                                                </div>
                                    <button type="button" class="btn btn-success"id="save_half<?php echo $id;?>"><i class="uil uil-check me-2"></i> <span id="booktext_half<?php echo $id;?>">Book</span></button>
 
 
  <?php
		 $id3 = $_GET['id'];
		 $trip_date3 = $_GET['trip_date'];
		 $bus3 = $_GET['bus'];
		 
		
		
	
		 
		 ?>
 <script>
$(document).ready(function(){
	var seat_table = "load_seat_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?> ";
var booking = "load_status.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>";

$("#save_half<?php echo $id;?>").click(function(){
	var starting_point_half = $( "#starting_point_half<?php echo $id;?> option:selected" ).text();
	var end_point_half = $("#end_point_half<?php echo $id;?> option:selected" ).text();
	var firstname_half = $(".firstname_half<?php echo $id;?>").val();
	var lastname_half = $(".lastname_half<?php echo $id;?>").val();
	var phone_no_half = $(".phone_no_half<?php echo $id;?>").val();
	var verification_half = $(".verification_half<?php echo $id;?>").val();
	var gender_half = $( "#gender_half<?php echo $id;?> option:selected" ).text();
	var age_group_half = $( "#age_group_half<?php echo $id;?> option:selected" ).text();
	var seat_id = $( "#seat_id<?php echo $id;?>" ).text();
    var focus_half = $("#foc_half<?php echo $id;?>").val();
	var trans_half = $("#trans_half<?php echo $id;?>").val();
    var reason_half = $("#reason_half<?php echo $id;?>").val();
	var last_route = $("#last_route").text();


		if(starting_point_half=="Choose Starting Point"){
				 swal("", "Choose starting point!", "warning");
			 control.disabled="disabled";
}if((end_point_half=="") || (end_point_half=="Choose Ending Point") ){
				 swal("", "Please Choose Ending point!", "warning");

			 control.disabled="disabled";
}
if(firstname_half==""){
				 swal("", "Please Enter Firstname!", "warning");

			 control.disabled="disabled";
}if((phone_no_half=="") || (phone_no_half=="+255")){
				 swal("", "Please Enter phone number!", "warning");

			 control.disabled="disabled";
}if(gender_half=="Gender"){
				 swal("", "Please Enter gender!", "warning");

			 control.disabled="disabled";
}if(age_group_half=="Age group"){
				 swal("", "Please Enter Age group!", "warning");

			 control.disabled="disabled";
}if((focus_half=="checked") && (reason_half=="")){
				 swal(focus_half, "Please Enter reason for FOC!", "warning");

			 control.disabled="disabled";
}
$("#booktext_half<?php echo $id;?>").text("booking...");

 $.ajax({
				url:"insert_passenger_half.php",
				method:"POST",
				data:{firstname_half:firstname_half,lastname_half:lastname_half,phone_no_half:phone_no_half,gender_half:gender_half,seat_id:seat_id,age_group_half:age_group_half,starting_point_half:starting_point_half,end_point_half:end_point_half,reason_half:reason_half,verification_half:verification_half,focus_half:focus_half,trans_half:trans_half},
				success:function(response){
				$("#booktext_half<?php echo $id;?>").text("Book");
						$("#load_booking").load("load_booking.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&route_id=<?php echo $route_id;;?> ").fadeIn('1000');
						$("#load_booking_sub").load("load_sub_booking.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&route_id=<?php echo $route_id;;?> ").fadeIn('1000');
	                    $("#load_seat_table").load("load_seat_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?> ");
	                    $("#load_table").load("booking_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>");
		 	$("#load_status").load("load_status.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>");

		 	
				if($.trim(response) == "half"){
					
swal("", "warning!", "warning");
						} else if($.trim(response) == "updated"){
					
				
$('.exampleModalFullscreen_half<?php echo $id;?>').modal('show');
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print_half<?php echo $id;?>");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_ticket.php?id=<?php echo $id;?>";
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	

						}else if ($.trim(response)=="exist_half"){
												
							  swal({
	type: "warning",
    title: "Exist",
    text: "Same went wrong check if check dublicate end.",
    timer: 10000,
    showConfirmButton: false
  });	


	}else if ($.trim(response)==""){
$('.exampleModalFullscreen_half<?php echo $id;?>').modal('show');
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print_half<?php echo $id;?>");

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

 $('#starting_point_half<?php echo $id;?>').change(function(){
 if($(this).val() != '')
  {
var route_id = $( "#route_id<?php echo $id;?>" ).text();
   var action = $(this).attr("id");
   var query = $(this).val();
   var result = '';
   
   if(action == "starting_point_half<?php echo $id;?>")
   {
	  
    result = 'end_point_half<?php echo $id;?>';
   }
  
   $.ajax({
    url:"fetch_dependence_half.php",
    method:"POST",
    data:{action:action, query:query, route_id:route_id },
    success:function(data){
     $('#'+result).html(data);
    }
   })
  }
 });


             $("#phone_no_half<?php echo $id;?>").keyup(function () {
                    var query = $("#phone_no_half<?php echo $id;?>").val();
					var seat_id = $( "#seat_id<?php echo $id;?>" ).text();
$('#response_half<?php echo $id;?>').show();	
                    if (query.length > 0) {
                        $.ajax(
                            {
                                url: 'searching_phone_half.php',
                                method: 'POST',
                                data: {
									
                                    search: 1,seat_id:seat_id,
                                    q: query
                                },
                                success: function (data) {
                                    $("#response_half<?php echo $id;?>").html(data);
                                },
                                dataType: 'text'
                            }
                        );
                    }
                });
$( "body" ).click(function() {
	$('#response_half<?php echo $id;?>').hide();	

										});



$("#foc_half<?php echo $id;?>").click(function(){
if ($('#foc_half<?php echo $id;?>').prop('checked')) {
    //blah blah
swal("FOC SEAT", "You have activate FOC Seat the sale amount will be 0.00 please put reasons for fos seat!", "warning");
$("#focarea<?php echo $id;?>").show();
$("#foc_half<?php echo $id;?>").val("checked");
}else{
		$("#focarea<?php echo $id;?>").hide();
		$("#foc_half<?php echo $id;?>").val("");
}
});

$("#trans_half<?php echo $id;?>").click(function(){
if ($('#trans_half<?php echo $id;?>').prop('checked')) {
    //blah blah
swal("Transit", "You have activate TRANSIST Seat the sale amount will be 0.00 ", "warning");

$("#trans_half<?php echo $id;?>").val("checked");
}else{
	
		$("#trans_half<?php echo $id;?>").val("");
}
});



$("#transfer2<?php echo $id;?>").click(function(){
$("#prev_id").text("<?php echo $id;?>");
});
	});


</script>
       <div class="modal fade exampleModalFullscreen_half<?php echo $id;?>" tabindex="-1"
                                            aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalFullscreenLabel">Print reciepts</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
<div id="container_print_half<?php echo $id;?>"style="background-color:white;"></div>                                                    </div>
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
