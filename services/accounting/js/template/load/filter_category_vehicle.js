$(document).ready(function(){
  

	$("#start_date").change(function(){
    category_properites =   $("#category_properites").val();
  start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
  category_no = $("#array_no").text();
 vehicle_category = $("#vehicle_category").val();
 selected_currency = $("#selected_currency").val();
     const  start_date_holder = moment(start_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(end_date).format('Do/MMM/YYYY');


category_table(start_date,end_date,category_no,vehicle_category,category_properites,selected_currency);


  });
	$("#end_date").change(function(){
category_properites =   $("#category_properites").val();
start_date = $("#start_date"). val();
end_date = $("#end_date"). val();
category_no = $("#array_no").text();
 vehicle_category = $("#vehicle_category").val();
const  start_date_holder = moment(start_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(end_date).format('Do/MMM/YYYY');
category_table(start_date,end_date,category_no,vehicle_category,category_properites,selected_currency);

 	
  });
	$("#vehicle_category").change(function(){
    category_properites =   $("#category_properites").val();
start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
  category_no = $("#array_no").text();
  vehicle_category = $("#vehicle_category").val();
  
 	category_table(start_date,end_date,category_no,vehicle_category,category_properites,selected_currency);
  });


$(".prev").click(function(){
  category_properites =   $("#category_properites").val();
  $(".next").attr("disabled", false);
  start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
//start date
  const  date = moment(start_date).format('YYYY-MM-DD');
  const new_date = new Date(date);
 new_date.setMonth(new_date.getMonth() - 1);
 new_date2= new_date.toISOString();
 new_start_date = new_date2.split('T')[0];
const  new_og_st_date = moment(new_start_date).format('MM/DD/YYYY');
$("#start_date").val(new_og_st_date);
const new_og_end_date = moment(new_og_st_date).endOf('month').format('MM/DD/YYYY');
$("#end_date").val(new_og_end_date);

 const  start_date_holder = moment(new_og_st_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(new_og_end_date).format('Do/MMM/YYYY');
$("#start_date_holder").text(start_date_holder);
$("#end_date_holder").text(end_date_holder);


  category_no = $("#array_no").text();
  vehicle_category = $("#vehicle_category").val();
 	category_table(new_og_st_date,new_og_end_date,category_no,vehicle_category,category_properites,selected_currency);
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
$("#start_date_holder").text(start_date_holder);
$("#end_date_holder").text(end_date_holder);
category_no = $("#array_no").text();
  vehicle_category = $("#vehicle_category").val();
 	category_table(new_og_st_date,new_og_end_date,category_no,vehicle_category,category_properites,selected_currency);


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

category_no = $("#array_no").text();
  vehicle_category = $("#vehicle_category").val();
 	category_table(new_og_st_date,end_date,category_no,vehicle_category,category_properites,selected_currency);

});


function category_table(start_date,end_date,category_no,vehicle_category,category_properites,selected_currency){
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


$(".currency_selected").click(function(){
 selected_currency = $(this).data("currency_data");
$("#selected_currency").val(selected_currency);
  category_properites =   $("#category_properites").val();
start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
  category_no = $("#array_no").text();
  vehicle_category = $("#vehicle_category").val();
  
category_table(start_date,end_date,category_no,vehicle_category,category_properites,selected_currency);
})
});