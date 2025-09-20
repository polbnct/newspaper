<?php require_once 'classloader.php'; ?>

<?php
if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit; // Always exit after a header redirect
}

if ($userObj->isAdmin()) {
    header("Location: ../admin/index.php");
    exit; // Always exit after a header redirect
}

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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Nunito:wght@400;700&display=swap" rel="stylesheet">

    <style>
      body {
        font-family: 'Nunito', sans-serif;
      }
      h1, h2, h3, h4, h5, h6 {
        font-family: 'Luckiest Guy', cursive;
      }
    </style>
</head>
<body class="bg-gray-50">
    <?php include 'includes/navbar.php'; ?>

    <div class="container mx-auto p-8">
        <!-- Notification container -->
        <div id="notification-container" class="max-w-4xl mx-auto mb-8">
            <?php
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

        <h1 class="text-4xl md:text-5xl text-center text-blue-800 mb-4">
            Hello, <span class="text-pink-500"><?php echo htmlspecialchars($_SESSION['username']); ?></span>!
        </h1>
        <p class="text-center text-gray-600 text-xl mb-12">Ready to write your next masterpiece?</p>

        <div class="max-w-4xl mx-auto">
            <!-- Form to Add New Article (No changes here) -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
                <h2 class="text-3xl text-pink-500 mb-6">Submit a New Article</h2>
                <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
                        <input type="text" id="title" name="title" placeholder="What's your article called?" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="category_id" class="block text-gray-700 font-bold mb-2">Category</label>
                        <select id="category_id" name="category_id" class="w-full px-4 py-2 border rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="" disabled selected>-- Select a Category --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>">
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-bold mb-2">Content</label>
                        <textarea id="description" name="description" placeholder="Write your amazing story here..." rows="8" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>
                    <div class="mb-6">
                        <label for="article_image" class="block text-gray-700 font-bold mb-2">Add an Image (Optional)</label>
                        <input type="file" id="article_image" name="article_image" class="w-full px-4 py-2 border rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <button type="submit" name="insertArticleBtn" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                        Submit Article for Review
                    </button>
                </form>
            </div>

            <h2 class="text-5xl text-center text-blue-800 mt-16 mb-8">My Submissions</h2>
            <?php $my_articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); ?>
            <?php if (empty($my_articles)): ?>
                <p class="text-center text-gray-500">You haven't submitted any articles yet.</p>
            <?php else: ?>
                <?php foreach ($my_articles as $article) : ?>
                    <div class="bg-white rounded-lg shadow-lg p-8 mt-8">
                        <h3 class="text-4xl text-pink-500 mb-2"><?php echo htmlspecialchars($article['title']); ?></h3>
                        
                        <div class="flex items-center space-x-2 mt-2 flex-wrap">
                            <span class="text-sm font-medium mr-2 px-2.5 py-0.5 my-1 rounded bg-indigo-100 text-indigo-800">
                                <?php echo htmlspecialchars($article['category_name'] ?? 'Uncategorized'); ?>
                            </span>
                            <span class="text-sm font-medium mr-2 px-2.5 py-0.5 my-1 rounded <?php echo $article['is_active'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                <?php echo $article['is_active'] ? 'Published' : 'Pending Review'; ?>
                            </span>
                            <?php if ($article['is_editable'] == 1): ?>
                                <span class="bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 my-1 rounded">Edit Approved</span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="text-gray-600 mt-4">
                            <strong>Submitted on:</strong>
                            <span class="text-gray-500"><?php echo date("F j, Y, g:i a", strtotime($article['created_at'])); ?></span>
                        </p>

                        <?php if (!empty($article['image_path'])) : ?>
                            <img src="../<?php echo htmlspecialchars($article['image_path']); ?>" alt="Article Image" class="rounded-lg my-4 w-full h-auto object-cover max-h-96">
                        <?php endif; ?>

                        <p class="text-gray-800 mt-4 text-lg whitespace-pre-wrap">
                            <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                        </p>
                        
                        <hr class="my-6">

                        <!-- Action Buttons -->
                        <div class="mt-4 text-right">
                            <?php if ($article['is_editable'] == 1): ?>
                                <a href="edit_article.php?id=<?php echo $article['article_id']; ?>" class="inline-block bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition-colors">
                                    Edit Article
                                </a>
                            <?php else: ?>
                                <button class="request-edit-btn bg-gray-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors" data-article-id="<?php echo $article['article_id']; ?>">
                                    Request Edit
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- JavaScript section remains the same -->
    <script>
        $(document).ready(function() {
            // Logic for mobile menu toggle
            $('#mobile-menu-button').on('click', function() {
                $('#mobile-menu').toggleClass('hidden');
            });

            // Logic for dismissing notifications
            $('.dismiss-notification').on('click', function() {
                var notificationId = $(this).data('id');
                var notificationElement = $(this).parent();

                $.ajax({
                    type: 'POST',
                    url: 'core/handleForms.php',
                    data: {
                        notification_id: notificationId,
                        markNotificationAsRead: 1
                    },
                    success: function(response) {
                        notificationElement.fadeOut(300, function() { $(this).remove(); });
                    },
                    error: function() {
                        alert('Could not dismiss notification. Please try again.');
                    }
                });
            });

            // Logic for requesting an edit
            $('.request-edit-btn').on('click', function() {
                var button = $(this);
                var articleId = button.data('article-id');

                if (confirm("Are you sure you want to request edit access for this article? An administrator will need to approve it.")) {
                    $.ajax({
                        type: 'POST',
                        url: 'core/handleForms.php',
                        data: {
                            article_id: articleId,
                            requestEditBtn: 1
                        },
                        success: function(response) {
                            alert('Your request has been sent to an administrator.');
                            button.prop('disabled', true).text('Request Sent').removeClass('bg-gray-600 hover:bg-gray-700').addClass('bg-gray-400 cursor-not-allowed');
                        },
                        error: function() {
                            alert('Could not send request. A request may already be pending for this article.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>