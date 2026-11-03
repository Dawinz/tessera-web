	
	
	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script src="assets/js/alertify.min.js"></script>

			<div id="word_date"style="display:none;"><?php
require_once __DIR__ . '/includes/tessera_tenant.php';
    echo date('F-d-Y', strtotime($trip_date));?></div>

		 <?php
		 include 'connect.php';
$route_day_id = $_GET['id'];	
		 $trip_date = $_GET['trip_date'];
		 $bus = $_GET['bus'];
		 $start_location = $_GET['start_location'];
		 $ending_location = $_GET['ending_location'];

	session_start();
$username=$_SESSION['username'];
		
		$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    
			$role =$get12['role']; 

		
	}
}
		
		
		
		 ?>
	          <style>
		  .dt-button.red {
   color: #fff;
  background:none;
   background-color: #0379BB;
   border-radius: 05px;
   border:none;
    }
  .dataTables_filter input {
        width: 250px;
        height: 32px;
        background: #fcfcfc;
        border: 1px solid #aaa;
        border-radius: 5px;
    
        text-indent: 10px;
    }
 
    .dataTables_filter .fa-search {
        position: absolute;
        top: 10px;
        left: auto;
        right: 10px;
    }
.dataTables_filter {
    display: none;
}
#myInputTextField
{
	width:85%;
	 position: absolute;
right:40px;
  z-index: 4;
	margin-bottom:70px;
}
	  </style>
	  	  <div class=""style="padding:8px;">
         <input type="text" id="myInputTextField" class="form-control" placeholder="Search...">
      </div>
	  <br><br>
	  
<table id="example" class="table mb-0" style="width:100%">
        <thead>
            <tr>
                
				<th>Seat</th>
                <th>T.no</th>
                <th>Cost</th>
                <th>Lock</th>
				<th>Print</th>
                <th>Edit</th>
				<th>Delete</th>
            </tr>
        </thead>
        <tbody>
		                       <?php
						
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date' and delete_book='') ORDER by id DESC ");
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
 $origin_status= $get13['origin_status']; 
 $route_id= $get13['route_id'];
 $lock_option= $get13['lock_option'];
 $locker= $get13['locker'];
 $locker_fullname= $get13['locker_fullname'];

				
		 
?>
            <tr>

			
                <td>
				<?php 

if($status=="booked"){
	echo "<span id='seat_no'style='background:#B48C1B;padding:3px;'>$seat_no</span>";
}else if($status=="complete"){
	echo "<span id='seat_no'style='background:#DA1616;padding:3px;'>$seat_no</span>";
}else if($status=="half_complete"){
	echo "<span id='seat_no'style='background:green;padding:3px;'>$seat_no</span>";
}else if($status=="locked"){
	echo "<span id='seat_no'style='background:#4F4F4F;padding:3px;'>$seat_no</span>";
}
?>
				</td>
                <td><?php echo $id;?></td>
