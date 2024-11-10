<?php
ob_start();
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('connection_pdo.php');

// Check if admin is already logged in
if (isset($_SESSION['id_admin'])) {
    header("Location: admin_home.php");
    exit();
}

// Handle form submission
if (isset($_POST['login'])) {
    $usr = $_POST['usr'];
    $pass = $_POST['password'];

    // Prepare SQL statement to check for admin by username
    $stmt = $connection_pdo->prepare("SELECT * FROM users_admin WHERE username = :usr");
    $stmt->execute([':usr' => $usr]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify password
        if (password_verify($pass, $user['password'])) {
            // Set session variables for admin
            $_SESSION['id_admin'] = $user['id'];
            $_SESSION['username_admin'] = $user['username'];
            header("Location: admin_home.php");  // Redirect to admin homepage
            exit();
        } else {
            echo "<div class='message'>
                    <p>Wrong Password</p>
                  </div><br>";
            echo "<a href='admin_login.php'><button class='btn'>Go Back</button></a>";
        }
    } else {
        // Show access denied message if user is not in the admin table
        echo "<div class='message'>
                <p>Access Denied: You are not authorized to access the admin area.</p>
              </div><br>";
        echo "<a href='admin_login.php'><button class='btn'>Go Back</button></a>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Login</title>
    <link rel="stylesheet" href="css/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="container">
        <div class="form-box box">

            <header>Admin-Login</header>
            <hr>
            <form action="#" method="POST">

                <div class="form-box">
                    <div class="input-container">
                        <i class="fa fa-envelope icon"></i>
                        <input class="input-field" type="text" placeholder="Username" name="usr" required>
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
                toggle.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                toggle.classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    </script>
</body>

</html>

<?php
ob_end_flush();
?>

