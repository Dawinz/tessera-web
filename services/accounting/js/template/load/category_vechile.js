$(document).ready(function(){
  
start_date = $("#start_date"). val();
end_date = $("#end_date"). val();
category_no="";
vehicle_category = "";
 selected_currency = $("#selected_currency").val();
$("#category_properites").val("category");
category_properites =   $("#category_properites").val();
category();
category_table(start_date,end_date,category_no,vehicle_category,category_properites,selected_currency);


$(".categoryies").click(function(){
  $("#category_properites").val("category");
category();
category_properites =   $("#category_properites").val();
$("#array_no").text("");
  $("#array_buttons").html("");
  category_no="";
category_table(start_date,end_date,category_no,vehicle_category,category_properites,selected_currency);
});
$(".particular").click(function(){
  $("#category_properites").val("particular");
particular();

category_properites =   $("#category_properites").val();
$("#array_no").text("");
  $("#array_buttons").html("");
  category_no="";
category_table(start_date,end_date,category_no,vehicle_category,category_properites,selected_currency);
});

$("#search").keyup(function(){

  var value = $(this).val().toLowerCase();
  var value = $(this).val().toLowerCase();
    $("#myList li").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });



function category(){
    $.ajax({
    url:"load/category_group.php",
    method:"POST",
    data:{},
	   beforeSend: function(){
$("#load_category").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:1100px'></span></p>"); 
            },
    success:function(data){
$("#load_category").html(data);

    }
   }) 
}
function particular(){
 
    $.ajax({
    url:"load/particular_group.php",
    method:"POST",
    data:{},
	   beforeSend: function(){
		
$("#load_category").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:1100px'></span></p>"); 
		
            },
    success:function(data){
$("#load_category").html(data);

    }
   }) 
}

function category_table(start_date,end_date,category_no,vehicle_category,category_properites,selected_currency){
category_no="";

 	 $.ajax({
    url:"load/category_table_filter.php",
    method:"POST",
    data:{start_date:start_date,end_date:end_date,category_no:category_no,vehicle_category:vehicle_category,category_properites:category_properites,selected_currency:selected_currency},
	   beforeSend: function(){
$("#load_table").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:1100px'></span></p>"); 	
            },
    success:function(data){
$("#load_table").html(data);

    }
   })
}



});