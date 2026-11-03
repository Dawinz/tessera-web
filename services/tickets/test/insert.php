<?php

//insert.php

if(isset($_POST["firstname"]))
{

	include('database_connection.php');

	$order_id = uniqid();

	for($count = 0; $count < count($_POST["firstname"]); $count++)
	{

		$query = "
		INSERT INTO order_items 
        (order_id, item_name) 
        VALUES (:order_id, :item_name)
		";

		$statement = $connect->prepare($query);

		$statement->execute(
			array(
				':order_id'		=>	$order_id,
				':item_name'	=>	$_POST["firstname"][$count]
				
			)
		);

	}

	$result = $statement->fetchAll();

	if(isset($result))
	{
		echo 'ok';
	}

}else{
	echo "adsf";
}

?>
