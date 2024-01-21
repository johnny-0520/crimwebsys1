<?php
include('crimdb.php');

// Search functionality
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["query"])) {
    $searchQuery = $_GET["query"];

    $sql = "SELECT id, book_name, author FROM books WHERE book_name LIKE '%$searchQuery%' OR author LIKE '%$searchQuery%'";
    $result = $conn->query($sql);
}

// Borrowing functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] === "borrow" && isset($_POST["id"]) && isset($_POST["user_id"])) {
    $bookId = $_POST["id"];
    $userId = $_POST["user_id"];

    // Check if the user_id exists in the users table
    $checkUserStmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $checkUserStmt->bind_param("i", $userId);
    $checkUserStmt->execute();
    $checkUserResult = $checkUserStmt->get_result();
    $checkUserStmt->close();

    if ($checkUserResult->num_rows > 0) {
        // User ID exists, proceed with borrowing

        // Fetch the user's complete name
        $userNameStmt = $conn->prepare("SELECT CONCAT(last_name, ', ', first_name, ' ', middle_name, ' ', suffix_name) AS full_name FROM users WHERE id = ?");
        $userNameStmt->bind_param("i", $userId);
        $userNameStmt->execute();
        $userNameResult = $userNameStmt->get_result();
        $userName = $userNameResult->fetch_assoc()['full_name'];
        $userNameStmt->close();

        // Proceed with borrowing
        $stmt = $conn->prepare("INSERT INTO borrowed_books (user_id, book_id, borrow_date, return_schedule) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY))");
        $stmt->bind_param("ii", $userId, $bookId);
        $stmt->execute();
        $stmt->close();

        // Get information about the borrowed book
        $bookInfoStmt = $conn->prepare("SELECT book_name, author, borrow_date, return_schedule FROM books INNER JOIN borrowed_books ON books.id = borrowed_books.book_id WHERE books.id = ?");
        $bookInfoStmt->bind_param("i", $bookId);
        $bookInfoStmt->execute();
        $bookInfoResult = $bookInfoStmt->get_result();
        $bookInfo = $bookInfoResult->fetch_assoc();
        $bookInfoStmt->close();

        // Set success message with complete name
        $successMessage = "<h3><b>Book successfully borrowed!</b></h3><br>
                        <u><b>Details</b></u><t><br>
                        Borrower Name: " . $userName . "<br>
                        Book Name: " . $bookInfo["book_name"] . "<br> Author: " . $bookInfo["author"] . "<br> Borrow Date - " . $bookInfo["borrow_date"] . "<br> Return Schedule - " . $bookInfo["return_schedule"];
    } else {
        // User ID does not exist, show error message
        $errorMessage = "<h3><b>Error: User ID not found!</b></h3>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criminology Reviewer Book Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url("imgs/bg1.png");
            max-height: 300px;
        }

        .container {
            background-color: #ffffff;
            margin-top: 10px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            max-height: 600px;

        }

        h2,
        h6 {
            color: beige;
        }
        .img {
            max-width: 350px;
            max-height: 400px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }


        .navbar-nav .nav-link {
            color: #ffffff !important;
            font-size: 20px;
        }

        .mb-4 {
            margin-top: -20px;
            max-height: 500px;
            overflow-y: auto;
        }

        .table thead {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            /* Add your desired background color */
            z-index: 1;
        }

        .img-fluid {
            padding-left: 100px;
        }

        .navbar {
            padding-top: 30px;
            background-color: transparent;
        }
        .nav-link{
          margin-top: -50px;
          text-transform: uppercase;
          padding-left: 50px;
        }
        hr {
    border: 2px solid white; /* Set the desired border width and color */
    width: 122%; /* Adjust the width as needed */
    margin: 20px auto; /* Adjust the margin as needed */
}

        
    </style>
</head>
<header>
    <div class="navbar navbar-light">
        <div class="row w-100">
            <div class="col-md-2">
                <!-- Add your logo image here -->
                <img src="imgs/logo.png" alt="Logo" class="img-fluid">
            </div>
            <div class="col-md-8">
                <h6 class="mb-0">Criminology Reviewer Book Management System</h6>
                <h2 class="mb-0"><b>ADMIN DASHBOARD<hr></b>
                </h2>
            </div>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="signup.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin_login.php">Admin</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
</div>

</header>

<body>

    <div class="container">
        <div class="row">
            <!-- Search Box -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Search for a Book</h5>
                        <form id="searchForm" method="get" action="">
                            <div class="form-group">
                                <label for="searchQuery">Search:</label>
                                <input type="text" class="form-control" id="searchQuery" name="query" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>

                <!-- Display Success Message -->
                <?php if (isset($successMessage)): ?>
                    <div class="alert alert-success mt-4" role="alert">
                        <?php echo $successMessage; ?>
                    </div>
                <?php endif; ?>

                <!-- Display Error Message -->
                <?php if (isset($errorMessage)): ?>
                    <div class="alert alert-danger mt-4" role="alert">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Display Search Results -->
            <div class="col-md-6 results">
                <img src="" alt="">
                <!-- Box for Search Results -->
                <div class="card mb-4">
                    <div class="card-body">
                        <?php if (isset($result)): ?>
                            <?php if ($result->num_rows > 0): ?>
                                <h5 class="card-title">Search Results</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Book Name</th>
                                            <th>Author</th>
                                            <th>Action</th>
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
                                                <td>
                                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                                        data-target="#borrowModal" data-book-id="<?php echo $row["id"]; ?>">
                                                        Borrow
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No results found.</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <h5 class="card-title"><img src="mag.png" class="img" alt=""></h5>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow Modal -->
    <div class="modal fade" id="borrowModal" tabindex="-1" role="dialog" aria-labelledby="borrowModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="borrowModalLabel">Enter Your User ID</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="user_id_modal">Your User ID:</label>
                            <input type="text" class="form-control" id="user_id_modal" name="user_id" required>
                        </div>
                        <input type="hidden" name="action" value="borrow">
                        <input type="hidden" name="id" id="book_id_modal" value="">
                        <button type="submit" class="btn btn-success">Borrow</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Set the book_id value in the modal when the Borrow button is clicked
        $(document).ready(function () {
            $('#borrowModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var bookId = button.data('book-id');
                $('#book_id_modal').val(bookId);
            });
        });
    </script>
</body>

</html>