<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODOist</title>
</head>

<body>
    <h2>Please login to continue</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username: </label>
        <input type="text" name="username">
        <br>
        <label for="password">Password: </label>
        <input type="password" name="password">
        <br>
        <button type="submit" name="login">Login</button>
    </form>
    <br>
    <a href="signup.php">Create an account</a>
</body>

</html>

<?php
session_start();
include("database.php");
if (isset($_POST["login"])) {
    if (!empty($_POST["username"] && !empty($_POST["password"]))) {
        $username = mysqli_real_escape_string($con, $_POST["username"]);
        $query = "SELECT * FROM users WHERE username='{$username}' LIMIT 1"; // assuming username is unique
        $result = mysqli_query($con, $query);
        $password = $_POST["password"];
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $stored_password = $row["password"];
            // foreach ($row as $key => $value) {
            //     echo "<br> $key  :  $value  <br>";
            // }
            if (password_verify($password, $stored_password)) {
                $_COOKIE["username"] = $username;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $row["role"];
                $_SESSION["id"] = $row["id"]; // update session id as user is successfull logged in
                header("Location: index.php");
                exit();
            } else {
                echo "<p style='color: red;'>Invalid password. Please try again.</p>";
            }
        } else {
            echo "<p style='color: red;'>No user found with that username.</p>";
        }
    } else {
        echo "<p style='color: red;'>Please fill in all fields.</p>";
    }
}
mysqli_close($con);
?>