<?php
require_once 'classloader.php';

// --- Security Checks ---
// 1. Must be logged in as a writer
if (!$userObj->isLoggedIn() || $userObj->isAdmin()) {
    header("Location: login.php");
    exit;
}

// 2. Article ID must be provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$article_id = $_GET['id'];
$article = $articleObj->getArticles($article_id);

// 3. Security validation:
// - The article must exist.
// - The logged-in user must be the author of the article.
// - The article must be marked as 'editable' by an admin.
if (!$article || $article['author_id'] != $_SESSION['user_id'] || $article['is_editable'] != 1) {
    // Redirect if any condition fails, preventing unauthorized access.
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Your standard head content with Tailwind, jQuery, and Fonts -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <style>
      body { font-family: 'Nunito', sans-serif; }
      h1, h2, h3, h4, h5, h6 { font-family: 'Luckiest Guy', cursive; }
    </style>
</head>
<body class="bg-gray-50">
    <?php include 'includes/navbar.php'; ?>

    <div class="container mx-auto p-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl text-center text-blue-800 mb-8">Edit Your Article</h1>
            <p class="text-center text-gray-600 mb-12">Your request was approved! Make your changes and re-submit.</p>
            
            <div class="bg-white rounded-lg shadow-lg p-8">
                <form action="core/handleForms.php" method="POST">
                    <!-- Hidden input to send the article ID with the form -->
                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                    
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
                        <input type="text" id="title" name="title" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($article['title']); ?>" required>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-gray-700 font-bold mb-2">Content</label>
                        <textarea id="description" name="description" rows="12" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required><?php echo htmlspecialchars($article['content']); ?></textarea>
                    </div>

                    <button type="submit" name="editArticleBtn" class="w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-700 transition-colors">
                        Save Changes and Re-Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>