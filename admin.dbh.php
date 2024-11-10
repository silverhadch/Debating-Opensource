<?php

$debate_round = isset($_POST['debate_round']) ? htmlspecialchars($_POST['debate_round']) : '';
$debate_pro_TeamID = isset($_POST['debate_pro_TeamID']) ? htmlspecialchars($_POST['debate_pro_TeamID']) : '';
$debate_con_TeamID = isset($_POST['debate_con_TeamID']) ? htmlspecialchars($_POST['debate_con_TeamID']) : '';

function updateHostName($pdo, $newName) {

    try {
        // SQL-Statement zum Aktualisieren des Namens in der hosts-Tabelle vorbereiten
        $stmt = $pdo->prepare("UPDATE host SET name = :new_name WHERE id = 1");

        // Parameter binden und ausführen
        $stmt->bindParam(':new_name', $newName, PDO::PARAM_STR);

        $stmt->execute();
        echo "Name erfolgreich aktualisiert!";
    } catch (PDOException $e) {
        echo "Fehler beim Aktualisieren des Namens: " . $e->getMessage();
    }
}

function addRoomToDatabase($pdo,$roomInt) {

    try {
        // SQL-Statement zum Einfügen der Daten vorbereiten
        $stmt = $pdo->prepare("INSERT INTO rooms (Room_ID, Room_Name) VALUES (:room_id, :room_name)");

        // Parameter binden und ausführen
        $stmt->bindParam(':room_id', $roomInt, PDO::PARAM_INT);
        $stmt->bindParam(':room_name', $roomInt, PDO::PARAM_STR); // Als String speichern

        $stmt->execute();
        echo "Raum erfolgreich hinzugefügt!";
    } catch (PDOException $e) {
        echo "Fehler beim Hinzufügen des Raums: " . $e->getMessage();
    }
}

