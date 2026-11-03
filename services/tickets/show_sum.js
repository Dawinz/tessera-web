		$('#show_sum').click(function(){
          $(".spinner-border").show();
$("#load_user_paid").load("load_user_paid.php");
			$("#load_user_to_be").load("load_user_to_be.php");
			$("#load_all_sum").load("load_all_sum.php");
});