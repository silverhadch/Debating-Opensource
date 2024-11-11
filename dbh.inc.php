

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//updatet plyerpoints und plyerponits max in der datenbank 
function updatePlayerPoints($pdo, $playerID, $points) {
    try {
        // Update player_points
        $stmt = $pdo->prepare("UPDATE players SET player_points = player_points + :points WHERE PlayerID = :playerID");
        $stmt->execute(['points' => $points, 'playerID' => $playerID]);
        

        // Update player_Max_points
        $stmt = $pdo->prepare("UPDATE players SET player_Max_points = :points WHERE PlayerID = :playerID AND :points > player_Max_points");
        $stmt->execute(['points' => $points, 'playerID' => $playerID]);
        

    } catch (PDOException $e) {
        echo "Error updating player points for PlayerID: $playerID - " . $e->getMessage() . "<br>";
    }
}



//Debuganzeige der ausgerechneten werten
function ehcofunc($pdo){
    global $debate_bestplayer_ID, $debate_winner_TeamID,$temp_total_con_points,$temp_total_pro_points;

        $smt = $pdo->prepare('SELECT player_name From players where PlayerID = :playerID');
        $smt->execute(['playerID' => $debate_bestplayer_ID]);
        $name_bestspieler = $smt->fetchAll();

        $smt = $pdo->prepare('SELECT Team_Name From teams where Team_ID = :teamID');
        $smt->execute(['teamID' => $debate_winner_TeamID]);
        $name_winnerteam = $smt->fetchAll();
    
    echo "Total Con Points: ".$temp_total_con_points."<br>";
    echo "Total Pro Points: ".$temp_total_pro_points."<br>";
    echo "Bestspeaker: ";
    foreach ($name_bestspieler as $row): echo $row["player_name"]; 
    endforeach;
    echo "<br>";
    echo "Winner: ";
    foreach ($name_winnerteam as $row): echo $row["Team_Name"]; 
    endforeach;
    echo "<br>";
}

// Variablen für die Tabelle `debates`
$debate_round = isset($_POST['debate_round']) ? htmlspecialchars($_POST['debate_round']) : '';
$debate_room = isset($_POST['debate_room']) ? htmlspecialchars($_POST['debate_room']) : '';
$debate_pro_TeamID = isset($_POST['start_debate_pro_TeamID']) ? htmlspecialchars($_POST['start_debate_pro_TeamID']) : '';
$debate_con_TeamID = isset($_POST['start_debate_con_TeamID']) ? htmlspecialchars($_POST['start_debate_con_TeamID']) : '';
$debate_pro_Player_1 = isset($_POST['debate_pro_Player_1']) ? htmlspecialchars($_POST['debate_pro_Player_1']) : '';
$debate_pro_Player_2 = isset($_POST['debate_pro_Player_2']) ? htmlspecialchars($_POST['debate_pro_Player_2']) : '';
$debate_pro_Player_3 = isset($_POST['debate_pro_Player_3']) ? htmlspecialchars($_POST['debate_pro_Player_3']) : '';
$debate_pro_Player_reply = isset($_POST['debate_pro_Player_reply']) ? htmlspecialchars($_POST['debate_pro_Player_reply']) : '';
$debate_points_pro_Player_1 = isset($_POST['debate_points_pro_Player_1']) ? htmlspecialchars($_POST['debate_points_pro_Player_1']) : 0;
$debate_points_pro_Player_2 = isset($_POST['debate_points_pro_Player_2']) ? htmlspecialchars($_POST['debate_points_pro_Player_2']) : 0;
$debate_points_pro_Player_3 = isset($_POST['debate_points_pro_Player_3']) ? htmlspecialchars($_POST['debate_points_pro_Player_3']) : 0;
$debate_points_pro_Player_reply = isset($_POST['debate_points_pro_Player_reply']) ? htmlspecialchars($_POST['debate_points_pro_Player_reply']) : 0;
$debate_con_Player_1 = isset($_POST['debate_con_Player_1']) ? htmlspecialchars($_POST['debate_con_Player_1']) : '';
$debate_con_Player_2 = isset($_POST['debate_con_Player_2']) ? htmlspecialchars($_POST['debate_con_Player_2']) : '';
$debate_con_Player_3 = isset($_POST['debate_con_Player_3']) ? htmlspecialchars($_POST['debate_con_Player_3']) : '';
$debate_con_Player_reply = isset($_POST['debate_con_Player_reply']) ? htmlspecialchars($_POST['debate_con_Player_reply']) : '';
$debate_points_con_Player_1 = isset($_POST['debate_points_con_Player_1']) ? htmlspecialchars($_POST['debate_points_con_Player_1']) : 0;
$debate_points_con_Player_2 = isset($_POST['debate_points_con_Player_2']) ? htmlspecialchars($_POST['debate_points_con_Player_2']) : 0;
$debate_points_con_Player_3 = isset($_POST['debate_points_con_Player_3']) ? htmlspecialchars($_POST['debate_points_con_Player_3']) : 0;
$debate_points_con_Player_reply = isset($_POST['debate_points_con_Player_reply']) ? htmlspecialchars($_POST['debate_points_con_Player_reply']) : 0;

