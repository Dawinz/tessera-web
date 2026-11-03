
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

	 date_default_timezone_set('Africa/Dar_es_Salaam');

$time_now = date(' h:i A', time());
	 $connect = mysqli_connect("localhost", "root", "Teccross@360", "ticket");
	 session_start();
$username=$_SESSION['username'];
	 
	 $route_id = $_GET['route_id'];

//index.php

?>     <?php
//index.php


?>
		 <?php
		 $route_day_id = $_GET['id'];
		 $trip_date = $_GET['trip_date'];
		 $bus = $_GET['bus'];
		 $start_location = $_GET['start_location'];
		 $ending_location = $_GET['ending_location'];
		 $start_location = preg_replace('/_/',' ', $start_location);
		 $ending_location = preg_replace('/_/',' ', $ending_location);
		include 'connect.php';
		
	$select17=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date' and status='booked' and issuer='".$_SESSION['username']."') ORDER by id DESC ");
$select_num17=mysqli_num_rows($select17);

		 
		 
		 
$start_route2 = '';
$query = "
SELECT detail
FROM  sub_route_points Where route_id='$route_id' and point ='starting' and start_location='$start_location'
UNION DISTINCT
SELECT detail
FROM route_points where route_id='$route_id' and point ='starting' and start_location='$start_location'
";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["detail"].'">'.$row["detail"].'</option>';
}

$end_route = '';
$query = "
SELECT detail
FROM  sub_route_points Where route_id='$route_id' and point ='end' and end_location='$ending_location'
UNION DISTINCT
SELECT detail
FROM route_points where route_id='$route_id' and point ='end' and end_location='$ending_location'
";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $end_route .= '<option value="'.$row["detail"].'">'.$row["detail"].'</option>';
}

$end_route2 = '';
$query = "SELECT * FROM sub_route WHERE main_route_no='$route_id' and end_route !='$start_location'   group by end_route ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $end_route2 .= '<option value="'.$row["end_route"].'">'.$row["end_route"].'</option>';
}

$start_route5 = '';
$query = "SELECT * FROM  sub_route WHERE main_route_no='$route_id' and start_route !='$ending_location' group by start_route   ";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route5 .= '<option value="'.$row["start_route"].'">'.$row["start_route"].'</option>';
}

	
		 ?>
		 
		 
		 	<form method="post" id="insert_form">
<div id="error"></div>
				<?php
				  
$username=$_SESSION['username'];

$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date' and status='booked' and issuer='".$_SESSION['username']."') ORDER by id DESC ");
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
$timer= $get13['timer'];
?>



<span id="seat_id<?php echo $id;?>" style="display:none;"><?php echo $id;?></span>
 <table>
<th colspan="2" style="flex-grow-1 text-truncate">
<h5 class=" flex-grow-1 text-truncate"> 
SEAT | <span style=""><?php echo $seat_no;?> </span>
</h4>
</th>  
<hr/>
<?php 
$to_time = strtotime($timer);
$from_time = strtotime($time_now);
$time_left = round(abs($to_time - $from_time) / 60,2). "";
?>
<p id="demo"></p>

<script>
// Set the date we're counting down to
var countDownDate = new Date("Jan 5, 2024 15:37:25").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  document.getElementById("demo").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);
</script>
<tr>
 <td style="padding:2px">
                  <select class="form-select starting_location action<?php echo $id;?>" id="starting_location<?php echo $id;?>"name="starting_location[]"
                                                aria-label="Floating label select example">
												 <option><?php echo $start_location?></option>
												<?php echo $start_route5?>
                                                   

                                            </select>
 </td style="padding:2px">
 <td>
                  <select class="form-select ending_location action2<?php echo $id;?>" id="ending_location<?php echo $id;?>"name="ending_location[]"
                                                aria-label="Floating label select example">
												<option ><?php echo $ending_location?></option>
<?php echo $end_route2;?>
                                            </select>
 </td>

