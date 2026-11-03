<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
//fetch.php
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$connect = $db;
$columns = array('seat_no', 'id', 'seat_no', 'firstname', 'book_date','status');

$query = "SELECT * FROM booking WHERE " . tessera_sql_tenant_eq() . " AND delete_book='' and ";

if(isset($_POST["is_date_search"]) && $_POST["is_date_search"] == "yes")
{
 $query .= ''.$_POST["end_date"].'  '.$_POST["start_date"].'  ';
}

if(isset($_POST["search"]["value"]))
{
 $query .= '
  (trip_date LIKE "%'.$_POST["search"]["value"].'%" 
  OR id LIKE "%'.$_POST["search"]["value"].'%" 
  OR seat_no LIKE "%'.$_POST["search"]["value"].'%" 
  OR firstname LIKE "%'.$_POST["search"]["value"].'%")
 ';
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY trip_date DESC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

$result = mysqli_query($connect, $query . $query1);

$data = array();

while($row = mysqli_fetch_array($result))
{
 $sub_array = array();
 $sub_array[] = $row["seat_no"];
 $sub_array[] = $row["id"];
 $sub_array[] = ''.$row['firstname'].' '.$row['lastname'].'';

 $sub_array[] = $row["trip_date"]; 
 $sub_array[] = $row["bus"]; 
 $sub_array[] = ''.$row["issuer"].'';
  $sub_array[] = number_format($row['cost'],2);
   $sub_array[] = '
          <div class="dropdown">
                                                            <div class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i
                                                                    class="mdi mdi-dots-horizontal font-size-18 text-muted"></i>
                                                            </div>
                                                            <ul class="dropdown-menu dropdown-menu-end">
															
                                                                <li><div class="dropdown-item"id="manifest'.$row['id'].'"data-bs-toggle="modal"data-bs-target=".bs-example-modal-xl_manifest">
																<i class="uil uil-align"></i>
                                                                        Manifest</div></li>
                                                                <li><div id="view'.$row['id'].'" class="dropdown-item"data-bs-toggle="modal"data-bs-target=".bs-example-modal-xl_view">
																<i class="uil uil-eye"></i>
                                                                        View</div></li>
																<li><div id="print'.$row['id'].'" class="dropdown-item">
																<i class="uil uil-print"></i>
                                                                        Print</div></li>
																<li><div  id="transfer_btn'.$row['id'].'" class="dropdown-item">
																 <i class="uil  uil-arrow-random "></i> Transfer</li>
																<li><div id="delete'.$row['id'].'" class="dropdown-item">
																 <i class="uil uil-ban"></i> </button>
                                                                        Delete</div></li>
                                                            </ul>
                                                        </div>
														
														
														
					   <script>
					   $("#delete'.$row['id'].'").click(function(){
						$("#myModal_delete").modal("show");
						$("#seat_no_resonse").text("'.$row['id'].'");
					   });
					   
					   		    $("#transfer_btn'.$row['id'].'").click(function(){
									
								$(".load_user_t_content").load("load_user_t_content.php?id='.$row['id'].'");

								$(".seat_name_class").text("'.$row['seat_no'].'");
						 $(".bs-example-modal-xl2").modal("show");
						 var this_text="holder";
						  var route_day_id_modal ="'.$row['route_day_id'].'";
						  	 var route_id_modal ="'.$row['route_id'].'";
							 var start_route_modal ="'.$row['start_route'].'";
							 var end_route_modal ="'.$row['end_route'].'";
						 	$.ajax({
				url:"load_start_route_area.php",
				method:"POST",
				data:{this_text:this_text,start_route_modal:start_route_modal},
				success:function(response){
				
		$("#trip_start_modal").html(response);
				
	}
			
			});			
			$.ajax({
				url:"load_end_area.php",
				method:"POST",
				data:{this_text:this_text,end_route_modal:end_route_modal},
				success:function(response){
				
		$("#trip_end_modal").html(response);
				
	}
			
			});
		$.ajax({
				url:"load_bus_name.php",
				method:"POST",
				data:{route_id_modal:route_id_modal,route_day_id_modal:route_day_id_modal},
				success:function(response){
				
		$("#select_bus_modal").html(response);
				
	}
			
			});
			$("#starting_date_select").val("'.$row['trip_date'].'");
			$("#ticket_no_original").text("'.$row['id'].'");
			$("#t_seat_name").text("'.$row['seat_no'].'");
			$("#t_no_seat").text("'.$row['id'].'");
				
$("#load_class").load("load_seat_chart_transfer.php?start_location='.str_replace(" " , "_",$row['start_route']).'&ending_location='.str_replace(" " , "_",$row['end_route']).'&id='.$row['route_day_id'].'&route_id='.$row['route_id'].'&seat_no='.$row['seat_no'].'&trip_date='.$row['trip_date'].'&bus='.$row['bus'].'");


				
						 
					   });
					    $("#print'.$row['id'].'").click(function(){
				$(".exampleModalFullscreen'.$row['id'].'").modal("show");
					var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print'.$row['id'].'");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_ticket_table.php?id='.$row['id'].'";
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	
			 });
			 
			 
	 $("#manifest'.$row['id'].'").click(function(){
	  $(".trip_date_text").text("'.date("l jS \of F Y", strtotime($row["trip_date"])).'");
        $(".bus_name").text("'.$row["bus"].'");
$("#manifest_place_holder").load("passenger_manifest_whole.php?id='.$row["route_day_id"].'&trip_date='.$row["trip_date"].'&route_id='.$row["route_id"].'&bus='.$row["bus"].'&manifest_id='.$row["id"].'");

  });		 
			
					   $("#delete'.$row['id'].'").click(function(){
						$("#myModal_delete").modal("show");
						$("#seat_no_resonse").text("'.$row['id'].'");
					   }); 	 
			 $("#view'.$row['id'].'").click(function(){
			$("#Load_ticket_info").load("load_ticket_veiw.php?id_update='.$row['id'].'&route_update='.$row['route_id'].'");

});

					   </script>


	 <div class="modal fade exampleModalFullscreen'.$row['id'].'" tabindex="-1"
                                            aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalFullscreenLabel">Print reciepts</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
<div id="container_print'.$row['id'].'"style="background-color:white;"></div>                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                   
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
					   
   ';




 $sub_array[] = '<button type="button" id="reserved'.$row['id'].'"style="height:40px;"class="btn btn-soft-warning" data-bs-toggle="reserve" '.$row['book_status'].'><i class="uil uil-registered"></i> </button>   
			<script>
			$("#reserved'.$row['id'].'").click(function(){
				reserved_id = "'.$row['id'].'";
				
				Swal.fire({
  title: "Are you sure? You are about to change from Reserved to Complete book!",

  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Complete"
}).then((result) => {
  if (result.isConfirmed) {


				
							$.ajax({
				url:"update_resserved.php",
				method:"POST",
				data:{reserved_id:reserved_id},
				success:function(response){
$("#passenger_info").DataTable().ajax.reload();
if (window.TesseraStates) {
	TesseraStates.toastOk("Booking completed", "Reservation converted to a paid booking.");
}
	}
			
			});
			  }
})
			});
			</script>
			';$sub_array[] = '<button type="button" id="clear'.$row['id'].'" class="btn btn-soft-warning"'.$row['disable_loan'].'><i class="uil  uil-dollar-sign-alt "></i></button>
				<script>
			$("#clear'.$row['id'].'").click(function(){
				clear_id = "'.$row['id'].'";
				
				Swal.fire({
  title: "Are you sure? You are about to clear the due payment!",

  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Clear"
}).then((result) => {
  if (result.isConfirmed) {


				
							$.ajax({
				url:"update_clear.php",
				method:"POST",
				data:{clear_id:clear_id},
				success:function(response){
		$("#passenger_info").DataTable().ajax.reload();
		if (window.TesseraStates) {
			TesseraStates.toastOk("Payment successful", "Due payment cleared.");
		}
	}
			
			});
			  }
})
			});
			</script>
			';

		
 $data[] = $sub_array;
}

function get_all_data($connect)
{
 $query = "SELECT * FROM booking WHERE " . tessera_sql_tenant_eq() . "";
 $result = mysqli_query($connect, $query);
 return mysqli_num_rows($result);
}

$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  get_all_data($connect),
 "recordsFiltered" => $number_filter_row,
 "data"    => $data
);

echo json_encode($output);

?>
