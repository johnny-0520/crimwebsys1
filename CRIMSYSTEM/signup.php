<?php
include('crimdb.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user signup data
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $middle_name = $_POST["middle_name"];
    $suffix_name = $_POST["suffix_name"];
    $address = $_POST["address"];
    $contact_number = $_POST["contact_number"];
    $year_level = $_POST["year_level"];
    $section = $_POST["section"];

    // Insert user information into the 'users' table
    $userInsertStmt = $conn->prepare("INSERT INTO users (first_name, last_name, middle_name, suffix_name, address, contact_number, year_level, section) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$userInsertStmt) {
        die("Error in prepared statement: " . $conn->error);
    }

    $userInsertStmt->bind_param("ssssssss", $first_name, $last_name, $middle_name, $suffix_name, $address, $contact_number, $year_level, $section);
    $userInsertResult = $userInsertStmt->execute();

    if (!$userInsertResult) {
        die("Error executing user insert statement: " . $userInsertStmt->error);
    }

    $userInsertStmt->close();

    // Retrieve the user's ID after insertion
    $user_id = $conn->insert_id;

    // Close the database connection
    $conn->close();

    // Display success message and user information using JavaScript
    echo '<script type="text/javascript">';
    echo 'var successMessage = "SIGN UP SUCESS! \\nID Number: ' . $user_id . '\\nLast Name: ' . $last_name . '\\nFirst Name: ' . $first_name . '\\nMiddle Name: ' . $middle_name . '";';
    echo 'alert(successMessage);';
    echo 'window.location.href = "index.php";';  // Redirect to a success page
    echo '</script>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criminology Reviewer Book Management System - Signup</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
</head>
<div class="navbar navbar-light">
        <div class="row w-100">
            <div class="col-md-2">
                <!-- Add your logo image here -->
                <img src="imgs/logo.png" alt="Logo" class="img-fluid">
            </div>
            <div class="col-md-8">
                <h6 class="mb-0">Criminology Reviewer Book Management System</h6>
                <h2 class="mb-0"><b>SIGN UP | REGISTER<hr></b>
                </h2>
            </div>
        </div>
</div>
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
    width: 115%; /* Adjust the width as needed */
    margin: 20px auto; /* Adjust the margin as needed */
}
</style>

<body>
    <div class="container">
        <form id="signupForm" method="post" action="signup.php">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="first_name">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" pattern="[A-Za-z]+"
                        title="Letters only" required>
                    <small id="first_name_error" class="text-danger"></small>
                </div>
                <div class="form-group col-md-6">
                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" pattern="[A-Za-z]+"
                        title="Letters only" required>
                    <small id="last_name_error" class="text-danger"></small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="middle_name">Middle Name:</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name" pattern="[A-Za-z]+"
                        title="Letters only">
                    <small id="middle_name_error" class="text-danger"></small>
                </div>
                <div class="form-group col-md-6">
                    <label for="suffix_name">Suffix:</label>
                    <input type="text" class="form-control" id="suffix_name" name="suffix_name" pattern="[A-Za-z]+"
                        title="Letters only" !required>
                    <small id="suffix_name_error" class="text-danger"></small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="address">Address:</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                    <small id="address_error" class="text-danger"></small>
                </div>
                <div class="form-group col-md-6">
                    <label for="contact_number">Contact Number:</label>
                    <input type="tel" class="form-control" id="contact_number" name="contact_number" pattern="[0-9]+"
                        title="Numbers only" required>
                    <small id="contact_number_error" class="text-danger"></small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="year_level">Year Level:</label>
                    <input type="text" class="form-control" id="year_level" name="year_level" required>
                    <small id="year_level_error" class="text-danger"></small>
                </div>
                <div class="form-group col-md-6">
                    <label for="section">Section:</label>
                    <input type="text" class="form-control" id="section" name="section" required>
                    <small id="section_error" class="text-danger"></small>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Signup</button>
            <p class="text-center mt-3">Already have an account? <a href="index.php">Borrow books now.</a></p>
        </form>



        <script>
            function validateInput(inputField, pattern, errorMessageId) {
                var value = inputField.value;
                var isValid = pattern.test(value);
                var errorElement = document.getElementById(errorMessageId);

                if (!isValid) {
                    errorElement.textContent = 'Invalid input. Please enter the correct format.';
                    inputField.setCustomValidity('Invalid input. Please enter the correct format.');
                } else {
                    errorElement.textContent = '';
                    inputField.setCustomValidity('');
                }
            }

            document.getElementById('first_name').addEventListener('input', function (event) {
                validateInput(event.target, /^[A-Za-z]+$/, 'first_name_error');
            });

            document.getElementById('last_name').addEventListener('input', function (event) {
                validateInput(event.target, /^[A-Za-z]+$/, 'last_name_error');
            });

            document.getElementById('middle_name').addEventListener('input', function (event) {
                validateInput(event.target, /^[A-Za-z]+$/, 'middle_name_error');
            });

            document.getElementById('suffix_name').addEventListener('input', function (event) {
                validateInput(event.target, /^[A-Za-z]+$/, 'suffix_name_error');
            });

            document.getElementById('address').addEventListener('input', function (event) {
                validateInput(event.target, /^[\s\S]*$/, 'address_error');
            });

            document.getElementById('contact_number').addEventListener('input', function (event) {
                validateInput(event.target, /^[0-9]+$/, 'contact_number_error');
            });

            document.getElementById('year_level').addEventListener('input', function (event) {
                validateInput(event.target, /^[\s\S]*$/, 'year_level_error');
            });

            document.getElementById('section').addEventListener('input', function (event) {
                validateInput(event.target, /^[\s\S]*$/, 'section_error');
            });
        </script>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>