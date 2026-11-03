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

	$("#search").on("click", function () {
		var main_route = $("#main_route option:selected").text();
		var starting_point = $("#starting_point option:selected").text();
		var end_point = $("#end_point option:selected").text();
		var currency = $("#currency option:selected").text();
		var fair = $.trim($("#fair").val() || "");
		var initial = $.trim($("#initial").val() || "");
		var details = $.trim($("#details").val() || "");
		var a_aft = $.trim($("#a_aft").val() || "");
		var a_time = $.trim($("#a_time").val() || "");
		var d_ptime = $.trim($("#d_ptime").val() || "");

		if (main_route === "Choose Main Route") {
			swal("", "Please select main route.", "warning");
			return;
		}
		if (starting_point === "Choose Starting Point") {
			swal("", "Please select start point.", "warning");
			return;
		}
		if (end_point === "Choose Ending Point") {
			swal("", "Please select end point.", "warning");
			return;
		}
		if (starting_point === end_point) {
			swal("", "Start and end points must differ.", "warning");
			return;
		}
		if (currency === "Choose Currency") {
			swal("", "Please select currency.", "warning");
			return;
		}
		if (fair === "") {
			swal("", "Please enter amount.", "warning");
			return;
		}
		if (initial === "") {
			swal("", "Please enter initial.", "warning");
			return;
		}
		if (a_aft === "") {
			swal("", "Please enter advance booking days.", "warning");
			return;
		}
		if (a_time === "") {
			swal("", "Please enter arrival time.", "warning");
			return;
		}
		if (d_ptime === "") {
			swal("", "Please enter departure time.", "warning");
			return;
		}

		$("#search").text("registering...").prop("disabled", true);
		$.ajax({
			url: "save_sub_route.php",
			method: "POST",
			data: {
				main_route: main_route,
				starting_point: starting_point,
				end_point: end_point,
				fair: fair,
				initial: initial,
				details: details,
				a_aft: a_aft,
				a_time: a_time,
				d_ptime: d_ptime,
				currency: currency
			},
			success: function (response) {
				$("#form-event").trigger("reset");
				$("#search").text("Register").prop("disabled", false);
				$("#example").DataTable().ajax.reload();
				if ($.trim(response) === "succesfull") {
					swal({ text: "Sub-route saved.", timer: 1000, showConfirmButton: false });
					$("#myModal").modal("hide");
				} else {
					swal("Found", "Sub-route already exists.", "warning");
				}
			},
			error: function () {
				$("#search").text("Register").prop("disabled", false);
				swal("", "Could not save sub-route.", "error");
			}
		});
	});
});
