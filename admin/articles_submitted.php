<?php require_once 'classloader.php'; ?>

<?php
if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if (!$userObj->isAdmin()) {
    header("Location: ../writer/index.php");
    exit;
}

// Fetch all categories for the edit form dropdowns
$categories = $articleObj->getAllCategories();
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <title>Manage Submitted Articles</title>
</head>
<body class="bg-gray-100">
    <?php include 'includes/navbar.php'; ?>
    <div class="container mx-auto p-6">
        <div class="max-w-4xl mx-auto">

            <h1 class="text-4xl md:text-5xl font-bold text-center text-gray-800 mb-6">Manage Submitted Articles</h1>
            <p class="text-center text-gray-600 mb-10">Review articles submitted by writers. Click 'Edit' to modify content, or use the actions to approve, unpublish, or delete.</p>

            <?php 
            // FIXED: Call getArticles() to fetch ALL articles from ALL users.
            // This method joins the users table and provides the 'username'.
            $articles = $articleObj->getArticles(); 
            ?>
            
            <?php if (empty($articles)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                    There are no articles to display.
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article) : ?>
                    <div class="bg-white rounded-lg shadow-lg p-6 mt-6 articleCard">
                        <div class="article-content">
                            <!-- Status Indicator -->
                            <div class="mb-2">
                                <span class="text-sm font-medium mr-2 px-2.5 py-0.5 rounded <?php echo $article['is_active'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                    <?php echo $article['is_active'] ? 'Published' : 'Pending Review'; ?>
                                </span>
                                <span class="text-sm font-medium mr-2 px-2.5 py-0.5 rounded bg-blue-100 text-blue-800">
                                    <?php echo htmlspecialchars($article['category_name'] ?? 'Uncategorized'); ?>
                                </span>
                            </div>

                            <h2 class="text-3xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($article['title']); ?></h2>
                            <p class="text-gray-600">
                                <!-- This will now work correctly -->
                                <strong>By: <?php echo htmlspecialchars($article['username']); ?></strong> -
                                <span class="text-gray-500"><?php echo date("F j, Y, g:i a", strtotime($article['created_at'])); ?></span>
                            </p>
                            
                            <?php if (!empty($article['image_path'])) : ?>
                                <img src="../<?php echo htmlspecialchars($article['image_path']); ?>" alt="Article Image" class="rounded-lg my-4 w-full h-auto object-cover max-h-96">
                            <?php endif; ?>

                            <p class="text-gray-700 mt-4 text-lg whitespace-pre-wrap"><?php echo htmlspecialchars($article['content']); ?></p>

                            <hr class="my-4">
                            
                            <!-- ACTION BUTTONS FOR ADMIN -->
                            <div class="flex justify-end items-center space-x-3">
                                <button class="toggle-edit-btn bg-gray-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-600 transition-colors">Edit</button>
                                <?php if ($article['is_active'] == 0): ?>
                                    <button class="approve-btn bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition-colors" data-id="<?php echo $article['article_id']; ?>">Approve</button>
                                <?php else: ?>
                                    <button class="unpublish-btn bg-yellow-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-yellow-600 transition-colors" data-id="<?php echo $article['article_id']; ?>">Unpublish</button>
                                <?php endif; ?>
                                <button class="delete-btn bg-red-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-700 transition-colors" data-id="<?php echo $article['article_id']; ?>">Delete</button>
                            </div>
                        </div>

                        <!-- Hidden Edit Form -->
                        <div class="updateArticleForm hidden mt-6">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">Edit Article</h3>
                            <form action="core/handleForms.php" method="POST">
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Title</label>
                                    <input type="text" class="w-full px-4 py-2 border rounded-lg" name="title" value="<?php echo htmlspecialchars($article['title']); ?>">
                                </div>
                                
                                <!-- FIXED: Added Category Dropdown to Edit Form -->
                                <div class="mb-4">
                                    <label for="category_id" class="block text-gray-700 font-bold mb-2">Category</label>
                                    <select name="category_id" class="w-full px-4 py-2 border rounded-lg bg-white" required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['category_id']; ?>" <?php echo ($category['category_id'] == $article['category_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['category_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Content</label>
                                    <textarea name="description" rows="8" class="w-full px-4 py-2 border rounded-lg"><?php echo htmlspecialchars($article['content']); ?></textarea>
                                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors" name="editArticleBtn">Save Changes</button>
                                    <button type="button" class="toggle-edit-btn w-full bg-gray-200 text-gray-800 font-bold py-3 px-4 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
      $(document).ready(function() {
        // Toggle visibility of the edit form on button click
        $('.articleCard').on('click', '.toggle-edit-btn', function () {
            var card = $(this).closest('.articleCard');
            card.find('.updateArticleForm').toggleClass('hidden');
            card.find('.article-content').toggleClass('hidden');
        });

        // Function to handle article visibility changes (Approve/Unpublish)
        function updateVisibility(button, articleId, newStatus) {
            $.ajax({
                type: "POST",
                url: "core/handleForms.php",
                data: {
                    article_id: articleId,
                    status: newStatus,
                    updateArticleVisibility: 1
                },
                success: function(data) {
                    if (data) {
                        location.reload();
                    } else {
                        alert("Action failed. Please try again.");
                    }
                }
            });
        }

        // Event handler for Approve button
        $('.approve-btn').on('click', function() {
            updateVisibility($(this), $(this).data('id'), 1); // 1 for active/published
        });

        // Event handler for Unpublish button
        $('.unpublish-btn').on('click', function() {
            updateVisibility($(this), $(this).data('id'), 0); // 0 for inactive/pending
        });

        // AJAX for Deleting an Article
        $('.delete-btn').on('click', function () {
          var articleId = $(this).data('id');
          if (confirm("Are you sure you want to permanently delete this article?")) {
            $.ajax({
              type:"POST",
              url: "core/handleForms.php",
              data:{
                article_id: articleId,
                deleteArticleBtn: 1
              },
              success: function (data) {
                if (data) {
                  location.reload();
                } else {
                  alert("Deletion failed. Please try again.");
                }
              }
            })
          }
        });
      });
    </script>
</body>
</html>