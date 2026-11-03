<?php
include'../connect/connect.php';
include 'includes/ticket_income_report_vehicle.php';
$income_array = array();
$selected_currency= $_POST['selected_currency'];
$start_date= $_POST['start_date'];
$end_date= $_POST['end_date'];
$selected_currency= $_POST['selected_currency'];
$route_name= $_POST['route_name'];
$bus =$_POST['bus'];
////body///////////////////


if($route_name!=""){
$route_name = " route_name ='$route_name' and ";
}
if($start_date!=""){
	$start_date =  date("Y-m-d", strtotime($_POST['start_date']));
$start_date = " trip_date >='$start_date' and ";
}
if($end_date!=""){
	$end_date =  date("Y-m-d", strtotime($_POST['end_date']));
$end_date = " trip_date <='$end_date' and ";
}

$bus = " bus ='$bus' and ";

//////////
$select_route_currency=mysqli_query($db,"SELECT symbol FROM   currency   where name ='$selected_currency'  ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get=mysqli_fetch_array($select_route_currency)){
    	    $symbol =$get['symbol']; 
 }}
?>
<input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search....">
<table style="margin-bottom:100px; border:none;"id="order-listing" class="table" cellspacing="0" width="100%">
 

<thead>
<tr class="bg-primary text-white" style="font-color:grey;">
  
<td> No</td><td>bus</td><td>Routes</td><td>ticket</td><td>Road Collection</td><td>Cargo</td><td>Total</td><td>Expenses</td><td>Balance</td>
 
</tr>
<tr>
  <td ></td>
  <td ></td>
  <td ></td>
  <td style="border-left:1px solid #B2B2B2;"></td>
    <td style="text-align:right;"><strong>INCOME</strong> </td>
  <td ></td>

  <td style="border-right:1px solid #B2B2B2;"><span class="badge badge-success"><?php echo $selected_currency;?></span></td>
  <td ></td>
  <td > </td>

</tr>
</thead>
<?php
    $total_income =0;
    $total_expenses =0;

$select_status=mysqli_query($db,"SELECT status,bus,affect,income,expenses,route_name,current_route FROM   vehicle_account   where  ".$start_date." ".$end_date." ".$route_name."  ".$bus." 1  ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){

    	    $income =$get_status['income']; 
    	    $expenses =$get_status['expenses']; 
    	    $current_route =$get_status['current_route']; 
    	    $affect =$get_status['affect']; 
if($affect=="To be Paid"){

}else{


$select_route_main=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$current_route'  ");
$select_num_route_main=mysqli_num_rows($select_route_main);
if($select_num_route_main !=0){
 	WHILE($get_main=mysqli_fetch_array($select_route_main)){
    	    $currency_curent =$get_main['Currency']; 
 }}
if($selected_currency == $currency_curent){
$total_income += $income;
$total_expenses +=$expenses;
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$selected_currency' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_income += $income*$currency_value;
$total_expenses += $expenses*$currency_value;
}


	}
}
}

$select_order_bus=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by bus asc )
 row_num, bus,route_name,trip_date FROM   manifest   where ".$start_date." ".$end_date." ".$route_name." ".$bus."  1 ");
$select_num_order_bus=mysqli_num_rows($select_order_bus);
if($select_num_order_bus!=0){
   	WHILE($get_order_bus=mysqli_fetch_array($select_order_bus)){
    	    $row_num =$get_order_bus['row_num']; 
    	    $bus =$get_order_bus['bus']; 
    	    $route_name_holder =$get_order_bus['route_name']; 
    	    $trip_date =$get_order_bus['trip_date']; 
           
            ?>
            <tr>
         <td> <?php echo $row_num;?></td><td><u><a href="compose_vehicle.php?route_name=<?php echo $route_name_holder;?>&bus=<?php echo  $bus;?>&trip_date=<?php echo $trip_date?>&checked=&main_page=manifest.php"style="color:#3268a8;"><strong><?php echo $bus;?></strong></u></a></td><td style=""><?php echo $route_name_holder;?></td><td><?php incomeTicketReturn($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name,$route_name_holder,$trip_date)?></td><td><?php incomeRoadCollectionReturn($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name,$route_name_holder,$trip_date)?></td><td><?php incomeCargoReturn($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name,$route_name_holder,$trip_date)?></td>
        <td style="border-left:1px solid #B2B2B2;"><?php $total =sumTwoNumbers($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name,$route_name_holder,$trip_date) ;?></td><td style="border-left:1px solid #B2B2B2;border-right:1px solid #B2B2B2;"><?php expensesReturn($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name,$route_name_holder,$trip_date)?></td><td><?php balance($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name,$route_name_holder,$trip_date)?></td>
    </tr>
            <?php
 
    }}

