<?php require_once 'writer/classloader.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

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

    <nav class="bg-blue-600 p-6 shadow-lg">
      <div class="container mx-auto">
        <a class="text-white text-3xl font-bold" href="#">School Publication</a>
      </div>
    </nav>

    <div class="container mx-auto p-8">
      <h1 class="text-5xl text-center text-blue-800 mb-8">Hello there and welcome!</h1>

      <div class="grid md:grid-cols-2 gap-12 mb-12">
        <div class="bg-white rounded-lg shadow-lg p-8 transition-transform transform hover:scale-105">
          <h2 class="text-4xl text-pink-500 mb-4">Writer</h2>
          <img src="https://plus.unsplash.com/premium_photo-1676998930667-cab56c8fa602?q=80&w=387&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="A person writing in a notebook" class="rounded-lg mb-4">
          <p class="text-gray-700">Content writers create clear, engaging, and informative content that helps our school share amazing stories and ideas. They build our brand, attract readers, and make our publication a joy to read!</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-8 transition-transform transform hover:scale-105">
          <h2 class="text-4xl text-pink-500 mb-4">Admin</h2>
          <img src="https://images.unsplash.com/photo-1516321497487-e288fb19713f?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&id=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="A person working on a laptop" class="rounded-lg mb-4">
          <p class="text-gray-700">Admin writers are the leaders of our content team. They guide the vision of our publication, manage the editorial process, and ensure everything we publish is top-notch and aligns with our school's goals.</p>
        </div>
      </div>

      <h2 class="text-5xl text-center text-blue-800 mt-16 mb-8">All Articles Below!</h2>

      <div class="max-w-4xl mx-auto">
        <?php $articles = $articleObj->getActiveArticles(); ?>
        <?php foreach ($articles as $article) { ?>
          <div class="bg-white rounded-lg shadow-lg p-8 mt-8">
            <h3 class="text-4xl text-pink-500 mb-2"><?php echo htmlspecialchars($article['title']); ?></h3>
            <?php if ($article['is_admin'] == 1) { ?>
              <p><small class="bg-blue-600 text-white font-bold py-1 px-3 rounded-full text-sm">
                Message From Admin
              </small></p>
            <?php } ?>
            <p class="text-gray-600 mt-4">
              <strong><?php echo htmlspecialchars($article['username']); ?></strong> - <span class="text-gray-500"><?php echo date("F j, Y, g:i a", strtotime($article['created_at'])); ?></span>
            </p>
            
            <!-- THIS IS THE NEW CODE BLOCK TO DISPLAY THE IMAGE -->
            <?php if (!empty($article['image_path'])) : ?>
                <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="Article Image" class="rounded-lg my-4 w-full h-auto object-cover max-h-96">
            <?php endif; ?>

            <p class="text-gray-800 mt-4 text-lg"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
          </div>
        <?php } ?>
      </div>
    </div>
  </body>
</html>