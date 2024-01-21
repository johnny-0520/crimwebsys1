<?php
session_start();
include('crimdb.php'); // Include your database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Initialize variables
$search_count = 0;
$search_query = ''; // Initialize $search_query

// Handle search functionality
if (isset($_GET['query'])) {
    $search_query = $_GET['query'];

    // Modify your SQL query to include the search condition
    $sql = "SELECT borrowed_books.id AS borrow_id, CONCAT(users.first_name, ' ', users.middle_name, ' ', users.last_name) AS complete_name, books.book_name AS book_name, books.author AS author, borrowed_books.borrow_date, borrowed_books.return_schedule, borrowed_books.status, borrowed_books.date_time_return
            FROM borrowed_books
            INNER JOIN users ON borrowed_books.user_id = users.id
            INNER JOIN books ON borrowed_books.book_id = books.id
            WHERE 
                users.first_name LIKE '%$search_query%' OR
                users.last_name LIKE '%$search_query%' OR
                books.book_name LIKE '%$search_query%' OR
                borrowed_books.id LIKE '%$search_query%'
            ";

    // Filter logic
    if (!empty($_GET['filter'])) {
        $filter = $_GET['filter'];
        $currentDate = date("Y-m-d");

        switch ($filter) {
            case '1day':
                $sql .= " AND DATEDIFF('$currentDate', borrowed_books.borrow_date) <= 1";
                break;
            case '3days':
                $sql .= " AND DATEDIFF('$currentDate', borrowed_books.borrow_date) <= 3";
                break;
            case '1week':
                $sql .= " AND DATEDIFF('$currentDate', borrowed_books.borrow_date) <= 7";
                break;
        }
    }
} else {
    // If no search query is provided, fetch the list of borrowed books
    $sql = "SELECT borrowed_books.id AS borrow_id, CONCAT(users.first_name, ' ', users.middle_name, ' ', users.last_name) AS complete_name, books.book_name AS book_name, books.author AS author, borrowed_books.borrow_date, borrowed_books.return_schedule, borrowed_books.status, borrowed_books.date_time_return
            FROM borrowed_books
            INNER JOIN users ON borrowed_books.user_id = users.id
            INNER JOIN books ON borrowed_books.book_id = books.id";

    // Filter logic
    if (!empty($_GET['filter'])) {
        $filter = $_GET['filter'];
        $currentDate = date("Y-m-d");

        switch ($filter) {
            case '1day':
                $sql .= " WHERE DATEDIFF('$currentDate', borrowed_books.borrow_date) <= 1";
                break;
            case '3days':
                $sql .= " WHERE DATEDIFF('$currentDate', borrowed_books.borrow_date) <= 3";
                break;
            case '1week':
                $sql .= " WHERE DATEDIFF('$currentDate', borrowed_books.borrow_date) <= 7";
                break;
        }
    }
}

// Run the final query
$result = $conn->query($sql);

