<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("connection_pdo.php");

if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Make the admin panel button fixed at the bottom */
        .admin-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 25px;
            font-size: 18px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 50px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* Make the marksheet button bigger and more prominent */
        .marksheet-btn {
            padding: 20px 40px;
            font-size: 24px;
            background-color: #007bff;
            color: white;
            border-radius: 8px;
            transition: background-color 0.3s;
            display: inline-block;
        }

        .marksheet-btn:hover {
            background-color: #0056b3;
        }

        /* Add a subtle background color and some padding to fill space */
        .main-content {
            padding: 50px 20px;
            background-color: #f9f9f9;
            min-height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        /* Decorative element for filling empty space */
        .decorative-box {
            width: 100%;
            height: 50px;
            background-color: #efefef;
            margin-top: 30px;
            border-radius: 10px;
        }

        /* Add simple text under the blank space */
        .simple-text {
            font-family: Arial, sans-serif;
            font-size: 18px;
            color: #555;
            text-align: center;
            margin-top: 40px;
        }

    </style>
</head>

<body>

    <!-- navbar section -->
    <header class="navbar-section">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="images\bertha_inverted_logo.svg" width="130px"> Debating Tool</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="room_plan.php">Room Plan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="marksheet.php">Marksheet</a>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person"></i>
                                </a>
                                <ul class="dropdown-menu mt-2 mr-0" aria-labelledby="dropdownMenuLink">
                                    <li>
                                        <?php
                                        // Fetch user information
                                        $id = $_SESSION['id'];
                                        $stmt = $connection_pdo->prepare("SELECT * FROM users WHERE id = :id");
                                        $stmt->bindParam(':id', $id);
                                        $stmt->execute();
                                        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                                        if ($user) {
                                            echo "<a class='dropdown-item' href='edit.php?id={$user['id']}'>Change Profile</a>";
                                        }
                                        ?>
                                    </li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="name">
        <center>Welcome
            <?php echo $_SESSION['username']; ?>
            !
        </center>
    </div>

    <section class="main-content">
        <div>
            <!-- Make the marksheet button more prominent -->
            <a href="marksheet.php" class="btn marksheet-btn">Go to Marksheet</a>
        </div>

        <!-- Decorative element to fill space -->
        <div class="decorative-box"></div>

        <!-- Add simple text below the blank space -->
        <div class="simple-text">
            <p>Welcome to the Debating Tool System. Feel free to explore the available features!</p>
        </div>
    </section>

    <!-- Add the admin panel button at the bottom right -->
    <a href="admin_home.php" class="btn admin-btn">Go to Admin Panel</a>

    <!-- footer section -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <p class="logo"><img src="images\bertha_inverted_logo.svg" width="75px"> Debating Tool</p>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <ul class="d-flex">
                        <li><a href="home.php">Home</a></li>
                        
                        <li><a href="marksheet.php">Marksheet</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <p>&copy;
                        <a href="https://github.com/Cybersky4" target="_blank" style="color: inherit; text-decoration: underline;">2024_Cyber</a>,
                        <a href="https://github.com/silverhadch" target="_blank" style="color: inherit; text-decoration: underline;">2024_silverhadch</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz4fnFO9gybHiEBh8R+Klv0CpYn3zC8a8gWm2b3ddp6K69YYcR8WoZP4p6e"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-cuFcMI3J9YgMZ9xgjQwAri1XshYqRf01Ok3Lg4tLft7B9tvzF7Ib54f9prWfpyiY"
        crossorigin="anonymous"></script>

</body>

</html>