  <?php
  include '../connect/connect.php';
  $query = $_POST['q'];
  
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $first_date = $_POST['first_date'];
 
$start_date_query="";
$end_date_query="";
 if($start_date !=""){
	  $start_date= date("Y-m-d",strtotime($start_date));
	 $start_date_query = " date='$start_date' AND ";
 }
 if($end_date !=""){
	  $end_date= date("Y-m-d",strtotime($end_date));
	 $end_date_query = " date='$end_date' AND";
 }
 
 if(($start_date !="") AND ($end_date !="") ){
	 	  $start_date= date("Y-m-d",strtotime($start_date));
		  	  $end_date= date("Y-m-d",strtotime($end_date));
			  	 $start_date_query = " date >= '$start_date' AND ";
				 	 $end_date_query = " date <= '$end_date' AND ";


 }
 if($query !=""){
	 $query = "name LIKE '%$query%' or date LIKE '%$query%' AND ";
 }else{
	 $query=="";
 }
 
 if($first_date !=""){
  $last_date = date("Y-m-t", strtotime($first_date));
 $start_date_query = " date >= '$first_date' AND ";
				 	 $end_date_query = " date <= '$last_date' AND ";
 }
// echo "$start_date_query $end_date_query $query";
  ?>
  
  <div class=" gallery-container">

    <div class="tz-gallery">

        <div class="row" style="background-color:#E8E0EA;padding:10px;">
		
		<?php
		$select=mysqli_query($db,"SELECT name,date,bootstrap  FROM   job_card WHERE  $start_date_query   $end_date_query $query 1 ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
	
   	WHILE($get=mysqli_fetch_array($select)){
    	    $name =$get['name']; 
    	    $date =$get['date']; 
    	    $bootstrap =$get['bootstrap']; 
		?>
  <div class="col-sm-12 col-md-2">
  <div class="board-wrapper pt-5" >
                  <div class="portlet-card"style="cursor: pointer;">
                    <div class="progress">
                      <div class="progress-bar <?php echo $bootstrap;?>" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="task-date"><?php echo  date("d M Y",strtotime($date));?></p>
                    <div class="action-dropdown dropdown">
                      <button type="button" class="dropdown-toggle" id="portlet-action-dropdown_8" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="typcn icon typcn-delete text-danger"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="portlet-action-dropdown_8">
                        <a class="dropdown-item" href="#">Edit</a>
                        <a class="dropdown-item" href="#">Delete</a>
                      </div>
                    </div>
<?php
if($name!=""){
?>
                    <h4 class="task-title"><?php echo $name;?></h4>

<?php	
}
?>
                  </div>    
				 
				  </div>
				  </div>  
				  
				  <?php
}}
				  ?>
				  
        
               </div>

    </div>

</div>