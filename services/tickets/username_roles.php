        

		<ul class="list-group list-group-flush">
								<?php
												include 'connect.php';
												$username=$_GET['username'];
												$file="";
											
$select1=mysqli_query($db,"SELECT *  FROM  pages ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $id =$get13['id']; 
 $detail =$get13['detail']; 
 $page_name =$get13['page_name'];
 $page =$get13['page']; 
 $file = '';

												$select12=mysqli_query($db,"SELECT *  FROM  roles_assigned where file ='$page' and username='$username'");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){
$file =$get132['file']; 
}}

												?>		
     <li class="list-group-item py-3 px-0">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 me-3">
                                                                        <div>
			<input <?php if($file==$page){ echo "checked";}else{}?> type="checkbox" class="form-check-input" id="change<?php echo $id;?>">

                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <h5 class="font-size-14 mb-1 text-truncate"><a href="#" class="text-dark"><?php echo $detail;?></a></h5>
                                                                        <ul class="list-inline font-size-13 text-muted">
                                                                            <li class="list-inline-item">
                                                                                <a href="#" class="text-muted">
                                                                                    <i class="uil uil-user me-1"></i> <?php echo $page_name;?>
                                                                                </a>
                                                                            </li>
                                                                            <li class="list-inline-item">
                                                                                <i class="uil uil-calender me-1"></i><?php echo "$file";?>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                 
                                                                </div>
                                                            </li>
															

  
															  <script>
        $(document).ready(function(){
$("#change<?php echo $id;?>").click(function(){
	
	if ($('#change<?php echo $id;?>').prop('checked')) {
    //blah blah


$("#change<?php echo $id;?>").val("on");
}else{
		
		$("#change<?php echo $id;?>").val("");
}
var username_names = $("#username_names option:selected" ).text();

	if(username_names=="List of user in the system"){
 swal("", "Choose username !", "warning");

			 control.disabled="disabled";	}
	
	var change= $("#change<?php echo $id;?>").val();
	var file="<?php echo $page;?>";
	$.ajax({
				url:"change.php",
				method:"POST",
				data:{change:change,file:file,username_names:username_names},
				success:function(response){
if($.trim(response) == "succesfull"){
					
							  swal({
  
    text: "update Succefull.",
    timer: 2000,
    showConfirmButton: false
  });
			 $("#load_username_roles").load("username_roles.php?username="+username_names);

  $("#register_user").text("Register");
						}else{
							swal("Found", "Trip found !", "warning");
						}	}
			
			});
});


        });
        </script>
															<?php
															}}
															?>
															</ul>