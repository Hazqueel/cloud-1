<?php 
$host = "localhost"; // Use the service name defined in docker-compose.yml
$user = "root";
$pass = "Tafazzul@72"; // Assuming you want to use an empty password as per your Docker setup
$db = "registration";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Failed to connect to the database: " . $conn->connect_error);
}
?>