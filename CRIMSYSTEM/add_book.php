<?php
session_start();

include('crimdb.php'); // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $bookName = htmlspecialchars($_POST['book_name'], ENT_QUOTES);
    $author = htmlspecialchars($_POST['author'], ENT_QUOTES);

    // Insert the book details into the database
    $insertSql = "INSERT INTO books (book_name, author) VALUES ('$bookName', '$author')";

    if ($conn->query($insertSql) === TRUE) {
        $response = array('success' => true);
    } else {
        $response = array('success' => false, 'error' => $conn->error);
    }

    echo json_encode($response);
    exit();
} else {
    // Redirect to the home page or display an error message as needed
    header("Location: index.php");
    exit();
}
?>
