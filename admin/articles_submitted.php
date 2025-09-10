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

    <!-- Google Fonts -->
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

            <!-- ================================= -->
            <!-- ===== NEW NOTIFICATION AREA ===== -->
            <!-- ================================= -->
            <div id="notification-container" class="mb-8">
                <?php
                // Assumes you have $notificationObj available from your classloader.php
                if (isset($notificationObj)) {
                    $notifications = $notificationObj->getUnreadNotifications($_SESSION['user_id']);
                    foreach ($notifications as $notification) : ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md mb-4 flex justify-between items-center">
                            <p><?php echo htmlspecialchars($notification['message']); ?></p>
                            <button class="dismiss-notification text-red-500 hover:text-red-800 font-bold text-2xl leading-none" data-id="<?php echo $notification['notification_id']; ?>">
                                &times;
                            </button>
                        </div>
                    <?php endforeach;
                }
                ?>
            </div>

            <h1 class="text-5xl text-center text-blue-800 mb-8">My Submitted Articles</h1>
            <p class="text-center text-gray-600 mb-12">Double-click on an article card to edit its content.</p>

            <?php $articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); ?>
            <?php foreach ($articles as $article) : ?>
                <div class="bg-white rounded-lg shadow-lg p-8 mt-8 articleCard">
                    <div class="article-content">
                        <h2 class="text-4xl text-pink-500 mb-2"><?php echo htmlspecialchars($article['title']); ?></h2>
                        <p class="text-gray-600">
                            <strong>By: <?php echo htmlspecialchars($article['username']); ?></strong> -
                            <span class="text-gray-500"><?php echo date("F j, Y, g:i a", strtotime($article['created_at'])); ?></span>
                        </p>
                        
                        <!-- IMAGE DISPLAY BLOCK -->
                        <?php if (!empty($article['image_path'])) : ?>
                            <img src="../<?php echo htmlspecialchars($article['image_path']); ?>" alt="Article Image" class="rounded-lg my-4 w-full h-auto object-cover max-h-96">
                        <?php endif; ?>

                        <p class="text-gray-800 mt-4 text-lg whitespace-pre-wrap"><?php echo htmlspecialchars($article['content']); ?></p>

                        <hr class="my-6">
                        
                        <form class="deleteArticleForm flex justify-end">
                            <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
                            <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-700 transition-colors">Delete</button>
                        </form>
                    </div>

                    <!-- Hidden Edit Form -->
                    <div class="updateArticleForm hidden mt-6">
                        <h3 class="text-3xl text-pink-500 mb-4">Edit Article</h3>
                        <form action="core/handleForms.php" method="POST">
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2">Title</label>
                                <input type="text" class="w-full px-4 py-2 border rounded-lg" name="title" value="<?php echo htmlspecialchars($article['title']); ?>">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2">Content</label>
                                <textarea name="description" rows="8" class="w-full px-4 py-2 border rounded-lg"><?php echo htmlspecialchars($article['content']); ?></textarea>
                                <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors" name="editArticleBtn">Save Changes</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- =================================================== -->
    <!-- ===== JAVASCRIPT FOR FORMS AND NOTIFICATIONS ===== -->
    <!-- =================================================== -->
    <script>
      $(document).ready(function() {
        // Toggle visibility of the edit form on double-click
        $('.articleCard').on('dblclick', function (event) {
          if (!$(event.target).closest('form, input, textarea, button, select').length) {
              $(this).find('.updateArticleForm').toggleClass('hidden');
              $(this).find('.article-content').toggleClass('hidden');
          }
        });

        // AJAX for Deleting an Article
        $('.deleteArticleForm').on('submit', function (event) {
          event.preventDefault();
          var formData = {
            article_id: $(this).find('.article_id').val(),
            deleteArticleBtn: 1
          }
          if (confirm("Are you sure you want to permanently delete this article?")) {
            $.ajax({
              type:"POST",
              url: "core/handleForms.php",
              data:formData,
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

        // AJAX for Dismissing a Notification
        $('.dismiss-notification').on('click', function() {
            var notificationId = $(this).data('id');
            var notificationElement = $(this).parent();
            $.ajax({
                type: 'POST',
                url: 'core/handleForms.php',
                data: {
                    notification_id: notificationId,
                    markNotificationAsRead: 1 // Trigger for the handler
                },
                success: function(response) {
                    notificationElement.fadeOut(300, function() { $(this).remove(); });
                },
                error: function() {
                    alert('Could not dismiss notification. Please try again.');
                }
            });
        });
      });
    </script>
</body>
</html>