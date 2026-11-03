 <div class="cmp-tb-hd">
                            <h2>after every one hour during day time form will prompt</h2>
                            <p>Bootstrap-select is a jQuery plugin that utilizes Bootstrap's dropdown.js to style and bring additional functionality to standard select elements.</p>
                        </div>
                        
                       	<?php
						include 'connect.php';
		$query = "SELECT * FROM locations ";
	$result = $db->query($query);
	if ($result->num_rows > 0 ) {
			
		 while ($row = $result->fetch_assoc()) {
		 echo '
		<div class="row">
		
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="nk-int-mk sl-dp-mn sm-res-mg-t-10">
                                   
                                </div>
                                <div class="bootstrap-select fm-cmp-mg">
                                 <p>'.$row['name'].'</p>
                                </div>
                            </div>
							     <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="nk-int-mk sl-dp-mn">
                                   
                                </div>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select id="selection'.$row['id'].'" class="selectpicker">
											<option>Response</option>
											<option>Yes</option>
											<option>no</option>
										
										</select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" id="resoans'.$row['id'].'" style="display:none;">
                                <div class="nk-int-mk sl-dp-mn sm-res-mg-t-10">
                                 
                                </div>
                                <div class="bootstrap-select fm-cmp-mg">
                                   <div class="nk-int-st">
                                        <input type="text" class="form-control" placeholder="Resons">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="nk-int-mk sl-dp-mn sm-res-mg-t-10">
                                    
                                </div>
                                <div class="bootstrap-select fm-cmp-mg">
                                <button class="btn btn-primary btn-sm hec-button">complete
                                </button>
                                </div>
                            </div>
                        </div>
						<script>
						$(document).ready(function(){
						$("#selection'.$row['id'].'").change(function(){
						var selection = $("#selection'.$row['id'].'").val();
						if(selection=="no"){
							$("#resoans'.$row['id'].'").show();
						} else if(selection=="Yes"){
							$("#resoans'.$row['id'].'").hide();
						}
						});
						});
						</script>
		 ';
		 }
	}else{

		echo '<option>Destination not found</option>';
	}

	?>
                