?>
       <tr>
         <td> </td><td></td><td></td><td></td><td></td>
       <td></td> <td></td><td style="border-left:1px solid #B2B2B2;border-right:1px solid #B2B2B2;"><strong>Total Income</strong></td><td><strong><?php echo number_format(IncomeFunction ($total_income));?></strong></td>
    </tr>
       <tr>
         <td> </td><td></td><td></td><td></td><td></td>
      <td></td>  <td></td><td style="border-left:1px solid #B2B2B2;border-right:1px solid #B2B2B2;"><strong>Total Expense</strong></td><td><strong><?php echo number_format(ExpensesFunction($total_expenses))?></strong></td>
    </tr>
       <tr>
         <td> </td><td></td><td></td><td></td><td></td>
        <td></td><td></td><td class="sr-cell-ok" style="border-left:1px solid #B2B2B2;"><strong>Balance</strong></td><td class="sr-cell-ok" style=""><strong><?php balanceReport($total_income,$total_expenses);?></strong></td>
    </tr>
<?php



IncomeFunction ($total_income);
ExpensesFunction($total_expenses);

 function IncomeFunction($total_income){
return $total_income;
};
 function ExpensesFunction($total_expenses){
return $total_expenses;
};

function balanceReport($total_income,$total_expenses){
  IncomeFunction($total_income);
  ExpensesFunction($total_expenses);
  $balance = IncomeFunction($total_income)-ExpensesFunction($total_expenses);
  if($balance <=0){
  echo "<span style='color:red;'>".number_format($balance)."</span>";
  }else{
  echo number_format($balance);
  }

}

?>
</table>
<script>

  $(document).ready(function(){
	 var table = new DataTable('#order-listing', {
		   lengthMenu: [100],
		 				   "language": {
	     searchPlaceholder: "Search",
        search: "",
		
     processing: ''
},
ordering: false,
 paging: false,
 info: false,
"order": [],
    layout: {
        topStart: {
            buttons: [
			 {
			 extend: 'copy',
			 footer: true, 
			 class: 'buttons-copy',
			 init: function(api, node,cofig){
				 $(node).hide();
			 }
			 },
			 { extend: 'csv', footer: true, class: 'buttons-csv',init: function(api, node,cofig){
				 $(node).hide();
			 }},
			 { extend: 'print', footer: true, class: 'buttons-print',init: function(api, node,cofig){
				 $(node).hide();
			 }},
			 { extend: 'pdf', footer: true, class: 'buttons-pdf',init: function(api, node,cofig){
				 $(node).hide();
			 }},
			{ extend: 'excel', text: 'Save as Excel', class: 'buttons-excel',init: function(api, node,cofig){
				 $(node).hide();
			 }}
			],
		

        }
    }
});
table.buttons('.export').remove();
   $("#print_table").click(function(){
		  table.buttons('.buttons-print').trigger();

	  
   });  
   $("#copy_table").click(function(){
		  table.buttons('.buttons-copy').trigger();

   });  

   $("#pdf_table").click(function(){
		  table.buttons('.buttons-pdf').trigger();

   });  
   $("#excel_table").click(function(){
		  table.buttons('.buttons-excel').trigger();

   }); 
   $("#csv_table").click(function(){
		  table.buttons('.buttons-csv').trigger();

   });  

   $("#search_row").click(function(){
		  $("#myInputTextField").toggle();

   });
      					 oTable2 = $('#order-listing').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable2.search($(this).val()).draw() ;
}); 
  });

</script>