function upd($pdo){
    // Extract data from POST
    $debate_round = $_POST['debate_round'];
    $debate_pro_TeamID = $_POST['debate_pro_TeamID'];
    $debate_con_TeamID = $_POST['debate_con_TeamID'];
    $proPlayer1Points = $_POST['debate_points_pro_Player_1'];
    $proPlayer2Points = $_POST['debate_points_pro_Player_2'];
    $proPlayer3Points = $_POST['debate_points_pro_Player_3'];
    $proPlayerReplyPoints = $_POST['debate_points_pro_Player_reply'];
    $conPlayer1Points = $_POST['debate_points_con_Player_1'];
    $conPlayer2Points = $_POST['debate_points_con_Player_2'];
    $conPlayer3Points = $_POST['debate_points_con_Player_3'];
    $conPlayerReplyPoints = $_POST['debate_points_con_Player_reply'];
    $bestplayerid = $_POST['bestplayerid'];
    $winnerteamid = $_POST['winnerteamid'];

    //Hardcoding yeaah!!!

    global $proPlayer1ID;
    global $proPlayer2ID ;
    global $proPlayer3ID ;
    global $conPlayer1ID ;
    global $conPlayer2ID ;
    global $conPlayer3ID ;

   // Prepare SQL query
    $sql = "SELECT debate_pro_Player_1, debate_pro_Player_2, debate_pro_Player_3,
    debate_con_Player_1, debate_con_Player_2, debate_con_Player_3
    FROM debates
    WHERE debate_round = :debate_round
    AND debate_pro_TeamID = :debate_pro_TeamID
    AND debate_con_TeamID = :debate_con_TeamID";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':debate_round', $debate_round, PDO::PARAM_INT);
    $stmt->bindParam(':debate_pro_TeamID', $debate_pro_TeamID, PDO::PARAM_INT);
    $stmt->bindParam(':debate_con_TeamID', $debate_con_TeamID, PDO::PARAM_INT);

   // Execute the query
    $stmt->execute();

    // Fetch the result into variables
    $stmt->bindColumn('debate_pro_Player_1', $proPlayer1ID);
    $stmt->bindColumn('debate_pro_Player_2', $proPlayer2ID);
    $stmt->bindColumn('debate_pro_Player_3', $proPlayer3ID);
    $stmt->bindColumn('debate_con_Player_1', $conPlayer1ID);
    $stmt->bindColumn('debate_con_Player_2', $conPlayer2ID);
    $stmt->bindColumn('debate_con_Player_3', $conPlayer3ID);

// Fetch the result (if needed, depending on how you plan to use the variables)
    $stmt->fetch(PDO::FETCH_BOUND);



            echo "Pro Player 1 ID: " . $proPlayer1ID . "<br>";


            echo "Pro Player 2 ID: " . $proPlayer2ID . "<br>";


            echo "Pro Player 3 ID: " . $proPlayer3ID . "<br>";


            echo "Con Player 1 ID: " . $conPlayer1ID . "<br>";


            echo "Con Player 2 ID: " . $conPlayer2ID . "<br>";


            echo "Con Player 3 ID: " . $conPlayer3ID . "<br>";





    try {


        // Begin transaction
        $pdo->beginTransaction();

        // Retrieve old points and values from the database
        $sql_query_points = "SELECT
            debate_points_pro_Player_1,
            debate_points_pro_Player_2,
            debate_points_pro_Player_3,
            debate_points_pro_Player_reply,
            debate_points_con_Player_1,
            debate_points_con_Player_2,
            debate_points_con_Player_3,
            debate_points_con_Player_reply,
            debate_bestplayer_ID,
            debate_winner_TeamID
            FROM debates
            WHERE debate_round = :debate_round
            AND debate_pro_TeamID = :debate_pro_TeamID
            AND debate_con_TeamID = :debate_con_TeamID";

        $stmt_query_points = $pdo->prepare($sql_query_points);
        $stmt_query_points->bindParam(':debate_round', $debate_round, PDO::PARAM_INT);
        $stmt_query_points->bindParam(':debate_pro_TeamID', $debate_pro_TeamID, PDO::PARAM_INT);
        $stmt_query_points->bindParam(':debate_con_TeamID', $debate_con_TeamID, PDO::PARAM_INT);
        $stmt_query_points->execute();

        // Retrieve old best player and winner team IDs from debates table for comparison
    $sql_old_best_player = "SELECT debate_bestplayer_ID FROM debates WHERE debate_round = :debate_round
    AND debate_pro_TeamID = :debate_pro_TeamID
    AND debate_con_TeamID = :debate_con_TeamID";
$stmt_old_best_player = $pdo->prepare($sql_old_best_player);
$stmt_old_best_player->bindParam(':debate_round', $debate_round, PDO::PARAM_INT);
$stmt_old_best_player->bindParam(':debate_pro_TeamID', $debate_pro_TeamID, PDO::PARAM_INT);
$stmt_old_best_player->bindParam(':debate_con_TeamID', $debate_con_TeamID, PDO::PARAM_INT);
$stmt_old_best_player->execute();
$old_bestplayerid = $stmt_old_best_player->fetchColumn();

$sql_old_winner_team = "SELECT debate_winner_TeamID FROM debates WHERE debate_round = :debate_round
    AND debate_pro_TeamID = :debate_pro_TeamID
    AND debate_con_TeamID = :debate_con_TeamID";
$stmt_old_winner_team = $pdo->prepare($sql_old_winner_team);
$stmt_old_winner_team->bindParam(':debate_round', $debate_round, PDO::PARAM_INT);
$stmt_old_winner_team->bindParam(':debate_pro_TeamID', $debate_pro_TeamID, PDO::PARAM_INT);
$stmt_old_winner_team->bindParam(':debate_con_TeamID', $debate_con_TeamID, PDO::PARAM_INT);
$stmt_old_winner_team->execute();
$old_winnerteamid = $stmt_old_winner_team->fetchColumn();

        $old_points = $stmt_query_points->fetch(PDO::FETCH_ASSOC);

        if ($old_points) {
            $proPlayer1PointsOld = $old_points['debate_points_pro_Player_1'];
            $proPlayer2PointsOld = $old_points['debate_points_pro_Player_2'];
            $proPlayer3PointsOld = $old_points['debate_points_pro_Player_3'];
            $proPlayerReplyPointsOld = $old_points['debate_points_pro_Player_reply'];
            $conPlayer1PointsOld = $old_points['debate_points_con_Player_1'];
            $conPlayer2PointsOld = $old_points['debate_points_con_Player_2'];
            $conPlayer3PointsOld = $old_points['debate_points_con_Player_3'];
            $conPlayerReplyPointsOld = $old_points['debate_points_con_Player_reply'];
            $old_bestplayerid = $old_points['debate_bestplayer_ID'];
            $old_winnerteamid = $old_points['debate_winner_TeamID'];

            // Calculate the differences between new and old points for each player
            $proPlayer1PointsDiff = $proPlayer1Points - $proPlayer1PointsOld;
            $proPlayer2PointsDiff = $proPlayer2Points - $proPlayer2PointsOld;
            $proPlayer3PointsDiff = $proPlayer3Points - $proPlayer3PointsOld;
            $conPlayer1PointsDiff = $conPlayer1Points - $conPlayer1PointsOld;
            $conPlayer2PointsDiff = $conPlayer2Points - $conPlayer2PointsOld;
            $conPlayer3PointsDiff = $conPlayer3Points - $conPlayer3PointsOld;

            // Update player points
            $sql_update_player_points = "UPDATE players SET
                player_points = player_points + :pointsDiff
                WHERE PlayerID = :playerID";

            $stmt_update_player_points = $pdo->prepare($sql_update_player_points);

            // Update points for pro players
            $stmt_update_player_points->bindParam(':pointsDiff', $proPlayer1PointsDiff, PDO::PARAM_INT);
            $stmt_update_player_points->bindParam(':playerID', $proPlayer1ID, PDO::PARAM_INT);
            $stmt_update_player_points->execute();

            $stmt_update_player_points->bindParam(':pointsDiff', $proPlayer2PointsDiff, PDO::PARAM_INT);
            $stmt_update_player_points->bindParam(':playerID', $proPlayer2ID, PDO::PARAM_INT);
            $stmt_update_player_points->execute();

            $stmt_update_player_points->bindParam(':pointsDiff', $proPlayer3PointsDiff, PDO::PARAM_INT);
            $stmt_update_player_points->bindParam(':playerID', $proPlayer3ID, PDO::PARAM_INT);
            $stmt_update_player_points->execute();

            // Update points for con players
            $stmt_update_player_points->bindParam(':pointsDiff', $conPlayer1PointsDiff, PDO::PARAM_INT);
            $stmt_update_player_points->bindParam(':playerID', $conPlayer1ID, PDO::PARAM_INT);
            $stmt_update_player_points->execute();

            $stmt_update_player_points->bindParam(':pointsDiff', $conPlayer2PointsDiff, PDO::PARAM_INT);
            $stmt_update_player_points->bindParam(':playerID', $conPlayer2ID, PDO::PARAM_INT);
            $stmt_update_player_points->execute();

            $stmt_update_player_points->bindParam(':pointsDiff', $conPlayer3PointsDiff, PDO::PARAM_INT);
            $stmt_update_player_points->bindParam(':playerID', $conPlayer3ID, PDO::PARAM_INT);
            $stmt_update_player_points->execute();

            // Update max points for players
            $sql_update_player_max_points = "UPDATE players SET
                player_Max_points = :points
                WHERE PlayerID = :playerID AND :points > player_Max_points";

            $stmt_update_player_max_points = $pdo->prepare($sql_update_player_max_points);

            // Update max points for pro players
            $stmt_update_player_max_points->bindParam(':points', $proPlayer1Points, PDO::PARAM_INT);
            $stmt_update_player_max_points->bindParam(':playerID', $proPlayer1ID, PDO::PARAM_INT);
            $stmt_update_player_max_points->execute();

            $stmt_update_player_max_points->bindParam(':points', $proPlayer2Points, PDO::PARAM_INT);
            $stmt_update_player_max_points->bindParam(':playerID', $proPlayer2ID, PDO::PARAM_INT);
            $stmt_update_player_max_points->execute();

            $stmt_update_player_max_points->bindParam(':points', $proPlayer3Points, PDO::PARAM_INT);
            $stmt_update_player_max_points->bindParam(':playerID', $proPlayer3ID, PDO::PARAM_INT);
            $stmt_update_player_max_points->execute();

            // Update max points for con players
            $stmt_update_player_max_points->bindParam(':points', $conPlayer1Points, PDO::PARAM_INT);
            $stmt_update_player_max_points->bindParam(':playerID', $conPlayer1ID, PDO::PARAM_INT);
            $stmt_update_player_max_points->execute();

            $stmt_update_player_max_points->bindParam(':points', $conPlayer2Points, PDO::PARAM_INT);
            $stmt_update_player_max_points->bindParam(':playerID', $conPlayer2ID, PDO::PARAM_INT);
            $stmt_update_player_max_points->execute();

            $stmt_update_player_max_points->bindParam(':points', $conPlayer3Points, PDO::PARAM_INT);
            $stmt_update_player_max_points->bindParam(':playerID', $conPlayer3ID, PDO::PARAM_INT);
            $stmt_update_player_max_points->execute();

            // Calculate total new points for pro and con teams
            $totalNewPointsPro = $proPlayer1Points + $proPlayer2Points + $proPlayer3Points + $proPlayerReplyPoints;
            $totalNewPointsCon = $conPlayer1Points + $conPlayer2Points + $conPlayer3Points + $conPlayerReplyPoints;

            // Calculate total old points for pro and con teams
            $totalOldPointsPro = $proPlayer1PointsOld + $proPlayer2PointsOld + $proPlayer3PointsOld + $proPlayerReplyPointsOld;
            $totalOldPointsCon = $conPlayer1PointsOld + $conPlayer2PointsOld + $conPlayer3PointsOld + $conPlayerReplyPointsOld;

            // Calculate the differences between new and old total points
            $proTotalPointsDiff = $totalNewPointsPro - $totalOldPointsPro;
            $conTotalPointsDiff = $totalNewPointsCon - $totalOldPointsCon;

            // Update total points for pro and con teams
            $sql_update_team_points = "UPDATE teams SET
                Team_Points = Team_Points + :pointsDiff
                WHERE Team_ID = :teamID";

            $stmt_update_team_points = $pdo->prepare($sql_update_team_points);

            // Update points for pro team
            $stmt_update_team_points->bindParam(':pointsDiff', $proTotalPointsDiff, PDO::PARAM_INT);
            $stmt_update_team_points->bindParam(':teamID', $debate_pro_TeamID, PDO::PARAM_INT);
            $stmt_update_team_points->execute();

            // Update points for con team
            $stmt_update_team_points->bindParam(':pointsDiff', $conTotalPointsDiff, PDO::PARAM_INT);
            $stmt_update_team_points->bindParam(':teamID', $debate_con_TeamID, PDO::PARAM_INT);
            $stmt_update_team_points->execute();

            // Update debate results in debates table
            $sql_update_debate_results = "UPDATE debates SET
                debate_points_pro_Player_1 = :proPlayer1Points,
                debate_points_pro_Player_2 = :proPlayer2Points,
                debate_points_pro_Player_3 = :proPlayer3Points,
                debate_points_pro_Player_reply = :proPlayerReplyPoints,
                debate_points_con_Player_1 = :conPlayer1Points,
                debate_points_con_Player_2 = :conPlayer2Points,
                debate_points_con_Player_3 = :conPlayer3Points,
                debate_points_con_Player_reply = :conPlayerReplyPoints,
                debate_bestplayer_ID = :bestplayerid,
                debate_winner_TeamID = :winnerteamid
                WHERE debate_round = :debate_round
                AND debate_pro_TeamID = :debate_pro_TeamID
                AND debate_con_TeamID = :debate_con_TeamID";

            $stmt_update_debate_results = $pdo->prepare($sql_update_debate_results);
            $stmt_update_debate_results->bindParam(':proPlayer1Points', $proPlayer1Points, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':proPlayer2Points', $proPlayer2Points, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':proPlayer3Points', $proPlayer3Points, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':proPlayerReplyPoints', $proPlayerReplyPoints, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':conPlayer1Points', $conPlayer1Points, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':conPlayer2Points', $conPlayer2Points, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':conPlayer3Points', $conPlayer3Points, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':conPlayerReplyPoints', $conPlayerReplyPoints, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':bestplayerid', $bestplayerid, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':winnerteamid', $winnerteamid, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':debate_round', $debate_round, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':debate_pro_TeamID', $debate_pro_TeamID, PDO::PARAM_INT);
            $stmt_update_debate_results->bindParam(':debate_con_TeamID', $debate_con_TeamID, PDO::PARAM_INT);
            $stmt_update_debate_results->execute();

    // Update teams for pro and con teams if winner team changed
    if ($debate_pro_TeamID !== $old_winnerteamid) {
        // Update old winner team's win count
        $sql_update_old_winner_team = "UPDATE teams SET team_wins = team_wins - 1 WHERE Team_ID = :old_winnerteamid";
        $stmt_update_old_winner_team = $pdo->prepare($sql_update_old_winner_team);
        $stmt_update_old_winner_team->bindParam(':old_winnerteamid', $old_winnerteamid, PDO::PARAM_INT);
        $stmt_update_old_winner_team->execute();

        // Update new winner team's win count
        $sql_update_new_winner_team = "UPDATE teams SET team_wins = team_wins + 1 WHERE Team_ID = :winnerteamid";
        $stmt_update_new_winner_team = $pdo->prepare($sql_update_new_winner_team);
        $stmt_update_new_winner_team->bindParam(':winnerteamid', $winnerteamid, PDO::PARAM_INT);
        $stmt_update_new_winner_team->execute();
    }

    // Update players for best player
    if ($bestplayerid !== $old_bestplayerid) {
        // Update old best player's win count
        $sql_update_old_best_player = "UPDATE players SET player_wins = player_wins - 1 WHERE PlayerID = :old_bestplayerid";
        $stmt_update_old_best_player = $pdo->prepare($sql_update_old_best_player);
        $stmt_update_old_best_player->bindParam(':old_bestplayerid', $old_bestplayerid, PDO::PARAM_INT);
        $stmt_update_old_best_player->execute();

        // Update new best player's win count
        $sql_update_new_best_player = "UPDATE players SET player_wins = player_wins + 1 WHERE PlayerID = :bestplayerid";
        $stmt_update_new_best_player = $pdo->prepare($sql_update_new_best_player);
        $stmt_update_new_best_player->bindParam(':bestplayerid', $bestplayerid, PDO::PARAM_INT);
        $stmt_update_new_best_player->execute();
    }
            // Commit the transaction
            $pdo->commit();
            updatePlayerMaxPoints($proPlayer1ID,$pdo);
            updatePlayerMaxPoints($proPlayer2ID,$pdo);
            updatePlayerMaxPoints($proPlayer3ID,$pdo);
            updatePlayerMaxPoints($conPlayer1ID,$pdo);
            updatePlayerMaxPoints($conPlayer2ID,$pdo);
            updatePlayerMaxPoints($conPlayer3ID,$pdo);


            echo "Debate results updated successfully.";
        }
   } catch (PDOException $e) {
       // Rollback transaction on error
       $pdo->rollBack();
       echo "Error updating debate: " . $e->getMessage();
echo "Failed: " . $e->getMessage();
       // Optionally, output more detailed error information
       echo "<pre>";
       var_dump($pdo->errorInfo());
       echo "</pre>";
    }
}
function ed($pdo){
    $debate_round = $_POST['debate_round'];
    $debate_pro_TeamID = $_POST['debate_pro_TeamID'];
    $debate_con_TeamID = $_POST['debate_con_TeamID'];

    // Query to fetch the specific debate record using global variables
    $sql = "SELECT *, DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') as formatted_timestamp FROM debates
    WHERE debate_round = :debate_round
    AND debate_pro_TeamID = :debate_pro_TeamID
    AND debate_con_TeamID = :debate_con_TeamID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':debate_round', $debate_round, PDO::PARAM_INT);
    $stmt->bindParam(':debate_pro_TeamID', $debate_pro_TeamID, PDO::PARAM_INT);
    $stmt->bindParam(':debate_con_TeamID', $debate_con_TeamID, PDO::PARAM_INT);
    $stmt->execute();
    $debate = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($debate) {
        // Extract necessary details to prefill the form
        $debateID = $debate['DebateID'];
        $roomID = $debate['debate_room'];
        $proTeamID = $debate['debate_pro_TeamID'];
        $proPlayer1 = $debate['debate_pro_Player_1'];
        $proPlayer2 = $debate['debate_pro_Player_2'];
        $proPlayer3 = $debate['debate_pro_Player_3'];
        $proPlayerReply = $debate['debate_pro_Player_reply'];
        $conTeamID = $debate['debate_con_TeamID'];
        $conPlayer1 = $debate['debate_con_Player_1'];
        $conPlayer2 = $debate['debate_con_Player_2'];
        $conPlayer3 = $debate['debate_con_Player_3'];
        $conPlayerReply = $debate['debate_con_Player_reply'];
        $submitterID = $debate['debates_submitterID'];
        $createdAt = $debate['formatted_timestamp'];

        // Query to fetch pro team name
        $query_pro_team = "SELECT team_name FROM teams WHERE Team_ID = :proTeamID";
        $stmt_pro_team = $pdo->prepare($query_pro_team);
        $stmt_pro_team->bindParam(':proTeamID', $proTeamID, PDO::PARAM_INT);
        $stmt_pro_team->execute();
        $proTeamName = $stmt_pro_team->fetchColumn();

        // Query to fetch con team name
        $query_con_team = "SELECT team_name FROM teams WHERE Team_ID = :conTeamID";
        $stmt_con_team = $pdo->prepare($query_con_team);
        $stmt_con_team->bindParam(':conTeamID', $conTeamID, PDO::PARAM_INT);
        $stmt_con_team->execute();
        $conTeamName = $stmt_con_team->fetchColumn();

        // Fetch the debate record to prefill the form
        $proPlayer1Points = $debate['debate_points_pro_Player_1'];
        $proPlayer2Points = $debate['debate_points_pro_Player_2'];
        $proPlayer3Points = $debate['debate_points_pro_Player_3'];
        $proPlayerReplyPoints = $debate['debate_points_pro_Player_reply'];
        $conPlayer1Points = $debate['debate_points_con_Player_1'];
        $conPlayer2Points = $debate['debate_points_con_Player_2'];
        $conPlayer3Points = $debate['debate_points_con_Player_3'];
        $conPlayerReplyPoints = $debate['debate_points_con_Player_reply'];
        $bestplayerid = $debate['debate_bestplayer_ID'];
        $winnerteamid = $debate['debate_winner_TeamID'];

        // Total points for each team
        $proTotalPoints = $proPlayer1Points + $proPlayer2Points + $proPlayer3Points + $proPlayerReplyPoints;
        $conTotalPoints = $conPlayer1Points + $conPlayer2Points + $conPlayer3Points + $conPlayerReplyPoints;

        // Query to fetch room name
        $query_room = "SELECT Room_Name FROM rooms WHERE Room_ID = :roomID";
        $stmt_room = $pdo->prepare($query_room);
        $stmt_room->bindParam(':roomID', $roomID, PDO::PARAM_INT);
        $stmt_room->execute();
        $roomName = $stmt_room->fetchColumn();

        // Query to fetch submitter username
        $query_submitter = "SELECT username FROM users WHERE id = :submitterID";
        $stmt_submitter = $pdo->prepare($query_submitter);
        $stmt_submitter->bindParam(':submitterID', $submitterID, PDO::PARAM_INT);
        $stmt_submitter->execute();
        $submitterUsername = $stmt_submitter->fetchColumn();

        // Query to fetch pro players' names
        $query_pro_players = "SELECT PlayerID, player_name FROM players WHERE PlayerID IN (:proPlayer1, :proPlayer2, :proPlayer3, :proPlayerReply)";
        $stmt_pro_players = $pdo->prepare($query_pro_players);
        $stmt_pro_players->bindParam(':proPlayer1', $proPlayer1, PDO::PARAM_INT);
        $stmt_pro_players->bindParam(':proPlayer2', $proPlayer2, PDO::PARAM_INT);
        $stmt_pro_players->bindParam(':proPlayer3', $proPlayer3, PDO::PARAM_INT);
        $stmt_pro_players->bindParam(':proPlayerReply', $proPlayerReply, PDO::PARAM_INT);
        $stmt_pro_players->execute();
        $proPlayers = $stmt_pro_players->fetchAll(PDO::FETCH_ASSOC);

        // Store player information for additional info
        $proPlayerInfos = [];
        foreach ($proPlayers as $player) {
            $proPlayerInfos[$player['PlayerID']] = $player['player_name'];
        }

        // Query to fetch con players' names
        $query_con_players = "SELECT PlayerID, player_name FROM players WHERE PlayerID IN (:conPlayer1, :conPlayer2, :conPlayer3, :conPlayerReply)";
        $stmt_con_players = $pdo->prepare($query_con_players);
        $stmt_con_players->bindParam(':conPlayer1', $conPlayer1, PDO::PARAM_INT);
        $stmt_con_players->bindParam(':conPlayer2', $conPlayer2, PDO::PARAM_INT);
        $stmt_con_players->bindParam(':conPlayer3', $conPlayer3, PDO::PARAM_INT);
        $stmt_con_players->bindParam(':conPlayerReply', $conPlayerReply, PDO::PARAM_INT);
        $stmt_con_players->execute();
        $conPlayers = $stmt_con_players->fetchAll(PDO::FETCH_ASSOC);

        // Store player information for additional info
        $conPlayerInfos = [];
        foreach ($conPlayers as $player) {
            $conPlayerInfos[$player['PlayerID']] = $player['player_name'];
        }

        // Query to fetch all players for best player selection
        $query_all_players = "SELECT PlayerID, player_name FROM players WHERE PlayerID IN (:proPlayer1, :proPlayer2, :proPlayer3, :proPlayerReply, :conPlayer1, :conPlayer2, :conPlayer3, :conPlayerReply)";
        $stmt_all_players = $pdo->prepare($query_all_players);
        $stmt_all_players->bindParam(':proPlayer1', $proPlayer1, PDO::PARAM_INT);
        $stmt_all_players->bindParam(':proPlayer2', $proPlayer2, PDO::PARAM_INT);
        $stmt_all_players->bindParam(':proPlayer3', $proPlayer3, PDO::PARAM_INT);
        $stmt_all_players->bindParam(':proPlayerReply', $proPlayerReply, PDO::PARAM_INT);
        $stmt_all_players->bindParam(':conPlayer1', $conPlayer1, PDO::PARAM_INT);
        $stmt_all_players->bindParam(':conPlayer2', $conPlayer2, PDO::PARAM_INT);
        $stmt_all_players->bindParam(':conPlayer3', $conPlayer3, PDO::PARAM_INT);
        $stmt_all_players->bindParam(':conPlayerReply', $conPlayerReply, PDO::PARAM_INT);
        $stmt_all_players->execute();
        $allPlayers = $stmt_all_players->fetchAll(PDO::FETCH_ASSOC);

        // Display the edit form in table format
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Edit Debate</title>
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    padding: 8px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
            </style>
        </head>
        <body>
            <h1>Edit Debate</h1>
            <form method="POST" action="">
                <input type="hidden" name="debate_round" value="{$debate_round}">
                <input type="hidden" name="debate_pro_TeamID" value="{$debate_pro_TeamID}">
                <input type="hidden" name="debate_con_TeamID" value="{$debate_con_TeamID}">

                <table>
                    <tr>
                        <th colspan="2">{$proTeamName}</th>
                        <th colspan="2">{$conTeamName}</th>
                    </tr>
                    <tr>
                        <th>Player</th>
                        <th>Points</th>
                        <th>Player</th>
                        <th>Points</th>
                    </tr>
                    <tr>
                        <td>{$proPlayerInfos[$proPlayer1]}</td>
                        <td><input type="text" name="debate_points_pro_Player_1" value="{$proPlayer1Points}"></td>
                        <td>{$conPlayerInfos[$conPlayer1]}</td>
                        <td><input type="text" name="debate_points_con_Player_1" value="{$conPlayer1Points}"></td>
                    </tr>
                    <tr>
                        <td>{$proPlayerInfos[$proPlayer2]}</td>
                        <td><input type="text" name="debate_points_pro_Player_2" value="{$proPlayer2Points}"></td>
                        <td>{$conPlayerInfos[$conPlayer2]}</td>
                        <td><input type="text" name="debate_points_con_Player_2" value="{$conPlayer2Points}"></td>
                    </tr>
                    <tr>
                        <td>{$proPlayerInfos[$proPlayer3]}</td>
                        <td><input type="text" name="debate_points_pro_Player_3" value="{$proPlayer3Points}"></td>
                        <td>{$conPlayerInfos[$conPlayer3]}</td>
                        <td><input type="text" name="debate_points_con_Player_3" value="{$conPlayer3Points}"></td>
                    </tr>
                    <tr>
                        <td>Reply: {$proPlayerInfos[$proPlayerReply]}</td>
                        <td><input type="text" name="debate_points_pro_Player_reply" value="{$proPlayerReplyPoints}"></td>
                        <td>Reply: {$conPlayerInfos[$conPlayerReply]}</td>
                        <td><input type="text" name="debate_points_con_Player_reply" value="{$conPlayerReplyPoints}"></td>
                    </tr>
                    <tr>
                        <th colspan="4" style="text-align: center;"><strong>Total Points</strong></th>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;"><strong>{$proTotalPoints}</strong></td>
                        <td colspan="2" style="text-align: center;"><strong>{$conTotalPoints}</strong></td>
                    </tr>
                </table>
                <br>
                <label for="winnerteamid">Select Winner Team:</label>
                <select name="winnerteamid" id="winnerteamid">
        HTML;

        // optionen für winner team
        echo "<option value='{$proTeamID}' " . ($winnerteamid == $proTeamID ? 'selected' : '') . ">{$proTeamName}</option>";
        echo "<option value='{$conTeamID}' " . ($winnerteamid == $conTeamID ? 'selected' : '') . ">{$conTeamName}</option>";

        echo <<<HTML
                </select>
                <br><br>
                <label for="bestplayerid">Select Best Player:</label>
                <select name="bestplayerid" id="bestplayerid">
        HTML;

        //dropdown für best player
        foreach ($allPlayers as $player) {
            echo "<option value='{$player['PlayerID']}' " . ($bestplayerid == $player['PlayerID'] ? 'selected' : '') . ">{$player['player_name']}</option>";
        }

        echo <<<HTML
                </select>
                <br><br>
                <input type="hidden" name="debateID" value="{$debateID}">
                <input type="submit" name="update" value="Update">
            </form>
        </body>
        </html>
        HTML;
    } else {
        echo "Debate not found.";
    }

}

