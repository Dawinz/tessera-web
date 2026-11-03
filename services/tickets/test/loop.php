<?php
/*
   $my_string = 'ii-ii';   

    // without passing length

    // length = 1 (by default)

    $my_array1 = str_split($my_string);

$i = 0;

while($i < count($my_array1))
{
$color = $my_array1[$i];
	
	
	$mode = prev($my_array1);
	echo $mode;
	if($mode=="-"){
		echo "-You are confused.<br>";
	}else{
		echo "".$i."".$color."<br>";
	}

	$i++;
} 
?>
<?php
$example = array('A1','A2','E','A3');
$searchword = 'E';
$matches = array();
foreach($example as $k=>$v) {
    if(preg_match("/\b$searchword\b/i", $v)) {
        $matches[$k] = $v;
		echo "$v";
    }
}*/
?>
<?php
 /*
// PHP program to get first and
// last iteration
 
// Declare an array and initialize it
$myarray = array( 1, 2, 3, 4, 5, 6 );
 
// Declare a counter variable and
// initialize it with 0
$counter = 0;
 
// Loop starts from here
foreach ($myarray as $item) {
     
    // Check condition if count is 0 then
    // it is the first iteration
    if( $counter == 1 ) {
         
        // Print the array content
        print( $item );
        print(": second iteration \n $counter");
    }
     
    // Check condition if count is length -1
    // then it is last iteration
    if( $counter == count( $myarray ) - 1) {
         
        // Print the array content
        print( $item );
        print(": Last iteration");
    }
         
    $counter = $counter + 1;
}
 */
 
 
?>
<?php
/*
    $arr = array(126 => "pitch", 134 => "pithc2", 155 => "pithc3", 87  => "pithc4", 45  => "pithc5", 192 => "pithc6");
    $from = 134;
    $howmany = 6;

    //Get last element + rest the array
    $end = end($arr);
    reset($arr);

    //set array pointer to '$from'
    while(key($arr) != $from) next($arr);

    //go through '$howmany' times
    for($i = 1; $i < $howmany; $i++) {

        //prints the current array element and goes to the next one
        echo  "".$i."".current($arr)."" . "<br />";

        //rest the array to the beginning, while it reached the end
        if(current($arr) == $end)
            reset($arr);
        else
            next($arr);

    }
*/


?>


<?php
$str = "apple";
 
$length = strlen($str);
for ($index = 0; $index < $length; $index++) {
    echo "".$index."".$str[$index]."";
    echo "<br>";
}
?>
