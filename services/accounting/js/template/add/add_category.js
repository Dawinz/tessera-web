$(document).ready(function(){
const unseleced_array = [];
const unseleced_buttons = [];
$(".category_selected").click(function(){
   
start_date = $("#start_date"). val();
end_date = $("#end_date"). val();
category_no = $("#array_no").text();
routes = $("#route_name").val();
category_properites =   $("#category_properites").val();
 selected_currency = $("#selected_currency").val();


    if($(this).hasClass('selected')){
$(this).removeClass("selected");  
id = $(this).data("id");
name = $(this).data("name");
item_button = '<span class="badge badge-danger button'+id+' item_button" data-id = "'+id+'" data-name = "'+name+'"style="margin:3px;"> <span style="color:white;">'+name+' X</span></span>';
remove_items(id,unseleced_array,unseleced_buttons,item_button);

  
    }else{
$(this).addClass("selected");  
id = $(this).data("id");
name = $(this).data("name");
item_button = '<span class="badge badge-danger button'+id+' item_button" data-id = "'+id+'" data-name = "'+name+'"style="margin:3px;"> <span style="color:white;">'+name+' X</span></span>';

add_item_array (id,unseleced_array,unseleced_buttons,item_button);
  $("#array_no").text(unseleced_array);
  $("#array_buttons").html(unseleced_buttons);
    }
start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
  category_no = $("#array_no").text();
   routes = $("#route_name").val();
category_table(start_date,end_date,category_no,routes,category_properites,selected_currency)
});

function add_item_array(id,unseleced_array,unseleced_buttons,item_button){
   unseleced_array.push(id);
   unseleced_buttons.push(item_button);
}
function remove_items(id,unseleced_array,unseleced_buttons,item_button){
    
const index = unseleced_array.indexOf(id);
if (index > -1) {
    unseleced_array.splice(index, 1);
}

const index_button = unseleced_buttons.indexOf(item_button);
if (index_button > -1) {
    unseleced_buttons.splice(index_button, 1);
}


$("#array_no").text(unseleced_array);
$("#array_buttons").html(unseleced_buttons);
}

    $(document).on('click', '.item_button', function(){
id = $(this).data("id");
name = $(this).data("name");
item_button = '<span class="badge badge-danger button'+id+' item_button" data-id = "'+id+'" data-name = "'+name+'"style="margin:3px;"> <span style="color:white;">'+name+' X</span></span>';
remove_items(id,unseleced_array,unseleced_buttons,item_button);
$(".selected_item"+id+"").removeClass("selected");  

});

function category_table(start_date,end_date,category_no,routes,category_properites,selected_currency){

 	 $.ajax({
    url:"load/category_table.php",
    method:"POST",
    data:{start_date:start_date,end_date:end_date,category_no:category_no,routes:routes,category_properites:category_properites,selected_currency:selected_currency},
	   beforeSend: function(){
$("#load_table").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:1100px'></span></p>"); 	
            },
    success:function(data){
$("#load_table").html(data);

    }
   })
}
});