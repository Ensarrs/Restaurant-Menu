<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_FILES['image'];

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($image['type'], $allowedTypes)) {
        die("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
    }
    $targetDir = "menu-uploads/";
    $imgPath = $targetDir . basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $imgPath)) {
        die("Failed to upload image.");
    }
    $sql = "INSERT INTO menu_items (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("ssdss", $name, $description, $price, $category, $imgPath);
    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }
    $stmt->close();
    echo "Menu item added successfully!";
}
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $sqlDelete = "DELETE FROM menu_items WHERE id = $deleteId";
    if ($conn->query($sqlDelete) === TRUE) {
        echo "<script>alert('Deleted successfully');</script>";
    } else {
        echo "<script>alert('Wrong');</script>" . $conn->error;
    }
};

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Add a New Menu Item</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Image</label>
                <input type="file" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="image" id="image" accept="image/*" required>
                <small class="text-gray-500 dark:text-gray-400">Upload an image file (JPEG, PNG, GIF).</small>
            </div>
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Name</label>
                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="name" id="name" placeholder="Enter item name" required>
            </div>
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Description</label>
                <textarea class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="description" id="description" rows="4" placeholder="Enter item description" required></textarea>
            </div>
            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                <input type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="price" id="price" placeholder="Enter price" step="0.01" required>
            </div>
            <div class="mb-6">
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="category" id="category" placeholder="Enter category (e.g. Appetizer, Main, Dessert)" required>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Add Menu Item</button>
        </form>
    </div>

    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Latest Products</h2>
        <?php
        include 'config.php';
        $sql = "SELECT * FROM menu_items";
        $result = $conn->query($sql);

        if($result->num_rows>0){
            echo "<div class='grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6'>";
            while($row=$result->fetch_assoc()){
                echo "<div class='max-w-sm bg-white border border-gray-200 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700'>";
                echo "<img class='rounded-t-lg' src='" . $row['image'] . "' alt='" . $row['name'] . "' style='width: 100%; height: 200px; object-fit: cover;' />";
                echo "<div class='p-5'>";
                echo "<h5 class='mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white'>" . $row['name'] . "</h5>";
                echo "<p class='mb-3 font-normal text-gray-700 dark:text-gray-400'>" . $row['description'] . "</p>";
                echo "<div class='flex justify-between mt-4'>";
                echo "<a href='edit_item.php?id=" . $row['id'] . "' class='text-blue-500 hover:text-blue-700'>Edit</a>";
                echo "<a href='?delete=" . $row['id'] . "' class='text-red-500 hover:text-red-700' onclick='return confirm('A je i sigiur per te fshir expensin');'>Delete</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
