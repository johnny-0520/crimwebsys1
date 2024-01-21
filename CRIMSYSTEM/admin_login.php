<?php
session_start();
include('crimdb.php'); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform the authentication, you may need to adjust this based on your actual authentication mechanism
    $sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);    

    if ($result->num_rows == 1) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url("imgs/bg1.png");
            max-height: 300px;
        }

        .container {
            background-color: #ffffff;
            margin-top: 50px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            max-height: 600px;
        }

        h2,
        h6 {
            color: beige;
            padding-top: 10px;
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
            padding-left: 70px;
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
    width: 113%; /* Adjust the width as needed */
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
                <h2 class="mb-0"><b>ADMINISTRATOR | LOGIN</b>
                    <hr>
                </h2>
            </div>
        </div>
    </div>
</header>

<body>

    <div class="container">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="submit">Login</button>
            <p class="text-center mt-3">Want to borrow books? <a href="index.php">Click Here.</a></p>
            
            
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
