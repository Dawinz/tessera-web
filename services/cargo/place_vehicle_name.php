<?php
require_once __DIR__ . '/includes/sr_env.php';
if (isset($_POST['search'])) {
		$response = "<ul><li>No Match Found!</li></ul>";

		$connection = sr_mysqli('sr');
		$q = $connection->real_escape_string($_POST['q']);
		$q = str_replace(' ', '', $q);

		$sql = $connection->query("SELECT * FROM vehicle_name WHERE Name LIKE '%$q%'  LIMIT 6");
		if ($sql->num_rows > 0) {
			$response = "<ul>";

			while ($data = $sql->fetch_array())
				$response .= '
					<div id="res'.$data['id'].'" style="font-size:14px; color:black;">
                                            <div class="hd-message-sn">
                                                <div class="hd-message-im"style="padding:auto;">
                                                    <img src="img/user2.png" alt="" />
                                                </div> &nbsp&nbsp&nbsp&nbsp
                                                <div class="hd-mg-ctnd">
												<div style="display:none;"id="vehicle'.$data['id'].'">'.$data['Name'].'</div>
												<div style="display:none;"id="driver'.$data['id'].'">'.$data['Driver'].'</div>
												<div style="display:none;"id="Conductor'.$data['id'].'">'.$data['Conductor'].'</div>
												
												
                                                    <h5>'.$data['Name'].'</h5>
                                                    <p>Driver <span style="color:#00C292; font-weight:bold;"> :
													'.$data['Driver'].' </p> <p>Conductor <span style="color:#00C292; font-weight:bold;"> :
													'.$data['Conductor'].' </p>  </p>
                                                </div>
                                            </div>
                                        </div>
										<script>
									$(document).ready(function(){
												$("#res'.$data['id'].'").click(function(){
										var vehicle = $("#vehicle'.$data['id'].'").text();
										var driver = $("#driver'.$data['id'].'").text();
										var Conductor = $("#Conductor'.$data['id'].'").text();
										
										$("#vehicle").val(vehicle);
										$("#driver").val(driver);
										$("#conductor").val(Conductor);
										
										
										});
										
									});
										</script>
				';

			$response .= "</ul>";
		}


		exit($response);
	}
?>