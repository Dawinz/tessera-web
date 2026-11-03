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
			   if( $route==""){
								       $route = "";
$username_choosen = "";   	 
			   }else{
		

       $route = preg_replace('/_/',' ', $route);
$username_choosen = preg_replace('/_/',' ', $username_choosen);	  
			   }



										
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
                    <th>sender's name</th>
                    <th>Receiver's name</th>
                    <th>Destination</th> 
					<th>Date</th>
					<th>Detail</th>
					<th>code</th>
					<th>Ammount</th>
					<th>Loans</th
					><th>Total Ammount</th>
					<th>Paid</th>
					<th>To be Paid</th>
					<th>Quantity</th>
                    
                </tr>
                </thead>
				                	  <tfoot>
              <tr>
                <th></th>
                		<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(s_surname) AS  s_surname  FROM parcel_info   where account='$route' and  username='$username_choosen' and   date_insert >= '$firstday' and  date_insert <= '$lastday' ");
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
										$sql = $db->query("SELECT SUM(to_be) AS  to_be FROM parcel_info   where  account='$route' and  username='$username_choosen' and date_insert >= '$firstday' and  date_insert <= '$lastday'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$to_be_paid=$data['to_be'];
				
				if($to_be_paid==""){
					echo "0.00";
				}else{
						echo number_format($to_be_paid,2);
				}
			
			
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
										$sql = $db->query("SELECT SUM(amount) AS  amount  FROM parcel_info   where account='$route' and  username='$username_choosen' and date_insert >= '$firstday' and  date_insert <= '$lastday' and status='paid'");
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
										$sql = $db->query("SELECT SUM(paid) AS  paid FROM parcel_info  where account='$route' and  username='$username_choosen' and   date_insert >= '$firstday' and  date_insert <= '$lastday' ");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$paid_count=$data['paid'];
				
				if($paid_count==""){
					echo "0.00";
				}else{
									echo number_format($paid_count,2);

				}
			
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
				$select222=mysqli_query($db,"SELECT *  FROM  parcel_info   where account='$route' and  username='$username_choosen' and   date_insert >= '$firstday' and  date_insert <= '$lastday'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "$select_num222";
}else{
	echo 0;
}
				?>
				</th>
								</th>
                          </tr>
						  
						  <tr><th></th>
				<th>Loan</th>
				<th>			<?php
				  include_once 'connect.php';
										$sql = $db->query("SELECT SUM(loan) AS  loan FROM parcel_info   where  account='$route' and  username='$username_choosen' and   date_insert >= '$firstday' and  date_insert <= '$lastday'");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$loan=$data['loan'];
				if($loan==""){
									echo '<span style="color:red;">0.00</span>';

				}else{
									echo '<span style="color:red;">'.number_format($loan,2).'</span>';

				}
			
		}}else{
			echo "Nothing";
		}
								?></th>
                          </tr>  <tr>
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


	


	 <script>

        $(document).ready(function(){
			var firstday = "<?php echo $firstday;?>";
			var lastday = "<?php echo $lastday;?>";
			var username_choosen = "<?php echo $username_choosen?>";
			var route = "<?php echo $route;?>";
			
			
	
            $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				 "order": [[ 5, "desc" ]],
                'ajax': {
                'url':'fetch_user_sum.php',
									 type:"POST","data": {
        "firstday":firstday,"lastday":lastday,"username_choosen":username_choosen,"route":route,
    }
                },'columns': [
                    { data: 's_name' },
					  { data: 'r_name' },
                    { data: 'account' }, 
					{ data: 'date_insert' },
					  { data: 'detail' },
                    { data: 'code' },
					{ data: 'amount' },
						{ data: 'loan' },
					{ data: 'loan_amount' },
					{ data: 'paid' },
					{ data: 'to_be' },
					{ data: 'question' },
       
                 
                ],
				"lengthMenu": [[100, 125, 150, -1], [100, 125, 150, "All"]],
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true,className: 'red'},
            { extend: 'excelHtml5', footer: true,className: 'red' },
            { extend: 'csvHtml5', footer: true,className: 'red' },
			{ extend: 'print', 
			className: 'red',
			 autoPrint: true,
                title: ' <img style="margin:10px;" src ="http://www.redetrade.com/sr.png" width="80" height="80" /><p style="font-size:25px;margin:10px;"> User charges</p><div style="font-size:14px;margin:20px;">TANZANIA HEAD QUATER <p>P.O.BOX 4804, Mobile +255 629 777 941<br>Lindi /Iringa  Street ILala Dar Es Salaam<br>Email:classiccoachcoltd@gmail.com<br>Date : <?php
	date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
echo $date_insert;
	?></div><p style="font-size:25px;margin:10px;">  <?php if(isset($_GET['date3'])){echo $_GET['date3'];}else{  }?></div>',
			
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
	 $('.dateselect').datepicker({
		todayHighlight: true,
		 orientation: "bottom left",
		 format: "yyyy-mm-dd",
	
		//container: console.log($(this).datepicker())
}); 
 oTable = $('#empTable').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
});  
$(".search_text").text("Search");

        });

        </script>