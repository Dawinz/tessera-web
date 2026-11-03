$(document).ready(function() {


	
	
    $('.js-example-basic-single').select2();
	
	   $('.accordion-toggle').on('click', function(event){
		   $('.accordion-content').toggle( "fast", function() {
    // Animation complete.
  });
    	

	var imgSrc = $(".down_image").attr('src');
	
	 if (imgSrc == 'images/up-arrows5.png') {
		 
		   $('.down_image').attr('src','images/arrow_up.png');
	 }else{
		 
		   $('.down_image').attr('src','images/up-arrows5.png');
	 }
	
	
    });
	
	  $('.search_table').hover(
			

			
               function () {
				 
				   $('#load_seat_chart').removeClass("stop");
				   	    var className = $("#load_seat_chart").attr("class");
          		   $('.accordion-content').show( "fast", function() {
    
  });
			 if (imgSrc == 'images/up-arrows5.png') {
		 
		   $('.down_image').attr('src','images/arrow_up.png');
	 }else{
		 
		   $('.down_image').attr('src','images/up-arrows5.png');
	 }	  


               }, 
				
               function () {
  
               }
            );
	
	

});
