<?php
include('crimdb.php'); // Include your database connection file

// Check if the borrow ID and action are set in the URL
if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $borrow_id = $_GET['id'];

    // Delete the record from the database
    $delete_sql = "DELETE FROM borrowed_books WHERE id = $borrow_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Redirect back to the admin dashboard after deleting
    header("Location: admin.php");
    exit();
} else {
    echo "Invalid borrow ID or action";
}
?>
