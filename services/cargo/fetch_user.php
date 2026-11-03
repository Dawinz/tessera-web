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

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (username LIKE :username or 
        firstname  LIKE :firstname  OR 
        surname LIKE :surname OR number Like :number  ) ";
    $searchArray = array( 
        'firstname'=>"%$searchValue%",  
		'username'=>"%$searchValue%", 
        'surname'=>"%$searchValue%",
		'number'=>"%$searchValue%"
		
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM users");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM users WHERE   1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM users  WHERE   1  ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
	$imgFile = trim((string) ($row['image'] ?? ''));
	$avatarSrc = 'assets/images/users/user.png';
	if ($imgFile !== '' && $imgFile !== 'user.png') {
		$uploadPath = __DIR__ . '/uploads/' . $imgFile;
		if (is_file($uploadPath)) {
			$avatarSrc = 'uploads/' . $imgFile;
		}
	}
    $data[] = array(
          
			"username"=>$row['username'],
			"image"=>'<a href ="eprofile.php?u='.$row['username'].'"><img class="rounded-circle header-profile-user" src ="'.$avatarSrc.'"></a>',
			"firstname"=>$row['firstname'],
			"surname"=>$row['surname'],
"role"=>$row['role'],
			"email"=>$row['email'],
			"number"=>$row['number'],
			"user_status"=>$row['status'],
			"status"=>'
		<button id="change'.$row['id'].'" type="button" class="'.$row['button_class'].'"><i class="'.$row['button_icon'].'"></i>'.$row['button_name'].'</button>
	<script>
						$(document).ready(function(){
						$("#change'.$row['id'].'").click(function(){
							var id = $("#id'.$row['id'].'").text();
							var username = "'.$row['username'].'";

							$("#change'.$row['id'].'").text("changing...");
											$.ajax({
				url:"change_user_status.php",
				method:"POST",
				data:{id:id,username:username},
				success:function(response){
				
			$("#example").DataTable().ajax.reload();

									if($.trim(response) == "redirect"){

window.location="log_out.php";
					}


}
			
			});
						});
						});
						</script>
			',
			 
         
			
	

			"delete"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button type="button" class="btn btn-soft-danger"id="delete'.$row['id'].'">
                       <i class="uil uil-ban"></i> </button>
			<script>
						$(document).ready(function(){
						$("#delete'.$row['id'].'").click(function(){
							var id = $("#id'.$row['id'].'").text();
							var username = "'.$row['username'].'";
							$("#delete'.$row['id'].'").text("deleting...");
											$.ajax({
				url:"delete_user.php",
				method:"POST",
				data:{id:id,username:username},
				success:function(response){
					
										$("#example").DataTable().ajax.reload();

					if($.trim(response) == "cant"){
													  swal({
    title: "Entries found",
    text: "you cannot delete.",
    timer: 1000,
    showConfirmButton: false
  });
					}else if($.trim(response) == ""){
													  swal({
  
    text: "update Succefull.",
    timer: 2000,
    showConfirmButton: false
  });

					}else if($.trim(response) == "redirect"){

window.location="log_out.php";
					}
					

	$("#example").DataTable().ajax.reload();

}
			
			});
						});
						});
						</script>
						',
										"update"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"type="button" class="btn btn-soft-light"id="update'.$row['id'].'">
                       <i class="uil uil-pen"></i> </button>
		<script>
		$("#update'.$row['id'].'").click(function(){
$("#fullname_update").val("'.$row['firstname'].'");
$("#lastname_update").val("'.$row['surname'].'");	
$("#username_update").val("'.$row['username'].'");
$("#password_update").val("'.$row['password'].'");
$("#phone_no_update").val("'.$row['number'].'");
$("#email_update").val("'.$row['email'].'");
$("#role_update_text").text("'.$row['role'].'");
$("#id_no").text("'.$row['id'].'");
	
			});	
			
			
		</script>

						',
	
	
   );
}

## account 
$response  = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
