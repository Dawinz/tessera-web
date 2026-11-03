<?php

include '../connect.php';


			$select14 = mysqli_query($db, "SELECT *  FROM  routes ");
			$select_num14 = mysqli_num_rows($select14);
			if ($select_num14 != 0) {
				while  ($get134 = mysqli_fetch_array($select14)) {
					$route_id = $get134['route_id'];
					$fare_price = $get134['fare_price'];

				mysqli_query($db, "INSERT INTO  ticket_price (route_id,vehicle_type,price)
 VALUES ('$route_id','normal','$fare_price'
 )");
                }}