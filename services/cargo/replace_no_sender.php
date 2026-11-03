<?php
require_once __DIR__ . '/includes/sr_env.php';
$count_number = $_POST['this_phone_id'];
	if (isset($_POST['search'])) {
		$response = "<ul><li>No Match Found!</li></ul>";

		$connection = sr_mysqli('cargo');
		$q = $connection->real_escape_string($_POST['q']);
		$q = str_replace(' ', '', $q);

		$sql = $connection->query("SELECT r_name,r_no,id FROM contact WHERE    s_no LIKE '%$q%' LIMIT 1 ");
		if ($sql->num_rows > 0) {
			$response = "<ul>";

			while ($data = $sql->fetch_array())
		$response .= '
			
			
			           <div id="res'.$data['id'].'"  >
                                            
                                                    <div class="flex-grow-1 ms-3" style="margin:10px;">
                                                        <div class="text-muted">
                                                            <h5 class="font-size-16 mb-1">'.$data['r_name'].'</h5>
																	<div style="display:none;"id="r_name'.$data['id'].'">'.$data['r_name'].'</div>
												
												<div style="display:none;"id="r_no'.$data['id'].'">'.$data['r_no'].'</div>
												
                                                              <p>Reciver Number <span style="color:#00C292; font-weight:bold;"> :
													'.$data['r_no'].' </p> 
                                                        </div>
                                                    </div>
                                                </div> <hr>
												
		
										<script>
									$(document).ready(function(){
												$("#res'.$data['id'].'").click(function(){
										var r_name = $("#r_name'.$data['id'].'").text();
										
										var r_no = $("#r_no'.$data['id'].'").text();
									
										$("#r_no'.$count_number.'").val(r_no);
										
										$("#r_firstname'.$count_number.'").val(r_name);
										
										
										});
										
									});
										</script>
				';

			$response .= "</ul>";
		}


		exit($response);
	}
?>