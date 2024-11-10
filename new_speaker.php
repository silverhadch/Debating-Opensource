<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Logout any non-admin user trying to access this page
unset($_SESSION['username']);
include("connection_pdo.php");

if (!isset($_SESSION['username_admin'])) {
    header("location:login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission

    $name = $_POST['name'];
    $team_ID = $_POST['team'];
    $points = 0;
    $max_points = 0;
    $wins = 0;

    // Prepare and execute a query to check if the player name already exists
    $stmt = $connection_pdo->prepare("SELECT * FROM players WHERE player_name = :name");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $player = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($player) {
        echo "<div class='message'>
                <p>This name is already in use. Please try another one.</p>
              </div><br>";
    } else {
        // Prepare and execute query to insert new player
        $stmt = $connection_pdo->prepare("INSERT INTO players (TeamID, player_name, player_points, player_Max_points, player_wins) VALUES (:team_ID, :name, :points, :max_points, :wins)");
        $stmt->bindParam(':team_ID', $team_ID);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':points', $points);
        $stmt->bindParam(':max_points', $max_points);
        $stmt->bindParam(':wins', $wins);

        if ($stmt->execute()) {
            echo "<div class='message'>
                    <p>You have registered successfully!</p>
                  </div><br>";
        } else {
            echo "<div class='message'>
                    <p>Registration failed. Please try again.</p>
                  </div><br>";
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
            <header>New Speaker</header>
            <hr>
            <form action="#" method="POST">
                <div class="form-box">
                    <div class="input-container">
                        <i class="fa fa-user icon"></i>
                        <input class="input-field" type="text" placeholder="Firstname + Name" name="name" required>
                    </div>
                    <div class="input-container row select-wrap">
                        <i class="bi bi-people-fill"></i>
                        <select name="team" class="input-field" aria-placeholder="Team" id="select-box">
                            <option selected disabled hidden><i class="bi bi-people-fill"></i>Team</option>
                            <?php
                            $stmt = $connection_pdo->query("SELECT * FROM teams");
                            while ($team = $stmt->fetch(PDO::FETCH_ASSOC)):
                            ?>
                                <option value="<?php echo $team["Team_ID"]; ?>"><?php echo $team["team_name"]; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <center><input type="submit" name="register" id="submit" value="Signup" class="btn"></center>
            </form>
        </div>
        <center><a href="admin_home.php" class="btn">Home</a><center>
        </div>
    </div>

    <script>
        const toggle = document.querySelector(".toggle"),
        input = document.querySelector(".password");
        toggle.addEventListener("click", () => {
        if (input.type === "password") {
            input.type = "text";
            toggle.classList.replace("fa-eye-slash", "fa-eye");
        } else {
            input.type = "password";
        }
        })
    </script>
</body>

</html>
