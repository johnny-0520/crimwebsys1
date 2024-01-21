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
    $sql = "SELECT * FROM books
            WHERE 
                book_name LIKE '%$search_query%' OR
                author LIKE '%$search_query%'
            ";
} else {
    // If no search query is provided, fetch the list of books
    $sql = "SELECT * FROM books";
    $sql = "SELECT * FROM books ORDER BY id DESC";  // Assuming 'id' is your timestamp or ID column
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
    <title>Book Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-image: url("imgs/bg1.png");
            overflow-x: hidden;
        }

        .container {
            background-color: #ffffff;
            margin-top: 80px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-height: 550px;
            max-width: 90%;
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

        .action-icons button {
            background-color: #fff;
            border: none;
            cursor: pointer;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 40px;
            margin-top:-8px;
        }

        .text-center {
            color: brown;
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
            max-height: 470px;
            overflow-y: auto;
        }

        .table thead {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 2;
        }

        hr {
            border: 2px solid white;
            width: 123%;
            margin: 20px auto;
        }

        .mng-btn {
          margin-left: -320px;
            margin-top: -5px;
        }
        .mng-btn0 {
            align-items: left;
            margin-left: -100px;
            margin-top: -5px;
        }
    </style>
</head>

<body>
    <div class="navbar navbar-light">
        <div class="row w-100">
            <div class="col-md-2">
                <img src="imgs/logo.png" alt="Logo" class="img-fluid">
            </div>
            <div class="col-md-8">
                <h6 class="mb-0">Criminology Reviewer Book Management System</h6>
                <h2 class="mb-0"><b>ADMIN | BOOK MANAGEMENT SITE</b>
                    <hr>
                </h2>
            </div>

            <div class="mng-btn0 text-left">
    <a href="admin.php" class="btn btn-warning ml-2">Manage Borrowed Books</a>
</div>


            <div class="mng-btn text-left">
                <button type="button" class="btn btn-success ml-2" onclick="openAddBookModal()">Add Book</button>
            </div>

            <div class="logout-btn text-right">
                <form action="logout.php" method="post" class="logout-btn">
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Search Bar Form -->
    <div class="fixed-search text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6 mb-2">
                <div class="search-box-container rounded p-2">
                    <form class="form-inline" action="book_management.php" method="GET">
                        <div class="input-group w-100">
                            <input type="text" class="form-control" placeholder="Search for Books or Authors"
                                name="query" required
                                value="<?php echo htmlspecialchars($search_query, ENT_QUOTES); ?>">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                            <?php if (!empty($_GET['query'])): ?>
                                <div class="input-group-append">
                                    <a href="book_management.php" class="btn btn-secondary">Clear Search</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search Result Count Box -->
            <div class="col-lg-3 mb-3">
                <div class="search-count-box rounded p-3 bg-light text-danger">
                    <p class="text-muted m-0">
                        <?php echo $search_count; ?> search result
                        <?php echo ($search_count > 1) ? 's' : ''; ?> for "
                        <?php echo htmlspecialchars($search_query, ENT_QUOTES); ?>"
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container table">
        <!-- Display search result count and table -->
        <?php if ($search_count > 0): ?>
            <div class="text-right mb-3">


                <!-- Table to display books -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Book Name</th>
                            <th>Author</th>
                            <th>Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php echo $row["id"]; ?>
                                </td>
                                <td>
                                    <?php echo $row["book_name"]; ?>
                                </td>
                                <td>
                                    <?php echo $row["author"]; ?>
                                </td>
                                <td class="action-icons">
                                    <button class="btn edit-btn" data-id="<?php echo $row['id']; ?>" data-toggle="modal"
                                        data-target="#editModal">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <button class="btn delete-btn" data-id="<?php echo $row['id']; ?>" data-toggle="modal"
                                        data-target="#deleteModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">No results found for "
                <?php echo htmlspecialchars($search_query, ENT_QUOTES); ?>"
            </p>
        <?php endif; ?>

    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Book Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" action="">
                        <div class="form-group p-0">
                            <label for="editBookName">Edit Book Name:</label>
                            <input type="text" class="form-control text-left" id="editBookName" name="editBookName">
                        </div>
                        <div class="form-group p-0">
                            <label for="editAuthor">Edit Author:</label>
                            <input type="text" class="form-control text-left" id="editAuthor" name="editAuthor">
                        </div>
                        <!-- Add additional form fields as needed -->
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this book?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="delete_book.php" method="post">
                        <input type="hidden" name="book_id" id="bookIdToDelete" value="">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this modal code to your HTML body -->
<style>
    /* Add the following CSS styles */
    #addBookModal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        animation: fadeIn 0.3s ease-out;
    }

    #modalOverlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Dark overlay background */
        z-index: 999;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>

