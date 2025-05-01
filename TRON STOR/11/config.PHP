<?php

$host = "localhost";
$dbname = "tron";
$username = "root";
$password = ""; 


$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("erorr" . $conn->connect_error);
} else {
    echo "";
}

?>