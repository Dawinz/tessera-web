$(document).ready(function(){
$("#save_update").click(function(){
  route_name =   $("#choose_route_update").val() || $("#choose_route_update option:selected").text();
  trip_date = $("#trip_date_update").val() || $("#trip_date_update option:selected").text();
  bus = $("#vehicle_update").val() || $("#vehicle_update option:selected").text();
  id = $("#id_holder").val();
  
  error ="";
  if(!trip_date || $.trim(trip_date)==="" || trip_date==="Select Trip Date"){
    error="trip_date";
  }
  if(!route_name || route_name==="Choose Route" || route_name==="Routes not found"){
    error="route";
  }
  if(!bus || bus==="Select Vehicle"){
    error="bus";
  }
  if(!id){
    error="id";
  }
if(error ==""){
$("#text_change").text("updating..");
$("#save_update").attr("disabled", true);
        $.ajax({
    url:"update/update_manifest.php",
    method:"POST",
    data:{route_name:route_name,trip_date:trip_date,bus:bus,id:id},
    success:function(data){
      new_response = $.trim(data);
      if(new_response=="analysed"){
if (window.TesseraStates) {
  TesseraStates.toastErr('Cannot edit', 'This manifest is already analysed.');
} else {
Swal.fire({
  icon: "error",
  title: "You cannot edit",
  text: "Manifest is analysed",
 
});
}
      }else{
      $("#text_change").text("Save changes");
      $("#save_update").attr("disabled", false);
      $("#example1").DataTable().ajax.reload();
      if (window.TesseraStates) {
        TesseraStates.toastOk('Manifest updated', 'Changes were saved.');
      } else {
    	$.notify("Updated", {
  className:"success",
  clickToHide: false,
  autoHide: true,
  globalPosition: "top center"
});
      }
      }
    },
    error: function () {
      $("#text_change").text("Save changes");
      $("#save_update").attr("disabled", false);
      if (window.TesseraStates) {
        TesseraStates.toastErr('Update failed', 'Could not update this manifest.');
      }
    }
   });

}else{
  if (window.TesseraStates) {
    TesseraStates.toastWarn('Missing information', 'Choose route, trip date, and vehicle before saving.');
  } else if (window.Swal) {
    Swal.fire({ icon: 'warning', title: 'Missing information', text: 'Choose route, trip date, and vehicle before saving.' });
  } else {
    alert('Choose route, trip date, and vehicle before saving.');
  }
}

})
});