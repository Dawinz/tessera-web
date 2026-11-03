$(document).ready(function(){
function showTime(){
  // to get current time/ date.
  var date = new Date();
  // to get the current hour
  var h = date.getHours();
    // to get the current minutes
  var m = date.getMinutes();
  //to get the current second
  var s = date.getSeconds();
  // AM, PM setting
  var session = "AM"; 
  
 //conditions for times behavior 
  if ( h == 0 ) {
    h = 12;
  }
  if( h >= 12 ){
     session = "PM";
     }
  
  if ( h > 12 ){
    h = h - 12;
  }
  m = ( m < 10 ) ? m = "0" + m : m;
  s = ( s < 10 ) ? s = "0" + s : s;
  
  //putting time in one variable
  var time = h + ":" + m + ":" + s + " " + session;
  //putting time in our div
  $('#clock').html(time); 
  //to change time in every seconds
  setTimeout( showTime, 1000 );

  if(time=="6:23:00 PM"){
  
    $.ajax({
      url:"update_my_charges.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
	   $.ajax({
      url:"monthly_check.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
  }
  if(time=="2:00:00 PM"){
  
    $.ajax({
      url:"update_my_charges.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
	   $.ajax({
      url:"monthly_check.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
  }  
  if(time=="4:00:00 PM"){
  
    $.ajax({
      url:"update_my_charges.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
	   $.ajax({
      url:"monthly_check.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
  }
    if(time=="8:00:00 PM"){
  
    $.ajax({
      url:"update_my_charges.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
	   $.ajax({
      url:"monthly_check.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
  }
    if(time=="9:00:00 PM"){
  
    $.ajax({
      url:"update_my_charges.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
    $.ajax({
      url:"monthly_check.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
  }
    if(time=="7:00:00 AM"){
  
    $.ajax({
      url:"update_my_charges.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
	
	   $.ajax({
      url:"monthly_check.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
  }
    if(time=="8:00:00 AM"){
  
    $.ajax({
      url:"update_my_charges.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });

     $.ajax({
      url:"monthly_check.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
  } 
     if(time=="5:00:00 AM"){
  
    $.ajax({
      url:"update_my_charges.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
    $.ajax({
      url:"monthly_check.php",
      method:"POST",
      data:{time:time},
      success:function(response){
                       

}  
    });
  }



}
showTime();

});