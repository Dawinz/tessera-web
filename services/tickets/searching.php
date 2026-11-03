<?php
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
if (isset($_POST['search'])) {
		$response = "<ul><li>No data found!</li></ul>";

		$connection = sr_mysqli('ticket');
		$q = $connection->real_escape_string($_POST['q']);
		$q = str_replace(' ', '', $q);
		$tenantEq = tessera_sql_tenant_eq();

		$sql = $connection->query("SELECT * FROM booking WHERE {$tenantEq} AND (id LIKE '%$q%' or  firstname LIKE '%$q%' or  lastname LIKE '%$q%' or phone_no LIKE '%$q%') and delete_book='' order by id desc LIMIT 10");
		if ($sql->num_rows > 0) {
			$response = "<ul>";

			while ($data = $sql->fetch_array())
				$response .= '
			 <a href="print_multiple_ticket.php?id='.$data['id'].'&noprint='.$data['id'].'" >
			   <div class="d-flex align-items-center" style="padding-top:5px;">
                                                    <div class="font-size-20 text-primary flex-shrink-0 me-3">
                                                        <i class="uil uil-user"></i>
                                                    </div>
                                            
                                                   
			   <div class="flex-grow-1">
                                                        <p class="text-muted mb-1 font-size-13">Ticket number : '.$data['id'].'</p>
                                                        <h5 class="mb-0 font-size-14">'.$data['firstname'].' '.$data['lastname'].' </h5>
                                                    </div>
                                              
                                                </div></a>
			
				
				';

			$response .= "</ul>";
		}


		exit($response);
	}
?>