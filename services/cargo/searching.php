<?php
require_once __DIR__ . '/includes/sr_env.php';
if (isset($_POST['search'])) {
		$response = "<ul><li>No data found!</li></ul>";

		$connection = sr_mysqli('cargo');
		$q = $connection->real_escape_string($_POST['q']);
		$q = str_replace(' ', '', $q);

		$sql = $connection->query("SELECT * FROM parcel_info WHERE code LIKE '%$q%' or s_name LIKE '$q%' or  r_name LIKE '$q%' or  r_no LIKE '%$q%' or  s_no LIKE '%$q%' order by id desc LIMIT 10");
		if ($sql->num_rows > 0) {
			$response = "<ul class='list-unstyled chat-list'>";

			while ($data = $sql->fetch_array())
				$response .= '
			
			
			            <li class="active">
                                                                <a href="view.php?u='.$data['id'].'" style="font-size:14px; color:black;">
                                                                    <div class="d-flex align-items-start">
                                                                        
                                                                        <div class="flex-shrink-0 user-img online align-self-center me-3">
                                                                            <img src="img/baggage2.png" class="rounded-circle avatar-sm" alt="">
                                                                            
                                                                        </div>
                                                                        
                                                                        <div class="flex-grow-1 overflow-hidden">
                                                                            <h5 class="text-truncate font-size-14 mb-1">'.$data['r_name'].'</h5>
                                                                            <p class="text-truncate mb-0">Luggage with code No <span style="color:#00C292; font-weight:bold;">'.$data['code'].'</span> was charged on <span style="color:#ff9933; font-weight:bold;"> '.$data['date_insert'].' </span> by <span style="font-weight:bold;color:#042e8f;">'.$data['uploader'].' </span>, it status is : <span style="color:#cc0000; font-weight:bold;">'.$data['store'].' </span> : Payment status is <span style="color:#026c6e;font-weight:bold;">'.$data['status'].' </span>  Tsh ( To be paid <span style="font-weight:bold; border-bottom:2px solid grey;">'.number_format($data['to_be'],2).'</span> , Paid <span style="font-weight:bold; border-bottom:2px solid grey;">'.number_format($data['paid'],2).'</span> )</p>
                                                                        </div>
                                                                        <div class="flex-shrink-0">
                                                                            <div class="font-size-11">'.$data['code'].'</div>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </li>
	
				';

			$response .= "</ul>";
		}


		exit($response);
	}
?>