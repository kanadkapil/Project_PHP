<?php
include('db.php');
include('style.php');


$sql = "SELECT * FROM category";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Categories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/daisyui@1.19.1/dist/full.js"></script>
</head>

<body class="bg-gray-100">

    <div class="container mx-auto p-4">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold mb-6 text-center text-gray-800">All Categories</h2>

            <!-- Button to Create a New Category -->
            <div class="mb-6 text-center">
                <a href="create_category.php" class="btn btn-primary bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700">Create New Category</a>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='card bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transform hover:scale-105 transition duration-300'>
                                <img src='uploads/" . $row['image'] . "' alt='" . $row['title'] . "' class='w-full h-48 object-cover'>
                                <div class='p-4'>
                                    <h3 class='text-xl font-semibold text-gray-800'>" . $row['title'] . "</h3>
                                    <p class='mt-2 text-gray-600 text-sm'>" . substr($row['description'], 0, 100) . "...</p>
                                    <div class='mt-4'>
                                        <a href='update_category.php?id=" . $row['id'] . "' class='btn btn-primary bg-blue-600 text-white py-1 px-3 rounded-md hover:bg-blue-700 text-sm'>Edit</a>
                                        <a href='delete_category.php?id=" . $row['id'] . "' class='btn btn-danger bg-red-600 text-white py-1 px-3 rounded-md hover:bg-red-700 text-sm'>Delete</a>
                                    </div>
                                </div>
                            </div>";
                    }
                } else {
                    echo "<div class='col-span-full text-center text-gray-600'>
                            <p>No categories found.</p>
                          </div>";
                }
                ?>
            </div>
        </div>
    </div>

</body>

</html>
