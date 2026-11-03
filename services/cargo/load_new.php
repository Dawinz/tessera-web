
   	<?php
							$route = $_GET['route'];
						
						
						?>
<style>
iframe {
    height: auto;
    overflow: scroll;
    -webkit-overflow-scrolling: touch;
} 
</style>
 <div class="curved-ctn">
 <h4 id="load"> &nbsp&nbsp <img src="images/loader.gif"> Loading daily charges chart....</h4>

                            </div>                            
							<iframe rel="preload" id="iframe"title="Monthly Chart"src="daily_chart.php?route=<?php echo $route; ?>"style="width:92%;border:none; height:600px;"></iframe>
	
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
 
		

</body>
<script>

$(document).ready(function(){




$('#iframe').on("load", function() {
   $("#load").hide();
});


});
</script>