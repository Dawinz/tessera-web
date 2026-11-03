            <?php
require_once __DIR__ . '/../includes/tessera_tenant.php';

include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');
	$month = date('m/Y', time());
	$year = date('Y', time());
	$month_one = date('m', time());
	$month_name = date("F", mktime(0, 0, 0, $month_one, 10));
	
	
	
	

?>
	
	
<?php
include 'connect.php';
					date_default_timezone_set('Africa/Dar_es_Salaam');

$query_date = date(' m/d/Y');


// First day of the month.
$first = date('m/01/Y', strtotime($query_date));

// Last day of the month.
$last =  date('m/t/Y', strtotime($query_date));



?>
	
  	

		<?php
				include 'connect.php';
	$select2=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date >= '$first' and trip_date <= '$last' and month ='$month_one' and year='$year') GROUP BY (day) ORDER BY trip_date asc ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
$day =$get12['day']; 
 echo $day;
$sql = $db->query("SELECT SUM(cost) AS  cost FROM booking  WHERE " . tessera_sql_tenant_eq() . " AND (day='$day' and month ='$month_one' and year='$year')");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$amount=$data['cost'];
			
			
		}}else{
			echo "0.00";
		}



}}else{
	echo "no";
}
?>
	<?php
				include 'connect.php';
	$select2=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date >= '$first' and trip_date <= '$last' and month ='$month_one' and year='$year') GROUP BY (trip_date) ORDER BY trip_date asc ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
$trip_date =$get12['trip_date']; 
$days = date('jS', strtotime($trip_date));
$days_word = date('D', strtotime($trip_date));
echo "'$days_word $days',";



}}else{
	echo "no";
}
?>