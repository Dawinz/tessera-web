$(".add_particular").click(function(){
	$("#modal_assigne").modal("show");
	id="";
	    $.ajax(
                            {
                                url: 'search/particular.php',
                                method: 'POST',
                                data: {
								   id:id
								
                                },
								  beforeSend: function(){

            
            },
                                success: function (data) {
									

					$("#particular_load").html(data);

                                },
                                dataType: 'text'
                            }
                        );
	
});