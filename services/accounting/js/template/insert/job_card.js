$("#save").click(function(){
insert_date = $("#insert_date").val();
if(insert_date==""){
	  Swal.fire("Please Enter Job card");
        control.disabled="disabled";
}

	 $.ajax({
    url:"insert/job_card.php",
    method:"POST",
    data:{insert_date:insert_date},
	   beforeSend: function(){
		
$("#save_text").text("loading...");
		
            },
    success:function(data){
alert(data);
$("#save_text").text("Save changes");
$("#save_text").notify(
 "Saved Successfull",
 "success",
  { position:"right" }
);

var query = "";
			var start_date = "";
			var end_date = "";
            var first_date = $("#month").val();
			
			
					function myFunction(query,start_date,end_date,first_date) {
				
                        $.ajax(
                            {
                                url: 'search/job_card.php',
                                method: 'POST',
                                data: {
                                    search: 1,
                                    q: query,
                                    start_date: start_date,
                                    end_date: end_date,
                                    first_date: first_date
                                },
                                success: function (data) {
					$("#response").html(data);

                                },
                                dataType: 'text'
                            }
                        );
                    
			}

				myFunction(query,start_date,first_date,first_date);

    }
   })


});