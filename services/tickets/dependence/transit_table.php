                       <?php
$rout_day_id = $_POST['rout_day_id'];
$trip_date = $_POST['trip_date'];
$route_id = $_POST['route_id'];
$start_location = $_POST['start_location'];
$ending_location = $_POST['ending_location'];
$bus = $_POST['bus'];
$fare_price = $_POST['fare_price'];


	
		$seat_no_array = array(); 
		$seat_no_array_id = array();  
		$sub_route_id="";
					   ?>
					   
					   <style>
					   #myInputTextField_tranit
{
width:350px;
}
					   </style>
<div class="alert alert-danger display_none" id="alert_display"role="alert"><h6 style="color:black">Seat selected</h6>
<span id="seat_array_list"></span>   <span class="display_none"id="ticket_array_list"></span>      
</div>
	<table id="myInputTextField2" style="display:none;">
		<tr>
		<td><button type="button" class="btn btn-soft-success" id="transfer_m_p"><i class="uil uil-arrow-random"></i> Transfter</button></td>
		
		</tr>
		</table><br>
		                		<form autocomplete="off" >
         <input type="text" id="myInputTextField_tranit" class="form-control" placeholder="Search...."style="position:relative;">
		 </form><br>
					   <div class="table-responsive">
                                        <table class="table table-hover table-nowrap mb-0 align-middle table-check"id="myTable2" >
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="rounded-start" style="width: 15px;">
                                                        <div class="form-check">
                                                            <input class="form-check-input font-size-16" type="checkbox"
                                                                value="" id="select_all">
                                                            <label class="form-check-label" for="select_all"> </label>
                                                        </div>
                                                    </th>
                                                    
                                                    <th>Seat no </th>
                                                    <th>Ticket no</th>
                                                    <th>Passanger</th>
                                                    <th>From</th>
                                                    <th >To</th>
                                                </tr>
                                                <!-- end tr -->
                                            </thead>
                                            <!-- end thead -->
                                            <tbody>
											
											
											<?php
											$route_name = $_POST['route_name'];
											include '../connect.php';
$select=mysqli_query($db,"SELECT *  FROM  transit_seat WHERE prev_route='$route_name' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
 $id =$get['id']; 
 $seat_no =$get['seat_no']; 
 $seat_id =$get['seat_id']; 
 $username =$get['username']; 
 $prev_route =$get['prev_route']; 
 $this_route =$get['this_route']; 
 
 array_push($seat_no_array,$seat_id );
			array_push($seat_no_array_id,$seat_id );

											?>
											
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input font-size-16 checkbox" type="checkbox"
                                                                value="" id="check_box<?php echo $id;?>">
                                                            <label class="form-check-label" for="check_box<?php echo $id;?>">
                                                            </label>
                                                        </div>
                                                    </td>
                                                 

                                                    <td>
                                             
                                                     <?php echo $seat_no;?>
                                                        
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                   <?php echo $seat_id; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                              <?php echo $username;?>
                                                    </td>

                                                    <td>
													 <?php echo $this_route;?>
                                                    </td>
													   <td class="fw-medium">
                                                  
												<?php echo $prev_route;?>

                                                    </td>
                                                
                                                
                                                </tr>
												
												
												
										<script>
							$("#check_box<?php echo $id;?>").click(function(){
								$("#myInputTextField2").show();
				remove_seat = "<?php echo "$seat_id,";?>";
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
fruits.push("<?php echo "$seat_id";?>");
	ticket.push("<?php echo "$seat_id";?>");
	sort_fruits = fruits.sort();
		 $("#alert_display").show();
$("#seat_array_list").text(fruits);
$("#ticket_array_list").text(ticket);
 sort_seat = $("#seat_array_list").text();
}else{
	
					remove_seat = "<?php echo ",$seat_id";?>";
				select_all_seats = $("#seat_array_list").text();
function removeCharacter() {
    let originalString = select_all_seats;
    newString = originalString.replace(remove_seat, "");
    $("#seat_array_list").text(newString);
}	


removeCharacter()		

		remove_seat = "<?php echo "$seat_id";?>";
				select_all_seats = $("#seat_array_list").text();
function removeCharacter() {
    let originalString = select_all_seats;
    newString = originalString.replace(remove_seat, "");
    $("#seat_array_list").text(newString);
}
	
	removeCharacter()	

	remove_seat_no = "<?php echo ",$seat_id";?>";
				select_all_seats_no = $("#ticket_array_list").text();
function removeCharacter_no() {
    let originalString = select_all_seats_no;
    newString = originalString.replace(remove_seat_no, "");
    $("#ticket_array_list").text(newString);
}
	
	 
removeCharacter_no()


remove_seat_no = "<?php echo "$seat_id";?>";
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
}}
												?>
															<div class="display_none" id="select_all_seats"><?php $update_area =  implode(",",$seat_no_array);echo $update_area; ?></div>
		<div class="display_none" id="select_all_seats_no_div"><?php $update_area2 =  implode(",",$seat_no_array_id);echo $update_area2; ?></div>
												
						<script>
						
						
						</script>						
												</tbody>
												</table>
												</div>
												
			<script>

		$(document).ready(function(){
			$('#myTable2').DataTable({
"lengthChange": false
		});
				 oTable = $('#myTable2').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField_tranit').keyup(function(){
      oTable.search($(this).val()).draw() ;
});
		});

		$('#select_all').on('click',function(){
				 								$("#myInputTextField2").show();

        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
				select_all_seats = $("#select_all_seats").text();
				select_all_seats_no_div = $("#select_all_seats_no_div").text();
		$("#seat_array_list").text(select_all_seats);
		$("#ticket_array_list").text(select_all_seats_no_div);
		$("#alert_display").show();
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
  $("#seat_array_list").text("");
  	$("#ticket_array_list").text("");
        }
		
		select_all_seats = $("#select_all_seats").text();
	
    });
	
	
	$("#transfer_m_p").click(function(){
			$("#carouselExampleIndicators_manifest2").carousel("next");	
 $("#carouselExampleIndicators_manifest2").carousel({
  interval: false,
});

route_name = <?php echo "'$start_location to $ending_location'";?>;
	rout_day_id ="<?php echo $rout_day_id; ?>";
	trip_date ="<?php echo $trip_date; ?>";
	route_id ="<?php echo $route_id; ?>";
	start_location ="<?php echo $start_location; ?>";
	ending_location ="<?php echo $ending_location; ?>";
	bus ="<?php echo $bus; ?>";
	fare_price ="<?php echo $fare_price; ?>";
select_all_seats = $("#ticket_array_list").text();$.ajax({
    url:"load_seat_chart_transit.php",
    method:"POST",
    data:{
		route_name:route_name,
	      rout_day_id:rout_day_id,
	      trip_date:trip_date,
	      route_id:route_id,
	      start_location:start_location,
	      ending_location:ending_location,
	      fare_price:fare_price,
	      select_all_seats:select_all_seats,
	      bus:bus
		
	},
	            beforeSend: function(){
$("#transit_seat_arrangement").html("loading..");

            
            },
    success:function(data){
$("#transit_seat_arrangement").html(data);

    }
   })
   
   											 $.ajax({
				url:"load_transit_point.php",
				method:"POST",
				data:{start_location:start_location,route_id:route_id},
				success:function(response){
		$("#trip_start_tranit").html(response);
				
	}
			
			});											 $.ajax({
				url:"load_transit_point.php",
				method:"POST",
				data:{ending_location:ending_location,route_id:route_id},
				success:function(response){
		$("#trip_end_tranit").html(response);
				
	}
			
			});	
   
	})
			</script>									