    <ul id="myList" style="list-style-type: none; margin: 0;padding: 0;">
    <?php
    include '../connect/connect.php';
    			$select_paticular=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id ASC 
	) row_num,
    particulars_detail,id,name

FROM 
	particular_category 
ORDER BY 
	id ASC 
		
		");
$select_num_paticular=mysqli_num_rows($select_paticular);
if($select_num_paticular !=0){
   	WHILE($get_paticular=mysqli_fetch_array($select_paticular)){
    	    $row_num =$get_paticular['row_num'];
    	    $name =$get_paticular['name'];
    	    $id =$get_paticular['id'];
    	    $particulars_detail =$get_paticular['particulars_detail'];

$string = $particulars_detail;
$length = strlen($string);

if($length >35){
$particular_detail_new = "".substr($particulars_detail, 0, 35)."...";
}else{
    $particular_detail_new =  $particulars_detail ;
}

   if($particulars_detail==""){

   }else{
    ?>
     <li style=""class="az-contact-item  category_selected selected_item<?php echo $id;?> " data-id="<?php echo $id;?>" data-name="<?php echo $name;?>">
            
              <div class="az-contact-body ">
                <h6><?php echo $name;?></h6>
                <span class="phone"><?php echo $particular_detail_new;?></span>
              </div><!-- az-contact-body -->
             
   </li>
    <?php
   }
    }}
            ?>
            </ul>
            <script src="/accounts/js/template/add/add_category.js"></script>