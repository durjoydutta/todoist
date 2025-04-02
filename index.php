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
        <input type="text" placeholder="Enter your to-do task" name="new-task" required>
        <button type="submit" name="add-task">Add Task</button>
    </form>
    <br>
    <?php
    session_start();
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit();
    }

    include("database.php");

    // Fetch tasks function
    function fetch_tasks($con)
    {
        $user_id = $_SESSION["id"];
        $fetch_task_query = "SELECT * FROM tasks WHERE user_id = '$user_id'";
        $result = mysqli_query($con, $fetch_task_query);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<br><table border='1'>";
            echo "<tr><th>Title</th><th>Description</th><th>Status</th><th>Due Date</th><th>Created On</th><th>Set Status</th><th>Actions</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                $creationDate = explode(" ", $row["created_at"])[0];
                echo "<tr>";
                echo "<td>{$row["title"]}</td>";
                echo "<td>{$row["description"]}</td>";
                echo "<td>{$row["status"]}</td>";
                echo "<td>{$row["due_date"]}</td>";
                echo "<td>{$creationDate}</td>";
                echo "<td>
                    <form action='index.php' method='post'>
                        <input type='hidden' name='task_id' value='{$row["id"]}'>
                        <select name='set-status' onchange='this.form.submit()'>
                            <option value='pending' " . ($row["status"] == "pending" ? "selected" : "") . ">Pending</option>
                            <option value='in_progress' " . ($row["status"] == "in_progress" ? "selected" : "") . ">In Progress</option>
                            <option value='completed' " . ($row["status"] == "completed" ? "selected" : "") . ">Completed</option>
                        </select>
                    </form>
                </td>";
                echo "<td>
                    <form action='index.php' method='post'>
                        <input type='hidden' name='delete-task-id' value='{$row["id"]}'>
                        <button type='submit' name='delete-task'>Delete</button>
                    </form>
                </td>";
                echo "</tr>";
            }
            echo "</table><br>";
        } else {
            echo "<p>No tasks found.</p>";
        }
    }

    // Fetch tasks
    fetch_tasks($con);

    // Add task logic
    if (isset($_POST["add-task"])) {
        if (!empty($_POST["new-task"])) {
            $new_task = mysqli_real_escape_string($con, $_POST["new-task"]);
            $user_id = $_SESSION["id"];

            // Check for duplicate tasks
            $check_task_query = "SELECT * FROM tasks WHERE user_id = '$user_id' AND title = '$new_task'";
            $result = mysqli_query($con, $check_task_query);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<p style='color: red;'>Task already exists.</p>";
            } else {
                $add_task_query = "INSERT INTO tasks (user_id, title) VALUES ('$user_id', '$new_task')";
                if (mysqli_query($con, $add_task_query)) {
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<p style='color: red;'>Error adding task.</p>";
                }
            }
        }
    }

    // Delete task logic
    if (isset($_POST["delete-task"])) {
        $task_id = $_POST["delete-task-id"];
        $user_id = $_SESSION["id"];
        $delete_task_query = "DELETE FROM tasks WHERE id = '$task_id' AND user_id = '$user_id'";
        if (mysqli_query($con, $delete_task_query)) {
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color: red;'>Error deleting task.</p>";
        }
    }

    // Update task status logic
    if (isset($_POST["set-status"])) {
        $task_id = $_POST["task_id"];
        $new_status = $_POST["set-status"];
        $user_id = $_SESSION["id"];
        $update_status_query = "UPDATE tasks SET status = '$new_status' WHERE id = '$task_id' AND user_id = '$user_id'";
        if (mysqli_query($con, $update_status_query)) {
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color: red;'>Error updating task status.</p>";
        }
    }

    if (isset($_GET["logout"])) {
        header("Location: login.php");
        mysqli_close($con);
        session_destroy();
        exit();
    }
    ?>
</body>

</html>