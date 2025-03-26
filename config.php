<?php
$localhost = "localhost";
$username = "root";
$password = "";
$database = "restaurant_menu";

$conn = new mysqli($localhost, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>