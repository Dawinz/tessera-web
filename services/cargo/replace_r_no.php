<?php
require_once __DIR__ . '/includes/sr_env.php';
if (isset($_POST['search'])) {
		$response = "<ul><li>No Match Found!</li></ul>";

		$connection = sr_mysqli('sr');
		$q = $connection->real_escape_string($_POST['q']);
		$q = str_replace(' ', '', $q);

		$sql = $connection->query("SELECT * FROM parcel_info WHERE r_no LIKE '%$q%'  or  s_no LIKE '%$q%' ");
		if ($sql->num_rows > 0) {
			$response = "<ul>";

			while ($data = $sql->fetch_array())
				$response .= '
			
			
			           <div class="card "id="res'.$data['id'].'"  >
                                            
                                                    <div class="flex-grow-1 ms-3" style="margin:10px;">
                                                        <div class="text-muted">
                                                            <h5 class="font-size-16 mb-1">'.$data['r_name'].'</h5>
																	<div style="display:none;"id="r_name'.$data['id'].'">'.$data['r_name'].'</div>
												<div style="display:none;"id="s_name'.$data['id'].'">'.$data['s_name'].'</div>
												<div style="display:none;"id="r_no'.$data['id'].'">'.$data['r_no'].'</div>
												<div style="display:none;"id="s_no'.$data['id'].'">'.$data['s_no'].'</div>
                                                               <p>Senders name <span style="color:#00C292; font-weight:bold;"> :
													'.$data['s_name'].' </p> <p>Reciver Number <span style="color:#00C292; font-weight:bold;"> :
													'.$data['r_no'].' </p> <p>Senders Number <span style="color:#00C292; font-weight:bold;"> :
													'.$data['s_no'].' </p>
                                                        </div>
                                                    </div>
                                                </div>
												
		
										<script>
									$(document).ready(function(){
												$("#res'.$data['id'].'").click(function(){
										var r_name = $("#r_name'.$data['id'].'").text();
										var s_name = $("#s_name'.$data['id'].'").text();
										var r_no = $("#r_no'.$data['id'].'").text();
										var s_no = $("#s_no'.$data['id'].'").text();
										$("#r_no").val(r_no);
										$("#s_no").val(s_no);
										$("#r_firstname").val(r_name);
										$("#firstname").val(s_name);
										
										});
										
									});
										</script>
				';

			$response .= "</ul>";
		}


		exit($response);
	}
?>