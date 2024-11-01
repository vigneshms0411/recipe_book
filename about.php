<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Book</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f8f9fa; 
            color: #343a40; 
            margin: 0; 
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .recipe-container {
            max-width: 800px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #495057;
            margin-bottom: 10px;
        }
        .navbar {
            display: flex;
            justify-content: center;
            background-color: #343a40;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .navbar a {
            flex: 1;
            padding: 14px 20px;
            color: #f8f9fa;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
        }
        .navbar a:hover {
            background-color: #495057;
        }
        .navbar a.active {
            background-color: #007bff;
            color: #fff;
        }
        .recipe {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .recipe h2 {
            margin: 0;
            color: #495057;
            font-size: 1.5em;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 8px;
        }
        .recipe p {
            margin: 10px 0;
            color: #6c757d;
            line-height: 1.6;
        }
        .recipe h3 {
            font-size: 1.2em;
            margin-top: 15px;
            color: #495057;
        }
        ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        ol li {
            color: #6c757d;
            margin: 5px 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <!-- Main Content Container -->
    <div class="recipe-container">
        <h1>Recipe Book</h1>
		<title>About Us - Recipe Book</title>
        
        <!-- Navigation Bar -->
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="about.php"class="active">About</a>
            <a href="admin_login.php">Admin Login</a>
    </div>
	<div class="about-container">
        <h1>About Us</h1>
        <p>Welcome to the Recipe Book! Our mission is to share delicious recipes from around the world, making cooking easy and enjoyable for everyone.</p>
        <p>Whether you're a beginner or an experienced chef, our curated collection of recipes has something for everyone. From appetizers to desserts, we strive to provide clear instructions and ingredient lists to help you create mouthwatering dishes.</p>
        <p>Join us on this culinary journey, and let's make cooking fun!</p>
    </div>

        
	<footer>
        <div class="footer-content">
            <p>&copy; 2024 Recipe Book. All rights reserved.</p>
            <div class="social-media">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">Instagram</a>
            </div>
            <div class="contact-info">
                <p>Contact us at: <a href="mailto:info@recipebook.com">info@recipebook.com</a></p>
            </div>
        </div>
    </footer>
	</div>
</body>
</html>
