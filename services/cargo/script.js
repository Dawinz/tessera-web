$(document).ready(function() {
  clockUpdate();
  setInterval(clockUpdate, 1000);
  var clock =$('.digital-clock').text();

})

function clockUpdate() {
  var date = new Date();

  function addZero(x) {
    if (x < 10) {
      return x = '0' + x;
    } else {
      return x;
    }
  }

  function twelveHour(x) {
    if (x > 12) {
      return x = x - 12;
    } else if (x == 0) {
      return x = 12;
    } else {
      return x;
    }
  }

  var h = addZero(twelveHour(date.getHours()));
  var m = addZero(date.getMinutes());
  var s = addZero(date.getSeconds());
  
var x = document.getElementById("myAudio"); 
function playAudio() { 
  x.play(); 
} 

function pauseAudio() { 
  x.pause(); 
} 
$('#audio').click(function(){
 x.play(); 
});
  $('.digital-clock').text(h + ':' + m + ':' + s)
  if((h == 07) && (m ==52) && (s ==27)){
  x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
			alert(response);

	}
			
			});
  }else if((h == 12) && (m ==30) && (s ==00)){
	  x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});  
  }else if((h == 01) && (m ==00) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  } else if((h == 01) && (m ==30) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 02) && (m ==00) && (s ==00)){
	   x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			}); 
  }else if((h == 02) && (m ==30) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 03) && (m ==00) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 03) && (m ==30) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 04) && (m ==00) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 04) && (m ==30) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 05) && (m ==00) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 05) && (m ==30) && (s ==00)){
	  x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});  
  }else if((h == 06) && (m ==00) && (s ==00)){
	   x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			}); 
  }else if((h == 06) && (m ==30) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 07) && (m ==00) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 07) && (m ==30) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 08) && (m ==00) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 08) && (m ==30) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 09) && (m ==00) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 09) && (m ==30) && (s ==00)){
	  x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});  
  }else if((h == 10) && (m ==00) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 10) && (m ==30) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 11) && (m ==00) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 11) && (m ==30) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 12) && (m ==00) && (s ==00)){
	    x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});
  }else if((h == 12) && (m ==30) && (s ==00)){
	  x.play(); 
	  var d = new Date();
var strDate =  d.getDate()+ "/" + (d.getMonth()+1) + "/" + d.getFullYear();
  x.play(); 
  var d_time =  $('.digital-clock').text();
  $.ajax({
				url:"insert_situation.php",
				method:"POST",
				data:{d_time:d_time,strDate:strDate},
				success:function(response){
					$('.d_time').text(response);
					var timer_d =$('.d_time').text();
					
					window.setTimeout(function(){
						window.location="logs.php?time="+timer_d;
					},2000);

	}
			
			});  
  }
}