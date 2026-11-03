          <style>
		  .dt-button.red {
   color: #fff;
  background:none;
   background-color: #0379BB;
   border-radius: 05px;
   border:none;
    }
  .dataTables_filter input {
        width: 250px;
        height: 32px;
        background: #fcfcfc;
        border: 1px solid #aaa;
        border-radius: 5px;
    
        text-indent: 10px;
    }
 
    .dataTables_filter .fa-search {
        position: absolute;
        top: 10px;
        left: auto;
        right: 10px;
    }
.dataTables_filter {
    display: none;
}
#myInputTextField
{
	width:30%;
	 position: absolute;
right:40px;
  z-index: 4;
	margin-bottom:70px;
}
	  </style>
	  	  <div class="col-lg-4"style="padding:3px;">
         <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search Code">
      </div>
	    <?php
			   $firstday = @$_GET['firstday'];
			   $lastday = @$_GET['lastday']; 
			   $username_choosen = @$_GET['trip'];
			   $route = @$_GET['route'];
		       $route = preg_replace('/_/',' ', $route);
              $username_choosen = preg_replace('/_/',' ', $username_choosen);

include 'connect.php';						
									
$select1=mysqli_query($db,"SELECT *  FROM users where fullname='$username_choosen'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
	
	if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $username_choosen=$get13['username']; 
}}}

			   ?>
<table class="table mb-0" id="empTable">
           <thead class="table-light">
              <tr>
                    
                   
					<th>Username</th>
					<th>Date</th>
				 
					<th>Store Total Ammout</th>
					<th>Manifest </th>
					<th>Total</th>
					<th>Store Paid </th>
					<th>Manifest paid </th>
					<th>Total </th>
					<th>To be paid </th>
					<th>Manifest To be paid </th>
					<th>Total </th>
					
                    
                </tr>

				
				
                </thead>
				<tbody>
								<?php
								include 'connect.php';
				$select1=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE account='$route' and  username='$username_choosen' and   date_insert >= '$firstday'  and date_insert <= '$lastday'  group by date_insert");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
	   	WHILE($get12=mysqli_fetch_array($select1)){
			$date_insert_day =$get12['date_insert']; 
			$route =$get12['account']; 
?>
<tr>
<td> <?php echo $username_choosen;?></td>
<td><?php echo $date_insert_day;?></td>

<td>
				<?php
				  include_once 'connect.php';
$sql = $db->query("SELECT SUM(amount) AS  amount FROM parcel_info  where account='$route' and  username='$username_choosen' and   date_insert = '$date_insert_day'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$store_amount=$data['amount'];
				echo number_format($store_amount,2);
			
		}}else{
			echo "Nothing";
		}
								?>

</td>
<td>

<?php
				  
$sql_manifest= $db->query("SELECT SUM(paid) AS  paid FROM  manifest   where parcel_route='$route' and  parcel_uploader='$username_choosen' and   trip_date = '$date_insert_day'  ");
		if ($sql_manifest->num_rows > 0) {
			$response = "";

			while($data = $sql_manifest->fetch_array()) {
				$manifest_amount_paid=$data['paid'];
			
			
		}}else{
			echo "Nothing";
		}
		$sql_manifest= $db->query("SELECT SUM(to_be) AS  to_be FROM  manifest   where parcel_route='$route' and  parcel_uploader='$username_choosen' and   trip_date = '$date_insert_day'  ");
		if ($sql_manifest->num_rows > 0) {
			$response = "";

			while($data = $sql_manifest->fetch_array()) {
				$manifest_amount_to_paid=$data['to_be'];
				
			
		}}else{
			echo "Nothing";
		}
		$total_amount_manifest_parcel = $manifest_amount_paid+$manifest_amount_to_paid;
		echo number_format($total_amount_manifest_parcel,2);
								?>
</td>
<td style="border-left:1px solid grey;border-right:1px solid grey"><?php echo number_format($store_amount -$total_amount_manifest_parcel,2) ?></td>
<td><?php
				 