//Temporäre Variable für Gesamtpunkte contra
$temp_total_con_points = (float)$debate_points_con_Player_1 + (float)$debate_points_con_Player_2 + (float)$debate_points_con_Player_3 + (float)$debate_points_con_Player_reply;
//Temporäre Variable für Gesamtpunkte pro
$temp_total_pro_points = (float)$debate_points_pro_Player_1 + (float)$debate_points_pro_Player_2 + (float)$debate_points_pro_Player_3 + (float)$debate_points_pro_Player_reply;
//---
$debate_bestplayer_ID = isset($debate_bestplayer_ID) ? $debate_bestplayer_ID : 0;
$debate_winner_TeamID = isset($winner_select_id) ? $winner_select_id : 0;

function setwinnerteam(){
    global $temp_total_con_points, $temp_total_pro_points, $debate_pro_TeamID,$debate_con_TeamID;
    //Check welches team mehr punkte hat
    if ($temp_total_con_points < $temp_total_pro_points) {
        $debate_winner_TeamID = $debate_pro_TeamID;
    } elseif ($temp_total_con_points > $temp_total_pro_points) {
        $debate_winner_TeamID = $debate_con_TeamID;
    } else {
            $winner_select_id = isset($_POST['debate_winner_TeamID']) ? $_POST['debate_winner_TeamID'] : '';
            $debate_winner_TeamID = (int) $winner_select_id;
    }
    

    return $debate_winner_TeamID;
}

