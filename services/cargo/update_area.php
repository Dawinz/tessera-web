<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
										include'connect.php';
											$select1=mysqli_query($db,"SELECT *  FROM  notification WHERE notification='changes' and status='not changed' ORDER BY id Desc limit 5");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $uploader=$get13['uploader']; 
	        $sid = $get13['s_id'];
			$code = $get13['code'];
			$id =  $get13['id'];
			$update_v =  $get13['update_v'];
echo '
	<a href="edit_luggages.php?u='.$sid.'"> 
                                            <div class="hd-message-sn"style="border-left:2px solid #bf1004;padding-left:5px;margin:10px;">
                                               
                                                <div class="hd-mg-ctn" style="margin:5px;">
                                                    <h3>'.$uploader.'</h3>
                                                    <p>please update luggage No '.$code.'</p> 
													<p>'.$update_v .'</p>
                                                </div>
                                            </div>
                                        </a>
';

	}
}else{
	echo "no new notifcaton";
}
										?>