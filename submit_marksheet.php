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
    $check = $_POST['debate_round'].$_POST['start_debate_pro_TeamID'].$_POST['start_debate_con_TeamID'];
?>
    <section class= "marksheet-section">
        <div class="container">

            <div class="row gy-4">

                <div class="col-sm-3">

                </div>

                <div class="col-lg-6">
                    <h1>Marksheet</h1>


                        <div>
                                                        <h2 style="color: #FF0000">Note the following number (including the slashes) on the paper marksheet if the following results are correct. </h2>
                            <h2 style="color: #FF0000">Do this before confirming!!!</h2>
                            <h3>Number: #<?php if(isset($_POST['debate_round'])) echo $_POST['debate_round']."/" ?><?php if(isset($_POST['start_debate_pro_TeamID'])) echo $_POST['start_debate_pro_TeamID']."/" ?> <?php if(isset($_POST['start_debate_con_TeamID'])) echo $_POST['start_debate_con_TeamID'] ?></h3>
                            <br>
                        </div>
                        <h2>Confirm the  debate results!</h2>

                    <form action="submit_marksheet.php" method="post" class="php-email-form">
                        <!--
                        <p class="col-sm-6">Runde:  <?php //if(isset($_POST['debate_round'])) echo $_POST['debate_round']; ?></h3>
                        <h3 class="col-sm-6">Raum:  <?php //if(isset($_POST['selected_debate_room'])) echo $_POST['selected_debate_room']?></h3>
                        <h3 class="col-sm-6">Team Pro:  <?php //if(isset($_POST['start_debate_pro_TeamID'])) echo $_POST['start_debate_pro_TeamID'] ?></h3>
                        <h3 class="col-sm-6">Team Con:  <?php //if(isset($_POST['start_debate_con_TeamID'])) echo $_POST['start_debate_con_TeamID'] ?></h3>
                                    -->
                        <div>
                            <p>
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


                        /*
                        //Wenn auswertung gedrückt wird werden die sieger und daten gezeigt
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Admin']) && $_POST['Admin'] == 'Submit'){
                            header("Location: adminpage.php");
                            //auswertung($pdoindex);
                            exit;
                        } */



                    ob_end_flush(); // Sende den gepufferten Output
                    ?>
                </p>

                            <input type="hidden" name="debate_room" id="debate_room"  value="<?php if(isset($_POST['selected_debate_room'])) echo $_POST['selected_debate_room'] ?>" >
                            <input type="hidden" name="debate_round" id="debate_round"  value="<?php if(isset($_POST['debate_round'])) echo $_POST['debate_round'] ?>" >
                            <input type="hidden" name="start_debate_pro_TeamID" id="start_debate_pro_TeamID"  value="<?php if(isset($_POST['start_debate_pro_TeamID'])) echo $_POST['start_debate_pro_TeamID'] ?>">
                            <input type="hidden" name="start_debate_con_TeamID" id="start_debate_con_TeamID"  value="<?php if(isset($_POST['start_debate_con_TeamID'])) echo $_POST['start_debate_con_TeamID'] ?>" >
                            <input type="hidden" name="debate_pro_Player_1" id="debate_pro_Player_1"  value="<?php if(isset($_POST['debate_pro_Player_1'])) echo $_POST['debate_pro_Player_1'] ?>">
                            <input type="hidden" name="debate_pro_Player_2" id="debate_pro_Player_2"  value="<?php if(isset($_POST['debate_pro_Player_2'])) echo $_POST['debate_pro_Player_2'] ?>">
                            <input type="hidden" name="debate_pro_Player_3" id="debate_pro_Player_3"  value="<?php if(isset($_POST['debate_pro_Player_3'])) echo $_POST['debate_pro_Player_3'] ?>">
                            <input type="hidden" name="debate_pro_Player_reply" id="debate_pro_Player_reply"  value="<?php if(isset($_POST['debate_pro_Player_reply'])) echo $_POST['debate_pro_Player_reply'] ?>">

                            <input type="hidden" name="debate_points_pro_Player_1" id="debate_points_pro_Player_1"  value="<?php if(isset($_POST['debate_points_pro_Player_1'])) echo $_POST['debate_points_pro_Player_1'] ?>">
                            <input type="hidden" name="debate_points_pro_Player_2" id="debate_points_pro_Player_2"  value="<?php if(isset($_POST['debate_points_pro_Player_2'])) echo $_POST['debate_points_pro_Player_2'] ?>">
                            <input type="hidden" name="debate_points_pro_Player_3" id="debate_points_pro_Player_3"  value="<?php if(isset($_POST['debate_points_pro_Player_3'])) echo $_POST['debate_points_pro_Player_3'] ?>">
                            <input type="hidden" name="debate_points_pro_Player_reply" id="debate_points_pro_Player_reply"  value="<?php if(isset($_POST['debate_points_pro_Player_reply'])) echo $_POST['debate_points_pro_Player_reply'] ?>">


                            <input type="hidden" name="debate_con_Player_1" id="debate_con_Player_1"  value="<?php if(isset($_POST['debate_con_Player_1'])) echo $_POST['debate_con_Player_1'] ?>">
                            <input type="hidden" name="debate_con_Player_2" id="debate_con_Player_2"  value="<?php if(isset($_POST['debate_con_Player_2'])) echo $_POST['debate_con_Player_2'] ?>">
                            <input type="hidden" name="debate_con_Player_3" id="debate_con_Player_3"  value="<?php if(isset($_POST['debate_con_Player_3'])) echo $_POST['debate_con_Player_3'] ?>">
                            <input type="hidden" name="debate_con_Player_reply" id="debate_con_Player_reply"  value="<?php if(isset($_POST['debate_con_Player_reply'])) echo $_POST['debate_con_Player_reply'] ?>">

                            <input type="hidden" name="debate_points_con_Player_1" id="debate_points_con_Player_1"  value="<?php if(isset($_POST['debate_points_con_Player_1'])) echo $_POST['debate_points_con_Player_1'] ?>">
                            <input type="hidden" name="debate_points_con_Player_2" id="debate_points_con_Player_2"  value="<?php if(isset($_POST['debate_points_con_Player_2'])) echo $_POST['debate_points_con_Player_2'] ?>">
                            <input type="hidden" name="debate_points_con_Player_3" id="debate_points_con_Player_3"  value="<?php if(isset($_POST['debate_points_con_Player_3'])) echo $_POST['debate_points_con_Player_3'] ?>">
                            <input type="hidden" name="debate_points_con_Player_reply" id="debate_points_con_Player_reply"  value="<?php if(isset($_POST['debate_points_con_Player_reply'])) echo $_POST['debate_points_con_Player_reply'] ?>">
                            <input type="hidden" name="debate_submitterID" id="debate_submitterID"  value="<?php if(isset($_SESSION['id'])) echo $_SESSION['id']?>">
                            <input type="hidden" name="debate_bestplayer_ID" id="debate_bestplayer_ID"  value="<?php if(isset($_POST['debate_bestplayer_ID'])) echo $_POST['debate_bestplayer_ID'] ?>">
                            <input type="hidden" name="debate_winner_TeamID" id="debate_winner_TeamID"  value="<?php if(isset($_POST['debate_winner_TeamID'])) echo $_POST['debate_winner_TeamID'] ?>">


                            <center>
                            <div>
                            <h2 style="color: #FF0000">Check if these are the correct results!!!! Else go back to the marksheet tab and restart.</h2>
                            <button type="submit" name="Abschicken" value="Submit" style="background:linear-gradient(45deg, red, blue);"> CONFIRM </button>



                            </div>
                            </center>
                        </div>
                    </form>
                    <p>
                            <?php

                            ob_start(); // Starte den Output-Buffer  weil beim reloaden endDebate Funktion herrumbitcht
                            //Diese datei wird in zukunft durchs frontend nicht mehr index sein,also lies die komentare
                            //Beim ändern wichtig: die header Location beim abschickbutton auch ändern zurück an diese datei


                            // Datenbankmanager einbinden/ Databasemanager einbinden

                            //Startet main funktion auch wenn es nicht wichtig ist da mein Gehirn in java gefangen ist


                                //wenn Abschickenbutton gedrückt werden alle wichtigen daten zur datenbank gesendet
                                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Abschicken']) && $_POST['Abschicken'] == 'Submit') {
                                    $smt = $connection_pdo->prepare('SELECT * FROM debates WHERE debate_con_TeamID = :conteamID AND debate_pro_TeamID = :proteamID AND debate_round = :debateRound');

                                    $smt->execute(['conteamID' => $_POST['start_debate_con_TeamID'],'proteamID' => $_POST['start_debate_pro_TeamID'], 'debateRound' => $_POST['debate_round']]);
                                    $resultscheck = $smt->fetchAll();

                                    if(empty($resultscheck)){


                                    setterVariables();

                                    endDebate($connection_pdo);


                                    //auswertung($pdoindex);, kann für live aktualisierung beim  jedem abschicken sorgen

                                    // Redirect zurück hier (jetzt grad index.php also beim umbennen dieser datei ändern)
                                    //(um beim reload zuverhindern das die post argumente im url noch mal ausgeführt werden)
                                    exit; // Stelle sicher, dass nach dem Redirect kein weiterer Code ausgeführt wird
                                    }
                                }



                            ob_end_flush(); // Sende den gepufferten Output
                            ?>
                            </p>
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
