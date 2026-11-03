<?php


$dsn = 'mysql:host=localhost;dbname=account;charset=utf8';
$username = 'srclassic';
$password = 'Teccross@360';

try {
    // Create the connection
    $pdo_acc = new PDO($dsn, $username, $password);

    // Set error mode to exception for easier debugging
    $pdo_acc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
}

?>