<?php
session_start(); // Start session at the beginning
require 'vendor/autoload.php'; // Load MongoDB library

// Enable error reporting for troubleshooting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to MongoDB
try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->admin->login;
} catch (Exception $e) {
    echo '<p class="error">Could not connect to the database: ' . $e->getMessage() . '</p>';
    exit();
}

// Check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    try {
        // Find admin by username
        $admin = $collection->findOne(['username' => $username]);

        // Verify password and log in
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;

            // Redirect to Admin Panel
            echo "Login successful! Redirecting...";
            header("Location: Admin_panel.php");
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } catch (Exception $e) {
        $error_message = "Error logging in: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 35px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            text-align: center;
            color: #495057;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #495057;
            font-weight: bold;
            text-align: left;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            font-size: 0.9em;
            margin-top: 10px;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>

        <?php if (!empty($error_message)) : ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="admin_login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
