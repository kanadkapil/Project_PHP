<?php
include('db.php');
include('style.php');

// session
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch existing category details
    $sql = "SELECT * FROM category WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);  // Bind the ID as integer
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    $stmt->close();
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
    }

    // If there are no errors, proceed with database update
    if (empty($errors)) {
        // If new image is uploaded, update image
        if (isset($image_name)) {
            move_uploaded_file($image_tmp, $image_path);
            $sql = "UPDATE category SET title = ?, description = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $title, $description, $image_name, $id);  // Bind parameters as strings and integer
        } else {
            $sql = "UPDATE category SET title = ?, description = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $title, $description, $id);  // Bind parameters as strings and integer
        }

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Category updated successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        // Close the statement
        $stmt->close();
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
    <title>Update Category</title>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold mb-6 text-center text-gray-800">Edit Category</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="title" class="block text-lg font-medium text-gray-700">Category Title</label>
                    <input type="text" id="title" name="title" class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($category['title']); ?>" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-lg font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required><?php echo htmlspecialchars($category['description']); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-lg font-medium text-gray-700">Upload New Image (optional)</label>
                    <input type="file" id="image" name="image" class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <button type="submit" class="w-full py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
