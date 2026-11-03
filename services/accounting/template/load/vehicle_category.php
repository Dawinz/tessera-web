<?php
include '../connect/connect.php';
include 'includes/vehicle_sum.php';
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$selected_currency = $_POST['selected_currency'];
$category_selected = $_POST['category_selected'];
$symbol = $_POST['symbol'];
$start_date =  date("Y-m-d", strtotime($_POST['start_date']));
$end_date =  date("Y-m-d", strtotime($_POST['end_date']));
$start_date_word =  date("jS/M/Y", strtotime("$start_date"));
$end_date_word =  date("jS/M/Y", strtotime("$end_date"));

if($category_selected=="all"){
$category_query = "";
}else{
$category_query = "name='$category_selected' and   ";

}

?>
           <div class="az-content-breadcrumb lh-1 mg-b-10">
            <span style="font-size:12px;"><span id="start_date_holder"><?php echo $start_date_word;?></span>  <span id="end_date_holder"><?php echo $end_date_word ;?></span></span>
            
          </div>
   <table style="margin-bottom:100px; border:none;"id="order-listing" class="table" cellspacing="0" width="100%">

<thead>
<tr class="bg-primary text-white" style="font-color:grey;">
  
<td>Category</td><td>Vehicle</td><td>income </td><td>Expenses </td><td>Balance </td><td>Trips</td>
 
</tr>

</thead>
<body>
<?php
$total_grand_income =0;
$total_grand_expenses =0;
$total_grand_occurance =0;
$select=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id asc )
 row_num, name  FROM   vehicle_category WHERE $category_query 1"  );
$select_num=mysqli_num_rows($select);
if($select_num!=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $name =$get['name']; 
$counter = 0;
?>

<?php
$grand_income = 0;
$grand_expeses = 0;
$grand_total_final= 0;
$total_occurance= 0;
$select_vehicle=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id desc )
 row_num, name  FROM   vehicle_particular_details where category_name = '$name' "  );
$select_num_vehicle=mysqli_num_rows($select_vehicle);
if($select_num_vehicle!=0){
   	WHILE($get_vehicle=mysqli_fetch_array($select_vehicle)){
    	    $row_num =$get_vehicle['row_num']; 
    	    $vehicle =$get_vehicle['name']; 
       
  $total_expenses =0; 
$total_income =0; 
$select_status=mysqli_query($db,"SELECT expenses,current_route,income FROM   vehicle_account   where trip_date >= '$start_date' AND trip_date < '$end_date' and bus='$vehicle' ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	   
    	    $expenses =$get_status['expenses']; 
    	    $income =$get_status['income']; 
    	    $current_route =$get_status['current_route']; 

           
$select_route_main=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$current_route'  ");
$select_num_route_main=mysqli_num_rows($select_route_main);
if($select_num_route_main !=0){
 	WHILE($get_main=mysqli_fetch_array($select_route_main)){
    	    $currency_curent =$get_main['Currency']; 
 }}

 if($selected_currency == $currency_curent){
$total_expenses += $expenses;
$total_income+= $income;
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$selected_currency' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_expenses += $expenses*$currency_value;
$total_income+= $income*$currency_value;
}


    }}
    $total_balance = $total_income - $total_expenses;
    $occurence =  get_occurrenc($db,$start_date,$end_date,$vehicle);

$grand_income+=$total_income;
$grand_expeses+=$total_expenses;
$grand_total_final=$grand_income - $grand_expeses;
$total_occurance +=  $occurence ;
    if($occurence !=0){

    $counter ++;
?>
<tr >
  
<td><?php echo ($counter ==1) ? "$name " : "";?></td><td><a style="color:black;"href="vehicle_account_trip_date.php?start_date=<?php echo $start_date;?>&end_date=<?php echo $end_date;?>&vehicle=<?php echo $vehicle; ?>&selected_currency=<?php echo $selected_currency;?>&symbol=<?php echo $symbol;?>"><u><?php echo $vehicle;?></u></a></td><td><?php  echo number_format($total_income);?></td><td><?php  echo number_format($total_expenses);?></td><td><span class="<?php echo ($total_balance <=0) ?  "text-danger": "";?>"><?php echo number_format($total_balance);?></span></td><td><?php echo $occurence ;?></td>
 
</tr>
<?php
  $total_grand_occurance += $occurence;
    }
    }}
    $total_grand_income +=$grand_income;
    $total_grand_expenses +=$grand_expeses;
    $total_gross_balance = $total_grand_income - $total_grand_expenses;
  
?>

<?php


    }}

    
?>
<tr  >
  
<td ></td>
<td ><strong>Total</strong></td>
<td ><strong><?php echo number_format($total_grand_income );?><?php echo $symbol;?></strong></td>
<td ><strong><?php echo number_format($total_grand_expenses,2)?><?php echo $symbol;?></strong></td>
<td ><strong><?php echo number_format($total_gross_balance,2)?><?php echo $symbol;?></strong></td>
<td ><strong><span class="<?php echo ($total_gross_balance <=0) ?  "text-danger": "";?>"><?php echo  $total_grand_occurance ;?></span></strong></td>
 
</tr>
</body>
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