</tr>

 <tr >

 <td style="padding:2px">
  <label class="form-label" for="formrow-email-input">Pick Up Point</label>
                                                            <select class="form-select starting_point" id="starting_point<?php echo $id;?>"name="starting_point[]"
                                                aria-label="Floating label select example">
												 <option >Choose pickup point</option>
                                                    <?php echo $start_route2; ?>

                                            </select>
 </td><td style="padding:2px">
                <label class="form-label" for="formrow-password-input">Droping Point</label>
                                                          <select class="form-select" id="end_point<?php echo $id;?>" name="end_point[]"
                                                aria-label="Floating label select example">
                                                  <?php echo $end_route; ?>
                                            </select>
 </td>
 </tr> <tr >
 <td style="padding:2px" >
   <label class="form-label" for="formrow-email-input">Firstname</label>
															
                                                            <input type="text" class="form-control firstname<?php echo $id;?>"id="firstname<?php echo $id;?>"name="firstname[]">
 </td><td style="padding:2px" >
        <label class="form-label" for="formrow-password-input">Last name</label>
                                                            <input type="text" class="form-control lastname<?php echo $id;?>" id="lastname<?php echo $id;?>"name="lastname[]">
                                                        
														
 </td>
 </tr>
 <tr style= >
 <td style="padding:2px; " >
  <label class="form-label" for="formrow-email-input">Phone number</label>
 <input type="text" class="form-control phone_no<?php echo $id;?>" id="phone_no<?php echo $id;?>"value="" name="phone_no[]">
   <div style="display:none;position: absolute;" class="card response_card"style=""id="response<?php echo $id;?>"></div>
                                                    
 </td><td style="padding:2px" >
                     <label class="form-label" for="formrow-password-input">Gender</label>
<select class="form-select" id="gender<?php echo $id;?>" name="gender[]"
                                                aria-label="Floating label select example">
                                                <option id="gender_first<?php echo $id;?>">Gender</option>
												<option value="1">Male</option>
                                                <option value="2">Female</option>
                                            </select>         
														
 </td>
 </tr> 
 <tr >
 <td style="padding:2px" >
                                 <label class="form-label" for="formrow-firstname-input">Passport or Id</label>
                        <input type="text" class="form-control verification<?php echo $id;?>" id="verification<?php echo $id;?>" name="verification[]" >
                                               
 </td><td style="padding:2px" >
                 <label class="form-label" for="formrow-firstname-input">Age group</label>
                                                   <select class="form-select" id="age_group<?php echo $id;?>" name="age_group[]"
                                                aria-label="Floating label select example">
                                               <option id="age_group_first<?php echo $id;?>">Age group</option>
                                                <option value="1">Adult</option>
												
                                                <option value="2">child</option>
                                               
                                            </select>      
														
 </td>
 </tr>
 <tr >
 <td style="padding:2px" >
 <label class="form-label" for="formrow-password-input">FOC </label>
<input type="checkbox" class="form-check-input "  id="foc<?php echo $id;?>" name="foc[]" >        
 </td><td style="padding:2px" >
<label class="form-label" for="formrow-password-input">TRANSIST </label>
<input type="checkbox" class="form-check-input " id="trans<?php echo $id;?>" name="trans[]"> 
														
 </td>
 </tr> 
 <tr >
 <td   colspan="2"style="padding:5px; display:none;" id="focarea_one<?php echo $id;?>" >
<label class="form-label" for="formrow-firstname-input">FOC Reasons</label>
<input type="text" value="" class="form-control reason<?php echo $id;?>"id="reason<?php echo $id;?>" name="reason[]">
 </td>
 </tr>
 <tr >
 <td    >
                                    <button type="button" class="btn btn-success"id="save<?php echo $id;?>"><i class="uil uil-check me-2"></i> <span id="booktext<?php echo $id;?>">Full Book</span></button>

 </td>
 <td    >
 <button type="button" data-bs-toggle="modal"
                                        data-bs-target=".bs-example-modal-center" id="transfer<?php echo $id;?>"class="btn btn-danger"><i class="uil  uil-arrow-random "></i> Transfer</button>
 </td>
 </tr>
 
