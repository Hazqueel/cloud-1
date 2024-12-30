<?php
$host = 'db'; // This should match the service name in docker-compose.yml
$user = 'root';
$pass = 'Tafazzul@72'; // Use the password you set for MYSQL_ROOT_PASSWORD
$db = 'registration';

$maxRetries = 5;
$retryCount = 0;
$connected = false;

while (!$connected && $retryCount < $maxRetries) {
    $conn = new mysqli($host, $user, $pass, $db);
    

    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error . "\n";
        $retryCount++;
        sleep(2); // Wait for 2 seconds before retrying
    } else {
        $connected = true;
        echo "Connected successfully to the database.";
    }
}

if (!$connected) {
    die("Failed to connect to the database after $maxRetries attempts.");
}
?>