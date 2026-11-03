
<?php
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
    $data[] = array(
          
			"username"=>$row['username'],
			"image"=>'<a href ="eprofile.php?u='.$row['username'].'"><img class="az-img-user avatar-md" src ="uploads/'.$row['image'].'"></a>',
			"firstname"=>$row['firstname'],
			"surname"=>$row['surname'],
            "role"=>$row['role'],
			"email"=>$row['email'],
			"number"=>$row['number'],
			"user_status"=>''.$row['status'].'
		
			',
			"route"=>'<button class="btn btn-indigo btn-icon" id="assign_elem'.$row['id'].'">'.$row['id'].'<i class="bx bx-plus"></i></button>
			
		
			',
			"status"=>'
		<button id="change'.$row['id'].'" type="button" class="'.$row['button_class'].'"><i class="'.$row['button_icon'].'"></i>'.$row['button_name'].'</button>
			',


			"delete"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button type="button" class="btn btn-soft-danger"id="delete'.$row['id'].'">
                       <i class="uil uil-ban"></i> </button>
		
						',
										"update"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"type="button" class="btn btn-soft-light"id="update'.$row['id'].'">
                       <i class="uil uil-pen"></i> </button>
		

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
