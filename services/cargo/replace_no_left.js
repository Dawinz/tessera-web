         $(document).ready(function () {
                $("#s_no").keyup(function () {
                    var query = $("#s_no").val();

                    if (query.length > 0) {
                        $.ajax(
                            {
                                url: 'replace_no.php',
                                method: 'POST',
                                data: {
                                    search: 1,
                                    q: query
                                },
                                success: function (data) {
										$('#r_no_response').show();		
                                    $("#r_no_response").html(data);
                                },
                                dataType: 'text'
                            }
                        );
                    }
                });
$( "body" ).click(function() {
	$('#r_no_response').hide();						
										});
										
										
										
            });
			