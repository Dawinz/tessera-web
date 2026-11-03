<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config2.php';
## Read value
$draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
$row = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? (int)$_POST['length'] : 10;
if ($rowperpage < 1) { $rowperpage = 10; }
$columnIndex = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
$columnName = 'id';
if (isset($_POST['columns'][$columnIndex]['data']) && $_POST['columns'][$columnIndex]['data'] !== '') {
	$columnName = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$_POST['columns'][$columnIndex]['data']);
	if ($columnName === '') { $columnName = 'id'; }
}
$columnSortOrder = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'asc') ? 'ASC' : 'DESC';
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

$searchArray = array();

$tripFilterQuery = '';
$tripFilterParams = array();
$routeDayFilter = isset($_POST['route_day_id']) ? trim((string) $_POST['route_day_id']) : '';
$tripDateFilter = isset($_POST['trip_date']) ? trim((string) $_POST['trip_date']) : '';
$busFilter = isset($_POST['bus']) ? trim((string) $_POST['bus']) : '';
if ($routeDayFilter !== '' && $tripDateFilter !== '' && $busFilter !== '') {
	$tripFilterQuery = " AND route_day_id = :route_day_id AND trip_date = :trip_date AND bus = :bus AND delete_book = '' ";
	$tripFilterParams = array(
		'route_day_id' => $routeDayFilter,
		'trip_date' => $tripDateFilter,
		'bus' => $busFilter,
	);
}

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (trip_date LIKE :trip_date or 
        id  LIKE :id  or 
        seat_no  LIKE :seat_no  or 
        firstname  LIKE :firstname  or 
        lastname  LIKE :lastname    ) ";
    $searchArray = array( 
        'trip_date'=>"%$searchValue%", 
        'id'=>"%$searchValue%", 
		'seat_no'=>"%$searchValue%",
		'firstname'=>"%$searchValue%",'lastname'=>"%$searchValue%",
       
		
    );
}

## Total number of records without filtering
$countBase = "SELECT COUNT(*) AS allcount FROM booking WHERE " . tessera_sql_tenant_eq() . " AND 1=1 " . $tripFilterQuery;
$stmt = $conn->prepare($countBase);
$stmt->execute($tripFilterParams);
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare($countBase . $searchQuery);
$stmt->execute(array_merge($tripFilterParams, $searchArray));
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$fetchWhere = " AND delete_book='' ";
if ($tripFilterQuery === '') {
	$fetchWhere = " AND delete_book='' ";
}
$stmt = $conn->prepare(
	"SELECT * FROM booking WHERE " . tessera_sql_tenant_eq() . " AND 1=1 " . $tripFilterQuery . $fetchWhere . $searchQuery
	. " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset"
);

