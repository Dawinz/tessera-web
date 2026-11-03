
<script>
	function myFunction(route_name,username) {
                        $.ajax(
                            {
                                url: 'search/assign_route.php',
                                method: 'POST',
                                data: {
								   route_name:route_name,
								   username:username
								
                                },
								  beforeSend: function(){
            	$("#load_assigned").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:150px'></span></p>"); 

            
            },
                                success: function (data) {
									

					$("#load_assigned").html(data);

                                },
                                dataType: 'text'
                            }
                        );
                    
			}	
</script>
   <div class="">
      <div class="container">
        <div class="az-content-left az-content-left-chat">

         

       <!-- az-chat-active-contacts -->

          <div id="azChatList" class="az-chat-list">
            
              <?php
			  include 'connect/connect.php';
 
  $users = $_GET['u'];




 
 	$select=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id ASC 
	) row_num,
    fullname,id,start_point,end_point


FROM 
	route where status='Main'
ORDER BY 
	id ASC 
		
		");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $start_point =$get['start_point'];
    	    $end_point =$get['end_point'];
    	    $fullname =$get['fullname'];
    	    $id =$get['id'];
    	    $row_num =$get['row_num'];
		

	
?><div id="choose_one<?php echo $id;?>" class="media choose_one <?php if($row_num==1){ echo "selected";}?>">
              <div class="media-body">
                <div class="media-contact-name">
                  <h5 id="route_name<?php echo $id;?>"><?php echo $fullname;?></h5>
                </div>
                
              </div><!-- media-body -->
            </div>  
              	<script>
 $("#choose_one<?php echo $id;?>").click(function(){
	  $(".choose_one").removeClass("selected");
	  $(this).addClass("selected");
	  route_name = $("#route_name<?php echo $id;?>").text();
	  username = "<?php echo $users;?>";
	  	myFunction(route_name,username) ;

	  });

	</script>
         
		<?php
		if($row_num==1){
			?>
			<script>
			  $(".choose_one").removeClass("selected");
	   $("#choose_one<?php echo $id;?>").addClass("selected");
	  route_name = $("#route_name<?php echo $id;?>").text();
	  username = "<?php echo $users;?>";
	  	myFunction(route_name,username) ;
			</script>
			<?php
		}
		
		?> 
		 
		 <?php
}}
		 ?>
            <!-- media -->
        <!-- media -->
          </div><!-- az-chat-list -->

        </div><!-- az-content-left -->
        <div class="">
          
          <div id="azChatBody" class="">
           <div id="load_assigned" style="padding:10px;"></div>
          </div><!-- az-chat-body -->
       <!-- az-chat-footer -->
        </div><!-- az-content-body -->
      </div>
    </div>
