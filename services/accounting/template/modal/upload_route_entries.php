<div class="modal fade " id="modal_manifest_ent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Load Manifest</h5>
        
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Trip date:</label>
        <select class="form-select" id="upload_manifest_ent">
		<option value="">Loading...</option>

		</select>
          </div>
        <div id="current_route_t" style="display:none;"></div>
        <div id="prev_date_t" style="display:none;"></div>
        <div id="main_route_t" style="display:none;"></div>
        </form>
      </div>
      <div class="modal-footer">
      
        <button type="button" class="btn btn-primary" id="insert_m_t">Upload</button>
      </div>
    </div>
  </div>
</div>

<script>
$("#insert_m_t").click(function(){
	trip_date = $("#upload_manifest_ent").val();
	route_name = $("#current_route_t").text();
	og_trip_date = $("#prev_date_t").text();
	main_route = $("#main_route_t").text();
	if(trip_date =="Choose Date"){
Swal.fire("Choose Date");
	}else{
		      $.ajax(
                            {
                                url: 'fetch/manifest_details.php',
                                method: 'POST',
                                data: {
								   trip_date:trip_date,
								   route_name:route_name,
								   main_route:main_route,
								   og_trip_date:og_trip_date
								
                                },
								  beforeSend: function(){
			    $("#insert_m_t").attr("disabled", true);
	  $("#insert_m_t").text("Uploading..");
            
            
            },
                                success: function (data) {
									alert(data);
    $("#insert_m_t").attr("disabled", false);
	  $("#insert_m_t").text("Upload");
            
               },
                                dataType: 'text'
                            }
                        );
	}
});
</script>