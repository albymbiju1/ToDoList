<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    echo "Please log in first!";
    exit;
}


$conn = new mysqli("localhost", "root", "", "todolist");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_task'])) {
    $task_name = $_POST["task_name"];
    $task_description = $_POST["task_description"];
    $task_status = "pending";
    
    
    $sql = "INSERT INTO tasks (user_id, task_name, description, status) VALUES ('$user_id', '$task_name', '$task_description', '$task_status')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Task created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_task'])) {
    $task_id = $_POST["task_id"];
    $task_name = $_POST["task_name"];
    $task_description = $_POST["task_description"];
    $task_status = $_POST["task_status"]; 

    
    $sql = "UPDATE tasks SET task_name='$task_name', description='$task_description', status='$task_status' WHERE id='$task_id' AND user_id='$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Task updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


if (isset($_GET['delete_task_id'])) {
    $task_id = $_GET['delete_task_id'];
    
    
    $sql = "DELETE FROM tasks WHERE id='$task_id' AND user_id='$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Task deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM tasks WHERE user_id = '$user_id'";
$result = $conn->query($sql);


echo '<h3>Create New Task</h3>';
echo '<form method="POST">
        <input type="text" name="task_name" placeholder="Task Name" required>
        <textarea name="task_description" placeholder="Task Description" required></textarea>
        <button type="submit" name="create_task">Create Task</button>
      </form>';

echo "<h3>Your Tasks</h3>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . $row["task_name"] . "</h3>";
        echo "<p>" . $row["description"] . "</p>";
        echo "<p>Status: " . $row["status"] . "</p>";
        
        echo "<a href='?edit_task_id=" . $row["id"] . "'>Edit</a> | ";
        
        echo "<a href='?delete_task_id=" . $row["id"] . "'>Delete</a>";
        echo "</div>";
        
       
        if (isset($_GET['edit_task_id']) && $_GET['edit_task_id'] == $row['id']) {
            ?>
            <h3>Edit Task</h3>
            <form method="POST">
                <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                <input type="text" name="task_name" value="<?php echo $row['task_name']; ?>" required>
                <textarea name="task_description" required><?php echo $row['description']; ?></textarea>
                <select name="task_status" required>
                    <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="completed" <?php echo $row['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
                <button type="submit" name="update_task">Update Task</button>
            </form>
            <?php
        }
    }
} else {
    echo "No tasks available. Create one now!";
}

$conn->close();
?>






          



