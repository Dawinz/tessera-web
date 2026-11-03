	    start_date =  $("#start_date").val();
            end_date =  $("#end_date").val();
             selected_currency = $("#currency").val();
category_selected =$("#vehicle_cat").val();
symbol =$("#symbol").val();
    fetch_data(start_date,end_date,selected_currency,category_selected,symbol);

    $(".change_r").click(function(){
			my_id = $(this).attr('id');
			my_name = $(this).attr('data');
            start_date =  $("#start_date").val();
            end_date =  $("#end_date").val();
			$(".cname_route").text(my_name);
			this_check = $(this).hasClass("btn-indigo");
			
				$( ".first_b" ).removeClass( "btn-indigo" );
				$( ".first_b" ).removeClass( "btn-dark" );
				$( ".first_b" ).addClass( "btn-dark" );
			$( this).removeClass( "btn-dark" );
			$( this).addClass( "btn-indigo" );
       vehicle_cat = $(this).data("vehicl_cat");
	$("#vehicle_cat").val(vehicle_cat);

    start_date =  $("#start_date").val();
end_date =  $("#end_date").val();
selected_currency = $("#currency").val();
category_selected =$("#vehicle_cat").val();
fetch_data(start_date,end_date,selected_currency,category_selected,symbol);
		});  
        

$(".prev").click(function(){

  $(".next").attr("disabled", false);
  start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
  const  date = moment(start_date).format('YYYY-MM-DD');
  const new_date = new Date(date);
 new_date.setMonth(new_date.getMonth() - 1);
 new_date2= new_date.toISOString();
 new_start_date = new_date2.split('T')[0];
const  new_og_st_date = moment(new_start_date).format('MM/DD/YYYY');
$("#start_date").val(new_og_st_date);
const new_og_end_date = moment(new_og_st_date).endOf('month').format('MM/DD/YYYY');
$("#end_date").val(new_og_end_date);

   start_date =  $("#start_date").val();
end_date =  $("#end_date").val();
selected_currency = $("#currency").val();
category_selected =$("#vehicle_cat").val();
fetch_data(start_date,end_date,selected_currency,category_selected,symbol);
});



$(".next").click(function(){
    category_properites =   $("#category_properites").val();
  start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
const  date = moment(start_date).format('YYYY-MM-DD');
  const new_date = new Date(date);
 new_date.setMonth(new_date.getMonth() + 1);
 new_date2= new_date.toISOString();
 new_start_date = new_date2.split('T')[0];
const  new_og_st_date = moment(new_start_date).format('MM/DD/YYYY');
$("#start_date").val(new_og_st_date);

const new_og_end_date = moment(new_og_st_date).endOf('month').format('MM/DD/YYYY');
$("#end_date").val(new_og_end_date);
   start_date =  $("#start_date").val();
end_date =  $("#end_date").val();
selected_currency = $("#currency").val();
category_selected =$("#vehicle_cat").val();
fetch_data(start_date,end_date,selected_currency,category_selected,symbol);

});

$(".selected_duration").click(function(){
category_properites =   $("#category_properites").val(); 
duration =   $(this).data("month");
duration = parseInt(duration);
var currentdate = new Date(); 
  if (currentdate.getMonth() < 2) {
month = (currentdate.getMonth()+1);
  }else{
  month = '0'+ (currentdate.getMonth()+1) ;
  }
        
 
var datetime = + month + "/" 
                + (currentdate.getDate()+1)+ "/"  
                + currentdate.getFullYear() ;


var start_month_end_date = + month + "/" 
                + (1)+ "/"  
                + currentdate.getFullYear() ;

end_date = datetime;
const  date = moment(start_month_end_date).format('YYYY-MM-DD');
  const new_date = new Date(date);
 new_date.setMonth(new_date.getMonth() - duration);
 new_date2= new_date.toISOString();
 new_start_date = new_date2.split('T')[0];
const  new_og_st_date = moment(new_start_date).format('MM/DD/YYYY');
$("#start_date").val(new_og_st_date);
$("#end_date").val(end_date);
   start_date =  $("#start_date").val();
end_date =  $("#end_date").val();
selected_currency = $("#currency").val();
category_selected =$("#vehicle_cat").val();
fetch_data(start_date,end_date,selected_currency,category_selected,symbol);
});



 $(".currency_selected").click(function(){
currency_selected = $(this).data("currency_data");
currency_symbol = $(this).data("currency_symbol");
$("#currency").val(currency_selected);
$("#symbol").val(currency_symbol);

start_date =  $("#start_date").val();
end_date =  $("#end_date").val();
selected_currency = $("#currency").val();
category_selected =$("#vehicle_cat").val();
fetch_data(start_date,end_date,selected_currency,category_selected,symbol);

})
        
 $("#start_date").change(function(){
start_date =  $("#start_date").val();
end_date =  $("#end_date").val();
selected_currency = $("#currency").val();
category_selected =$("#vehicle_cat").val();
fetch_data(start_date,end_date,selected_currency,category_selected,symbol);
        })	; 
 $("#end_date").change(function(){
start_date =  $("#start_date").val();
end_date =  $("#end_date").val();
selected_currency = $("#currency").val();
category_selected =$("#vehicle_cat").val();
fetch_data(start_date,end_date,selected_currency,category_selected,symbol);

        })	; 


		 function fetch_data(start_date,end_date,selected_currency,category_selected,symbol)
{
	 
	  	 $.ajax({
    url:"load/vehicle_category.php",
    method:"POST",
    data:{start_date:start_date,end_date:end_date,selected_currency:selected_currency,category_selected:category_selected,symbol:symbol},
	   beforeSend: function(){
$("#load_table").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:1100px'></span></p>"); 	
            },
    success:function(data){
$("#load_table").html(data);

    }
   })

}