<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "todolist");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_task'])) {
    $user_id = $_SESSION['user_id'];
    $task_name = $_POST["task_name"];
    $description = $_POST["description"];
    $sql = "INSERT INTO tasks (user_id, task_name, description) VALUES ('$user_id', '$task_name', '$description')";
    $conn->query($sql);
}

// Update Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_task'])) {
    $task_id = $_POST["task_id"];
    $status = $_POST["status"];
    $sql = "UPDATE tasks SET status='$status' WHERE id='$task_id'";
    $conn->query($sql);
}

// Delete Task
if (isset($_GET['delete_task'])) {
    $task_id = $_GET['delete_task'];
    $sql = "DELETE FROM tasks WHERE id='$task_id'";
    $conn->query($sql);
}

// Fetch Tasks
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tasks WHERE user_id='$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To DO List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        input, textarea, select, button {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #4cae4c;
        }

        .tasks {
            margin-top: 20px;
        }

        .task {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .task h3 {
            margin: 0 0 10px;
            color: #555;
        }

        .task p {
            margin: 0 0 10px;
            color: #777;
        }

        .task-actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }

        .task-actions a:hover {
            text-decoration: underline;
        }

        .task-status {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Task Manager</h1>

        <form method="POST">
            <input type="text" name="task_name" placeholder="Task Name" required>
            <textarea name="description" placeholder="Task Description" required></textarea>
            <button type="submit" name="create_task">Create Task</button>
        </form>

        <div class="tasks">
            <h2>Your Tasks</h2>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($task = $result->fetch_assoc()): ?>
                    <div class="task">
                        <h3><?php echo htmlspecialchars($task['task_name']); ?></h3>
                        <p><?php echo htmlspecialchars($task['description']); ?></p>
                        <p>Status: <?php echo htmlspecialchars($task['status']); ?></p>
                        <div class="task-actions">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <select name="status">
                                    <option value="pending" <?php echo $task['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="completed" <?php echo $task['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                </select>
                                <button type="submit" name="update_task">Update</button>
                            </form>
                            <a href="?delete_task=<?php echo $task['id']; ?>">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No tasks found. Add a new task to get started!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
