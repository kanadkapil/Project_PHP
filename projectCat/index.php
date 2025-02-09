<?php
include('db.php');
include('style.php');

// Session
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Fetch categories
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

    <!-- navbar  -->

    <nav class="bg-gray-800 p-4">
        <div class="max-w-screen-xl mx-auto flex items-center justify-between">
            <!-- Logo or Brand Name -->
            <a href="#" class="text-white text-lg font-semibold">MySite</a>

            <!-- Navbar Links (can add more links here) -->
            <div class="flex space-x-4">
                <a href="index" class="text-white hover:bg-gray-700 px-3 py-2 rounded-md">Home</a>
                <a href="about" class="text-white hover:bg-gray-700 px-3 py-2 rounded-md">About</a>
                <a href="services" class="text-white hover:bg-gray-700 px-3 py-2 rounded-md">Services</a>
                <a href="contact" class="text-white hover:bg-gray-700 px-3 py-2 rounded-md">Contact</a>
            </div>


            <!-- Logout Button -->
            <div class="text-right">
                <a href="logout.php" class="btn btn-secondary bg-gray-600 text-white py-2 px-6 rounded-md hover:bg-gray-700">Logout</a>
            </div>
        </div>
    </nav>


    <!-- Container -->
    <div class="container mx-auto p-4">
        <!-- Header Section -->
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold mb-6 text-center text-gray-800">All Categories</h2>

            <!-- Button to Create a New Category -->
            <div class="mb-6 text-center">
                <a href="create_category.php" class="btn btn-primary bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700">Create New Category</a>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <div class="card bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transform hover:scale-105 transition duration-300">
                            <!-- Category Image -->
                            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="w-full h-48 object-cover">

                            <!-- Category Details -->
                            <div class="p-4">
                                <h3 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($row['title']); ?></h3>
                                <p class="mt-2 text-gray-600 text-sm"><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?></p>

                                <!-- Action Buttons -->
                                <div class="mt-4 flex justify-between">
                                    <!-- here goes a different button im saving it in a text file  -->
                                    <a href="view_category/<?php echo $row['id']; ?>" class="btn btn-info bg-green-600 text-white py-1 px-3 rounded-md hover:bg-green-700 text-sm">View</a>
                                    <a href="update_category.php?id=<?php echo $row['id']; ?>" class="btn btn-primary bg-blue-600 text-white py-1 px-3 rounded-md hover:bg-blue-700 text-sm">Edit</a>
                                    <a href="delete_category.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');" class="btn btn-danger bg-red-600 text-white py-1 px-3 rounded-md hover:bg-red-700 text-sm">Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="col-span-full text-center text-gray-600">
                        <p>No categories found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>




</body>

</html>