function updateCompetitionDate($newDate,$pdo) {


    try {
        // Vorbereiten der SQL-Abfrage
        $stmt = $pdo->prepare("UPDATE dates SET competition_date = :newDate WHERE id = 1");

        // Binden von Parametern und Ausführen der Abfrage
        $stmt->bindParam(':newDate', $newDate);
        $stmt->execute();

        echo "Wettbewerbsdatum erfolgreich aktualisiert.";
    } catch(PDOException $e) {
        echo "Fehler beim Aktualisieren des Wettbewerbsdatums: " . $e->getMessage();
    }
}

function getConfirmnumber() {
    global $debate_round, $debate_pro_TeamID, $debate_con_TeamID;
    $debate_round = isset($_POST['debate_round']) ? htmlspecialchars($_POST['debate_round']) : '';
    $debate_pro_TeamID = isset($_POST['debate_pro_TeamID']) ? htmlspecialchars($_POST['debate_pro_TeamID']) : '';
    $debate_con_TeamID = isset($_POST['debate_con_TeamID']) ? htmlspecialchars($_POST['debate_con_TeamID']) : '';
}

function updatePlayerMaxPoints($playerID, $pdo) {
    // Initialize an array to hold the points
    $points = [];

    // SQL query to find all debates where the player participated (excluding reply roles)
    $query = "SELECT debate_points_pro_Player_1, debate_points_pro_Player_2, debate_points_pro_Player_3,
                     debate_points_con_Player_1, debate_points_con_Player_2, debate_points_con_Player_3,
                     debate_pro_Player_1, debate_pro_Player_2, debate_pro_Player_3,
                     debate_con_Player_1, debate_con_Player_2, debate_con_Player_3
              FROM debates
              WHERE debate_pro_Player_1 = :playerID OR debate_pro_Player_2 = :playerID OR debate_pro_Player_3 = :playerID
                 OR debate_con_Player_1 = :playerID OR debate_con_Player_2 = :playerID OR debate_con_Player_3 = :playerID";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':playerID', $playerID, PDO::PARAM_INT);
    $stmt->execute();
    $debates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Loop through all debates and collect points
    foreach ($debates as $debate) {
        if ($debate['debate_pro_Player_1'] == $playerID) {
            $points[] = $debate['debate_points_pro_Player_1'];
        }
        if ($debate['debate_pro_Player_2'] == $playerID) {
            $points[] = $debate['debate_points_pro_Player_2'];
        }
        if ($debate['debate_pro_Player_3'] == $playerID) {
            $points[] = $debate['debate_points_pro_Player_3'];
        }
        if ($debate['debate_con_Player_1'] == $playerID) {
            $points[] = $debate['debate_points_con_Player_1'];
        }
        if ($debate['debate_con_Player_2'] == $playerID) {
            $points[] = $debate['debate_points_con_Player_2'];
        }
        if ($debate['debate_con_Player_3'] == $playerID) {
            $points[] = $debate['debate_points_con_Player_3'];
        }
    }

    // Find the highest points
    if (!empty($points)) {
        $maxPoints = max($points);

        // Update the player's max points in the database
        $updateQuery = "UPDATE players SET player_MAX_points = :maxPoints WHERE playerID = :playerID";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':maxPoints', $maxPoints, PDO::PARAM_INT);
        $updateStmt->bindParam(':playerID', $playerID, PDO::PARAM_INT);
        $updateStmt->execute();

        echo "Player ID $playerID max points updated to $maxPoints.";
    } else {
        echo "Player ID $playerID did not participate in any debates.";
    }
}