// Bind values
foreach (array_merge($tripFilterParams, $searchArray) as $key => $search) {
    $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();


foreach($empRecords as $row){
    $data[] = array(
          
			"trip_date"=>$row['trip_date'],
			"bus"=>$row['bus'],	 
            "seat_no"=> '<span class="'.$row['status'].'_seat"> '.$row['seat_no'].'</span>',
			"id"=>$row['id'], 
			"status"=>$row['status'], 
			"fullname"=>''.$row['firstname'].' '.$row['lastname'].'',
			"trip_date"=>$row['trip_date'],
			"issuer"=>$row['issuer'],
			"edit"=>'<button id="edit'.$row['id'].'"type="button" style="height:40px;"class="btn btn-soft-light" data-bs-toggle="modal" data-bs-target="#myModal_edit" ><i class="uil uil-edit"></i></button> 
<script>
$("#edit'.$row['id'].'").click(function(){
			$("#first_model_area").load("edit_booked.php?id_update='.$row['id'].'&route_update='.$row['route_id'].'");

});


</script>
			',
			"lock"=>'<button type="button"id="unlock'.$row['id'].'" style="height:40px;"class="btn btn-soft-warning" '.$row['locked_status'].' " ><i class="uil uil-unlock"></i></button>  
<script>

$("#unlock'.$row['id'].'").click(function(){
	unlock = "'.$row['id'].'";
Swal.fire({
  title: "Do you want to save the changes?",
  showDenyButton: true,
  showCancelButton: true,
  confirmButtonText: "Unlock and book",
  denyButtonText: "Unlock only",
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
  
  
  		$.ajax({
				url:"unlock_seat2.php",
				method:"POST",
				data:{unlock:unlock},
				success:function(response){
window.location="schedule_book.php?id='.base64_encode($row['route_day_id']).'&trip_date='.base64_encode($row['trip_date']).'&route_id='.base64_encode($row['route_id']).'&start_location='.base64_encode($row['start_route']).'&ending_location='.base64_encode($row['end_route']).'&bus='.base64_encode($row['bus']).'&seat_no='.base64_encode($row['seat_no']).'"
				
	}
			
			});
  
  
  } else if (result.isDenied) {
   				$.ajax({
				url:"unlock_seat2.php",
				method:"POST",
				data:{unlock:unlock},
				success:function(response){
					$("#passenger_info").DataTable().ajax.reload();
					if (window.TesseraStates) {
						TesseraStates.toastOk("Unlocked", "Seat is unlocked.");
					} else {
						Swal.fire("Unlocked!");
					}
	}
			
			});
  }
})
});

</script>

			',
			"print"=>'<button id="print'.$row['id'].'"type="button" style="height:40px;"class="btn btn-soft-primary"  ><i class="uil uil-print"></i></button>
			 <script>
			 $("#print'.$row['id'].'").click(function(){
window.open("print_multiple_ticket.php?id='.$row['id'].'");
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
			',
			"reserve"=>'<button type="button" id="reserved'.$row['id'].'"style="height:40px;"class="btn btn-soft-warning" data-bs-toggle="reserve" '.$row['book_status'].'><i class="uil uil-registered"></i> </button>   
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
				
	}
			
			});
			  }
})
			});
			</script>
			',
			"cost"=>number_format($row['cost'],2),
		
			"ending_location"=>$row['ending_location'],
			"start_location"=>$row['start_location'],
			"book_date_time"=>''.$row['book_date'].' '.$row['book_time'].'',
			"transfer"=>'<button type="button" class="btn btn-soft-success" id="transfer_btn'.$row['id'].'">
                       <i class="uil  uil-arrow-random "></i>   </button>
					   <script>
				
						    $("#transfer_btn'.$row['id'].'").click(function(){
								$(".load_user_t_content").load("load_user_t_content.php?id='.$row['id'].'");

								$(".seat_name_class").text("'.$row['seat_no'].'");
						 $(".bs-example-modal-xl2").modal("show");
						 var this_text="holder";
						  var route_day_id_modal ="'.$row['route_day_id'].'";
						  	 var route_id_modal ="'.$row['route_id'].'";
						  	 var ticket_id ="'.$row['id'].'";
							 var start_route_modal ="'.$row['start_route'].'";
							 var end_route_modal ="'.$row['end_route'].'";
						 	$.ajax({
				url:"load_start_route_area.php",
				method:"POST",
				data:{this_text:this_text,start_route_modal:start_route_modal,ticket_id:ticket_id},
				success:function(response){
			
		$("#trip_start_modal").html(response);
			
				
				$.ajax({
				url:"load_bus_name.php",
				method:"POST",
				data:{route_id_modal:route_id_modal,route_day_id_modal:route_day_id_modal},
				success:function(response){
				
		$("#select_bus_modal").html(response);
		trip_start_modal = $("#trip_start_modal").val();
		select_bus_modal = $("#select_bus_modal").val();
		starting_date_select = $("#starting_date_select").val();
		trip_end_modal = "";
		ticket_no_original = "'.$row['id'].'";

			   $.ajax({
    url:"load_seat_chart_transfer_sel.php",
    method:"POST",
    data:{trip_start_modal:trip_start_modal, select_bus_modal:select_bus_modal,trip_end_modal:trip_end_modal,starting_date_select:starting_date_select,ticket_no_original:ticket_no_original},
    success:function(data){
		
     $("#load_class").html(data);
	 
    }
   })
		
		

	}
			
			});
			var route_id_start = "'.$row['route_id'].'";
			var sub_route_id_end = "'.$row['sub_route_id'].'";
			var start_location = "'.$row['start_location'].'";
					   $.ajax({
    url:"dependence/start_point.php",
    method:"POST",
    data:{route_id_start:route_id_start, sub_route_id_end:sub_route_id_end,start_location:start_location},
    success:function(data){
	
$("#trip_start_tranit").html(data);
    }
   });	
   var route_id_start = "'.$row['route_id'].'";
			var sub_route_id_end = "'.$row['sub_route_id'].'";
			var ending_location = "'.$row['ending_location'].'";
					   $.ajax({
    url:"dependence/end_point.php",
    method:"POST",
    data:{route_id_start:route_id_start, sub_route_id_end:sub_route_id_end,ending_location:ending_location},
    success:function(data){
		
$("#trip_end_tranit").html(data);
    }
   });
		
			
			
	}
			
			});			
	
		
			$("#starting_date_select").val("'.$row['trip_date'].'");
			$("#ticket_no_original").text("'.$row['id'].'");
			$("#t_seat_name").text("'.$row['seat_no'].'");
			$("#t_no_seat").text("'.$row['id'].'");
		

		   $.ajax({
    url:"load_seat_chart_transfer_sel.php",
    method:"POST",
    data:{trip_start_modal:trip_start_modal, select_bus_modal:select_bus_modal,trip_end_modal:trip_end_modal,starting_date_select:starting_date_select,ticket_no_original:ticket_no_original},
    success:function(data){
		
     $("#load_class").html(data);
	 
    }
   });

		
//$("#load_class").load("load_seat_chart_transfer.php?start_location='.str_replace(" " , "_",$row['start_route']).'&ending_location='.str_replace(" " , "_",$row['end_route']).'&id='.$row['route_day_id'].'&route_id='.$row['route_id'].'&seat_no='.$row['seat_no'].'&trip_date='.$row['trip_date'].'&bus='.$row['bus'].'&sub_route_id='.$row['sub_route_id'].'");


				
						 
					   });
					 
					   </script>
					   
					   
					   ',
			"delete"=>'<button type="button" class="btn btn-soft-danger"id="delete'.$row['id'].'">
                       <i class="uil uil-ban"></i> </button>
					   <script>
					     $("#delete'.$row['id'].'").click(function(){
						$("#myModal_delete").modal("show");
						$("#seat_no_resonse").text("'.$row['id'].'");
					   });
					   </script>
					   ',
		    "clear"=>'<button type="button" id="clear'.$row['id'].'" class="btn btn-soft-warning"'.$row['disable_loan'].'><i class="uil  uil-dollar-sign-alt "></i></button>
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
	}
			
			});
			  }
})
			});
			</script>
			',
			 "view"=>'<button type="button" id="view'.$row['id'].'" class="btn btn-soft-info"data-bs-toggle="modal"data-bs-target=".bs-example-modal-xl_view"><i class="uil uil-eye"></i></button>
<script>
$("#view'.$row['id'].'").click(function(){
			$("#Load_ticket_info").load("load_ticket_veiw.php?id_update='.$row['id'].'&route_update='.$row['route_id'].'");

});


</script>
			 '
	
	
   );
}

## account 
$response  = array(
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecordwithFilter),
    "data" => $data,
    // legacy DataTables keys
    "iTotalRecords" => intval($totalRecords),
    "iTotalDisplayRecords" => intval($totalRecordwithFilter),
    "aaData" => $data
);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
