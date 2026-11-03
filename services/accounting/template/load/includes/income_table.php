 <div class="az-mail-compose show_income" >
      <div>
        <div class="container">
          <div class="az-mail-compose-box ">
            <div class="az-mail-compose-header "style="background:#2B57D9;">
              <h2  style=""><span id="point_name"><?php echo $point;?></span> - Income</h2>
              <nav class="nav">
                <a href="" class="nav-link"><i class="fas fa-minus"></i></a>
                <a href="" class="nav-link"><i class="fas fa-compress"></i></a>
                <a href="" class="nav-link"><i class="fas fa-times"></i></a>
              </nav>
            </div><!-- az-mail-compose-header -->
            <div class="az-mail-compose-body"style=" overflow-x: hidden;overflow-y: auto; max-height:500px;">
               <form id="insert_form" enctype="multipart/form-data">
		<input name ="point_value"type="hidden"id="point_value"value="<?php echo $point;?>"></input>
		<input name="busname"type="hidden"id="busname"value="<?php echo $bus;?>"></input>
		<input name="trip_date"type="hidden"id="trip_date"value="<?php echo $trip_date;?>"></input>
		<input name="route_name"type="hidden"id="route_name"value="<?php echo $route_name;?>"></input>
		<input name="main_route"type="hidden"id="main_route"value="<?php echo $main_route;?>"></input>
		<input name="current_route"type="hidden"id="current_route"value="<?php echo $current_route_currency;?>"></input>
<input name="row_num"type="hidden"id="row_number"value="<?php echo $row_num_route;?>"></input>
		<input name="end_point"type="hidden"id="row_number"value="<?php echo $end_point;?>"></input>
		
		<div class="table-repsonsive">
							<span id="error"></span>
							<table class="table table-bordered" id="item_table">
								<tr>
<th></th>

									<th>Particular Category</th>
									<th>Particular </th>
									<th>Currency </th>
									<th>Amount </th>
									<th>Detail </th>
									
									<th><button type="button" name="add" class="btn btn-success btn-sm add "><i class="fas fa-plus"></i></button></th>
								</tr>
								<tfoot>
								<tr>
									<th></th>
									<th></th>
									<th> </th>
									<th> </th>
									<th> </th>
									<th> </th>
									
									<th></th>
								</tr>
								</tfoot>
							</table>
						
						</div>
						 <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save">Save changes</button>
            
          </div>
		</form>
            </div><!-- az-mail-compose-body -->
          </div><!-- az-mail-compose-box -->
        </div><!-- container -->
      </div>
    </div> 