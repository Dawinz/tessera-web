				<div class="breadcomb-icon">
										                         <div class="email-statis-wrap">
                                <div class="email-round-nock">
                                    <input type="text" class="knob" value="0" data-rel="
									<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where    trip_date >= '$date_vehicle' and trip_date <= '$date_vehicle' and bus='$name_vehicle'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t = $paid + $to_be;
			
			
		}}else{
			echo "Nothing";
		}?>
									<?php
									include 'connect.php';
			 include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where    trip_date >= '$date_vehicle' and trip_date <= '$date_vehicle' and bus='$name_vehicle' and checked='out'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid2=$data['paid'];
				$to_be2 =$data['to_be'];
				$total_amount_t2 = $paid2 + $to_be2;
			$full_amount = ($total_amount_t2 / $total_amount_t) * 100 ;
			$full_amount = round($full_amount, 0);
			echo $full_amount;
		}}else{
			echo "Nothing";
		}
?>" data-linecap="round" data-width="120" data-bgcolor="#E4E4E4" data-fgcolor="#00c292" data-thickness=".10" data-readonly="true">
                              </div>
                           
                            </div>
									</div>
									<div class="breadcomb-ctn">
										<h2>
<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where    trip_date >= '$date_vehicle' and trip_date <= '$date_vehicle' and bus='$name_vehicle'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid=$data['paid'];
				$to_be =$data['to_be'];
				$total_amount_t = $paid + $to_be;
				echo number_format($total_amount_t ,2);
			
		}}else{
			echo "Nothing";
		}?>
										Total Manifest Ammount  </h2>
										<p>Only check if the manifest has been received <span class="bread-ntd"></span></p>
										
									<p>
								        <div class="hd-message-info hd-task-info">
                                        <div class="skill">
                                            <div class="progress"style="height:9px;">
                                                <div class="lead-content">
                                                    <p><strong><span>
													<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid , SUM(to_be) AS  to_be  FROM manifest   where    trip_date >= '$date_vehicle' and trip_date <= '$date_vehicle' and bus='$name_vehicle' and checked='out'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid2=$data['paid'];
				$to_be2 =$data['to_be'];
				$total_amount_t2 = $paid2 + $to_be2;
				echo number_format($total_amount_t2 ,2);
			
		}}else{
			echo "Nothing";
		}?>
													</span></strong>Total amount of recieved Cargos</p>
                                                </div>
												<?php
												$full_amount = ($total_amount_t2 / $total_amount_t) * 100 ;
												?>
												<?php $full_amount = round($full_amount, 0); 
												$full_amount = round($full_amount, 0);
											
												?>
                                                <div class="progress-bar wow fadeInLeft" data-progress="<?php echo $full_amount;?>%" style="width: <?php echo $full_amount;?>%;height:9px;" data-wow-duration="1.5s" data-wow-delay="1.2s"> <span><?php echo $full_amount;?>%</span>
                                                </div>
                                            </div>  <div class="progress"style="height:9px;">
                                                <div class="lead-content">
                                                    <p>
													<span><strong><?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM manifest   where    trip_date >= '$date_vehicle' and trip_date <= '$date_vehicle' and bus='$name_vehicle' and checked='out'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_be=$data['to_be'];
				echo number_format($amount_be,2);
			
		}}else{
			echo "Nothing";
		}
								?>
									<?php
												$to_be_amount = ($amount_be / $total_amount_t) * 100 ;
												?>
												<?php $to_be_amount = round($to_be_amount, 0); 
										
											
												?>
								</strong></span> Total To be paid Ammount </p>
                                                </div>
                                                <div class="progress-bar wow fadeInLeft" data-progress="<?php echo $to_be_amount;?>%" style="width: <?php echo $to_be_amount;?>%;height:9px;" data-wow-duration="1.5s" data-wow-delay="1.2s"> <span><?php echo $to_be_amount;?>%</span>
                                                </div>
                                            </div>  <div class="progress"style="height:9px;">
                                                <div class="lead-content">
                                                    <p>
													<span><strong><?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid FROM manifest   where    trip_date >= '$date_vehicle' and trip_date <= '$date_vehicle' and bus='$name_vehicle' and checked='out'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount_paid=$data['paid'];
				echo number_format($amount_paid,2);
			
		}}else{
			echo "Nothing";
		}
								?></strong> </span> Total Payed Ammount
													</p>
													<?php
												$paid_amount = ($amount_paid / $total_amount_t) * 100 ;
												?>
												<?php $paid_amount = round($paid_amount, 0); 
										
											
												?>
                                                </div>
                                                <div class="progress-bar wow fadeInLeft" data-progress="<?php echo $paid_amount;?>%" style="width: <?php echo $paid_amount;?>%;height:9px;" data-wow-duration="1.5s" data-wow-delay="1.2s"> <span><?php echo $paid_amount;?>%</span>
                                                </div>
                                            </div>
                                   
                                      
                                      
                                        </div>
                                    </div>
									</p>
								
									</div>