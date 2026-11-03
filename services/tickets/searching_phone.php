<?php
require_once __DIR__ . '/includes/sr_env.php';
$seat_id=$_POST['seat_id'];


	if (isset($_POST['search'])) {
		$response = "<ul><li>No Match Found!</li></ul>";

		$connection = sr_mysqli('ticket');
		$q = $connection->real_escape_string($_POST['q']);
		$q = str_replace(' ', '', $q);

		$sql = $connection->query("SELECT * FROM contact WHERE no LIKE '%$q%' group by no LIMIT 10");
		if ($sql->num_rows > 0) {
			$response = "<ul>";

			while ($data = $sql->fetch_array())
				$response .= '
		    <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown'.$data['id'].'"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="assets/images/users/user.png"
                            alt="Header Avatar">
                            <span class="ms-2 d-none d-sm-block user-item-desc">
                                <span class="user-name">'.$data['firstname'].' '.$data['lastname'].'</span>
                                <span class="user-sub-title">('.$data['code'].')'.$data['no'].'</span>
                            </span>
                        </button>
								<script>
									$(document).ready(function(){
												$("#page-header-user-dropdown'.$data['id'].'").click(function(){
                                       $("#'.$seat_id.'").val("'.$data['no'].'");	
									   $("#ftn'.$seat_id.'").val("'.$data['firstname'].'");	
									   $("#lst'.$seat_id.'").val("'.$data['lastname'].'");	
									   $("#very'.$seat_id.'").val("'.$data['verification'].'");	
									   $("#intl'.$seat_id.'").val("'.$data['code'].'");	
									   $("#age'.$seat_id.'").val("'.$data['age'].'");	
																		
								
										});
										
									});
										</script>
									
				';

			$response .= "</ul>";
		}


		exit($response);
	}
?>