</table>
<input type="hidden" id="seat_id<?php echo $id;?>" name="seat_id[]" value="<?php echo $id;?>">
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
	var starting_location = $("#starting_location<?php echo $id;?> option:selected" ).text();
	var ending_location = $("#ending_location<?php echo $id;?> option:selected" ).text();
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
	}	if(end_point=="Choose pickup point"){
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
				data:{firstname:firstname,lastname:lastname,phone_no:phone_no,gender:gender,seat_id:seat_id,age_group:age_group,starting_point:starting_point,end_point:end_point,reason:reason,verification:verification,foc:foc,tran:tran,starting_location:starting_location,ending_location:ending_location},
				success:function(response){
						$("#booktext<?php echo $id;?>").text("Book");
				$("#load_booking").load("load_booking.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&route_id=<?php echo $route_id;;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>").fadeIn('1000');
						$("#load_booking_sub").load("load_sub_booking.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ").fadeIn('1000');
	                    $("#load_seat_table").load("load_seat_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	                    $("#load_table").load("booking_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
		 	$("#load_status").load("load_status.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");

						 if($.trim(response) == 'log_out'){
			window.location='index.php';
		}else if($.trim(response) == "half"){
					
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

localStorage.setItem("foc<?php echo $id;?>", $('#foc<?php echo $id;?>').val());

}else{
		$("#focarea_one<?php echo $id;?>").hide();
		$("#foc<?php echo $id;?>").val("");
		localStorage.setItem("foc<?php echo $id;?>", $('#foc<?php echo $id;?>').val());

}
});
$("#trans<?php echo $id;?>").click(function(){
if ($('#trans<?php echo $id;?>').prop('checked')) {
swal("Transit", "You have activate TRANSIST Seat the sale amount will be 0.00 ", "warning");

$("#trans<?php echo $id;?>").val("checked");
localStorage.setItem("trans<?php echo $id;?>", $('#trans<?php echo $id;?>').val());

}else{
	
		$("#trans<?php echo $id;?>").val("");
		localStorage.setItem("trans<?php echo $id;?>", $('#trans<?php echo $id;?>').val());

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



$("#transfer<?php echo $id;?>").click(function(){
$("#prev_id").text("<?php echo $id;?>");
});

$("#firstname<?php echo $id;?>").keyup(function(){
    localStorage.setItem("firstname<?php echo $id;?>", $('#firstname<?php echo $id;?>').val());
});

$("#lastname<?php echo $id;?>").keyup(function(){
    localStorage.setItem("lastname<?php echo $id;?>", $('#lastname<?php echo $id;?>').val());
});
$("#phone_no<?php echo $id;?>").keyup(function(){
    localStorage.setItem("phone_no<?php echo $id;?>", $('#phone_no<?php echo $id;?>').val());
});
$("#reason<?php echo $id;?>").keyup(function(){
    localStorage.setItem("reason<?php echo $id;?>", $('#reason<?php echo $id;?>').val());
});

$(".verification<?php echo $id;?>").keyup(function(){
    localStorage.setItem("verification<?php echo $id;?>", $('.verification<?php echo $id;?>').val());
});

$('#starting_point<?php echo $id;?>').on('change', function (e) {
localStorage.setItem("starting_point<?php echo $id;?>", $( "#starting_point<?php echo $id;?> option:selected" ).text());

});


$('#end_point<?php echo $id;?>').on('change', function (e) {
localStorage.setItem("end_point<?php echo $id;?>", $( "#end_point<?php echo $id;?> option:selected" ).text());

});

$('#gender<?php echo $id;?>').on('change', function (e) {
localStorage.setItem("gender<?php echo $id;?>", $( "#gender<?php echo $id;?> option:selected" ).text());

});
$('#age_group<?php echo $id;?>').on('change', function (e) {
localStorage.setItem("age_group<?php echo $id;?>", $( "#age_group<?php echo $id;?> option:selected" ).text());

});
$('#starting_location<?php echo $id;?>').on('change', function (e) {
localStorage.setItem("starting_location<?php echo $id;?>", $( "#starting_location<?php echo $id;?> option:selected" ).text());

});
$('#ending_location<?php echo $id;?>').on('change', function (e) {
localStorage.setItem("ending_location<?php echo $id;?>", $( "#ending_location<?php echo $id;?> option:selected" ).text());

});




 var name = localStorage.getItem("firstname<?php echo $id;?>");
    if (name !== null) $('#firstname<?php echo $id;?>').val(name);
	
	var lastname = localStorage.getItem("lastname<?php echo $id;?>");
    if (lastname !== null) $('#lastname<?php echo $id;?>').val(lastname);
	
var phone_no = localStorage.getItem("phone_no<?php echo $id;?>");
    if (phone_no !== null) $('#phone_no<?php echo $id;?>').val(phone_no);
	
	
	var reason = localStorage.getItem("reason<?php echo $id;?>");
    if (reason !== null) $('#reason<?php echo $id;?>').val(reason);
	
	var verification = localStorage.getItem("verification<?php echo $id;?>");
    if (verification !== null) $('.verification<?php echo $id;?>').val(verification);
	
	var starting_point = localStorage.getItem("starting_point<?php echo $id;?>");
    if (starting_point !== null) $('#starting_point<?php echo $id;?> option:selected' ).text(starting_point);
	

	var end_point = localStorage.getItem("end_point<?php echo $id;?>");
    if (end_point !== null) $('#end_point<?php echo $id;?> option:selected' ).text(end_point);
	
	var gender = localStorage.getItem("gender<?php echo $id;?>");
    if (gender !== null) $('#gender<?php echo $id;?> option:selected' ).text(gender);
		
	var age_group = localStorage.getItem("age_group<?php echo $id;?>");
    if (age_group !== null) $('#age_group<?php echo $id;?> option:selected' ).text(age_group);
	
	var starting_location = localStorage.getItem("starting_location<?php echo $id;?>");
    if (starting_location !== null){
$('#starting_location<?php echo $id;?> option:selected' ).text(starting_location);	
	}else{
			$('#starting_location<?php echo $id;?> option:selected' ).text("<?php echo $start_location;?>");	

	}	
	var ending_location = localStorage.getItem("ending_location<?php echo $id;?>");
    if (ending_location !== null){
$('#ending_location<?php echo $id;?> option:selected' ).text(ending_location);	
	}else{
			$('#ending_location<?php echo $id;?> option:selected' ).text("<?php echo $ending_location;?>");	

	}
	
	var foc2 = localStorage.getItem("foc<?php echo $id;?>");
    if ((foc2 !== null) && (foc2 !== "")){ 
	$('#foc<?php echo $id;?>').val(foc2);
	$('#foc<?php echo $id;?>').prop('checked', true);
	}else{
			$('#foc<?php echo $id;?>').prop('checked', false);

	}	
	var trans = localStorage.getItem("trans<?php echo $id;?>");
    if ((trans !== null) && (trans !== "")){ 
	$('#trans<?php echo $id;?>').val(foc2);
	$('#trans<?php echo $id;?>').prop('checked', true);
	}else{
			$('#trans<?php echo $id;?>').prop('checked', false);

	}
	
 $('.action<?php echo $id;?>').change(function(){
  if($(this).val() != '')
  {
   var action = $(this).attr("id");
   var query = $(this).val();
   var ending_location = $("#ending_location<?php echo $id;?> option:selected" ).text();
   var result = '';
  
    result = 'starting_point<?php echo $id;?>';

  
   $.ajax({
    url:"fetch_dependence_single.php",
    method:"POST",
    data:{action:action, query:query ,ending_location:ending_location},
    success:function(data){
		
     $('#'+result).html(data);
    }
   })
  }
 }); 
 
 $('.action2<?php echo $id;?>').change(function(){
  if($(this).val() != '')
  {
   var action = $(this).attr("id");
   var query = $(this).val();
    var starting_location = $("#starting_location<?php echo $id;?> option:selected" ).text();
   var result = '';
  
    result = 'end_point<?php echo $id;?>';

  
   $.ajax({
    url:"fetch_dependence_single_end.php",
    method:"POST",
    data:{action:action, query:query ,starting_location:starting_location},
    success:function(data2){
		
     $('#'+result).html(data2);
    }
   })
  }
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
<br>
 <button style="display:none;"type="submit" name="submit" id="submit_button" class="btn btn-primary"><i class="uil uil-check me-2"></i> <span class="text_change">Book all</span> (<span class="counter_number"><?php echo $select_num17;?></span>)</button>

</form>
<script>

$(document).ready(function(){
	counter_number = $('.counter_number').text();
if(counter_number >= 2){
	$("#submit_button").show();
}
});

$('#insert_form').on('submit', function(event){

		event.preventDefault();

		var error = '';

		count = 1;

		$('.firstname').each(function(){

			if($(this).val() == '')
			{

				error += "<li>Enter Item firstname at "+count+" Row</li>";
			$(this).css('border','1px solid #F34E4E');

			}else{
		$(this).css('border','1px solid #043A5A');	
			}
			count = count + 1;

		});	
		$('.lastname').each(function(){

			if($(this).val() == '')
			{

				error += "<li>Enter Item Lastname at "+count+" Row</li>";
			$(this).css('border','1px solid #F34E4E');

			}else{
		$(this).css('border','1px solid #043A5A');	
			}
			count = count + 1;

		});	
		$('.phone_no').each(function(){

			if($(this).val() == '')
			{

				error += "<li>Enter Item Phone number at "+count+" Row</li>";
			$(this).css('border','1px solid #F34E4E');

			}else{
		$(this).css('border','1px solid #043A5A');	
			}
			count = count + 1;

		});


	$("select[name='starting_point[]']").each(function(){

			if($(this).val() == 'Choose pickup point')
			{

				error += "<li>Select Pick up point "+count+" Row</li>";
$(this).css('border','1px solid #F34E4E');
			}else{
		$(this).css('border','1px solid #043A5A');	
			}

			count = count + 1;

		});
$("select[name='end_point[]']").each(function(){

			if($(this).val() == 'Choose pickup point')
			{

				error += "<li>Select End point "+count+" Row</li>";
$(this).css('border','1px solid #F34E4E');

			}else{
		$(this).css('border','1px solid #043A5A');	
			}

			count = count + 1;

		});
		
		$("select[name='gender[]']").each(function(){

			if($(this).val() == 'Gender')
			{

				error += "<li>Select Gender "+count+" Row</li>";
$(this).css('border','1px solid #F34E4E');

			}else{
		$(this).css('border','1px solid #043A5A');	
			}

			count = count + 1;

		});	
		$("select[name='age_group[]']").each(function(){

			if($(this).val() == 'Age group')
			{

				error += "<li>Select Gender "+count+" Row</li>";
$(this).css('border','1px solid #F34E4E');

			}else{
		$(this).css('border','1px solid #043A5A');	
			}

			count = count + 1;

		});



		var form_data = $(this).serialize();

		if(error == '')
		{

			$.ajax({

				url:"insert_multiple_passengers.php",

				method:"POST",

				data:form_data,

				beforeSend:function()
	    		{

	    			$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').text("booking...");

	    		},

				success:function(data)
				{
					 if($.trim(data) == 'log_out'){
			window.location='index.php';
		}
$('.exampleModalFullscreen').modal('show');
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_multiple_ticket.php?print_id="+data;
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	

				$("#load_booking").load("load_booking.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&route_id=<?php echo $route_id;;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>").fadeIn('1000');
						$("#load_booking_sub").load("load_sub_booking.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ").fadeIn('1000');
	                    $("#load_seat_table").load("load_seat_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	                    $("#load_table").load("booking_table.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
		 	$("#load_status").load("load_status.php?id=<?php echo $id3;?>&trip_date=<?php echo $trip_date3;?>&bus=<?php echo $bus3;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");

					

				}
			})

		}else{
						$('#error').html('<div class="alert alert-danger alert-outline alert-dismissible fade show" role="alert"><button type="button" class="btn-close" style=""data-bs-dismiss="alert" aria-label="Close"></button><ul>'+error+'</ul></div>');

		}

	

	});
</script>