<td><?php echo ''.number_format($cost,2).'';?></td>       

			<?php
			if($status=="booked"){
				
				?>

						  	<td>
			<button id=""type="button" style="height:40px;"class="btn btn-soft-warning" data-bs-toggle="modal"data-bs-target="#lock_modal<?php echo $id;?>" ><i class="uil uil-lock"></i></button>                                 

       <div id="lock_modal<?php echo $id;?>" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Locking reasons</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a comment here" id="lock_reason<?php echo $id;?>"
                                                style="height: 100px"></textarea>
                                            <label for="lock_reason<?php echo $id;?>">State Your Reasons here..</label>
                                        </div>
                                                 

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" id="lock<?php echo $id;?>"class="btn btn-primary "><span id="delete_text<?php echo $id;?>">Save changes</span></button>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>

<script>
$("#lock<?php echo $id;?>").click(function(){
id = "<?php echo $id;?>"
reason =  $("#lock_reason<?php echo $id;?>").val();

		    $.ajax({
				url:'lock_seat.php',
				method:'POST',
				data:{id:id,reason:reason},
				success:function(response){
					
$("#lock_modal<?php echo $id;?>").modal("hide");
							$("#load_seat_table").load("load_seat_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	$("#load_table").load("booking_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	
$("#load_booking").load("load_booking.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	$("#load_booking_sub").load("load_sub_booking.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
  	
	}
			
			});	

});
</script>
</td>
					<?php
					
				
				
		        }else{
					
if($lock_option=="locked"){
	
		?>

		
		<?php
	
		 if(($locker==$_SESSION['username']) OR ($role=="SuperAdmin")){
			 
			 ?>
			 <td>
								
								
			<button id="lock<?php echo $id;?>"type="button" style="height:40px;"class="btn btn-soft-success"  ><i class="uil uil-unlock"></i></button>                                 

<script>
$("#lock<?php echo $id;?>").click(function(){
id = "<?php echo $id;?>"

		    $.ajax({
				url:'unlock_seat.php',
				method:'POST',
				data:{id:id},
				success:function(response){
					

							$("#load_seat_table").load("load_seat_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	$("#load_table").load("booking_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	
$("#load_booking").load("load_booking.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	$("#load_booking_sub").load("load_sub_booking.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
  	
	}
			
			});	

});
</script>
</td>
			 <?php
			 
		 }else{
			 ?>
			  	<td>
			<button id="lock<?php echo $id;?>"type="button" style="height:40px;"class="btn btn-soft-success"  ><i class="uil uil-unlock"></i></button>                                 

<script>
$("#lock<?php echo $id;?>").click(function(){
swal({
  title: "Locked!!",
  text: "Only (<?php echo $locker_fullname;?>) and Super Admin can unlock",
  icon: "warning",
  
})

});
</script>
</td> 
			 <?php
			 
		 }
		
		
		
	
	
				  }else{
					 ?>
					 <td><button id="lock<?php echo $id;?>"type="button" style="height:40px;"class="btn btn-soft-success" disabled ><i class="uil uil-lock"></i></button></td>                             

					 <?php
				  }
				  }
			
			?>
			




	
   
     <td>
<?php
if($status=="booked"){
	?>
<button id="info<?php echo $id;?>"type="button" style="height:40px;"class="btn btn-soft-primary" disabled ><i class="uil uil-print"></i></button>                                 
	<?php
}else{
?>
<button id="info<?php echo $id;?>"type="button" style="height:40px;"class="btn btn-soft-primary"><i class="uil uil-print"></i></button>  

<script>
$("#info<?php echo $id;?>").click(function(){
	$('.exampleModalFullscreen<?php echo $id;?>').modal('hide');
$('.exampleModalFullscreen<?php echo $id;?>').modal('show');
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print<?php echo $id;?>");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_ticket_table.php?id=<?php echo $id;?>";
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
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
	
}
	?>



</td>
<td>
                                 
<?php
if($status=="booked"){
	?>
	<button id="print<?php echo $id;?>"type="button" style="height:40px;"class="btn btn-soft-light" disabled><i class="uil uil-pen" ></i></button> 
	<?php
}else{
	?>
	<div id="editor_no<?php echo $id;?>"style="display:none"><?php echo $origin_status; ?></div>
<button data-bs-toggle="modal" data-bs-target="#myModal"id="print<?php echo $id;?>"type="button" style="height:40px;"class="btn btn-soft-light"><i class="uil uil-pen"></i></button> 
<script>

$(document).ready(function(){


$("#print<?php echo $id;?>").click(function(){
var  editor_no = $( "#editor_no<?php echo $id;?>" ).text();
if(editor_no !="half_complete"){
			$("#second_model_area").load();

		$("#first_model_area").load("edit_booked.php?id=<?php echo $id;?>&route_id=<?php echo $route_id;;?> ");

}else if (editor_no !="complete"){
		$("#second_model_area").load("edit_booked_half.php?id=<?php echo $id;?>&route_id=<?php echo $route_id;;?> ");
		$("#first_model_area").load();

}
});
	});


</script>
</script>


<?php
}
?>
</td>
             <td>
			 <?php
			 if($status=="booked"){
				 ?>
				<span style="display:none;"id="id<?php echo $id;?>"><?php echo $id;?></span>
<button id="deletebtn_booked<?php echo $id;?>"type="button" style="height:40px;"class="btn btn-soft-danger"><i  class="uil uil-ban"></i></button> 
<span style="display:none;"id="status<?php echo $id;?>"><?php echo $status;?></span>   
<span id="deleted_text_change<?php echo $id;?>"></span>  
				 <?php
			 }else{
				 ?>
				 <span style="display:none;"id="id<?php echo $id;?>"><?php echo $id;?></span>
<button id="deletebtn<?php echo $id;?>"type="button" style="height:40px;"class="btn btn-soft-danger"data-bs-toggle="modal"data-bs-target="#delete_modal<?php echo $id;?>"><span id=""></span><i  class="uil uil-ban"></i></button> 
<span style="display:none;"id="status<?php echo $id;?>"><?php echo $status;?></span>   
				 <?php
			 }
			 ?>
			 
 

        <div id="delete_modal<?php echo $id;?>" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Delete Reasons</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a comment here" id="delete_resons<?php echo $id;?>"
                                                style="height: 100px"></textarea>
                                            <label for="delete_resons<?php echo $id;?>">State Your Reasons here..</label>
                                        </div>
                                                 

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" id="deleted_btn<?php echo $id;?>"class="btn btn-primary "><span id="delete_text<?php echo $id;?>">Save changes</span></button>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
                                 
<script>
$(document).ready(function(){

	$("#deleted_btn<?php echo $id;?>").click(function(){
reason =  $("#delete_resons<?php echo $id;?>").val();
var id= $("#id<?php echo $id;?>").text();
		var status= $("#status<?php echo $id;?>").text();
		
if(reason==""){
	 swal("Fill Resons!", "State why you want to delete!", "warning");
			 			 control.disabled="disabled";
}
		$("#delete_text<?php echo $id;?>").text('deleting..');
		    $.ajax({
				url:'delete_seat.php',
				method:'POST',
				data:{id:id,reason:reason},
				success:function(response){
	if($.trim(response)=="not"){
		 $("#delete_modal<?php echo $id;?>").modal("hide");
							 swal("Admin", "only Admin can delete the ticket", "warning");
$("#delete_text<?php echo $id;?>").text('');
		}else if(response==""){
					
    $("#delete_modal<?php echo $id;?>").modal("hide");

	 alertify.success('You have delete ticket no '+id);
alertify.set('notifier','position', 'top-right');
		
		}else if (response=="past"){
			 $("#delete_modal<?php echo $id;?>").modal("hide");
				 swal("Past", "Cannot delete past bookings", "warning");
				 $("#load_seat_table").load("load_seat_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	
			
		}else if (response=="locked"){
			 $("#delete_modal<?php echo $id;?>").modal("hide");
				 swal("Locked", "Please consult <?php echo $locker_fullname;?>", "warning");
				 $("#load_seat_table").load("load_seat_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	
			
		}

							$("#load_seat_table").load("load_seat_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	$("#load_table").load("booking_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	
$("#load_booking").load("load_booking.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	$("#load_booking_sub").load("load_sub_booking.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
  	
  	
	}
			
			});	

	});
	
	$("#delete_resons<?php echo $id;?>").on('keypress',function(e) { 
 var key = e.which;
 if(key == 13)  // the enter key code
  {
reason =  $("#delete_resons<?php echo $id;?>").val();
var id= $("#id<?php echo $id;?>").text();
		var status= $("#status<?php echo $id;?>").text();
		
if(reason==""){
	 swal("Fill Resons!", "State why you want to delete!", "warning");
			 			 control.disabled="disabled";
}
		$("#delete_text<?php echo $id;?>").text('deleting..');
		    $.ajax({
				url:'delete_seat.php',
				method:'POST',
				data:{id:id,reason:reason},
				success:function(response){

	if($.trim(response)=="not"){
							 swal("Admin", "only Admin can delete the ticket", "warning");
$("#delete_text<?php echo $id;?>").text('');
		}else if(response==""){
					
    $("#delete_modal<?php echo $id;?>").modal("hide");

	 alertify.success('You have delete ticket no '+id);
alertify.set('notifier','position', 'top-right');
		
		}else if (response=="past"){
				 swal("Past", "Cannot delete past bookings", "warning");
				 $("#load_seat_table").load("load_seat_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	
			
		}

							$("#load_seat_table").load("load_seat_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	$("#load_table").load("booking_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	
$("#load_booking").load("load_booking.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	$("#load_booking_sub").load("load_sub_booking.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
  	
  	
	}
			
			});	


    return false;  
  }
});

	
	
	$("#deletebtn_booked<?php echo $id;?>").click(function(){
reason =  "nil";
var id= $("#id<?php echo $id;?>").text();
		var status= $("#status<?php echo $id;?>").text();
$("#deleted_text_change<?php echo $id;?>").text("changing...");		

		    $.ajax({
				url:'delete_seat.php',
				method:'POST',
				data:{id:id,reason:reason},
				success:function(response){

						$("#load_seat_table").load("load_seat_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	$("#load_table").load("booking_table.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?>");
	
$("#load_booking").load("load_booking.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
	$("#load_booking_sub").load("load_sub_booking.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&route_id=<?php echo $route_id;;?>&start_location=<?php echo str_replace(' ', '_', $start_location);?>&ending_location=<?php echo str_replace(' ', '_', $ending_location);?> ");
  	
  	
	
	}
			
			});	

	});	
	
	
	
});

</script>
</td>
            </tr>
           <?php
}}
		   ?>
        </tbody>
     
    </table>
	<script>
	$(document).ready(function(){
		
		 $('#example').DataTable({
			 dom: 'Bfrtip',
			 "order": [[ 1, "desc" ]],
	});
	
				 oTable = $('#example').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
}); 
	});
	</script>