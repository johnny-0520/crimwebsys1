<?php
include('crimdb.php'); // Include your database connection file

// Check if the borrow ID and action are set in the URL
if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'return') {
    $borrow_id = $_GET['id'];

    // Check the current status
    $status_check_sql = "SELECT status FROM borrowed_books WHERE id = $borrow_id";
    $status_result = $conn->query($status_check_sql);

    if ($status_result->num_rows > 0) {
        $row = $status_result->fetch_assoc();
        $current_status = $row['status'];

        // Check if the status is not already "RETURNED"
        if ($current_status !== 'RETURNED') {
            // Update the status and return date/time in the database
            $update_sql = "UPDATE borrowed_books SET status = 'RETURNED', date_time_return = NOW() WHERE id = $borrow_id";

            if ($conn->query($update_sql) === TRUE) {
                // Display modal message on successful update
                echo '<script>
                        alert("Record updated successfully");
                        window.location.href = "admin.php";
                     </script>';
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            // Display modal message if the book has already been returned
            echo '<script>
                    alert("This book has already been returned.");
                    window.location.href = "admin.php";
                 </script>';
            exit();
        }
    } else {
        echo "Error checking status: " . $conn->error;
    }
} else {
    echo "Invalid borrow ID or action";
}
?>
