<?php
  date_default_timezone_set('Africa/Dar_es_Salaam');
				  $tomorrow = date("m/d/Y", strtotime("+1 day")); 		
				  $tommorw = date('F l Y', strtotime($tomorrow));
				  $tommorw_day = date('l', strtotime($tomorrow));
				  echo $tommorw_day;
?>