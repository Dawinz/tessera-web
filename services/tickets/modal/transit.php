 <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
                                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myLargeModalLabel"><span id="loading_text"></span></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">


													   <div id="carouselExampleIndicators_manifest2" class="carousel slide" data-bs-ride="false" >

                                 <div class="carousel-inner" role="listbox">
                                        <div class="carousel-item active">
                                       <div id="transit_load"></div>
                                        </div>
                                        <div class="carousel-item">

<div class="row">
<div class="col">
	<div id="transit_seat_arrangement"></div>

</div>
<div class="col">
		   <form id="transit_transfer" enctype="multipart/form-data">
<input type="hidden" name ="bus_t" id="bus_t">
<input type="hidden" name ="trip_date_t" id="trip_date_t">
<input type="hidden" name ="route_id_t" id="route_id_t">
<input type="hidden" name ="route_day_id_t" id="route_day_id_t">
<input type="hidden" name ="start_route_t" id="start_route_t">
<input type="hidden" name ="end_route_id_t" id="end_route_id_t">
<table>
						  <tr>
						  <td class="table_padding" >



<select class="form-select" id="trip_start_tranit" name="trip_start_tranit" >

  
 
</select>
						 </td>  <td class="table_padding" >
					   
<select class="form-select" id="trip_end_tranit" name="trip_end_tranit" >

   
</select>
</td>

 

						  </tr>
						  </table>
						  
						  
	       <table class="table " id="transit_table">
								<tr>
									<th>Ticket No </th>
									<th>Seat No</th>
									<th></th>
				
								</tr>
							</table>	
										 <div class="modal-footer">
            <button type="submit"class="btn btn-outline-success" id="save" disabled>Save changes</button>
          </div>
                  							
						  
</div>
</div>
                                        </div>
                                     
                                    </div>


                                                    </div>
		
                                                    </div>
																								
			                              </div>
												
												</form>
												<!-- /.modal-content 
												
                                            </div><!-- /.modal-dialog -->
                                        </div>

<script>
$(function () {
  if (!$.fn.carousel) {
    return;
  }
  $("#carouselExampleIndicators_manifest2").carousel({
    interval: false
  });
});
</script>