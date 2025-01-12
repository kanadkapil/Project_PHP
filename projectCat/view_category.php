<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include('db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch category details by ID
    $sql = "SELECT * FROM category WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $category = $result->fetch_assoc();
    } else {
        echo "Category not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold mb-6 text-center text-gray-800">
                <?php echo htmlspecialchars($category['title']); ?>
            </h2>
            <img src="uploads/<?php echo htmlspecialchars($category['image']); ?>" alt="<?php echo htmlspecialchars($category['title']); ?>" class="w-full h-64 object-cover rounded-md mb-4">
            <p class="text-gray-600 mb-6">
                <?php echo nl2br(htmlspecialchars($category['description'])); ?>
            </p>
            <div class="text-center">
                <a href="index.php" class="btn btn-secondary bg-gray-600 text-white py-2 px-6 rounded-md hover:bg-gray-700">Back to Categories</a>
            </div>
        </div>
    </div>
</body>

</html>