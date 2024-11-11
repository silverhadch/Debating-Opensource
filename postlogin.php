<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Top Bar Navbar -->
    <header class="navbar-section">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="login.php">
                     Debating Tool
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                       
                            
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Form Styles -->
    <style>
        form {
            padding: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="number"], input[type="date"] {
            padding: 5px;
            margin: 10px 0;
            width: 100%;
            max-width: 300px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin: 5px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>

    <!-- Main Content -->
    <form action="postlogin.php" method="post">
        <?php
        ob_start();
        session_start();
        require_once "admin.dbh.php";
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        require_once('connection_pdo.php');

        if (!isset($_SESSION['username_admin'])) {
            header("location:admin_login.php");
            exit();
        }

        // Function to display confirmation button
        function displayConfirmationButton($action) {
            echo "<h2>Confirm Action: $action</h2>";
            echo "<form method='post' action='postlogin.php'>";
            echo "<input type='hidden' name='confirmed_action' value='$action'>";
            echo "<button type='submit' name='confirm_action'>Confirm</button>";
            echo "</form>";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['addRoom'])) {
                $roomNumber = $_POST['roomNumber'];
                addRoomToDatabase($connection_pdo, $roomNumber);
            } elseif (isset($_POST['changeSchoolName'])) {
                $schoolName = $_POST['schoolName'];
                updateHostName($connection_pdo, $schoolName);
            } elseif (isset($_POST['echoAllTables'])) {
                echoAllTables($connection_pdo);
            } elseif (isset($_POST['startlock'])) {
                displayConfirmationButton('startlock');
            } elseif (isset($_POST['marksheet'])) {
                echoMarksheet($connection_pdo);
            } elseif (isset($_POST['endlock'])) {
                displayConfirmationButton('endlock');
            } elseif (isset($_POST['resetto0'])) {
                displayConfirmationButton('resetto0');
            } elseif (isset($_POST['date'])) {
                $newDate = $_POST['newDate'];
                updateCompetitionDate($newDate, $connection_pdo);
            } elseif (isset($_POST['totaldrop'])) {
                displayConfirmationButton('totaldrop');
            } elseif (isset($_POST['confirm_action'])) {
                // Handle confirmed actions
                $confirmed_action = $_POST['confirmed_action'];

                switch ($confirmed_action) {
                    case 'startlock':
                        startlock($connection_pdo);
                        echo "Database lock started.";
                        break;
                    case 'endlock':
                        endlock($connection_pdo);
                        echo "Database lock ended.";
                        break;
                    case 'resetto0':
                        resetnodel($connection_pdo);
                        echo "All points and debate records reset to 0.";
                        break;
                    case 'totaldrop':
                        totaldrop($connection_pdo);
                        echo "Absolute delete executed.";
                        break;

                    default:
                        echo "Invalid action.";
                        break;
                }
            } elseif (isset($_POST['edit'])) {
                ed($connection_pdo);  // Ensure this is working as expected
            } elseif (isset($_POST['update'])) {
                upd($connection_pdo);  // Ensure this is working as expected
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Home'])) {
            header("Location: admin_home.php");
            exit();
        }

        ob_end_flush();
        ?>

        <label for="debate_round">Debate Round:</label>
        <input type="text" id="debate_round" name="debate_round" value="<?php if(isset($_POST['debate_round'])) echo htmlspecialchars($_POST['debate_round'], ENT_QUOTES); ?>">

        <label for="debate_pro_TeamID">Pro Team ID:</label>
        <input type="text" id="debate_pro_TeamID" name="debate_pro_TeamID" value="<?php if(isset($_POST['debate_pro_TeamID'])) echo htmlspecialchars($_POST['debate_pro_TeamID'], ENT_QUOTES); ?>">

        <label for="debate_con_TeamID">Con Team ID:</label>
        <input type="text" id="debate_con_TeamID" name="debate_con_TeamID" value="<?php if(isset($_POST['debate_con_TeamID'])) echo htmlspecialchars($_POST['debate_con_TeamID'], ENT_QUOTES); ?>">

        <input type="submit" name="edit" value="Edit Marksheet">
        <input type="submit" name="marksheet" value="Echo Marksheet">
        <br><br>
        <input type="submit" name="echoAllTables" value="Echo All Tables">
        <br><br>

        <label for="newDate">New Date:</label>
        <input type="date" id="newDate" name="newDate" value="<?php if(isset($_POST['newDate'])) echo htmlspecialchars($_POST['newDate'], ENT_QUOTES); ?>">
        <input type="submit" name="date" value="Update Competition Date">
        <br><br>
        <input type="submit" name="startlock" value="Start Lock">
        <input type="submit" name="endlock" value="End Lock">
        <br><br>

        <!-- Add Room Input and Button -->
        <label for="roomNumber">Room Number:</label>
        <input type="number" id="roomNumber" name="roomNumber" placeholder="Enter room number">
        <input type="submit" name="addRoom" value="Add Room">
        <br>

        <!-- Change Host School Name Input and Button -->
        <label for="schoolName">School Name for Host:</label>
        <input type="text" id="schoolName" name="schoolName" placeholder="Enter school name">
        <input type="submit" name="changeSchoolName" value="Change School Name">
        <br>

        <br>
        <input type="submit" name="resetto0" value="Reset to 0">
        <input type="submit" name="totaldrop" value="Total Drop">
    </form>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <p class="logo">
                         Debating Tool
                    </p>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <ul class="d-flex">
                        <li><a href="admin_login.php">Home</a></li>
                        
                        
                    </ul>
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <p>&copy;
                        <a href="https://github.com/Cybersky4" target="_blank" style="color: inherit; text-decoration: underline;">2024_Cyber</a>,
                        <a href="https://github.com/silverhadch" target="_blank" style="color: inherit; text-decoration: underline;">Hadi Chokr</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
