<?php
$email= $_COOKIE['type'];
include('pdflayer.class.php');
$id = $_GET['item_id'];
//Instantiate the class
$html2pdf = new pdflayer();

//set the URL to convert
$html2pdf->set_param('document_url','http://www.teccross.co.tz/invoice.php?item_id='.$id.'& email='.$email.'');

//start the conversion
$html2pdf->convert();

//display the PDF file
$html2pdf->display_pdf();
?>