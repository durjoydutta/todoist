<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODOist</title>
</head>

<body>
    <h2>Please signup to continue</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="username">Username: </label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Password: </label>
        <input type="password" name="password" required>
        <br>
        <label for="confirm_password">Confirm Password: </label>
        <input type="password" name="confirm_password" required>
        <br>
        <button type="submit" name="signup">Sign Up</button>
    </form>
</body>

</html>

<?php
session_start();
if (isset($_SESSION["id"])) { //if user is logged in redirect him back
    header("Location: index.php");
    exit();
}
include("database.php");
if (isset($_POST["signup"])) {
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        $username = mysqli_real_escape_string($con, $_POST["username"]);
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        if ($password != $confirm_password) {
            echo "<p style='color: red;'>Passwords do not match. Please try again.</p>";
        } else {
            $hashed_pw = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (id, username, password) VALUES (UUID(), '$username', '$hashed_pw')";
            try {
                $query = mysqli_query($con, $sql);
                if ($query) {
                    header("Location: index.php");
                    exit();
                }
            } catch (mysqli_sql_exception $e) {
                echo "Failed to sign you up! Error: " . $e->getMessage();
            }
        }
    } else {
        echo "<br> Username or Password field incomplete!<br>";
    }
}
mysqli_close($con);
?>