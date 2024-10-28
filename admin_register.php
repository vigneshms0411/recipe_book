<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
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
        .register-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center-align content */
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
            text-align: left; /* Align label text to the left */
        }
        input[type="text"], input[type="email"], input[type="password"] {
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
        .error, .success {
            font-size: 0.9em;
            margin-top: 10px;
            text-align: center;
        }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Admin Register</h2>

        <?php
        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        require 'vendor/autoload.php'; // Load MongoDB library

        // Connect to MongoDB and select the database and collection
        try {
            $client = new MongoDB\Client("mongodb://localhost:27017");
            $collection = $client->admin->login; // Set the database to 'admin' and collection to 'login'
        } catch (Exception $e) {
            echo '<p class="error">Could not connect to the database: ' . $e->getMessage() . '</p>';
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = htmlspecialchars($_POST['username']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                echo '<p class="error">Passwords do not match!</p>';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                try {
                    // Insert the new admin record
                    $result = $collection->insertOne([
                        'username' => $username,
                        'email' => $email,
                        'password' => $hashed_password
                    ]);
                    
                    // Check if the document was inserted
                    if ($result->getInsertedCount() === 1) {
                        echo '<p class="success">Admin registered successfully!</p>';
                    } else {
                        echo '<p class="error">Failed to register admin. No document inserted.</p>';
                    }
                } catch (MongoDB\Driver\Exception\Exception $e) {
                    // Catch MongoDB-specific exceptions
                    echo '<p class="error">MongoDB Error: ' . $e->getMessage() . '</p>';
                } catch (Exception $e) {
                    // Catch all other exceptions
                    echo '<p class="error">Error: ' . $e->getMessage() . '</p>';
                }
            }
        }
        ?>

        <form action="admin_register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
