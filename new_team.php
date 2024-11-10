<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
unset($_SESSION['username']);

include("connection_pdo.php");

if (!isset($_SESSION['username_admin'])) {
    header("location:login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission
    if (isset($_POST['register'])) {
        $name = $_POST['team_name'];
        $suffix = $_POST['suffix']; // Get the suffix from the selection
        $school = $_POST['school_name']; // Get the school name input
        $points = 0;
        $wins = 0;

        // Append the suffix if it's selected
        if ($suffix) {
            $name .= " " . $suffix;
        }

        // Check if team name already exists
        $checkQuery = "SELECT * FROM teams WHERE team_name = :name";
        $checkStmt = $connection_pdo->prepare($checkQuery);
        $checkStmt->execute(['name' => $name]);

        if ($checkStmt->rowCount() > 0) {
            echo "<div class='message'>
                    <p>This name is already in use. Please try another one.</p>
                  </div><br>";
        } else {
            // Insert new team with school name
            $insertQuery = "INSERT INTO teams (team_name, team_school, team_points, team_wins) VALUES (:name, :school, :points, :wins)";
            $insertStmt = $connection_pdo->prepare($insertQuery);

            try {
                $insertStmt->execute(['name' => $name, 'school' => $school, 'points' => $points, 'wins' => $wins]);
                echo "<div class='message'>
                        <p>The team has been registered successfully!</p>
                      </div><br>";
            } catch (PDOException $e) {
                echo "<div class='message'>
                        <p>Registration failed. Please try again.</p>
                      </div><br>";
                // You can output $e->getMessage() for debugging purposes
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="container">
        <div class="form-box box">
            <header>New Team</header>
            <hr>
            <form action="#" method="POST">
                <div class="form-box">
                    <div class="input-container">
                        <i class="bi bi-people-fill"></i>
                        <input class="input-field" type="text" placeholder="Team Name" name="team_name" required>
                    </div>
                    <div class="input-container">
                        <i class="bi bi-building"></i>
                        <input class="input-field" type="text" placeholder="School Name" name="school_name" required>
                    </div>
                    <div class="input-container">
                        <i class="fa fa-cog icon"></i>
                        <select name="suffix" class="input-field">
                            <option value="">Select League</option>
                            <option value="Jr.">Jr.</option>
                            <option value="Sr.">Sr.</option>
                        </select>
                    </div>
                </div>
                <center><input type="submit" name="register" id="submit" value="Signup" class="btn"></center>
            </form>
        </div>

        <center><a href="admin_home.php" class="btn">Home</a></center>
    </div>

    <!-- Footer Section -->

    <!-- End of Footer Section -->
</body>

</html>

