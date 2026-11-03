         $(document).ready(function () {
			 
                $("#searchBox22").keyup(function () {
					$(".search_images_gif").show();
                    var query = $("#searchBox22").val();

                    if (query.length > 0) {
                        $.ajax(
                            {
                                url: 'searching.php',
                                method: 'POST',
                                data: {
                                    search: 1,
                                    q: query
                                },
                                success: function (data) {
									$(".search_images_gif").hide();
                                    $("#response").html(data);
                                },
                                dataType: 'text'
                            }
                        );
                    }
                });

            });