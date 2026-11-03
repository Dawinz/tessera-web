<link href="https://code.jquery.com/ui/1.9.2/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<input type='text' class='date'>
<script src="https://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script>
$(".date")
.datepicker({
    onSelect: function(dateText) {
	alert();
        console.log("Selected date: " + dateText + "; input's current value: " + this.value);
    }
})
.on("change", function() {
    console.log("Got change event from field");
});
</script>