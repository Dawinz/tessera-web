
		const final_info = [];
		const final_info_read = [];
$(document).on('keyup','.r_no', function(){
r_no_val = $(this).val();
if (r_no_val.match(/[a-z]/i)) {

str_r_no = r_no_val.slice(0, -1);
$(this).val(str_r_no);

} else{
	 r_no_val_no =(r_no_val.length);
	if(r_no_val_no ==11){
		str_r_no = r_no_val.slice(0, -1);
$(this).val(str_r_no);
	}
}
});	

$(document).on('keyup','.sender_no', function(){
s_no_val = $(this).val();
if (s_no_val.match(/[a-z]/i)) {

str_s_no = s_no_val.slice(0, -1);
$(this).val(str_s_no);

} else{
	s_no_val_no =(s_no_val.length);
	if(s_no_val_no ==11){
		str_s_no = s_no_val.slice(0, -1);
$(this).val(str_s_no);
	}
}
});	
$('#submit_button').on('keypress', function(e) {
    if (e.which == 13) {
      
    }
});
	$('#rgform').on('submit', function(event){
		event.preventDefault();
s_no  = $("#s_no").val();
s_name  = $("#firstname").val();

$("#s_no_new").val(s_no);
$("#s_name_new").val(s_name);

var error = '';


s_no_val_no =(s_no.length);
if((s_no_val_no < 9) || (s_no_val_no>11)){
	error += "<li>Please Enter Phone number at  Row</li>";

$("#s_no").css("border","1px solid red");
}else{
$("#s_no").css("border","1px solid #E2E5E8");
}

if(s_name==""){
$("#firstname").css("border","1px solid red");

	error += "<li>Please Sender name</li>";
		

}else{
	$("#firstname").css("border","1px solid #E2E5E8");

}


		

		count = 1;

		$('.r_firstname').each(function(){
my_advc_id = $(this).attr('id');
my_advc_id = my_advc_id.replace(/\D/g,'');

			if($(this).val() == '')
			{

				error += "<li>Please Fill Reciever firstname, on place no: "+count+" Row</li>";
				$("#r_firstname"+my_advc_id+"").css("border","1px solid red");
				
			}else{
				
				$("#r_firstname"+my_advc_id+"").css("border","1px solid #E2E5E8");
			}

			count = count + 1;

		});	
		

		count = 1;

		$('.r_no').each(function(){
			r_no_val =$(this).val();
			 r_no_val_no =(r_no_val.length);
			 my_advc_id = $(this).attr('id');
            my_advc_id = my_advc_id.replace(/\D/g,'');

			if((r_no_val_no < 9) || (r_no_val_no>11)){
               error += "<li>Please Fill Firstname"+count+" Row</li>";
				$("#r_no"+my_advc_id+"").css("border","1px solid red");
			}else{
							$("#r_no"+my_advc_id+"").css("border","1px solid #E2E5E8");
			}
			count = count + 1;


		});	
	





count = 1;
		$("select[name='routs[]']").each(function(){

			if($(this).val() == 'Choose Route')
			{
				
			
				
             my_advc_id = $(this).attr('id');
my_advc_id = my_advc_id.replace(/\D/g,'');
				error += "<li>Please Fill Firstname"+count+" Row</li>";
				$(".route"+my_advc_id+"").css("border","1px solid red");
				
			}else{   
  			
				$(".route"+my_advc_id+"").css("border","1px solid #E2E5E8");
			}

			count = count + 1;

		});
	
		count = 1;
		$("select[name='status_em[]']").each(function(){

my_advc_id = $(this).attr('id');
my_advc_id = my_advc_id.replace(/\D/g,'');

			if($(this).val() == 'Payment Status')
			{
             
		

				error += "<li>Please Fill Firstname"+count+" Row</li>";
				$(".status_em"+my_advc_id+"").css("border","1px solid red");
				
			}else{   
				$(".status_em"+my_advc_id+"").css("border","1px solid #E2E5E8");
			}

			count = count + 1;

		});

	
		

		count = 1;

		$('.quantity').each(function(){
my_advc_id = $(this).attr('id');
my_advc_id = my_advc_id.replace(/\D/g,'');

			if($(this).val() == '')
			{

				error += "<li>Please Fill Firstname"+count+" Row</li>";
				
				$("#quantity"+my_advc_id+"").css("border","1px solid red");
				
			}else{
				
				$("#quantity"+my_advc_id+"").css("border","1px solid #E2E5E8");
			}

			count = count + 1;

		});	
		

		count = 1;

		$('.amount_fees').each(function(){
my_advc_id = $(this).attr('id');
my_advc_id = my_advc_id.replace(/\D/g,'');

			if($(this).val() == '')
			{
			
				error += "<li>Please Fill Firstname"+count+" Row</li>";
				$("#amount_new"+my_advc_id+"").css("border","1px solid red");
				
			}else{
				$("#amount_new"+my_advc_id+"").css("border","1px solid #E2E5E8");
				
				if(error == '')
		{
			
			status_type =  $( "#status_em"+my_advc_id+" option:selected" ).text();
				route_name =  $( "#trip"+my_advc_id+" option:selected" ).text();
				amount_pin = $(this).val();
				amount_with_status = "<tr style='border-bottom:1px solid grey;border-top:1px solid grey;'><td>"+route_name+"</td><td>"+status_type+"</td><td>"+amount_pin+"</td></tr>";
				final_info_read.push(amount_with_status);
				amount_pin = amount_pin.replace(/,/g,"");
				final_info.push(amount_pin);	
		}else{
			
			
		}
			
				
				
				
		
			}

			count = count + 1;

		});	
		
		
		var total = 0;
for (var i = 0; i < final_info.length; i++) {
    total += final_info[i] << 0;
}
		
		

		if(error == '')
		{

const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
	  confirmButton: "btn btn-danger",
     cancelButton: "btn btn-success"
    
  },
  buttonsStyling: false
});
swalWithBootstrapButtons.fire({
 title: "<strong>Clarification</strong>",
  icon: "info",
    html: "<table class='table'style='width:100%;'><tr style='border-bottom:1px solid grey;'><th>Route</th><th>Status</th><th>Ammount</th></tr> "+final_info_read+"<tfoot><td></td><td><strong>Total</strong></td><td><strong>"+total+"</strong></td></tfoot></table>",

  icon: "warning",
  showCancelButton: true,
  
  reverseButtons: true,focusConfirm: false,
  
  cancelButtonText: `
   <i class="uil uil-check me-2"></i> Procced
  `,
   confirmButtonText: `
    <i class="uil uil-exclamation-octagon me-2"></i> Cancel
  `,
}).then((result) => {
  if (result.isConfirmed) {

final_info.length = 0;
final_info_read.length = 0;
  } else if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
  ) {

var form_data = $(this).serializeArray();
var scode = $("#scode").val();
var s_no = $("#s_no").val();

form_data.push({ name: 'scode', value: scode });
form_data.push({ name: 's_no', value: s_no  });


$.ajax({

				url:"insert_script.php",

				method:"POST",

				data:form_data,

				beforeSend:function()
	    		{

	    		$("#submit_button").attr("disabled", true);
	    		$("#submit_button").val("inserting....");
	    		},

				success:function(data)
				{
	    		$("#submit_button").attr("disabled", false);
	    		$("#submit_button").val("Register");
final_info.length = 0;
final_info_read.length = 0;
				 $("#rgform").trigger("reset");
							 alertify.success("Registered");
alertify.set('notifier','position', 'bottom-right');
											
$("#insert").attr("disabled", false);
$("#saving").text("Save");
$("#empTable").DataTable().ajax.reload();
			window.open("print_ticket_mulitple.php?id="+data);
			

$('#item_table').find('tr:gt(5)').remove();

	
							




				}
			})
  }
});
		

		}
		else
		{
		Swal.fire({
  title: "Blank",
  text: "Fill all information",
  icon: "error"
});	
		}

		
		

	});