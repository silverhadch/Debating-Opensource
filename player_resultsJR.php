<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$pdo = include("connection_pdo.php");

if (!isset($_SESSION['username_admin'])) {
    header("location:admin_login.php");
    exit();
}

// Fetch user details for dropdown menu
$id = $_SESSION['id'];
$query = $connection_pdo->prepare("SELECT * FROM users WHERE id = :id");
$query->execute(['id' => $id]);
$result = $query->fetch(PDO::FETCH_ASSOC);
$res_username = $result['username'];
//$res_email = $result['email'];
$res_id = $result['id'];

// Fetch player results
$stmt = $connection_pdo->prepare("SELECT players.*, teams.team_name FROM players INNER JOIN teams ON players.TeamID = teams.team_ID WHERE teams.team_name LIKE '%Jr.%' ORDER BY player_Max_points DESC");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Marksheet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- navbar section -->
    <header class="navbar-section">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="images\bertha_inverted_logo.svg" width="130px"></a> Debating Tool
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="admin_home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="results.php">Results Table</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="team_results.php">Results Teams</a>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <a class='nav-link dropdown-toggle' href='#' id='dropdownMenuLink'
                                    data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='bi bi-person'></i>
                                </a>

                                <ul class="dropdown-menu mt-2 mr-0" aria-labelledby="dropdownMenuLink">
                                    <li>
                                        <a class='dropdown-item' href='edit.php?id=<?php echo $res_id; ?>'>Change Profile</a>
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

    <!-- marksheet section -->
    <section class="marksheet-section">
        <div class="container">
            <h2>Player Results</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Speaker ID</th>
                        <th>Speaker Name</th>
                        <th>Team</th>
                        <th>Wins</th>
                        <th>Total Points</th>
                        <th>Max Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo $row['PlayerID']; ?></td>
                            <td><?php echo $row['player_name']; ?></td>
                            <td><?php echo $row['team_name']; ?></td>
                            <td><?php echo $row['player_wins']; ?></td>
                            <td><?php echo $row['player_points']; ?></td>
                            <td><?php echo $row['player_Max_points']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div><!-- /container -->
    </section>

    <!-- footer section -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <p class="logo"><img src="images\bertha_inverted_logo.svg" width="75px"> Debating Tool</p>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <ul class="d-flex">
                        <li><a href="admin_home.php">Home</a></li>
                        <li><a href="results.php">Results Table</a></li>
                        <li><a href="team_results.php">Results Teams</a></li>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
</body>

</html>
