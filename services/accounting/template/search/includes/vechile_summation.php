<?php

function summation($bus,$db,$selected_currency,$symbol,$start_date,$end_date,$route_name,$trip_date,&$ticket_income_array,&$road_collection_income_array,&$cargo_income_array,&$exp_array,&$routs_array,&$currency_array,&$currency_value_array,&$cargo_income_to_be_array ){
    
$query_text = "";
    $select_status=mysqli_query($db,"SELECT status,currency,bus,affect,income,route_name,current_route,category,name,expenses FROM   vehicle_account   where ".$start_date." ".$end_date." ".$route_name."  bus='$bus' ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	   
    	  
    	    $income =$get_status['income']; 
    	    $expenses =$get_status['expenses']; 
    	    $current_route =$get_status['current_route']; 
    	    $category =$get_status['category']; 
    	    $name =$get_status['name']; 
    	    $status =$get_status['status']; 
    	    $route_name =$get_status['route_name']; 
    	    $affect =$get_status['affect']; 
    	    $currency =$get_status['currency']; 
            $currency_exchange = "$selected_currency-$currency";
            $exchanged_rate = 1;

            
            if($selected_currency !=$currency){
          $exchanged_rate = $currency_value_array[$currency_exchange];
            }



if(($status=="Income") && ($category=="Passanger") && ($name=="Ticket")){
$income = $income * $exchanged_rate;
    
array_push($ticket_income_array,$income );
    
}
if(($status=="Income") && ($category=="Passanger") && ($name=="Road collection")){
    $income = $income * $exchanged_rate;
array_push($road_collection_income_array,$income );
    
}
if(($status=="Income") && ($category=="Cargo") && ($name=="Paid")){
    $income = $income * $exchanged_rate;
array_push($cargo_income_array,$income );
    
}
if(($status=="Income") && ($category=="Cargo") && ($name!="Paid")){
    $income = $income * $exchanged_rate;
array_push($cargo_income_to_be_array,$income );
    
}
if(($status=="Expenses") && ($affect=="Paid") ) {
    $expenses = $expenses * $exchanged_rate;
   array_push($exp_array,$expenses);
}
if (in_array($route_name, $routs_array)) {

}else{
array_push($routs_array,$route_name );
}


}}
          //return $selected_currency;
}
?>