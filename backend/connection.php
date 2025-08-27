<?php

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'novostella_technologies-db';

$conn = new mysqli($host, $user, $password, $dbname);

if($conn->connect_error){
    die("connection Failed") . $conn->connect_error;
}


?>