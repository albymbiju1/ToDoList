<?php
// Start the session to access session variables
session_start();

// Ensure a valid user session exists
if (!isset($_SESSION['user_id'])) {
    echo "Please log in first!";
    exit;
}

// Establish database connection
$conn = new mysqli("localhost", "root", "", "todolist");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // Get user_id from session

// Handle task creation (Create operation)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_task'])) {
    $task_name = $_POST["task_name"];
    $task_description = $_POST["task_description"];
    $task_status = "pending"; // Default status is pending
    
    // Insert the task into the database
    $sql = "INSERT INTO tasks (user_id, task_name, description, status) VALUES ('$user_id', '$task_name', '$task_description', '$task_status')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Task created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle task editing (Update operation)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_task'])) {
    $task_id = $_POST["task_id"];
    $task_name = $_POST["task_name"];
    $task_description = $_POST["task_description"];
    $task_status = $_POST["task_status"]; // Task status (pending or completed)
    
    // Update task details in the database
    $sql = "UPDATE tasks SET task_name='$task_name', description='$task_description', status='$task_status' WHERE id='$task_id' AND user_id='$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Task updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle task deletion (Delete operation)
if (isset($_GET['delete_task_id'])) {
    $task_id = $_GET['delete_task_id'];
    
    // Delete task from the database
    $sql = "DELETE FROM tasks WHERE id='$task_id' AND user_id='$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Task deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch tasks for the logged-in user
$sql = "SELECT * FROM tasks WHERE user_id = '$user_id'";
$result = $conn->query($sql);

// Show task creation form
echo '<h3>Create New Task</h3>';
echo '<form method="POST">
        <input type="text" name="task_name" placeholder="Task Name" required>
        <textarea name="task_description" placeholder="Task Description" required></textarea>
        <button type="submit" name="create_task">Create Task</button>
      </form>';

// Show tasks if available
echo "<h3>Your Tasks</h3>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . $row["task_name"] . "</h3>";
        echo "<p>" . $row["description"] . "</p>";
        echo "<p>Status: " . $row["status"] . "</p>";
        
        // Edit link
        echo "<a href='?edit_task_id=" . $row["id"] . "'>Edit</a> | ";
        
        // Delete link
        echo "<a href='?delete_task_id=" . $row["id"] . "'>Delete</a>";
        echo "</div>";
        
        // If editing a task, show the edit form
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






          



