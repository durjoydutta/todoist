 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>TODOist</title>
 </head>

 <body>
     <h2>index.php</h2>
     <form action="index.php" method="get">
         <button type="submit" name="logout">Logout</button>
     </form>
     <br>
     <form action="index.php" method="post">
         <input type="text" placeholder="enter your to-do task" name="new-task">
         <button type="submit" name="add-task">Add Task</button>
     </form>
 </body>

 </html>


 <?php
    session_start();
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
    }
    include("database.php");

    $fetch_task_query = "select * from tasks where user_id='{$_SESSION['id']}'";
    $fetch_task_res = mysqli_query($con, $fetch_task_query);
    if ($fetch_task_res && mysqli_num_rows($fetch_task_res) > 0) {
        echo "<br><table border='1'>";
        echo "<tr><th>Title</th><th>Description</th><th>Status</th><th>Due Date</th><th>Created On</th><th>Set Status</th></tr>";
        while ($row = mysqli_fetch_assoc($fetch_task_res)) {
            echo "<tr>";
            $creationDate = explode(" ", $row["created_at"])[0];
            echo "<td>{$row["title"]}</td><td>{$row["description"]}</td><td>{$row["status"]}</td><td>{$row["due_date"]}</td><td>{$creationDate}</td>";
            echo "<td><select name='set-status'>
                <option value='pending'>Pending</option>
                <option value='in_progress'>In Progress</option>
                <option value='complete'>Complete</option>
            </select></td>";
            echo "<tr>";
        }
        echo "</table><br>";
    }

    if (isset($_POST["add-task"])) {
        if (!empty($_POST["new-task"])) {
            $new_task = mysqli_real_escape_string($con, $_POST["new-task"]);
            $add_task = "insert into tasks(title) values ('{$new_task}')";
        }
    }

    if (isset($_GET["logout"])) {
        header("Location: login.php");
        mysqli_close($con);
        session_destroy();
        exit();
    }
    ?>