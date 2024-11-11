<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "connection_pdo.php";

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marksheet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- navbar section   -->

    <header class="navbar-section">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"></i> Debating Tool</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">home</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="marksheet.php">restart</a>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <a class='nav-link dropdown-toggle' href='edit.php?id=$res_id' id='dropdownMenuLink'
                                    data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='bi bi-person'></i>
                                </a>


                                <ul class="dropdown-menu mt-2 mr-0" aria-labelledby="dropdownMenuLink">

                                    <li>
                                        <?php

                                        $id = $_SESSION['id'];
                                        $stmt = $connection_pdo->prepare("SELECT * FROM users WHERE id = :id");
                                        $stmt->bindParam(':id', $id);
                                        $stmt->execute();
                                        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                                        echo "<a class='dropdown-item' href='edit.php?id={$user['id']}'>Change Profile</a>";

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
    <!-- marksheet section   -->
    <section class="marksheet-section">
        <div class="container">

            <div class="row gy-4">

                <div class="col-sm-3">

                </div>

                <div class="col-lg-6">
                    <h1>Marksheet</h1>
                    <h3>Start a new debate Marksheet:</h3>
                    <form action="start_marksheet.php" method="post" class="php-email-form">
                        <div>
                            <div class="row">
                                <div class="col-3 col-sm-6">
                                    <h2>Motion</h2>
                                    <select id="debate_round" name="debate_round" class="form-control" required>
                                        <option selected value="">Select Motion</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                                <div class="col-3 col-sm-6">
                                    <h2>Room</h2>
                                    <select id="debate_room" name="debate_room" class="form-control" required>
                                        <option selected value=""> Select a Room </option>
                                        <?php
                                        $stmt = $connection_pdo->prepare('SELECT * FROM rooms ORDER BY Room_Name ASC');
                                        $stmt->execute();
                                        $data_rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($data_rooms as $row):
                                        ?>
                                            <option value="<?php echo $row["Room_ID"];?>"><?php echo $row["Room_Name"];?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <br>
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 col-sm-6">
                                    <h2>Pro Team</h2>
                                    <select id="debate_pro_TeamID" name="debate_pro_TeamID" class="form-control" required>
                                        <option selected value="">Select Pro Team</option>
                                        <?php
                                        $stmt = $connection_pdo->prepare('SELECT * FROM teams ORDER BY team_name ASC');
                                        $stmt->execute();
                                        $data_teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($data_teams as $row):
                                        ?>
                                            <option value="<?php echo $row["Team_ID"];?>"><?php echo $row["team_name"];?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <br>
                                </div>
                                <div class="col-3 col-sm-6">
                                    <h2>Contra Team</h2>
                                    <select id="debate_con_TeamID" name="debate_con_TeamID" class="form-control" required>
                                        <option selected value="">Select Contra Team</option>
                                        <?php foreach ($data_teams as $row): ?>
                                            <option value="<?php echo $row["Team_ID"];?>"><?php echo $row["team_name"];?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <br>
                                </div>
                            </div>
                            <br>
                            <center>
                                <button type="submit" name="mark" class="btn btn-primary">Submit</button>
                            </center>
                            <br>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </section>
    <!-- footer section   -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <p class="logo"></i> Debating Tool</p>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <ul class="d-flex">
                        <li><a href="home.php">Home</a></li>
                        <li><a href="#">Marksheet</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-12 col-sm-12">
    <p>&copy;
        <a href="https://github.com/Cybersky4" target="_blank" style="color: inherit; text-decoration: underline;">2024_Cyber</a>,
        <a href="https://github.com/silverhadch" target="_blank" style="color: inherit; text-decoration: underline;">Hadi</a>
    </p>
</div>


                <div class="col-lg-1 col-md-12 col-sm-12">
                    <!-- back to top  -->
                    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                            class="bi bi-arrow-up-short"></i></a>
                </div>

            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
</body>

</html>
