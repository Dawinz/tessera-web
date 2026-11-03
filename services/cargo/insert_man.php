<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
$connect = sr_mysqli('sr2');
//insert.php



	$skill = $_POST["skill"];

$number = count($_POST["skill"]);
if($number > 1)
{
	for($i=0; $i<$number; $i++)
	{
		if(trim($_POST["name"][$i] != ''))
		{
			$sql = "INSERT INTO tbl_name(name) VALUES('".mysqli_real_escape_string($connect, $_POST["name"][$i])."')";
			mysqli_query($connect, $sql);
		}
	}
	echo "Data Inserted";
}
else
{
	echo "Please Enter Name";
}


?>