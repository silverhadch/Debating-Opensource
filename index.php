<!DOCTYPE html>
<html lang="en">
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .hero-section {
            text-align: center;
        }

        .button-group {
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        .button-group .btn {
            font-size: 1.25rem; /* Larger font size */
            padding: 10px 25px; /* Increase padding for bigger buttons */
            min-width: 150px; /* Minimum button width */
        }
    </style>
</head>

<body>
    <!-- navbar section -->
    <header class="navbar-section">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="login.php">
                    <img src="images/bertha_inverted_logo.svg" width="130px"> Debating Tool
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- hero section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 text-content">
                    <h1>Debating Tool</h1>
                    <p>
                        <?php
                        require_once("connection_pdo.php");
                        try {
                            $stmt = $connection_pdo->query("SELECT competition_date FROM dates WHERE id = 1");
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($row) {
                                $competition_date = date("d.m.Y", strtotime($row['competition_date']));
                                $st = $connection_pdo->query("SELECT name FROM host");
                                $rw = $st->fetch(PDO::FETCH_ASSOC);
                                echo "For the Debating Contest at the <br> {$rw['name']} <br> on the $competition_date";
                            } else {
                                echo "Competition date not found.";
                            }
                        } catch (PDOException $e) {
                            echo "Error fetching competition date: " . $e->getMessage();
                        }
                        ?>
                    </p>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 d-flex flex-column align-items-center">
                    <!-- Buttons next to the logo -->
                    <div class="button-group">
                        <a href="login.php" class="btn btn-primary btn-lg">Login</a>
                        <a href="admin_home.php" class="btn btn-secondary btn-lg">Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- contact section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-3 col-md-12 col-sm-12 col-5">
                    <h1 class="logo"><i class="bi bi-chat"></i> Contact:</h1>
                </div>
                <div class="col-lg-6 form">
                    <form action="contact.php" method="POST" class="php-email-form">
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                            </div>
                            <div class="col-md-12">
                                <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" name="submit">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- footer section -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <p class="logo">
                        <img src="images/bertha_inverted_logo.svg" width="75px"> Debating Tool
                    </p>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <ul class="d-flex">
                        <li><a href="login.php">Home</a></li>
                        <li><a href="#contact">contact</a></li>
                        <li><a href="admin_login.php">Admin</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <p>&copy;
                        <a href="https://github.com/Cybersky4" target="_blank" style="color: inherit; text-decoration: underline;">2024_Cyber</a>,
                        <a href="https://github.com/silverhadch" target="_blank" style="color: inherit; text-decoration: underline;">Hadi</a>
                    </p>
                </div>
                <div class="col-lg-1 col-md-12 col-sm-12">
                    <!-- back to top -->
                    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                            class="bi bi-arrow-up-short"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>