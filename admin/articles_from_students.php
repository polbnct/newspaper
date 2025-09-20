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
            <h1 class="text-5xl text-center text-blue-800 mb-8">Pending Student Articles</h1>
            <p class="text-center text-gray-600 mb-12">Review, approve, or delete submissions from writers.</p>

            <?php $articles = $articleObj->getArticles(); ?>
            <?php foreach ($articles as $article) : ?>
                <div class="bg-white rounded-lg shadow-lg p-8 mt-8 articleCard">
                    <h2 class="text-4xl text-pink-500 mb-2"><?php echo htmlspecialchars($article['title']); ?></h2>
                    <p class="text-gray-600">
                        <strong>By: <?php echo htmlspecialchars($article['username']); ?></strong> -
                        <span class="text-gray-500"><?php echo date("F j, Y, g:i a", strtotime($article['created_at'])); ?></span>
                    </p>

                    <!-- Status Indicator -->
                    <div class="mt-4">
                        <?php if ($article['is_active'] == 0) : ?>
                            <p class="font-bold text-yellow-600 bg-yellow-100 py-1 px-3 inline-block rounded-full text-sm">Status: PENDING</p>
                        <?php else : ?>
                            <p class="font-bold text-green-600 bg-green-100 py-1 px-3 inline-block rounded-full text-sm">Status: ACTIVE</p>
                        <?php endif; ?>
                    </div>

                    <!-- **** IMAGE DISPLAY BLOCK **** -->
                    <?php if (!empty($article['image_path'])) : ?>
                        <img src="../<?php echo htmlspecialchars($article['image_path']); ?>" alt="Article Image" class="rounded-lg my-4 w-full h-auto object-cover max-h-96">
                    <?php endif; ?>
                    
                    <p class="text-gray-800 mt-4 text-lg whitespace-pre-wrap"><?php echo htmlspecialchars($article['content']); ?></p>
                    
                    <hr class="my-6">

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between">
                        <div class="w-1/2 pr-2">
                            <label class="block text-sm font-medium text-gray-700">Change Status</label>
                            <select name="is_active" class="is_active_select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" data-article-id="<?php echo $article['article_id']; ?>">
                                <option value="" disabled <?php if ($article['is_active'] != '0' && $article['is_active'] != '1') echo 'selected'; ?>>Select an option</option>
                                <option value="0" <?php if ($article['is_active'] == '0') echo 'selected'; ?>>Pending</option>
                                <option value="1" <?php if ($article['is_active'] == '1') echo 'selected'; ?>>Active</option>
                            </select>
                        </div>
                        <div class="w-1/2 pl-2 flex justify-end self-end">
                            <form class="deleteArticleForm w-full">
                                <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
                                <button type="submit" class="w-full bg-red-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-700 transition-colors">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
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

      // AJAX for Updating Article Visibility (Approve/Decline)
      $('.is_active_select').on('change', function (event) {
        var formData = {
          article_id: $(this).data('article-id'),
          status: $(this).val(),
          updateArticleVisibility: 1
        }
        if (formData.article_id != "" && formData.status != "") {
          $.ajax({
            type:"POST",
            url: "core/handleForms.php",
            data:formData,
            success: function (data) {
              if (data) {
                location.reload();
              } else {
                alert("Visibility update failed. Please try again.");
              }
            }
          })
        }
      });
    </script>
</body>
</html>