<div id="modalOverlay"></div>

<div id="addBookModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Book</h5>
                <button type="button" class="close" onclick="closeAddBookModal()">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addBookForm">
                    <div class="form-group">
                        <label for="bookName">Book Name:</label>
                        <input type="text" class="form-control" id="bookName" name="book_name" required>
                    </div>

                    <div class="form-group">
                        <label for="author">Author:</label>
                        <input type="text" class="form-control" id="author" name="author" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddBookModal()">Close</button>
                <button type="button" class="btn btn-success" onclick="submitAddBookForm()">Add Book</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript function to open the add book modal
    function openAddBookModal() {
        var modal = document.getElementById("addBookModal");
        var overlay = document.getElementById("modalOverlay");
        modal.style.display = "block";
        overlay.style.display = "block";
        setTimeout(function () {
            document.getElementById("bookName").focus();
        }, 100); // Set timeout for focus effect
    }

    // JavaScript function to close the add book modal
    function closeAddBookModal() {
        var modal = document.getElementById("addBookModal");
        var overlay = document.getElementById("modalOverlay");
        modal.style.display = "none";
        overlay.style.display = "none";
    }

    // JavaScript function to submit the add book form
function submitAddBookForm() {
    var form = document.getElementById("addBookForm");
    var formData = new FormData(form);

    // Use fetch API to send data to add_book.php
    fetch('add_book.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Handle the response from add_book.php
        if (data.success) {
            alert('Book successfully added!');
            closeAddBookModal();
            location.reload(); // Reload page after adding a book

        } else {
            alert('Error adding book. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please check the console for details.');
    });
}


</script>
    <!-- Include necessary JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Handle edit button click
            $('.edit-btn').click(function () {
                // Get the book ID from the data-id attribute
                var bookId = $(this).data('id');

                // Set the modal's action attribute dynamically
                var editFormAction = 'edit_book.php?id=' + bookId;
                $('#editForm').attr('action', editFormAction);

                // Populate the modal with existing data
                var bookName = $(this).closest('tr').find('td:eq(1)').text().trim(); // Trim spaces
                var author = $(this).closest('tr').find('td:eq(2)').text().trim(); // Trim spaces


                $('#editBookName').val(bookName);
                $('#editAuthor').val(author);

                // Open the modal
                $('#editModal').modal('show');
            });

            // Handle save changes button click in edit modal
            $('#saveChangesBtn').click(function () {
                // Submit the form when the "Save changes" button is clicked
                $('#editForm').submit();
            });


            // Handle delete button click
            $('.delete-btn').click(function () {
                // Get the book ID from the data-id attribute
                var bookId = $(this).data('id');

                // Set the book ID in the form before showing the modal
                $('#bookIdToDelete').val(bookId);

                // Open the modal
                $('#deleteModal').modal('show');
            });

            // Handle delete button click in delete modal
            $('#deleteBtn').click(function () {
                // Trigger the form submission when the "Delete" button is clicked
                $('#deleteModal form').submit();
            });

        });
    </script>




</body>

</html>