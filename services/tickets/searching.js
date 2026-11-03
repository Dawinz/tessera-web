(function () {
	function bindSearch() {
		if (typeof window.jQuery === "undefined") {
			setTimeout(bindSearch, 40);
			return;
		}
		var $ = window.jQuery;
		$(document).ready(function () {
			$("#searchBox22").on("keyup", function () {
				var query = $("#searchBox22").val();
				if (query.length > 0) {
					$.ajax({
						url: "searching.php",
						method: "POST",
						data: { search: 1, q: query },
						success: function (data) {
							$("#response").html(data);
						},
						dataType: "text"
					});
				}
			});
		});
	}
	bindSearch();
})();
