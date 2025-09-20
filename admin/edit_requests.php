<?php require_once 'classloader.php'; ?>

<?php
if (!$userObj->isLoggedIn() || !$userObj->isAdmin()) {
    header("Location: login.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
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
            <h1 class="text-5xl text-center text-blue-800 mb-8">Article Edit Requests</h1>
            
            <?php $requests = $editRequestObj->getPendingRequests(); ?>
            <?php if (empty($requests)): ?>
                <p class="text-center text-gray-500 mt-12">There are no pending edit requests.</p>
            <?php endif; ?>

            <?php foreach ($requests as $request) : ?>
                <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                    <p class="text-gray-600">
                        Writer <strong class="text-pink-500"><?php echo htmlspecialchars($request['username']); ?></strong> has requested to edit the article:
                    </p>
                    <h3 class="text-2xl font-bold mt-2"><?php echo htmlspecialchars($request['title']); ?></h3>
                    <p class="text-sm text-gray-500 mt-1">Requested on: <?php echo date("F j, Y", strtotime($request['created_at'])); ?></p>
                    
                    <div class="flex space-x-4 mt-4">
                        <form method="POST" action="core/handleForms.php">
                            <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                            <input type="hidden" name="article_id" value="<?php echo $request['article_id']; ?>">

                            <input type="hidden" name="user_id" value="<?php echo $request['user_id']; ?>">
                            <button type="submit" name="approveEditBtn" class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700">Approve</button>
                        </form>
                        <form method="POST" action="core/handleForms.php">
                            <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">

                            <input type="hidden" name="user_id" value="<?php echo $request['user_id']; ?>">
                            <input type="hidden" name="article_id" value="<?php echo $request['article_id']; ?>">
                            <button type="submit" name="denyEditBtn" class="bg-red-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-700">Deny</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>