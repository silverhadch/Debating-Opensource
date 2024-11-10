<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
unset($_SESSION['username']);

require_once('connection_pdo.php');

// Check if an admin is logged in
if (!isset($_SESSION['username_admin'])) {
    // Optional: You can comment out the following line if you want regular users to access this page.
    header("location:login.php");
    exit();
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
            <header>Sign Up</header>
            <hr>

            <form action="#" method="POST">

                <?php
                if (isset($_POST['register'])) {
                    $name = $_POST['username'];
                    $pass = $_POST['password'];
                    $cpass = $_POST['cpass'];
                    $isAdmin = isset($_POST['isAdmin']) ? 1 : 0; // Get checkbox value

                    // Check if username is already registered
                    $checkStmt = $connection_pdo->prepare("SELECT * FROM users WHERE username = :name");
                    $checkStmt->bindParam(':name', $name);
                    $checkStmt->execute();

                    if ($checkStmt->rowCount() > 0) {
                        echo "<div class='message'><p>This Username is already in use. Please choose another one.</p></div><br>";
                        echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button></a>";
                    } else {
                        // Hash the password
                        $passwd = password_hash($pass, PASSWORD_DEFAULT);

                        // Insert user into database if passwords match
                        if ($pass === $cpass) {
                            // Insert into users table first
                            $insertStmt = $connection_pdo->prepare("INSERT INTO users(username, password) VALUES (:name, :passwd)");
                            $insertStmt->bindParam(':name', $name);
                            $insertStmt->bindParam(':passwd', $passwd);
                            $result = $insertStmt->execute();

                            // If the user is an admin, insert into users_admin as well
                            if ($result && $isAdmin) {
                                $adminInsertStmt = $connection_pdo->prepare("INSERT INTO users_admin(username, password) VALUES (:name, :passwd)");
                                $adminInsertStmt->bindParam(':name', $name);
                                $adminInsertStmt->bindParam(':passwd', $passwd);
                                $adminInsertStmt->execute(); // No need to check the result for admin insertion
                            }

                            if ($result) {
                                echo "<div class='message'><p>You have registered successfully!</p></div><br>";
                                echo "<a href='login.php'><button class='btn'>Login Now</button></a>";
                            } else {
                                echo "<div class='message'><p>Registration failed. Please try again later.</p></div><br>";
                                echo "<a href='signup.php'><button class='btn'>Go Back</button></a>";
                            }
                        } else {
                            echo "<div class='message'><p>Passwords do not match.</p></div><br>";
                            echo "<a href='signup.php'><button class='btn'>Go Back</button></a>";
                        }
                    }
                } else {
                ?>

                    <div class="input-container">
                        <i class="fa fa-user icon"></i>
                        <input class="input-field" type="text" placeholder="Username" name="username" required>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-lock icon"></i>
                        <input class="input-field password" type="password" placeholder="Password" name="password" required>
                        <i class="fa fa-eye icon toggle"></i>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-lock icon"></i>
                        <input class="input-field" type="password" placeholder="Confirm Password" name="cpass" required>
                    </div>

                    <!-- Admin tickbox with spacing -->
                    <div class="input-container">
                        <input type="checkbox" id="isAdmin" name="isAdmin">
                        <label for="isAdmin" style="margin-left: 10px;">Register as Admin</label> <!-- Add margin for spacing -->
                    </div>

                    <center>
                        <input type="submit" name="register" id="submit" value="Signup" class="btn">
                    </center>

                <?php
                }
                ?>

            </form>
            <center>
                <a href="admin_home.php"><button class="btn" style="margin-top: 10px;">Home</button></a> <!-- Home button outside the form -->
            </center>
        </div>
    </div>

    <script>
        const toggle = document.querySelector(".toggle"),
            input = document.querySelector(".password");
        toggle.addEventListener("click", () => {
            if (input.type === "password") {
                input.type = "text";
                toggle.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                toggle.classList.replace("fa-eye-slash", "fa-eye");
            }
        })
    </script>
</body>

</html>