function echoMarksheet($pdo){
    global $debate_round, $debate_pro_TeamID, $debate_con_TeamID;

    // Query to fetch the specific debate record using global variables
    $sql = "SELECT *, DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') as formatted_timestamp FROM debates
            WHERE debate_round = :debate_round
            AND debate_pro_TeamID = :debate_pro_TeamID
            AND debate_con_TeamID = :debate_con_TeamID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':debate_round', $debate_round, PDO::PARAM_INT);
    $stmt->bindParam(':debate_pro_TeamID', $debate_pro_TeamID, PDO::PARAM_INT);
    $stmt->bindParam(':debate_con_TeamID', $debate_con_TeamID, PDO::PARAM_INT);
    $stmt->execute();

    // Fetching the single debate record (assuming it's unique)
    $debate = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($debate) {
        $debateID = $debate['DebateID'];
        $roomID = $debate['debate_room'];
        $proTeamID = $debate['debate_pro_TeamID'];
        $proPlayer1 = $debate['debate_pro_Player_1'];
        $proPlayer2 = $debate['debate_pro_Player_2'];
        $proPlayer3 = $debate['debate_pro_Player_3'];
        $proPlayerReply = $debate['debate_pro_Player_reply'];
        $conTeamID = $debate['debate_con_TeamID'];
        $conPlayer1 = $debate['debate_con_Player_1'];
        $conPlayer2 = $debate['debate_con_Player_2'];
        $conPlayer3 = $debate['debate_con_Player_3'];
        $conPlayerReply = $debate['debate_con_Player_reply'];
        $submitterID = $debate['debates_submitterID'];
        $createdAt = $debate['formatted_timestamp'];

        // Points for pro players
        $proPlayer1Points = $debate['debate_points_pro_Player_1'];
        $proPlayer2Points = $debate['debate_points_pro_Player_2'];
        $proPlayer3Points = $debate['debate_points_pro_Player_3'];
        $proPlayerReplyPoints = $debate['debate_points_pro_Player_reply'];

        // Points for con players
        $conPlayer1Points = $debate['debate_points_con_Player_1'];
        $conPlayer2Points = $debate['debate_points_con_Player_2'];
        $conPlayer3Points = $debate['debate_points_con_Player_3'];
        $conPlayerReplyPoints = $debate['debate_points_con_Player_reply'];

        // Total points for each team
        $proTotalPoints = $proPlayer1Points + $proPlayer2Points + $proPlayer3Points + $proPlayerReplyPoints;
        $conTotalPoints = $conPlayer1Points + $conPlayer2Points + $conPlayer3Points + $conPlayerReplyPoints;

        // Query to fetch room name
        $query_room = "SELECT Room_Name FROM rooms WHERE Room_ID = :roomID";
        $stmt_room = $pdo->prepare($query_room);
        $stmt_room->bindParam(':roomID', $roomID, PDO::PARAM_INT);
        $stmt_room->execute();
        $roomName = $stmt_room->fetchColumn();

        // Query to fetch pro team name
        $query_pro_team = "SELECT team_name FROM teams WHERE Team_ID = :proTeamID";
        $stmt_pro_team = $pdo->prepare($query_pro_team);
        $stmt_pro_team->bindParam(':proTeamID', $proTeamID, PDO::PARAM_INT);
        $stmt_pro_team->execute();
        $proTeamName = $stmt_pro_team->fetchColumn();

        // Query to fetch pro players' names
        $query_pro_players = "SELECT PlayerID, player_name FROM players WHERE PlayerID IN (:proPlayer1, :proPlayer2, :proPlayer3, :proPlayerReply)";
        $stmt_pro_players = $pdo->prepare($query_pro_players);
        $stmt_pro_players->bindParam(':proPlayer1', $proPlayer1, PDO::PARAM_INT);
        $stmt_pro_players->bindParam(':proPlayer2', $proPlayer2, PDO::PARAM_INT);
        $stmt_pro_players->bindParam(':proPlayer3', $proPlayer3, PDO::PARAM_INT);
        $stmt_pro_players->bindParam(':proPlayerReply', $proPlayerReply, PDO::PARAM_INT);
        $stmt_pro_players->execute();
        $proPlayers = $stmt_pro_players->fetchAll(PDO::FETCH_KEY_PAIR);

        // Query to fetch con team name
        $query_con_team = "SELECT team_name FROM teams WHERE Team_ID = :conTeamID";
        $stmt_con_team = $pdo->prepare($query_con_team);
        $stmt_con_team->bindParam(':conTeamID', $conTeamID, PDO::PARAM_INT);
        $stmt_con_team->execute();
        $conTeamName = $stmt_con_team->fetchColumn();

        // Query to fetch con players' names
        $query_con_players = "SELECT PlayerID, player_name FROM players WHERE PlayerID IN (:conPlayer1, :conPlayer2, :conPlayer3, :conPlayerReply)";
        $stmt_con_players = $pdo->prepare($query_con_players);
        $stmt_con_players->bindParam(':conPlayer1', $conPlayer1, PDO::PARAM_INT);
        $stmt_con_players->bindParam(':conPlayer2', $conPlayer2, PDO::PARAM_INT);
        $stmt_con_players->bindParam(':conPlayer3', $conPlayer3, PDO::PARAM_INT);
        $stmt_con_players->bindParam(':conPlayerReply', $conPlayerReply, PDO::PARAM_INT);
        $stmt_con_players->execute();
        $conPlayers = $stmt_con_players->fetchAll(PDO::FETCH_KEY_PAIR);

        // Query to fetch submitter username
        $query_submitter = "SELECT username FROM users WHERE id = :submitterID";
        $stmt_submitter = $pdo->prepare($query_submitter);
        $stmt_submitter->bindParam(':submitterID', $submitterID, PDO::PARAM_INT);
        $stmt_submitter->execute();
        $submitterUsername = $stmt_submitter->fetchColumn();

        // Example output (adjust as needed)
        echo "<h3>Debate Details</h3>";
        echo "<p><strong>Debate ID:</strong> $debateID</p>";
        echo "<p><strong>Debate Round:</strong> $debate_round</p>";
        echo "<p><strong>Room Name:</strong> $roomName</p>";

        echo "<table border='1' cellspacing='0' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th colspan='2'>Pro Team: $proTeamName</th><th colspan='2'>Con Team: $conTeamName</th></tr>";
        echo "<tr><th>Player</th><th>Points</th><th>Player</th><th>Points</th></tr>";
        echo "<tr><td>{$proPlayers[$proPlayer1]} (Player 1)</td><td>$proPlayer1Points</td><td>{$conPlayers[$conPlayer1]} (Player 1)</td><td>$conPlayer1Points</td></tr>";
        echo "<tr><td>{$proPlayers[$proPlayer2]} (Player 2)</td><td>$proPlayer2Points</td><td>{$conPlayers[$conPlayer2]} (Player 2)</td><td>$conPlayer2Points</td></tr>";
        echo "<tr><td>{$proPlayers[$proPlayer3]} (Player 3)</td><td>$proPlayer3Points</td><td>{$conPlayers[$conPlayer3]} (Player 3)</td><td>$conPlayer3Points</td></tr>";
        echo "<tr><td>{$proPlayers[$proPlayerReply]} (Reply)</td><td>$proPlayerReplyPoints</td><td>{$conPlayers[$conPlayerReply]} (Reply)</td><td>$conPlayerReplyPoints</td></tr>";
        echo "<tr><td><strong>Total Points</strong></td><td><strong>$proTotalPoints</strong></td><td><strong>Total Points</strong></td><td><strong>$conTotalPoints</strong></td></tr>";
        echo "</table>";

        echo "<p><strong>Submitter Username:</strong> $submitterUsername</p>";
        echo "<p><strong>Submission Timestamp:</strong> $createdAt</p>";

        // Additional variables
        echo "<h3>Additional Details</h3>";
        echo "<p><strong>Pro Team ID:</strong> $proTeamID</p>";
        echo "<p><strong>Pro Player 1 ID:</strong> $proPlayer1</p>";
        echo "<p><strong>Pro Player 2 ID:</strong> $proPlayer2</p>";
        echo "<p><strong>Pro Player 3 ID:</strong> $proPlayer3</p>";
        echo "<p><strong>Pro Player Reply ID:</strong> $proPlayerReply</p>";
        echo "<p><strong>Con Team ID:</strong> $conTeamID</p>";
        echo "<p><strong>Con Player 1 ID:</strong> $conPlayer1</p>";
        echo "<p><strong>Con Player 2 ID:</strong> $conPlayer2</p>";
        echo "<p><strong>Con Player 3 ID:</strong> $conPlayer3</p>";
        echo "<p><strong>Con Player Reply ID:</strong> $conPlayerReply</p>";
        echo "<p><strong>Submitter ID:</strong> $submitterID</p>";
        echo "<hr>";
    } else {
        echo "No debate found matching the given criteria.";
    }
}


