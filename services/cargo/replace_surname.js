         $(document).ready(function () {
                $("#firstname").keyup(function () {
                    var query = $("#firstname").val();

                    if (query.length > 0) {
                        $.ajax(
                            {
                                url: 'replace_surname.php',
                                method: 'POST',
                                data: {
                                    search: 1,
                                    q: query
                                },
                                success: function (data) {
										$('#sender').show();		
                                    $("#sender").html(data);
                                },
                                dataType: 'text'
                            }
                        );
                    }
                });
$( "body" ).click(function() {
	$('#sender').hide();						
										});
										
										
										
            });
			