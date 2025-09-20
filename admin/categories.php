<?php
require_once './classloader.php';

// Redirect if not an admin
if (!$userObj->isLoggedIn() || !$userObj->isAdmin()) {
    header("Location: ../login.php");
    exit;
}

// Assume you have a getAllCategories method in a class
$categories = $articleObj->getAllCategories();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin - Manage Categories</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include 'includes/navbar.php'; ?>
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Manage Categories</h1>

        <!-- Form to Add New Category -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl mb-4">Add New Category</h2>
            <form action="core/handleForms.php" method="POST">
                <div class="flex items-center">
                    <input type="text" name="category_name" placeholder="e.g., News Reports" class="w-full px-4 py-2 border rounded-l-lg focus:outline-none" required>
                    <button type="submit" name="addCategoryBtn" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-r-lg hover:bg-blue-700">Add</button>
                </div>
            </form>
        </div>

        <!-- List of Existing Categories -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl mb-4">Existing Categories</h2>
            <ul class="space-y-2">
                <?php foreach ($categories as $category) : ?>
                    <li class="p-2 border rounded-md"><?php echo htmlspecialchars($category['category_name']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>