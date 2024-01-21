<?php
include('crimdb.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $password_input = htmlspecialchars($_POST["password"]);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $db_username, $db_password);

    if ($stmt->fetch() && password_verify($password_input, $db_password)) {
        // Successful login
        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $db_username;
        
        header("Location: index.php");  // Redirect to the secure page
        exit();
    } else {
        // Display error message and redirect back to the login page
        $_SESSION["login_error"] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
}
?>
