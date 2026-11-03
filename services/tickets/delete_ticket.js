$("#delete_ticket").click(function(){
	var reason = $("#delete_resonse").val();
	id = $("#seat_no_resonse").text();
	if(reason ==""){
		if (window.TesseraStates) {
			TesseraStates.toastWarn('Add a reason', 'Enter why this ticket should be deleted.');
		} else {
			Swal.fire('Fill Reasons?', '', 'error');
		}
	}else{
		$("#delete_ticket").attr("disabled",true);
			    $.ajax({
				url:'delete_seat.php',
				method:'POST',
				data:{id:id,reason:reason},
				success:function(response){
					
$("#delete_ticket").attr("disabled",false);
if($.trim(response)=="not"){
		if (window.TesseraStates) {
			TesseraStates.toastErr('Not allowed', 'Only an admin can delete this ticket.');
		} else {
			Swal.fire('Only Admin can delete', '', 'error');
		}
control.disabled="disabled";
		}else if (response=="past"){
			if (window.TesseraStates) {
				TesseraStates.toastErr('Past ticket', 'You cannot delete past tickets.');
			} else {
				Swal.fire('You cannot delete past tickets', '', 'error');
			}
		control.disabled="disabled";	
		}else if (response=="locked"){
				if (window.TesseraStates) {
					TesseraStates.toastErr('Locked ticket', 'Unlock the ticket before deleting.');
				} else {
					Swal.fire('You cannot delete Locked tickets', '', 'error');
				}
control.disabled="disabled";
		}else{
			if (window.TesseraStates) {
				TesseraStates.toastOk('Ticket deleted', 'The booking was removed.');
			} else {
				Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'Deleted',
  showConfirmButton: false,
  timer: 2500
})
			}
		}
											if (window.SR_reloadPassengerInfoTable) {
												window.SR_reloadPassengerInfoTable();
											} else if ($.fn.DataTable && $.fn.DataTable.isDataTable('#passenger_info')) {
												$("#passenger_info").DataTable().ajax.reload();
											}
											if (window.SR_reloadSeatChartForTrip) {
												window.SR_reloadSeatChartForTrip();
											}
$("#delete_resonse").val("");
	},
				error: function () {
					$("#delete_ticket").attr("disabled", false);
					if (window.TesseraStates) {
						TesseraStates.toastErr('Delete failed', 'Could not reach the server. Try again.');
					}
				}
			
			});	
		
		
	}
});