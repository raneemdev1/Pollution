<?php
// Database Connection - Ahmad Hikmat Website 2025

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Pollution";



try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8");
} catch(PDOException $e) {
    echo '<div style="color: red; padding: 20px; border: 1px solid red; margin: 20px;">
        <strong>Connection failed:</strong> '. $e->getMessage() .'
    </div>';
}