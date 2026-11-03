<?php
include 'connect.php';
	$insert=mysqli_query($db,"DELETE FROM `product` ");			
include 'database.php';
	
if(isset($_POST['submit']))
{

$checked_array=$_POST['prodid'];
foreach ($_POST['prodname'] as $key => $value) 
{
	if(in_array($_POST['prodname'][$key], $checked_array))
	{
	$prodname=$_POST['prodname'][$key];
	$prod_price= $_POST['prod_price'][$key];
	$prod_qty= $_POST['prod_qty'][$key];
	$pd= '1'[$key];


	$insertqry="INSERT INTO `product`( `product_name`, `product_price`, `product_quantity`,`code`) VALUES ('$prodname','$prod_price','$prod_qty','1')";
	$insertqry=mysqli_query($con,$insertqry);
	}
	
	
}
}
																			
header('Location: check_manifest.php?u=1');
?>