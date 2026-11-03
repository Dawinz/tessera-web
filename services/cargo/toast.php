<!DOCTYPE html>
<?php
$date = $_GET['id'];
$car = $_GET['car'];
include 'connect.php';
$select23=mysqli_query($db,"SELECT * FROM `manifest` WHERE bus='$car' and trip_date='$date' ORDER BY id DESC LIMIT 1 ");
$select_num23=mysqli_num_rows($select23);
if($select_num23 !=0){
   	WHILE($get123=mysqli_fetch_array($select23)){
    	   
 $code =  $get123['code'];
$tobe = number_format($get123['to_be'],2);
$paid = number_format($get123['paid'],2);


		
	}
}
?>
<html lang="en">
    <head>
     
        <meta charset="UTF-8">
        <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="toast.style.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="toast.script.js"></script>
     
    </head>
    <body>


</div>
 
        <script>
     
			
			$(document).ready(function(){
				var id =$('#id').text();
				var car =$('#car').text();
     $.Toast("Last inforamtion", " code : <?php echo $code; ?> , Paid : <?php echo $paid?>  , To Be Paid <?php echo $tobe;?>", "success", {
                    has_icon:true,
                    has_close_btn:true,
					stack: true,
                    fullscreen:true,
                    timeout:3000,
                    sticky:false,
                    has_progress:true,
                    rtl:false,
                });
			});
        </script>
        <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
    </body>
</html>