<?php
session_start(); // Start the session to store user info

// Database connection
$conn = new mysqli("localhost", "root", "", "todolist");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if the user exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the hashed password
        if (password_verify($password, $row['password'])) {
            // Set session for user_id
            $_SESSION['user_id'] = $row['id']; // Store user ID in session

            // Redirect to task manager page
            header("Location: task_manager.php");
            exit();
        } else {
            echo "Invalid email or password!";
        }
    } else {
        echo "Invalid email or password!";
    }
}

$conn->close();
?>

<!-- HTML form for login -->
<form method="POST">
    <input type="email" name="email" placeholder="Enter your email" required>
    <input type="password" name="password" placeholder="Enter your password" required>
    <button type="submit">Login</button>
</form>





