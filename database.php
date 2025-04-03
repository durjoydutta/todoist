<?php
$hostname = "sql202.infinityfree.com";
$username = "if0_38662848";
$db = "if0_38662848_todoist";
$pw = "L858LRI3bSPzk";

$con = mysqli_connect($hostname, $username, $pw, $db);

if (!$con) {
    die("<br>Error connecting to database: " . mysqli_connect_error() . "<br>");
} 
// else {
//     echo "<br> Database '{$db}' connected successfully  <br>";
// }
