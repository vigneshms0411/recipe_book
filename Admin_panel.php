<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

require 'vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->recipe_book->recipe;
} catch (Exception $e) {
    die("Error connecting to the database: " . $e->getMessage());
}

// Handle form submissions for adding, updating, and deleting recipes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_recipe'])) {
        // Adding a new recipe
        $title = htmlspecialchars($_POST['title']);
        $description = htmlspecialchars($_POST['description']);
        $ingredients = array_map('htmlspecialchars', explode("\n", $_POST['ingredients'])); // Ensure ingredients are sanitized
        $steps = array_map('htmlspecialchars', explode("\n", $_POST['steps']));

        $newRecipe = [
            'title' => $title,
            'description' => $description,
            'ingredients' => $ingredients,
            'steps' => $steps
        ];

        $collection->insertOne($newRecipe);
    } elseif (isset($_POST['edit_recipe'])) {
        // Editing an existing recipe
        $id = new MongoDB\BSON\ObjectId($_POST['id']);
        $title = htmlspecialchars($_POST['title']);
        $description = htmlspecialchars($_POST['description']);
        $ingredients = array_map('htmlspecialchars', explode("\n", $_POST['ingredients'])); // Ensure ingredients are sanitized
        $steps = array_map('htmlspecialchars', explode("\n", $_POST['steps']));

        $collection->updateOne(
            ['_id' => $id],
            ['$set' => ['title' => $title, 'description' => $description, 'ingredients' => $ingredients, 'steps' => $steps]]
        );
    } elseif (isset($_POST['delete_recipe'])) {
        // Deleting a recipe
        $id = new MongoDB\BSON\ObjectId($_POST['id']);
        $collection->deleteOne(['_id' => $id]);
    }
}

// Fetch all recipes
$recipes = $collection->find();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Recipe Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .recipe-form, .recipe {
            background-color: #fff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .recipe-form h2, .recipe h2 {
            color: #007bff;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .edit, .delete {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            margin: 5px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <h1>Admin Panel - Recipe Book</h1>

    <!-- Add Recipe Form -->
    <div class="recipe-form">
        <h2>Add New Recipe</h2>
        <form action="admin_panel.php" method="POST">
            <label for="title">Recipe Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="3" required></textarea>

            <label for="ingredients">Ingredients (One ingredient per line):</label>
            <textarea id="ingredients" name="ingredients" rows="5" required></textarea>

            <label for="steps">Steps (One step per line):</label>
            <textarea id="steps" name="steps" rows="5" required></textarea>

            <input type="submit" name="add_recipe" value="Add Recipe">
        </form>
    </div>

    <!-- Display Existing Recipes -->
    <h2>Existing Recipes</h2>
    <?php foreach ($recipes as $recipe): ?>
        <div class="recipe">
            <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($recipe['description']); ?></p>

            <!-- Display Ingredients -->
            <?php if (isset($recipe['ingredients'])): ?>
                <h4>Ingredients:</h4>
                <ul>
                    <?php 
                    $ingredients = $recipe['ingredients']->getArrayCopy(); // Convert BSONArray to PHP array
                    foreach ($ingredients as $ingredient): ?>
                        <li><?php echo htmlspecialchars($ingredient); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No ingredients available.</p>
            <?php endif; ?>

            <!-- Display Steps -->
            <?php if (isset($recipe['steps'])): ?>
                <h4>Steps:</h4>
                <ol>
                    <?php 
                    $steps = $recipe['steps']->getArrayCopy(); // Convert BSONArray to PHP array
                    foreach ($steps as $step): ?>
                        <li><?php echo htmlspecialchars($step); ?></li>
                    <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <p>No steps available.</p>
            <?php endif; ?>

            <!-- Edit Recipe Form -->
            <form action="admin_panel.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $recipe['_id']; ?>">
                <label for="title">Title:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>
                
                <label for="description">Description:</label>
                <textarea name="description" rows="3" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>

                <label for="ingredients">Ingredients:</label>
                <textarea name="ingredients" rows="5" required><?php echo implode("\n", $ingredients); ?></textarea>

                <label for="steps">Steps:</label>
                <textarea name="steps" rows="5" required><?php echo implode("\n", $steps); ?></textarea>

                <input type="submit" name="edit_recipe" class="edit" value="Edit Recipe">
            </form>

            <!-- Delete Recipe Button -->
            <form action="admin_panel.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $recipe['_id']; ?>">
                <input type="submit" name="delete_recipe" class="delete" value="Delete Recipe">
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
