<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config2.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$route = $_POST['route_id_modal_manifest'];
$route_day_id = $_POST['route_day_id_modal_manifest'];
$trip_date = $_POST['trip_date_modal_manifest'];
$searchArray = array();

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
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM booking WHERE " . tessera_sql_tenant_eq() . "");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM booking WHERE " . tessera_sql_tenant_eq() . " AND 1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM booking WHERE " . tessera_sql_tenant_eq() . " AND route_id ='$route' AND route_day_id = '$route_day_id' AND trip_date='$trip_date' and   1  ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

// Bind values
foreach($searchArray as $key=>$search){
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
			"lock"=>'<button type="button" style="height:40px;"class="btn btn-soft-warning" data-bs-toggle="modal"data-bs-target="#lock_modal<?php echo $id;?>" ><i class="uil uil-lock"></i></button>   ',
			"print"=>'<button id="print'.$row['id'].'"type="button" style="height:40px;"class="btn btn-soft-primary"  ><i class="uil uil-print"></i></button>
			 <script>
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
  confirmButtonText: "Book"
}).then((result) => {
  if (result.isConfirmed) {


				
							$.ajax({
				url:"update_resserved.php",
				method:"POST",
				data:{reserved_id:reserved_id},
				success:function(response){
					$("#passenger_info").DataTable().ajax.reload();

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
					 
					   </script>
					   
					   
					   ',
			"delete"=>'<button type="button" class="btn btn-soft-danger"id="delete'.$row['id'].'">
                       <i class="uil uil-ban"></i> </button>',
			 "view"=>'<button type="button" id="view'.$row['id'].'" class="btn btn-outline-light"data-bs-toggle="modal"data-bs-target=".bs-example-modal-xl_view"><i class="uil uil-eye"></i></button>
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
