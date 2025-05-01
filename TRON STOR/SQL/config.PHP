<?php

$host = "localhost";
$dbname = "tron_store";
$username = "root";
$password = ""; 


$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("erorr" . $conn->connect_error);
} else {
    echo "success";
}

?>