function startlock($pdo) {
    if ($pdo) {
        $query = 'UPDATE config SET wettbewerb_aktiv = TRUE WHERE id = 1';
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    } else {
        echo "Invalid PDO object.";
    }
}

function endlock($pdo) {
    if ($pdo) {
        $query = 'UPDATE config SET wettbewerb_aktiv = FALSE WHERE id = 1';
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    } else {
        echo "Invalid PDO object.";
    }
}

function resetnodel($pdo) {
    if ($pdo) {
        try {
            // Delete debates
            $query = 'DELETE FROM `debates`';
            $stmt = $pdo->prepare($query);
            $stmt->execute();

            // Update teams
            $query = 'UPDATE `teams` SET `team_points` = 0, `team_wins` = 0';
            $stmt = $pdo->prepare($query);
            $stmt->execute();

            // Update players
            $query = 'UPDATE `players` SET `player_points` = 0, `player_Max_points` = 0, `player_wins` = 0';
            $stmt = $pdo->prepare($query);
            $stmt->execute();

            echo "Reset successfully completed.";
        } catch (PDOException $e) {
            echo "Error resetting tables: " . $e->getMessage();
        }
    } else {
        echo "Invalid PDO object.";
    }
}

function totaldrop($pdo) {
    if ($pdo) {
        try {
            // Delete debates
            $query = 'DELETE FROM `debates`';
            $stmt = $pdo->prepare($query);
            $stmt->execute();

            // Delete players
            $query = 'DELETE FROM `players`';
            $stmt = $pdo->prepare($query);
            $stmt->execute();

            // Delete teams
            $query = 'DELETE FROM `teams`';
            $stmt = $pdo->prepare($query);
            $stmt->execute();

            echo "Dropped all tables successfully.";
        } catch (PDOException $e) {
            echo "Error dropping tables: " . $e->getMessage();
        }
    } else {
        echo "Invalid PDO object.";
    }
}

