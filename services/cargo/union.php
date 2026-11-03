<?php
include 'connect.php';
				$select12=mysqli_query($db,"
SELECT *
FROM (
(SELECT * FROM dummy )
UNION DISTINCT
(SELECT * FROM dummy )
)
 AS i
GROUP BY s_no ORDER BY id desc
");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
WHILE($get132=mysqli_fetch_array($select12)){
	    $s_no =$get132['s_no'];
		$s_no = substr($s_no, 1);
			$r_no_str = strlen($s_no);
			echo "<br>$s_no<br>";
}}

?>