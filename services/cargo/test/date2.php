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
         <input type="text" id="myInputTextField" class="form-control" placeholder="Search Code">
      </div>
	    <?php
			   $firstday = @$_GET['firstday'];
			   $lastday = @$_GET['lastday']; 
			   $username_choosen = @$_GET['trip'];
			   $route = @$_GET['route'];
		       $route = preg_replace('/_/',' ', $route);


			   ?>
<table class="table mb-0" id="empTable">
           <thead class="table-light">
              <tr>
                    
                   
					<th>Username</th>
					<th>Date</th>
				 <th>Destination</th> 
					<th>Ammount</th>
					<th>Paid</th>
					<th>To be Pay</th>
					<th>Receipts</th>
                    
                </tr>

				
				
                </thead>
				<tbody>
								<?php
								include 'connect.php';
				$select1=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE account='$route'  and   date_insert >= '$firstday'  and date_insert <= '$lastday'  group by date_insert");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
	   	WHILE($get12=mysqli_fetch_array($select1)){
			$date_insert_day =$get12['date_insert']; 
			$route =$get12['account'];
			$username =$get12['username']; 
?>
<tr>
<td><?php echo $date_insert_day;?></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>



</tr>

		<?php
								include 'connect.php';
				$select122=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE account='$route'  and   date_insert = '$date_insert_day' group by username");
$select_num122=mysqli_num_rows($select122);
if($select_num122 !=0){
	   	WHILE($get122=mysqli_fetch_array($select122)){
			$date_insert_day2 =$get122['date_insert']; 
			$route =$get122['account'];
			$username =$get122['username']; 
?>
<tr>
<td>ghjgh</td>
<td>kkk</td>
<td></td>
<td>

</td>
<td>

</td>
<td>
	
</td>
<td>

</td>

</tr>

<?php
}}
?>

<?php
	}
}
				
				?>
				
			
				
				</tbody>
				
				
				                	  <tfoot>
              <tr>
                <th></th>
                		<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(s_surname) AS  s_surname  FROM parcel_info   where username='$username' and account='$route' and  date_insert >= '$firstday' and date_insert <= '$lastday' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$due_new=$data['s_surname'];
			
		}}else{
			echo "Nothing";
		}
								?>
				
              
				<th>To Be Payed Total</th>
				<th>			<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM parcel_info   where username='$username' and account='$route' and  date_insert >= '$firstday' and  date_insert <= '$lastday' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be_paid=$data['to_be'];
				echo number_format($to_be_paid,2);
			
		}}else{
			echo "Nothing";
		}
								?></th>
                          </tr> 
						  
						  
						  <tr>
                <th></th>
                
				
              
				<th>Cash Payed</th>
				<th>		<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(amount) AS  amount  FROM parcel_info   where username='$username' and account='$route' and  date_insert >= '$firstday' and date_insert <= '$lastday' and status='paid'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['amount'];
			
		}}else{
			echo "Nothing";
		}
								?>	
								<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info  where username='$username' and account='$route' and   date_insert >= '$firstday'  and date_insert <= '$lastday'  ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid_count=$data['paid'];
				echo number_format($paid_count,2);
			
		}}else{
			echo "Nothing";
		}
								?></th>
                          </tr>
						  <tr>
                <th></th>
                
				
              
				<th>Charged Receipt</th>
				<th>		
									<?php
				include_once 'connect.php';
				$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where username='$username' and account='$route' and date_insert >= '$firstday' and date_insert <= '$lastday' ");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}
				?>
				</th>
								</th>
                          </tr>
						  
						  <tr>
                <th></th>
                
				
              
				<th>Total</th>
				<th>			<?php
echo number_format($paid_count + $to_be_paid,2);

?></th>
                          </tr>
        </tfoot>
	
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
				
                  order: [[2, 'asc'], [1, 'asc']],
        rowGroup: {
            dataSrc: [ 1, 2 ]
        },
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
        ], rowCallback: function(row, data, index) {
      if (data.status == "Due payment") {
    	
$(row).css('background-color','#6b0121');
$(row).css('color','#ffffff');
      } 
	  if (data.status == "To be Payed"){
	$(row).css('background-color','#ffffff');	  
	  } if (data.status == "paid"){
	$(row).css('background-color','#99ff9c');	  
	  }
    },
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