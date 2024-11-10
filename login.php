<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('connection_pdo.php');

// Check if the form was submitted
if (isset($_POST['login'])) {
    $usr = $_POST['usr'];
    $password = $_POST['password'];

    // Prepare SQL statement to fetch user by username from the regular users table
    $stmt = $connection_pdo->prepare("SELECT * FROM users WHERE username = :usr");
    $stmt->bindParam(':usr', $usr);
    $stmt->execute();

    // Fetch the user
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables for regular user
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: home.php");
            exit();
        } else {
            echo "<div class='message'>
                  <p>Wrong Password</p>
                  </div><br>";
            echo "<a href='login.php'><button class='btn'>Go Back</button></a>";
        }
    } else {
        // Check if the user exists in the admin table if not found in regular users
        $stmt_admin = $connection_pdo->prepare("SELECT * FROM users_admin WHERE username = :usr");
        $stmt_admin->bindParam(':usr', $usr);
        $stmt_admin->execute();

        // Fetch the admin user
        $admin_user = $stmt_admin->fetch(PDO::FETCH_ASSOC);

        if ($admin_user) {
            // Verify admin password
            if (password_verify($password, $admin_user['password'])) {
                // Set session variables for admin user
                $_SESSION['id_admin'] = $admin_user['id'];
                $_SESSION['username_admin'] = $admin_user['username'];
                header("Location: home.php");
                exit();
            } else {
                echo "<div class='message'>
                      <p>Wrong Password</p>
                      </div><br>";
                echo "<a href='login.php'><button class='btn'>Go Back</button></a>";
            }
        } else {
            echo "<div class='message'>
                  <p>Wrong Username or Password</p>
                  </div><br>";
            echo "<a href='login.php'><button class='btn'>Go Back</button></a>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="container">
        <div class="form-box box">
            <header>Login</header>
            <hr>
            <form action="#" method="POST">
                <div class="form-box">
                    <div class="input-container">
                        <i class="fa fa-envelope icon"></i>
                        <input class="input-field" type="usr" placeholder="Username" name="usr" required>
                    </div>
                    <div class="input-container">
                        <i class="fa fa-lock icon"></i>
                        <input class="input-field password" type="password" placeholder="Password" name="password" required>
                        <i class="fa fa-eye toggle icon"></i>
                    </div>
                </div>
                <div class="links">
                    <input type="submit" name="login" id="submit" value="Login" class="btn">
                </div>
            </form>
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
                toggle.classList.replace("fa-eye", "fa-eye-slash");
            }
        });
    </script>
</body>

</html>

