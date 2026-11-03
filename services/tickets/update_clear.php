<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$id = $_POST['clear_id'];
include 'connect.php';

$insert=mysqli_query($db,"UPDATE  booking SET status='complete',disable_loan='disabled',amount_paid='',amount_status='',amount_left='' WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");

?>