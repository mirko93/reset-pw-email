<?php

ob_start();

$dbName = 'resetemail';
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $con = new PDO("mysql:dbname=$dbName;host=$host", "$username", $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}