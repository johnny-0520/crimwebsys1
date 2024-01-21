<?php
session_start();
include('crimdb.php'); // Include your database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];

    // Perform the delete operation in the database
    $deleteSql = "DELETE FROM books WHERE id = $bookId";

    if ($conn->query($deleteSql) === TRUE) {
        // Book deleted successfully
        header("Location: book_management.php");
        exit();
    } else {
        // Error deleting book
        echo "Error: " . $conn->error;
        // Handle the error as needed
    }
} else {
    // Invalid request
    echo "Invalid request";
    // Handle the error as needed
}

// Close the database connection
$conn->close();
?>
