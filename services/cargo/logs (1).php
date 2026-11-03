 <?php
 date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$time_insert = date('h:i:a', time());
 include 'connect.php';
 session_start();
$username=$_SESSION['username'];
   $url= $_SERVER['REQUEST_URI'];    
   
 $sqlQ = "INSERT INTO  logs   (user, page , date,time) VALUES (?,?,?,?)"; 
        $stmt = $db->prepare($sqlQ); 
        $stmt->bind_param("ssss", $username, $url , $date_insert ,$time_insert); 
        $insert = $stmt->execute();	
		?>