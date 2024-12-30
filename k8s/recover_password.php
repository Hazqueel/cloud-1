<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database connection
include 'connect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['signUp'])) {
    // Collect and sanitize user input
    $firstName = htmlspecialchars($_POST['fName']);
    $lastName = htmlspecialchars($_POST['lName']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Debugging output for POST data
    echo '<pre>';
    print_r($_POST); // Debug the POST data
    echo '</pre>';

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email Address Already Exists!');window.location.href='index.php';</script>";
    } else {
        // Provide a value for recover_password (a default value or generate a recovery token)
        $recoverPassword = 'default_value'; // Replace this with your own recovery token logic if needed
        $insertQuery = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, recover_password) VALUES (?, ?, ?, ?, ?)");
        if (!$insertQuery) {
            die("Error preparing statement: " . $conn->error);
        }
        $insertQuery->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $recoverPassword);

        if ($insertQuery->execute()) {
            echo "<script>alert('Registration Successful!');window.location.href='index.php';</script>";
        } else {
            echo "Error executing query: " . $insertQuery->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    // Collect and sanitize user input
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Hash the password securely (for the login)
    $sql = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Start session if password is correct
            session_start();
            $_SESSION['email'] = $row['email'];
            header("Location: homepage.php");
            exit();
        } else {
            echo "<script>alert('Incorrect Password');window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('No user found with that email address');window.location.href='index.php';</script>";
    }
}
?>
