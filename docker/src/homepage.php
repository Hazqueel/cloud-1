<?php
session_start();
include("connect.php");
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome</h1>
        <p>
            Hello 
            <?php 
            $email = $_SESSION['email'];
            $query = mysqli_query($conn, "SELECT firstName, lastName FROM users WHERE email = '$email'");
            if ($row = mysqli_fetch_assoc($query)) {
                echo $row['firstName'] . " " . $row['lastName'];
            }
            ?>
        </p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>