<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "blog_db";

// Create Connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check Connection
if($conn->connect_error){
    die("Connection Failed:" . $conn->connect_error);
}

?>