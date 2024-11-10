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
    <title>Start Marksheet</title>

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
                <a class="navbar-brand" href="#"><img src="images\bertha_inverted_logo.svg" width="130px"></i> Debating Tool</a>
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
                                            $query = $connection_pdo->prepare("SELECT * FROM users WHERE id = :id");
                                            $query->execute(['id' => $id]);
                                            $result = $query->fetch(PDO::FETCH_ASSOC);
                                            $res_username = $result['username'];
                                            //$res_email = $result['email'];
                                            $res_id = $result['id'];



                                        echo "<a class='dropdown-item' href='edit.php?id=$res_id'>Change Profile</a>";


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
    <?php
        require_once "dbh.inc.php";

        $smt = $connection_pdo->prepare('SELECT * From teams');
        $smt->execute();
        $data_teams = $smt->fetchAll();

        $smt = $connection_pdo->prepare('SELECT * From rooms');
        $smt->execute();
        $data_rooms = $smt->fetchAll();


        $room = $_POST["debate_room"];
        $teampro = $_POST["debate_pro_TeamID"];
        $teamcon = $_POST["debate_con_TeamID"];

        // Fetch data for teampro and teamcon only
        $smt = $connection_pdo->prepare('SELECT * FROM teams WHERE Team_ID IN (:teampro, :teamcon)');
        $smt->execute(['teampro' => $teampro, 'teamcon' => $teamcon]);
        $data_teamsselect = $smt->fetchAll();


        $smt = $connection_pdo->prepare('SELECT * From players where TeamID = :teamID');
        $smt->execute(['teamID' => $teamcon]);
        $data_team_con = $smt->fetchAll();

        $smt = $connection_pdo->prepare('SELECT * From players where TeamID = :teamID');
        $smt->execute(['teamID' => $teampro]);
        $data_team_pro = $smt->fetchAll();

        $smt = $connection_pdo->prepare('SELECT * From players where TeamID = :teamIDPro OR TeamID = :teamIDCon');
        $smt->execute(['teamIDPro' => $teampro, 'teamIDCon' => $teamcon]);
        $data_team_both = $smt->fetchAll();

        $smt = $connection_pdo->prepare('SELECT team_name From teams where Team_ID = :teamID');
        $smt->execute(['teamID' => $teampro]);
        $name_team_pro = $smt->fetchAll();

        $smt = $connection_pdo->prepare('SELECT team_name From teams where Team_ID = :teamID');
        $smt->execute(['teamID' => $teamcon]);
        $name_team_con = $smt->fetchAll();

        ?>

    <section class= "marksheet-section">
        <div class="container">

            <div class="row gy-4">

                <div class="col-sm-3">

                </div>

                <div class="col-lg-6">
                    <h1>Marksheet</h1>
                    <form action="submit_marksheet.php" method="POST" class="php-email-form">
                            <div>

                                    <input type="hidden" name="selected_debate_room" id="selected_debate_room"  value="<?php if(isset($_POST['debate_room'])) echo $_POST['debate_room'] ?>" >
                                    <input type="hidden" name="debate_round" id="debate_round"  value="<?php if(isset($_POST['debate_round'])) echo $_POST['debate_round'] ?>" >
                                    <input type="hidden" name="start_debate_pro_TeamID" id="start_debate_pro_TeamID"  value="<?php if(isset($_POST['debate_pro_TeamID'])) echo $_POST['debate_pro_TeamID'] ?>">
                                    <input type="hidden" name="start_debate_con_TeamID" id="start_debate_con_TeamID"  value="<?php if(isset($_POST['debate_con_TeamID'])) echo $_POST['debate_con_TeamID'] ?>" >
                                        <div class="row">
                                            <h3 class="col-sm-6">Runde:  <?php if(isset($_POST['debate_round'])) echo $_POST['debate_round'] ?></h3>
                                            <h3 class="col-sm-6">Raum:  <?php if(isset($_POST['debate_room'])) echo $_POST['debate_room'] ?></h3>
                                        </div>
                                        <div class="row">
                                            <h3 class="col-sm-6">Team Pro:  <?php if(isset($_POST['debate_pro_TeamID'])) foreach ($name_team_pro as $row): echo $row["team_name"]?><?php endforeach ?></h3>
                                            <h3 class="col-sm-6">Team Con:  <?php if(isset($_POST['debate_con_TeamID'])) foreach ($name_team_con as $row): echo $row["team_name"]?><?php endforeach ?></h3>
                                            <br>
                                            <br>
                                        </div>
                                        <div>

                                            <div class="row">
                                                <h3>Speakers Pro</h3>
                                                <div class="form col-sm-6">
                                                    <select class="form-select" id="debate_pro_Player_1" name="debate_pro_Player_1" required>
                                                            <option selected value="">Speaker Pro 1</option>
                                                                <?php foreach ($data_team_pro as $row): ?>
                                                                    <option value="<?php echo $row["PlayerID"];?>"><?=$row["player_name"];?></option>
                                                                <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="form col-sm-6">
                                                    <input type="number" id="debate_points_pro_Player_1" name="debate_points_pro_Player_1" placeholder="Points" step="0.5" required  min= "0" max="100">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form col-sm-6">
                                                        <select class="form-select" id="debate_pro_Player_2" name="debate_pro_Player_2" required>
                                                            <option selected value="">Speaker Pro 2</option>
                                                            <?php foreach ($data_team_pro as $row): ?>
                                                                <option value="<?php echo $row["PlayerID"];?>"><?=$row["player_name"];?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                </div>
                                                <div class="form col-sm-6">
                                                    <input type="number" id="debate_points_pro_Player_2" name="debate_points_pro_Player_2" placeholder="Points" step="0.5" required min="0" max="100">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form col-sm-6">
                                                        <select class="form-select" id="debate_pro_Player_3" name="debate_pro_Player_3" required>
                                                            <option selected value="">Speaker Pro 3</option>
                                                            <?php foreach ($data_team_pro as $row): ?>
                                                                <option value="<?php echo $row["PlayerID"];?>"><?=$row["player_name"];?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                </div>
                                                <div class="form col-sm-6">
                                                    <input type="number" id="debate_points_pro_Player_3" name="debate_points_pro_Player_3" placeholder="Points" step="0.5" required min="0" max="100">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form col-sm-6">
                                                        <select class="form-select" id="debate_pro_Player_reply" name="debate_pro_Player_reply" required>
                                                            <option selected value="">Speaker Pro Reply</option>
                                                            <?php foreach ($data_team_pro as $row): ?>
                                                                <option value="<?php echo $row["PlayerID"];?>"><?=$row["player_name"];?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                </div>
                                                <div class="form col-sm-6">
                                                    <input type="number" id="debate_points_pro_Player_reply" name="debate_points_pro_Player_reply" placeholder="Points" step="0.5" required min="0" max="40">
                                                </div>
                                            </div>
                                    </div>

                                    <div>
                                        <div class="row">
                                            <h3>Speakers Contra</h3>
                                            <div class="form col-sm-6">
                                                    <select class="form-select" id="debate_con_Player_1" name="debate_con_Player_1" required>
                                                        <option selected value="">Speaker Con 1</option>
                                                        <?php foreach ($data_team_con as $row): ?>
                                                            <option value="<?php echo $row["PlayerID"];?>"><?=$row["player_name"];?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                            </div>
                                            <div class="form col-sm-6">
                                                <input type="number" id="debate_points_con_Player_1" name="debate_points_con_Player_1" placeholder="Points" step="0.5" required min="0" max="100">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form col-sm-6">
                                                    <select class="form-select" id="debate_con_Player_2" name="debate_con_Player_2" required>
                                                        <option selected value="">Speaker Con 2</option>
                                                        <?php foreach ($data_team_con as $row): ?>
                                                            <option value="<?php echo $row["PlayerID"];?>"><?=$row["player_name"];?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                            </div>
                                            <div class="form col-sm-6">
                                                <input type="number" id="debate_points_con_Player_2" name="debate_points_con_Player_2" placeholder="Points" step="0.5" required min="0" max="100">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form col-sm-6">
                                                    <select class="form-select" id="debate_con_Player_3" name="debate_con_Player_3" required>
                                                        <option selected value="">Speaker Con 3</option>
                                                        <?php foreach ($data_team_con as $row): ?>
                                                            <option value="<?php echo $row["PlayerID"];?>"><?=$row["player_name"];?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                            </div>
                                            <div class="form col-sm-6">
                                                <input type="number" id="debate_points_con_Player_3" name="debate_points_con_Player_3" placeholder="Points" step="0.5" required min="0" max="100">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form col-sm-6">
                                                    <select class="form-select" id="debate_con_Player_reply" name="debate_con_Player_reply" required>
                                                        <option selected value="">Speaker Con Reply</option>
                                                        <?php foreach ($data_team_con as $row): ?>
                                                            <option value="<?php echo $row["PlayerID"];?>"><?=$row["player_name"];?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                            </div>
                                            <div class="form col-sm-6">
                                                <input type="number" id="debate_points_con_Player_reply" name="debate_points_con_Player_reply" placeholder="Points" step="0.5" required min="0" max="37.5">
                                            </div>
                                        </div>
                                </div>
                                <div class="row">
    <div class="col-sm-6">
        <h2>Best Speaker</h2>
        <div class="form">
            <select class="form-select" name="debate_bestplayer_ID" id="debate_bestplayer_ID" required>
                <option selected value="">Select best Speaker</option>
                <?php foreach ($data_team_both as $row): ?>
                    <option value="<?php echo $row["PlayerID"];?>"><?=$row["player_name"];?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>

    <div class="col-sm-6">
        <h2>Winner Team</h2>
        <div class="form">
            <select class="form-select" id="debate_winner_TeamID" name="debate_winner_TeamID" required>
                <option selected value="">Select Winner Team</option>
                <?php foreach ($data_teamsselect as $team): ?>
                    <option value="<?= $team['Team_ID']; ?>"><?= $team['team_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

                                <br>
                                <br>
                                <center>
                                <button type="submit" name="Rechnen" value="Submit"> Submit </button>
                                </center>
                                <br>
                            </div>
                        </div>
                    </form>
                        <?php
                        ob_start(); // Starte den Output-Buffer  weil beim reloaden endDebate Funktion herrumbitcht
                        //Diese datei wird in zukunft durchs frontend nicht mehr index sein,also lies die komentare
                        //Beim ändern wichtig: die header Location beim abschickbutton auch ändern zurück an diese datei


                        // Datenbankmanager einbinden/ Databasemanager einbinden


                        //Startet main funktion auch wenn es nicht wichtig ist da mein Gehirn in java gefangen ist

                        // Einstiegspunkt für das Skript/ Hauptfunktion

                            //Datenbank verbindung herstellen


                            //Wenn rehcnen sumbit button gedrückt wird, wird die setterVraiblen Funktion für setten der werte und rechnen ausgeführt
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Rechnen']) && $_POST['Rechnen'] == 'Submit') {
                                setterVariables();
                                ehcofunc($connection_pdo);//Debugausgabe
                            }





                        ob_end_flush(); // Sende den gepufferten Output

                        ?>
                </div>

            </div>

        </div>
    </section>


                            <!-- footer section   -->

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <p class="logo"><img src="images\bertha_inverted_logo.svg" width="75px"></i> Debating Tool</p>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <ul class="d-flex">
                        <li><a href="home.php">Home</a></li>
                        <li><a href="marksheet.php">Restart</a></li>
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
<?php
        $debate_round = "";
        $debate_room = "";
        $debate_pro_TeamID = "";
        $debate_con_TeamID = "";
        $debate_pro_Player_1 = "";
        $debate_pro_Player_2 = "";
        $debate_pro_Player_3 = "";
        $debate_points_pro_Player_1 = "";
        $debate_points_pro_Player_2 = "";
        $debate_points_pro_Player_3 = "";
        $debate_points_pro_Player_reply = "";
        $debate_con_Player_1 = "";
        $debate_con_Player_2 = "";
        $debate_con_Player_3 = "";
        $debate_con_Player_reply = "";
        $debate_points_con_Player_1 = "";
        $debate_points_con_Player_2 = "";
        $debate_points_con_Player_3 = "";
        $debate_points_con_Player_reply = "";


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["debate_round"])) {
                $debate_round = "Select Round";
                $roundErr = "Round is reqired!";
            } else {
                $debate_round = test_input($_POST["debate_round"]);
              // check if name only contains letters and whitespace
            }

            if (empty($_POST["debate_room"])) {
                $debate_room = "Select Room";
            } else {
                $debate_room = test_input($_POST["debate_room"]);
              // check if name only contains letters and whitespace
            }


            if (empty($_POST["debate_pro_TeamID"])) {
                $debate_pro_TeamID = "Select Team Pro";
            } else {
                $debate_pro_TeamID = test_input($_POST["debate_pro_TeamID"]);
              // check if name only contains letters and whitespace
            }

            if (empty($_POST["debate_con_TeamID"])) {
                $debate_con_TeamID = "Select Team Con";
            } else {
                $debate_con_TeamID = test_input($_POST["debate_con_TeamID"]);
              // check if name only contains letters and whitespace
            }


            if (empty($_POST["debate_pro_Player_1"])) {
                $debate_pro_Player_1 = "Speaker Pro 1";
            } else {
                $debate_pro_Player_1 = test_input($_POST["debate_pro_Player_1"]);
              // check if name only contains letters and whitespace
            }

            if (empty($_POST["debate_pro_Player_2"])) {
                $playerPro2Err = "Speaker pro 2 is reqired!";
            } else {
                $debate_pro_Player_2 = test_input($_POST["debate_pro_Player_2"]);
              // check if name only contains letters and whitespace
            }

            if (empty($_POST["debate_pro_Player_3"])) {
                $playerPro3Err = "Speaker pro 3 is reqired!";
            } else {
                $debate_pro_Player_3 = test_input($_POST["debate_pro_Player_3"]);
              // check if name only contains letters and whitespace
            }

            if (empty($_POST["debate_points_pro_Player_1"])) {
                $playerPro1PointsErr = "Points Speaker Pro 1 is reqired!";
            } else {
                $debate_points_pro_Player_1 = test_input($_POST["debate_points_pro_Player_1"]);
              // check if name only contains letters and whitespace
            }


        }
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>