function setbestplayer(){
    global $debate_points_con_Player_1, $debate_points_con_Player_2, $debate_points_con_Player_3;
    global $debate_points_pro_Player_1, $debate_points_pro_Player_2, $debate_points_pro_Player_3;
    global $debate_con_Player_1, $debate_con_Player_2, $debate_con_Player_3;
    global $debate_pro_Player_1, $debate_pro_Player_2, $debate_pro_Player_3;
    
    $selected_bestplayer_ID = isset($_POST['debate_bestplayer_ID']) ? $_POST['debate_bestplayer_ID'] : '';

    // Spielerpunkte vergleichen und den besten Spieler bestimmen
    $max_punkte = max(
        $debate_points_pro_Player_1,
        $debate_points_pro_Player_2,
        $debate_points_pro_Player_3,
        $debate_points_con_Player_1,
        $debate_points_con_Player_2,
        $debate_points_con_Player_3
    );

    // Überprüfen, ob es mehrere Spieler mit den höchsten Punkten gibt
    $anzahl_max_punkte = 0;
    $bestplayer_id = 0;
    //guck ob es mehrer spieler gibt (höchst ineffekter Code aber egal)
    if ($debate_points_pro_Player_1 == $max_punkte) {
        $anzahl_max_punkte++;
        $bestplayer_id = $debate_pro_Player_1;
    }
    if ($debate_points_pro_Player_2 == $max_punkte) {
        $anzahl_max_punkte++;
        $bestplayer_id = $debate_pro_Player_2;
    }
    if ($debate_points_pro_Player_3 == $max_punkte) {
        $anzahl_max_punkte++;
        $bestplayer_id = $debate_pro_Player_3;
    }
    if ($debate_points_con_Player_1 == $max_punkte) {
        $anzahl_max_punkte++;
        $bestplayer_id = $debate_con_Player_1;
    }
    if ($debate_points_con_Player_2 == $max_punkte) {
        $anzahl_max_punkte++;
        $bestplayer_id = $debate_con_Player_2;
    }
    if ($debate_points_con_Player_3 == $max_punkte) {
        $anzahl_max_punkte++;
        $bestplayer_id = $debate_con_Player_3;
    }
    
    //Auswahl wenn mehr als 1 spiler hohe punkt zahl
    if ($anzahl_max_punkte == 1) {
        $debate_bestplayer_ID = $bestplayer_id;
    } else {
       $debate_bestplayer_ID = $selected_bestplayer_ID;
    } 
    
    return $debate_bestplayer_ID;
}