$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info  where account='$route' and  username='$username_choosen' and   date_insert = '$date_insert_day'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$store_paid=$data['paid'];
				echo number_format($store_paid,2);
			
		}}else{
			echo "Nothing";
		}
								?></td>
<td><?php
$sql_manifest= $db->query("SELECT SUM(paid) AS  paid FROM  manifest   where parcel_route='$route' and  parcel_uploader='$username_choosen' and   trip_date = '$date_insert_day'  ");
		if ($sql_manifest->num_rows > 0) {
			$response = "";

			while($data = $sql_manifest->fetch_array()) {
				$manifest_amount_paid=$data['paid'];
			echo number_format($manifest_amount_paid,2);
			
		}}else{
			echo "0.00";
		}
?></td>
<td style="border-left:1px solid grey;border-right:1px solid grey"><?php
echo number_format($store_paid-$manifest_amount_paid,2);
?></td>
<td><?php
$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM parcel_info  where account='$route' and  username='$username_choosen' and   date_insert = '$date_insert_day'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$manifest_amount_to_paid_parcel=$data['to_be'];
				echo number_format($manifest_amount_to_paid_parcel,2);
			
		}}else{
			echo "Nothing";
		}
?></td>
<td><?php
	$sql_manifest= $db->query("SELECT SUM(to_be) AS  to_be FROM  manifest   where parcel_route='$route' and  parcel_uploader='$username_choosen' and   trip_date = '$date_insert_day'  ");
		if ($sql_manifest->num_rows > 0) {
			$response = "";

			while($data = $sql_manifest->fetch_array()) {
				$manifest_amount_to_paid_manifest=$data['to_be'];
				echo number_format($manifest_amount_to_paid_manifest,2);
			
		}}else{
			echo "0.00";
		}
?></td>
<td style="border-left:1px solid grey;border-right:1px solid grey"><?php
echo number_format($manifest_amount_to_paid_parcel-$manifest_amount_to_paid_manifest);
?></td>
</tr>



<?php
	}
}
				
				?>
				
				
				
				</tbody>
				
				

                                          <!-- end tbody --> 
                                            </table>
											<link rel="stylesheet" type="text/css" href="datatable/datatable.css">
											  	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>
      <script>
        $(document).ready(function(){
			var firstday_exp = $('#firstday_exp').text();
			var lastday_exp = $('#lastday_exp').text();
			var username_choosenexp = $('#username_choosenexp').text();
			var route = "<?php echo $route;?>";
			
			var start_month = $('#start_month').text();
			var end_month = $('#end_month').text();
			
            $('#empTable').DataTable({
				
              
				"lengthMenu": [[100, 125, 150, -1], [100, 125, 150, "All"]],
				dom: 'Bfrtip',
        buttons: [
              { extend: 'copyHtml5', footer: true,className: 'red'},
            { extend: 'excelHtml5', footer: true,className: 'red' },
            { extend: 'csvHtml5', footer: true,className: 'red' },
			{ extend: 'print', 
			className: 'red',
			 autoPrint: true,
                title: ' <img style="margin:10px;" src ="http://www.redetrade.com/sr.png" width="80" height="80" /><p style="font-size:25px;margin:10px;"> </p><div style="font-size:14px;margin:20px;">TANZANIA HEAD QUATER <p>P.O.BOX 4804, Mobile +255 629 777 941<br>Lindi /Iringa  Street ILala Dar Es Salaam<br>Email:classiccoachcoltd@gmail.com<br>Date : <?php
	date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
echo $date_insert;
	?></div><p style="font-size:25px;margin:10px;">  <?php if(isset($_GET['username_choosen'])){echo $fullname;}else{  }?></div>',
			
			footer: true },
          { extend: 'pdfHtml5', footer: true ,className: 'red' }
        ],
	  "fnDrawCallback": function (oSettings) {
        $(".dataTables_filter").each(function () {
            $(this).appendTo($(this).parent().siblings(".panel-body"));
        });
    }
	
            });
			 oTable = $('#empTable').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
}); 
$(".search_text").text("Search");

        });

        </script>