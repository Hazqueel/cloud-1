<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'connect.php';

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['signUp'])) {
    // Collect and sanitize user input
    $firstName = htmlspecialchars($_POST['fName']);
    $lastName = htmlspecialchars($_POST['lName']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if (!$checkEmail) {
        die("Error preparing statement: " . $conn->error);
    }
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email Address Already Exists!');window.location.href='index.php';</script>";
    } else {
        // Insert new user into database
        $recoverPassword = null; // Optional: set a default or hashed recovery key
        $insertQuery = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, recover_password) VALUES (?, ?, ?, ?, ?)");
        if (!$insertQuery) {
            die("Error preparing insert statement: " . $conn->error);
        }
        $insertQuery->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $recoverPassword);

        if ($insertQuery->execute()) {
            echo "<script>alert('Registration Successful!');window.location.href='index.php';</script>";
        } else {
            echo "Error: " . $insertQuery->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    // Collect and sanitize user input
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Check user credentials
    $sql = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if (!$sql) {
        die("Error preparing select statement: " . $conn->error);
    }
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['email'] = $row['email'];
            header("Location: homepage.php");
            exit();
        } else {
            echo "<script>alert('Incorrect Email or Password');window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('No user found with that email address');window.location.href='index.php';</script>";
    }
}
?>