         $(document).ready(function () {
                $("#driver").keyup(function () {
                    var query = $("#driver").val();

                    if (query.length > 0) {
                        $.ajax(
                            {
                                url: 'place_vehicle_name.php',
                                method: 'POST',
                                data: {
                                    search: 1,
                                    q: query
                                },
                                success: function (data) {
										$('#view_place').show();
										$('.nk-label').hide();
										$('#chide').hide();		
                                    $("#view_place").html(data);
									
                                },
                                dataType: 'text'
                            }
                        );
                    }
                });
$( "#myModalone" ).click(function() {
	$('#view_place').hide();						
										});
										
										
										
            });
			