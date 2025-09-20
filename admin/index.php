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
    
    <title>Admin Dashboard</title>
</head>
<body class="bg-gray-100">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container mx-auto p-6">
      <div class="text-center mb-8">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="text-xl text-gray-600 mt-2">Welcome, <span class="text-green-600 font-bold"><?php echo htmlspecialchars($_SESSION['username']); ?></span>. Use the navbar to manage content.</p>
      </div>
      
      <div class="max-w-4xl mx-auto">
          <h2 class="text-3xl font-bold text-center mb-6 text-gray-700">Recently Published Articles</h2>
          <?php $articles = $articleObj->getActiveArticles(); ?>
          <?php if (empty($articles)): ?>
              <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                  No articles have been published yet.
              </div>
          <?php else: ?>
              <?php foreach ($articles as $article) : ?>
              <div class="bg-white rounded-lg shadow-md p-6 mt-4">
                  <h3 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($article['title']); ?></h3> 
                  <div class="my-2">
                      <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full">
                          <?php echo htmlspecialchars($article['category_name'] ?? 'Uncategorized'); ?>
                      </span>
                  </div>
                  <small class="text-gray-500">
                      By <strong><?php echo htmlspecialchars($article['username']); ?></strong> on <?php echo date("F j, Y", strtotime($article['created_at'])); ?>
                  </small>
                  <p class="text-gray-700 mt-3">
                      <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                  </p>
              </div>  
              <?php endforeach; ?>
          <?php endif; ?>
      </div>
    </div>

    <!-- JavaScript for Mobile Menu Toggle -->
    <script>
      document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
      });
    </script>

</body>
</html>