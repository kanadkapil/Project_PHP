<?php
include('db.php');
include('style.php');

// session
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input fields
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $errors = [];

    // Check if title and description are empty
    if (empty($title)) {
        $errors[] = "Title is required.";
    } else {
        $title = filter_var($title, FILTER_SANITIZE_STRING);
    }

    if (empty($description)) {
        $errors[] = "Description is required.";
    } else {
        $description = filter_var($description, FILTER_SANITIZE_STRING);
    }

    // Check if file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // Check image file size (limit to 500 KB)
        if ($_FILES['image']['size'] > 500 * 1024) { // 500 KB in bytes
            $errors[] = "Image size should not exceed 500 KB.";
        }

        if (!in_array($image_ext, $allowed_extensions)) {
            $errors[] = "Invalid image format. Allowed formats: jpg, jpeg, png, gif.";
        }

        // Sanitize image name (prevent directory traversal)
        $image_name = basename($image_name);
        $image_path = "uploads/" . $image_name;
    } else {
        $errors[] = "Image is required.";
    }

    // If there are no errors, proceed with database insertion
    if (empty($errors)) {
        // Move uploaded file to the "uploads" folder
        move_uploaded_file($image_tmp, $image_path);

        // Prepare the SQL query using placeholders
        $sql = "INSERT INTO category (title, description, image) VALUES (?, ?, ?)";

        // Prepare the statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters: "sss" means 3 strings
            $stmt->bind_param("sss", $title, $description, $image_name);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<script>alert('New category created successfully!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
        }
    } else {
        // Display errors
        echo "<script>alert('" . implode("\n", $errors) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category</title>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold mb-6 text-center text-gray-800">Create New Category</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="title" class="block text-lg font-medium text-gray-700">Category Title</label>
                    <input type="text" id="title" name="title" class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Category Title" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-lg font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Category Description" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-lg font-medium text-gray-700">Upload Image</label>
                    <input type="file" id="image" name="image" class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <button type="submit" class="w-full py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
