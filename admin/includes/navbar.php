<?php
// This gets the current page's filename to highlight the active link
$current_page = basename($_SERVER['PHP_SELF']);

// Helper function to apply active class
function active_class($page_name, $current_page) {
    return ($page_name == $current_page) ? 'bg-blue-700 font-bold' : '';
}
?>
<nav class="bg-blue-800 p-4 shadow-lg sticky top-0 z-50">
  <div class="container mx-auto flex justify-between items-center">
    <a class="text-white text-2xl font-bold" href="index.php">Admin Panel</a>
    
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-button" class="text-white md:hidden focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
      </svg>
    </button>

    <!-- Menu for Desktop -->
    <div class="hidden md:flex space-x-4 text-white items-center">
      <a href="index.php" class="px-3 py-2 rounded-md hover:bg-blue-700 transition-colors <?php echo active_class('index.php', $current_page); ?>">Dashboard</a>
      <a href="articles_submitted.php" class="px-3 py-2 rounded-md hover:bg-blue-700 transition-colors <?php echo active_class('articles_submitted.php', $current_page); ?>">Submitted Articles</a>
      <a href="edit_requests.php" class="px-3 py-2 rounded-md hover:bg-blue-700 transition-colors <?php echo active_class('edit_requests.php', $current_page); ?>">Edit Requests</a>
      <a href="categories.php" class="px-3 py-2 rounded-md hover:bg-blue-700 transition-colors <?php echo active_class('categories.php', $current_page); ?>">Categories</a>
      <a href="core/handleForms.php?logoutUserBtn=1" class="bg-pink-500 hover:bg-pink-600 px-4 py-2 rounded-lg font-bold transition-colors">Logout</a>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden mt-3">
    <a href="index.php" class="block py-2 px-3 text-white rounded-md hover:bg-blue-700 <?php echo active_class('index.php', $current_page); ?>">Dashboard</a>
    <a href="articles_submitted.php" class="block py-2 px-3 text-white rounded-md hover:bg-blue-700 <?php echo active_class('articles_submitted.php', $current_page); ?>">Submitted Articles</a>
    <a href="edit_requests.php" class="block py-2 px-3 text-white rounded-md hover:bg-blue-700 <?php echo active_class('edit_requests.php', $current_page); ?>">Edit Requests</a>
    <a href="categories.php" class="block py-2 px-3 text-white rounded-md hover:bg-blue-700 <?php echo active_class('categories.php', $current_page); ?>">Categories</a>
    <a href="core/handleForms.php?logoutUserBtn=1" class="block py-2 px-3 text-white rounded-md hover:bg-blue-700">Logout</a>
  </div>
</nav>