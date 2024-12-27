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
    $task_name = $conn->real_escape_string($_POST['task_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO tasks (user_id, task_name, description, status) 
            VALUES ('$user_id', '$task_name', '$description', 'pending')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Task created successfully!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
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
    <title>Task Manager</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #4caf50, #2e7d32); /* Matching the theme of login */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .container {
            background-color: #ffffff;
            width: 500px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            color: #333;
            text-align: center;
        }

        h1 {
            color: #2e7d32;
            font-size: 30px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input, textarea, select, button {
            width: 80%;
            margin: 10px 0;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #4caf50;
            outline: none;
        }

        button {
            width: 80%;
            padding: 12px;
            background-color: #4caf50;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s;
        }

        button:hover {
            background-color: #2e7d32;
        }

        .tasks {
            margin-top: 20px;
            width: 80%;
            margin-left: 10%;
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

        .task-actions {
            display: flex;
            flex-direction: column; /* Align buttons vertically */
            width: 100%;
        }

        .task-actions form {
            width: 100%; /* Ensure the update form takes full width */
        }

        .task-actions button {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            margin-top: 10px; /* Space between buttons */
        }

        .task-actions .update-btn {
            background-color: #007bff;
        }

        .task-actions .update-btn:hover {
            background-color: #0056b3;
        }

        .task-actions .delete-btn {
            background-color: #dc3545;
        }

        .task-actions .delete-btn:hover {
            background-color: #c82333;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            font-size: 16px;
            text-decoration: none;
            color: #4caf50;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Success and error message styles */
        .success, .error {
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .success {
            background-color: #4caf50;
            color: white;
        }

        .error {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Task Manager</h1>

        <?php if (isset($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="task_name" placeholder="Task Name" required>
            <textarea name="description" placeholder="Task Description" required></textarea>
            <button type="submit" name="create_task">Create Task</button>
        </form>

        <div class="tasks">
            <h2>Your Tasks</h2>
            <?php if (isset($result) && $result->num_rows > 0): ?>
                <?php while ($task = $result->fetch_assoc()): ?>
                    <div class="task">
                        <h3><?php echo htmlspecialchars($task['task_name']); ?></h3>
                        <p><?php echo htmlspecialchars($task['description']); ?></p>
                        <p>Status: <?php echo htmlspecialchars($task['status']); ?></p>
                        <div class="task-actions">
                            <form method="POST">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <select name="status">
                                    <option value="pending" <?php echo $task['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="completed" <?php echo $task['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                </select>
                                <button type="submit" name="update_task" class="update-btn">Update</button>
                            </form>
                            <a href="?delete_task=<?php echo $task['id']; ?>" style="text-decoration: none;">
                                <button class="delete-btn">Delete</button>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No tasks found. Add a new task to get started!</p>
            <?php endif; ?>
        </div>

        <a href="login.php" class="back-link">Logout</a>

    </div>
</body>
</html>
