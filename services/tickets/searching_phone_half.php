<?php
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$seat_id=$_POST['seat_id'];


	if (isset($_POST['search'])) {
		$response = "<ul><li>No Match Found!</li></ul>";

		$connection = sr_mysqli('ticket');
		$q = $connection->real_escape_string($_POST['q']);
		$q = str_replace(' ', '', $q);

		$tenantEq = tessera_sql_tenant_eq();
		$sql = $connection->query("SELECT * FROM booking WHERE {$tenantEq} AND (phone_no LIKE '%$q%') group by phone_no LIMIT 6");
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
                                <span class="user-sub-title">'.$data['phone_no'].'</span>
                            </span>
                        </button>
								<script>
									$(document).ready(function(){
												$("#page-header-user-dropdown'.$data['id'].'").click(function(){
                                       $(".firstname_half'.$seat_id.'").val("'.$data['firstname'].'");	
									   $(".lastname_half'.$seat_id.'").val("'.$data['lastname'].'");	
									   $(".phone_no_half'.$seat_id.'").val("'.$data['phone_no'].'");	 
									   $("#gender_first_half'.$seat_id.'").text("'.$data['gender'].'");	
									   $("#age_group_first_half'.$seat_id.'").text("'.$data['age_group'].'");										
										});
										
									});
										</script>
									
				';

			$response .= "</ul>";
		}


		exit($response);
	}
?>