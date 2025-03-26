<?php
include 'config.php';
session_start();

if(isset($_GET['id'])){
    $id = intval($_GET['id']);

    $sql = "SELECT * FROM menu_items  WHERE id=$id";
    $result = $conn->query($sql);

    if($result->num_rows == 1){
        $row=$result->fetch_assoc();
    } else {
        header("Location: admin_dashboard.php");
        exit();
    } 
}

if($_SERVER['REQUEST_METHOD']=="POST"){
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_FILES['image'];


 $sql = "UPDATE menu_items SET name='$name', description='$description', price='$price', category='$category', image='$image' WHERE id=$id";

if($conn->query($sql)===TRUE){
    
    header("Location: admin_dashboard.php");
    exit();
   } else {
    echo "<p> Error, diqka shkoi gabim..! " .  $conn->error .  "</p>";
   }
}
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
                <input type="file" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="image" id="image" accept="image/*"  required>
                <small class="text-gray-500 dark:text-gray-400">Upload an image file (JPEG, PNG, GIF).</small>
            </div>
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Name</label>
                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="name" id="name" placeholder="Enter item name" value="<?php echo $row['name'];?>" required>
            </div>
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Description</label>
                <textarea class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="description" id="description" rows="4" placeholder="Enter item description" value="<?php echo $row['description'];?>" required></textarea>
            </div>
            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                <input type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="price" id="price" placeholder="Enter price" step="0.01" value="<?php echo $row['price'];?>" required>
            </div>
            <div class="mb-6">
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="category" id="category" placeholder="Enter category (e.g. Appetizer, Main, Dessert)" value="<?php echo $row['category'];?>" required>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Add Menu Item</button>
        </form>
    </div>
</body>
</html>
