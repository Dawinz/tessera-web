<?php // PHP part
    session_start();          // Start the session
    $_SESSION['student']=array(); // Makes the session an array
    $student_name="af"; //student_name form field name
    $student_city="adfasdf";   //city_id form field name
    array_push($_SESSION['student'],$student_name,$student_city);   
    //print_r($_SESSION['student']);
?>

<table class="table">     <!-- HTML Part (optional) -->
    <tr>
      <th>Name</th>
      <th>City</th>
    </tr>
                                                        
    <tr>
     <?php for($i = 0 ; $i < count($_SESSION['student']) ; $i++) {
     echo '<td>'.$_SESSION['student'][$i].'</td>';
     }  ?>
    </tr>
</table>