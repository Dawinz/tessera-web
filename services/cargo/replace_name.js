         $(document).ready(function () {
                $("#s_no").keyup(function () {
                    var query = $("#s_no").val();

                    if (query.length > 0) {
                        $.ajax(
                            {
                                url: 'replace_name.php',
                                method: 'POST',
                                data: {
                                    search: 1,
                                    q: query
                                },
                                success: function (data) {
										$('#names').show();		
                                    $("#names").html(data);
                                },
                                dataType: 'text'
                            }
                        );
                    }
                });
$( "body" ).click(function() {
	$('#names').hide();						
										});
										
										
										
            });
			