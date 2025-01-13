<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "blog";

// Connect to database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $title = $conn->real_escape_string($_POST['title']);
                $description = $conn->real_escape_string($_POST['description']);
                $image = $conn->real_escape_string($_POST['image']);

                $sql = "INSERT INTO category (title, description, image) VALUES ('$title', '$description', '$image')";
                $conn->query($sql);
                break;

            case 'delete':
                $id = intval($_POST['id']);
                $sql = "DELETE FROM category WHERE id = $id";
                $conn->query($sql);
                break;

            case 'update':
                $id = intval($_POST['id']);
                $title = $conn->real_escape_string($_POST['title']);
                $description = $conn->real_escape_string($_POST['description']);
                $image = $conn->real_escape_string($_POST['image']);

                $sql = "UPDATE category SET title = '$title', description = '$description', image = '$image' WHERE id = $id";
                $conn->query($sql);
                break;
        }
    }
}

// Fetch categories
$sql = "SELECT * FROM category";
$result = $conn->query($sql);
$categories = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category CRUD</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>Category CRUD</h1>

<!-- Add Category Form -->
<form method="post">
    <h2>Add Category</h2>
    <input type="hidden" name="action" value="add">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" required><br>
    <label for="description">Description:</label><br>
    <textarea id="description" name="description" required></textarea><br>
    <label for="image">Image URL:</label><br>
    <input type="text" id="image" name="image" required><br><br>
    <button type="submit">Add Category</button>
</form>

<!-- Category Table -->
<h2>Categories</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
        <tr>
            <td><?= $category['id'] ?></td>
            <td><?= htmlspecialchars($category['title']) ?></td>
            <td><?= htmlspecialchars($category['description']) ?></td>
            <td><img src="<?= htmlspecialchars($category['image']) ?>" alt="<?= htmlspecialchars($category['title']) ?>" width="50"></td>
            <td>
                <!-- Update Form -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $category['id'] ?>">
                    <input type="text" name="title" value="<?= htmlspecialchars($category['title']) ?>" required>
                    <input type="text" name="description" value="<?= htmlspecialchars($category['description']) ?>" required>
                    <input type="text" name="image" value="<?= htmlspecialchars($category['image']) ?>" required>
                    <button type="submit">Update</button>
                </form>

                <!-- Delete Form -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $category['id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
