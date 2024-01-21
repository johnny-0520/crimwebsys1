<?php
session_start();
include('crimdb.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the book ID from the URL or form data
    $bookId = isset($_GET['id']) ? $_GET['id'] : $_POST['bookId'];

    // Sanitize and validate input
    $bookName = htmlspecialchars($_POST['editBookName'], ENT_QUOTES);
    $author = htmlspecialchars($_POST['editAuthor'], ENT_QUOTES);

    // Update the book details in the database
    $updateSql = "UPDATE books SET book_name = '$bookName', author = '$author' WHERE id = $bookId";

    if ($conn->query($updateSql) === TRUE) {
        // Redirect to the book management page after successful update
        header("Location: book_management.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