// Get the number of search results
$search_count = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url("imgs/bg1.png");
        }

        .container {
            background-color: #ffffff;
            margin-top: 80px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2,
        h6 {
            color: beige !important;
            text-shadow: -1px 2px 6px rgba(128, 0, 0, 1);
        }

        .action-icons {
            display: flex;
            justify-content: space-between;
        }

        .action-icons img {
            width: 20px;
            /* Adjust the width as needed */
            height: 20px;
            /* Adjust the height as needed */
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .text-center {
            color: brown;
        }

        .table-responsive {
            max-height: 390px;
            /* Adjust the max height as needed */
            overflow-y: auto;
        }

        .logout-btn {
            margin-left: auto;
        }

        .img-fluid {
            padding-left: 100px;
        }

        .navbar {
            padding-top: 30px;
            background-color: transparent;
        }

        .table {
            margin-top: 10px;
        }

        .table thead {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            /* Add your desired background color */
            z-index: 1;
        }

        hr {
            border: 2px solid white;
            /* Set the desired border width and color */
            width: 113%;
            /* Adjust the width as needed */
            margin: 20px auto;
            /* Adjust the margin as needed */
        }
        .mng-btn{
            margin-right: 120px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="navbar navbar-light">
        <div class="row w-100">
            <div class="col-md-2">
                <!-- Add your logo image here -->
                <img src="imgs/logo.png" alt="Logo" class="img-fluid">
            </div>
            <div class="col-md-8">
                <h6 class="mb-0">Criminology Reviewer Book Management System</h6>
                <h2 class="mb-0"><b>ADMIN DASHBOARD</b>
                    <hr>
                </h2>
            </div>

            <div class="logout-btn text-left">
                <form action="book_management.php" method="post" class="mng-btn">
                    <button type="submit" class="btn btn-warning ml-2">Manage Books</button>
                </form>
            </div>

            <div class="logout-btn text-right">
                <form action="logout.php" method="post" class="logout-btn">
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container table">
        <!-- Search Bar and Filter Box -->
        <div class="row justify-content-between">
            <!-- Search Bar Form -->
            <div class="col-md-6 mb-3">
                <form class="form-inline" action="admin.php" method="GET">
                    <div class="input-group w-100">
                        <input type="text" class="form-control" placeholder="Search for IDs, Books, Borrowers"
                            name="query" required value="<?php echo htmlspecialchars($search_query, ENT_QUOTES); ?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </div>
                        <?php if (!empty($_GET['query']) || !empty($_GET['filter'])): ?>
                            <div class="input-group-append">
                                <a href="admin.php" class="btn btn-outline-secondary">Clear Search</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Filter Box Form -->
            <div class="col-md-6 mb-3">
                <form class="form-inline" action="admin.php" method="GET">
                    <div class="input-group w-100">
                        <select class="form-control" name="filter">
                            <option value="" <?php echo empty($_GET['filter']) ? 'selected' : ''; ?>>All</option>
                            <option value="1day" <?php echo (isset($_GET['filter']) && $_GET['filter'] == '1day') ? 'selected' : ''; ?>>Borrowed within 1 day</option>
                            <option value="3days" <?php echo (isset($_GET['filter']) && $_GET['filter'] == '3days') ? 'selected' : ''; ?>>Borrowed within 3 days</option>
                            <option value="1week" <?php echo (isset($_GET['filter']) && $_GET['filter'] == '1week') ? 'selected' : ''; ?>>Borrowed within 1 week</option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Apply Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Display search result count and table -->
        <?php if ($search_count > 0): ?>
            <div class="text-right mb-3">
                <p class="text-muted">
                    <?php echo $search_count; ?> search result
                    <?php echo ($search_count > 1) ? 's' : ''; ?> for "
                    <?php
                    if (!empty($_GET['filter'])) {
                        switch ($_GET['filter']) {
                            case '1day':
                                echo 'borrowed within 1 day';
                                break;
                            case '3days':
                                echo 'borrowed within 3 days';
                                break;
                            case '1week':
                                echo 'borrowed within 1 week';
                                break;
                            default:
                                echo htmlspecialchars($search_query, ENT_QUOTES);
                                break;
                        }
                    } else {
                        echo htmlspecialchars($search_query, ENT_QUOTES);
                    }
                    ?>"
                </p>

                <!-- Table to display borrowed books -->
                <table class="table">
                    <!-- Table header and body content -->
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">No results found for "
                <?php
                if (!empty($_GET['filter'])) {
                    switch ($_GET['filter']) {
                        case '1day':
                            echo 'borrowed within 1 day';
                            break;
                        case '3days':
                            echo 'borrowed within 3 days';
                            break;
                        case '1week':
                            echo 'borrowed within 1 week';
                            break;
                        default:
                            echo htmlspecialchars($search_query, ENT_QUOTES);
                            break;
                    }
                } else {
                    echo htmlspecialchars($search_query, ENT_QUOTES);
                }
                ?>"
            </p>
        <?php endif; ?>

        <!-- Table to display borrowed books -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Borrow ID</th>
                        <th>Borrower's Name</th>
                        <th>Book Name</th>
                        <th>Author</th>
                        <th>Borrow Date</th>
                        <th>Return Schedule</th>
                        <th>Status</th>
                        <th>Date/Time Return</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo $row["borrow_id"]; ?>
                            </td>
                            <td>
                                <?php echo $row["complete_name"]; ?>
                            </td> <!-- Updated to display "Complete Name" -->
                            <td>
                                <?php echo $row["book_name"]; ?>
                            </td>
                            <td>
                                <?php echo $row["author"]; ?>
                            </td>
                            <td>
                                <?php echo $row["borrow_date"]; ?>
                            </td>
                            <td>
                                <?php echo $row["return_schedule"]; ?>
                            </td>
                            <td>
                                <?php echo $row["status"]; ?>
                            </td>
                            <td>
                                <?php echo $row["date_time_return"]; ?>
                            </td>
                            <td class="action-icons">
                                <a href="update.php?id=<?php echo $row['borrow_id']; ?>&action=return"
                                    class="btn btn-success"><img src="return.png" alt="Return"></a>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteConfirmationModal"
                                    data-delete-id="<?php echo $row['borrow_id']; ?>"><img src="delete.png"
                                        alt="Delete"></button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            // Capture the ID of the record to be deleted when the modal is shown
            $('#deleteConfirmationModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var deleteId = button.data('delete-id');
                var confirmDeleteBtn = $('#confirmDeleteBtn');

                // Update the "Delete" button in the modal with the correct delete URL
                confirmDeleteBtn.attr('href', 'delete.php?id=' + deleteId + '&action=delete');
            });
        });
    </script>

</body>

</html>