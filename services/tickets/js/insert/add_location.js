$(document).ready(function () {
	$("#show").click(function () {
		$("#route_add_area").toggle();

		$("#save_location").off("click.srLocation").on("click.srLocation", function () {
			var l_name = $.trim($("#l_name").val() || "");
			if (l_name === "") {
				swal("", "Please enter location name.", "warning");
				return;
			}
			$("#save_location").text("saving...");
			$.ajax({
				url: "save_location.php",
				method: "POST",
				data: { l_name: l_name },
				success: function (response) {
					$("#l_name").val("");
					if ($.trim(response) === "succesfull") {
						$("#save_location").text("Save");
						swal({ text: "Location saved.", timer: 1000, showConfirmButton: false });
						$("#starting_point, #end_point").append('<option value="">' + l_name + "</option>");
					} else {
						swal("Found", "Location already exists.", "warning");
						$("#save_location").text("Save");
					}
				},
				error: function () {
					$("#save_location").text("Save");
					swal("", "Could not save location.", "error");
				}
			});
		});
	});
});