function echoConfigTable($pdo) {
    if ($pdo) {
        $query = 'SELECT * FROM config';
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            echo "<table border='1'>";
            echo "<tr>";
            foreach (array_keys($results[0]) as $header) {
                echo "<th>" . htmlspecialchars($header) . "</th>";
            }
            echo "</tr>";
            foreach ($results as $row) {
                echo "<tr>";
                foreach ($row as $column) {
                    echo "<td>" . htmlspecialchars($column) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No data found in the config table.";
        }
    } else {
        echo "Invalid PDO object.";
    }
}

function echoAllTables($pdo) {
    if ($pdo) {
        $tables = ['config', 'debates', 'players', 'teams',  'rooms', 'contact', 'users', 'users_admin','dates','host'];
        foreach ($tables as $table) {
            $query = 'SELECT * FROM ' . $table;
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                echo "<h2>Table: " . htmlspecialchars($table) . "</h2>";
                echo "<table border='1'>";
                echo "<tr>";
                foreach (array_keys($results[0]) as $header) {
                    echo "<th>" . htmlspecialchars($header) . "</th>";
                }
                echo "</tr>";
                foreach ($results as $row) {
                    echo "<tr>";
                    foreach ($row as $column) {
                        echo "<td>" . htmlspecialchars($column) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table><br>";
            } else {
                echo "No data found in the $table table.<br>";
            }
        }
    } else {
        echo "Invalid PDO object.";
    }
}
?>
