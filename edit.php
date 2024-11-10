<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once ("connection_pdo.php");

if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style1.css">
</head>

<body>

    <div class="container">
        <div class="form-box box">

            <?php

            if (isset($_POST['update'])) {
                $username = $_POST['username'];
               // $email = $_POST['email'];
                $password = $_POST['password'];

                $pass = password_hash($password, PASSWORD_DEFAULT);

                $id = $_SESSION['id'];

                try {
                    // Prepare the SQL statement
                    $stmt = $connection_pdo->prepare("UPDATE users SET username=?, password=? WHERE id=?");

                    // Bind parameters
                    $stmt->bindParam(1, $username);
                    //$stmt->bindParam(2, $email);
                    $stmt->bindParam(2, $pass);
                    $stmt->bindParam(3, $id);

                    // Execute the statement
                    $stmt->execute();

                    // Check if the update was successful
                    if ($stmt->rowCount() > 0) {
                        echo "<div class='message'>
                            <p>Profile Updated!</p>
                          </div><br>";
                        echo "<a href='home.php'><button class='btn'>Go Home</button></a>";
                    } else {
                        echo "<div class='message'>
                            <p>Profile update failed 😔</p>
                          </div><br>";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                // Fetch user data for displaying in the form
                $id = $_SESSION['id'];
                try {
                    // Prepare the SQL statement
                    $stmt = $connection_pdo->prepare("SELECT * FROM users WHERE id=?");

                    // Bind parameters
                    $stmt->bindParam(1, $id);

                    // Execute the statement
                    $stmt->execute();

                    // Fetch the result
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    $res_username = $result['username'];
                    //$res_email = $result['email'];
                    // Password should not be retrieved for security reasons
                    // $res_password = $result['password'];
                    $res_id = $result['id'];

                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }

                ?>

                <header>Change Profile</header>
                <form action="#" method="POST" enctype="multipart/form-data">

                    <div class="form-box">

                        <div class="input-container">
                            <i class="fa fa-user icon"></i>
                            <input class="input-field" type="text" placeholder="Username" name="username"
                                value="<?php echo $res_username; ?>" required>
                        </div>

                      

                        <div class="input-container">
                            <i class="fa fa-lock icon"></i>
                            <input class="input-field password" type="password" placeholder="New-Password"
                                name="password" required>
                            <i class="fa fa-eye toggle icon"></i>
                        </div>

                    </div>

                    <div class="field">
                        <input type="submit" name="update" id="submit" value="Update" class="btn">
                    </div>

                </form>
            </div>
            <?php } ?>
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
