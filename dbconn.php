<?php

$host = '';
$user = '';
$pass = '';
$dbname = '';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if($conn->connect_error){
    die('Database connection error:'.$conn->connect_error);
}

$conn->set_charset("utf8mb4");


?>