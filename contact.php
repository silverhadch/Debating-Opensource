<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once ("connection_pdo.php");

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    try {
        // Prepare the SQL statement
        $query = "INSERT INTO contact (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $connection_pdo->prepare($query);

        // Bind parameters
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $subject);
        $stmt->bindParam(4, $message);

        // Execute the statement
        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            echo "<div class='message'>
                    <p>Message sent successfully ✨</p>
                  </div><br>";
        } else {
            echo "<div class='message'>
                    <p>Message sending failed 😔</p>
                  </div><br>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="css/style1.css">
</head>

<body>
    <div class="container">
        <div class="form-box box">
            <form action="#" method="POST">
                <div class="input-container">
                    <input class="input-field" type="text" placeholder="Name" name="name" required>
                </div>
                <div class="input-container">
                    <input class="input-field" type="email" placeholder="Email Address" name="email" required>
                </div>
                <div class="input-container">
                    <input class="input-field" type="text" placeholder="Subject" name="subject" required>
                </div>
                <div class="input-container">
                    <textarea class="input-field" placeholder="Message" name="message" required></textarea>
                </div>
                <div class="input-container">
                    <input type="submit" name="submit" value="Send Message" class="btn">
                </div>
            </form>
            <a href="index.php"><button class="btn">Go Back</button></a>
        </div>
    </div>
</body>

</html>