//nach input settet diese funktion die eingegbenen variablen und nötige mathematik 
function setterVariables(){
    global $debate_pro_TeamID,$debate_con_TeamID;
    global $debate_points_con_Player_1, $debate_points_con_Player_2, $debate_points_con_Player_3;
    global $debate_points_pro_Player_1, $debate_points_pro_Player_2, $debate_points_pro_Player_3;
    global $debate_con_Player_1, $debate_con_Player_2, $debate_con_Player_3;
    global $debate_pro_Player_1, $debate_pro_Player_2, $debate_pro_Player_3;
    global $debate_bestplayer_ID,$debate_winner_TeamID;
    


    //Eingabewerte aus POST abrufen und validieren
    $debate_round = isset($_POST['debate_round']) ? htmlspecialchars($_POST['debate_round']) : '';
    $debate_room = isset($_POST['debate_room']) ? htmlspecialchars($_POST['debate_room']) : '';
    $debate_pro_TeamID = isset($_POST['start_debate_pro_TeamID']) ? htmlspecialchars($_POST['start_debate_pro_TeamID']) : '';
    $debate_con_TeamID = isset($_POST['start_debate_con_TeamID']) ? htmlspecialchars($_POST['start_debate_con_TeamID']) : '';
    $debate_pro_Player_1 = isset($_POST['debate_pro_Player_1']) ? htmlspecialchars($_POST['debate_pro_Player_1']) : '';
    $debate_pro_Player_2 = isset($_POST['debate_pro_Player_2']) ? htmlspecialchars($_POST['debate_pro_Player_2']) : '';
    $debate_pro_Player_3 = isset($_POST['debate_pro_Player_3']) ? htmlspecialchars($_POST['debate_pro_Player_3']) : '';
    $debate_pro_Player_reply = isset($_POST['debate_pro_Player_reply']) ? htmlspecialchars($_POST['debate_pro_Player_reply']) : '';
    $debate_points_pro_Player_1 = isset($_POST['debate_points_pro_Player_1']) ? htmlspecialchars($_POST['debate_points_pro_Player_1']) : 0;
    $debate_points_pro_Player_2 = isset($_POST['debate_points_pro_Player_2']) ? htmlspecialchars($_POST['debate_points_pro_Player_2']) : 0;
    $debate_points_pro_Player_3 = isset($_POST['debate_points_pro_Player_3']) ? htmlspecialchars($_POST['debate_points_pro_Player_3']) : 0;
    $debate_points_pro_Player_reply = isset($_POST['debate_points_pro_Player_reply']) ? htmlspecialchars($_POST['debate_points_pro_Player_reply']) : 0;
    $debate_con_Player_1 = isset($_POST['debate_con_Player_1']) ? htmlspecialchars($_POST['debate_con_Player_1']) : '';
    $debate_con_Player_2 = isset($_POST['debate_con_Player_2']) ? htmlspecialchars($_POST['debate_con_Player_2']) : '';
    $debate_con_Player_3 = isset($_POST['debate_con_Player_3']) ? htmlspecialchars($_POST['debate_con_Player_3']) : '';
    $debate_con_Player_reply = isset($_POST['debate_con_Player_reply']) ? htmlspecialchars($_POST['debate_con_Player_reply']) : '';
    $debate_points_con_Player_1 = isset($_POST['debate_points_con_Player_1']) ? htmlspecialchars($_POST['debate_points_con_Player_1']) : 0;
    $debate_points_con_Player_2 = isset($_POST['debate_points_con_Player_2']) ? htmlspecialchars($_POST['debate_points_con_Player_2']) : 0;
    $debate_points_con_Player_3 = isset($_POST['debate_points_con_Player_3']) ? htmlspecialchars($_POST['debate_points_con_Player_3']) : 0;
    $debate_points_con_Player_reply = isset($_POST['debate_points_con_Player_reply']) ? htmlspecialchars($_POST['debate_points_con_Player_reply']) : 0;

    $debate_bestplayer_ID = setbestplayer();
    $debate_winner_TeamID = setwinnerteam();
    
    
}
//es werden alle daten aus der debatte hochgeladen in die datenbank
function endDebate($pdo) {
global $temp_total_con_points, $temp_total_pro_points, $debate_pro_TeamID,$debate_con_TeamID;
global $debate_points_con_Player_1, $debate_points_con_Player_2, $debate_points_con_Player_3,$debate_points_con_Player_reply;
global $debate_points_pro_Player_1, $debate_points_pro_Player_2, $debate_points_pro_Player_3,$debate_points_pro_Player_reply;
global $debate_con_Player_1, $debate_con_Player_2, $debate_con_Player_3,$debate_con_Player_reply;
global $debate_pro_Player_1, $debate_pro_Player_2, $debate_pro_Player_3,$debate_pro_Player_reply;
global $debate_bestplayer_ID, $debate_winner_TeamID,$debate_room,$debate_round;


$debate_submitterID = isset($_SESSION['id']) ? $_SESSION['id'] : null;

    try {
        // Insert the debate record into the debates table
        $stmt = $pdo->prepare("
            INSERT INTO debates (
                debate_round, 
                debate_room, 
                debate_pro_TeamID, 
                debate_pro_Player_1, 
                debate_pro_Player_2, 
                debate_pro_Player_3, 
                debate_pro_Player_reply, 
                debate_points_pro_Player_1, 
                debate_points_pro_Player_2, 
                debate_points_pro_Player_3, 
                debate_points_pro_Player_reply, 
                debate_con_TeamID, 
                debate_con_Player_1, 
                debate_con_Player_2, 
                debate_con_Player_3, 
                debate_con_Player_reply, 
                debate_points_con_Player_1, 
                debate_points_con_Player_2, 
                debate_points_con_Player_3, 
                debate_points_con_Player_reply, 
                debate_bestplayer_ID, 
                debate_winner_TeamID,
                debates_submitterID 
            ) VALUES (
                :debate_round, 
                :debate_room, 
                :debate_pro_TeamID, 
                :debate_pro_Player_1, 
                :debate_pro_Player_2, 
                :debate_pro_Player_3, 
                :debate_pro_Player_reply, 
                :debate_points_pro_Player_1, 
                :debate_points_pro_Player_2, 
                :debate_points_pro_Player_3, 
                :debate_points_pro_Player_reply, 
                :debate_con_TeamID, 
                :debate_con_Player_1, 
                :debate_con_Player_2, 
                :debate_con_Player_3, 
                :debate_con_Player_reply, 
                :debate_points_con_Player_1, 
                :debate_points_con_Player_2, 
                :debate_points_con_Player_3, 
                :debate_points_con_Player_reply, 
                :debate_bestplayer_ID, 
                :debate_winner_TeamID,
                :debate_submitterID
            )
        ");
        
        // Execute the statement with the values
        $stmt->execute([
            'debate_round' => $debate_round, 
            'debate_room' => $debate_room, 
            'debate_pro_TeamID' => $debate_pro_TeamID, 
            'debate_pro_Player_1' => $debate_pro_Player_1, 
            'debate_pro_Player_2' => $debate_pro_Player_2, 
            'debate_pro_Player_3' => $debate_pro_Player_3, 
            'debate_pro_Player_reply' => $debate_pro_Player_reply, 
            'debate_points_pro_Player_1' => $debate_points_pro_Player_1, 
            'debate_points_pro_Player_2' => $debate_points_pro_Player_2, 
            'debate_points_pro_Player_3' => $debate_points_pro_Player_3, 
            'debate_points_pro_Player_reply' => $debate_points_pro_Player_reply, 
            'debate_con_TeamID' => $debate_con_TeamID, 
            'debate_con_Player_1' => $debate_con_Player_1, 
            'debate_con_Player_2' => $debate_con_Player_2, 
            'debate_con_Player_3' => $debate_con_Player_3, 
            'debate_con_Player_reply' => $debate_con_Player_reply, 
            'debate_points_con_Player_1' => $debate_points_con_Player_1, 
            'debate_points_con_Player_2' => $debate_points_con_Player_2, 
            'debate_points_con_Player_3' => $debate_points_con_Player_3, 
            'debate_points_con_Player_reply' => $debate_points_con_Player_reply, 
            'debate_bestplayer_ID' => $debate_bestplayer_ID, 
            'debate_winner_TeamID' => $debate_winner_TeamID,
            'debate_submitterID' => $debate_submitterID
        ]);
        
        
        $stmt = $pdo->prepare("UPDATE players SET player_wins = player_wins + 1 WHERE PlayerID = :playerID");
        $stmt->execute(['playerID' => $debate_bestplayer_ID]);

        // Update team_wins
        $stmt = $pdo->prepare("UPDATE teams SET team_wins = team_wins + 1 WHERE Team_ID = :teamID");
        $stmt->execute(['teamID' => $debate_winner_TeamID]);
        
        
        // Update team_points for con team
        $stmt = $pdo->prepare("UPDATE teams SET team_points = team_points + :points WHERE Team_ID = :teamID");
        $stmt->execute(['points' => $temp_total_con_points, 'teamID' => $debate_con_TeamID]);
        

        // Update team_points for pro team
        $stmt->execute(['points' => $temp_total_pro_points, 'teamID' => $debate_pro_TeamID]);
        

        // Update player_points and player_Max_points for pro players
        updatePlayerPoints($pdo, $debate_pro_Player_1, $debate_points_pro_Player_1);
        updatePlayerPoints($pdo, $debate_pro_Player_2, $debate_points_pro_Player_2);
        updatePlayerPoints($pdo, $debate_pro_Player_3, $debate_points_pro_Player_3);

        // Update player_points and player_Max_points for con players
        updatePlayerPoints($pdo, $debate_con_Player_1, $debate_points_con_Player_1);
        updatePlayerPoints($pdo, $debate_con_Player_2, $debate_points_con_Player_2);
        updatePlayerPoints($pdo, $debate_con_Player_3, $debate_points_con_Player_3);
        echo "Successfully sent all results to the Database!!!!";
    } catch (PDOException $e) {
        echo "Error in endDebate Function. Contact the Admins!!!! -" . $e->getMessage() . "<br>";
    }
}

?>
