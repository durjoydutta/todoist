<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$hostname = "localhost";
$username = "ddc";
$db = "todoist";
$pw = $_ENV["DB_PASSWORD"]; //create a .env file and put the db pw there

$con = mysqli_connect($hostname, $username, $pw, $db);

if (!$con) {
    die("Error connecting to database: " . mysqli_connect_error());
} 
// else {
//     echo "<br> Database '$db' connected successfully  <br>";
// }
