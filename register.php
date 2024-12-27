<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $conn = new mysqli("localhost", "root", "", "todolist");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "Email already exists. Please log in.";
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4caf50, #81c784);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 350px;
            text-align: center;
        }
        .container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #388e3c;
        }
        .container form {
            display: flex;
            flex-direction: column;
        }
        .container input {
            margin-bottom: 15px;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: border-color 0.3s;
        }
        .container input:focus {
            border-color: #4caf50;
            outline: none;
        }
        .container button {
            background-color: #4caf50;
            color: #fff;
            font-size: 16px;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        .container button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }
        .container .back-button {
            background-color: #388e3c;
            margin-top: 10px;
        }
        .container .back-button:hover {
            background-color: #2e7d32;
        }
        .container p {
            margin-top: 15px;
            font-size: 14px;
        }
        .container a {
            color: #4caf50;
            font-weight: bold;
            text-decoration: none;
        }
        .container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Your Account</h2>
        <form method="POST" action="register.php">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <button type="submit" name="register">Register</button>
        </form>
        <p>Already registered? <a href="login.php">Log in here</a></p>
    </div>
</body>
</html>
