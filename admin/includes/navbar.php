<nav class="bg-blue-600 p-6 shadow-lg">
  <div class="container mx-auto flex justify-between items-center">
    <a class="text-white text-2xl font-bold" href="index.php">Admin Panel</a>
    
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-button" class="text-white md:hidden">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
      </svg>
    </button>

    <!-- Menu for Desktop -->
    <div class="hidden md:flex space-x-6 text-white items-center">
      <a href="articles_from_students.php" class="hover:text-pink-300">Student Articles</a>
      <a href="articles_submitted.php" class="hover:text-pink-300">My Articles</a>
      <a href="edit_requests.php" class="hover:text-pink-300">Edit Requests</a>
      <a href="core/handleForms.php?logoutUserBtn=1" class="bg-pink-500 hover:bg-pink-600 px-4 py-2 rounded-lg font-bold">Logout</a>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden mt-4">
    <a href="articles_from_students.php" class="block py-2 px-4 text-white hover:bg-blue-700">Student Articles</a>
    <a href="articles_submitted.php" class="block py-2 px-4 text-white hover:bg-blue-700">My Articles</a>
    <a href="edit_requests.php" class="block py-2 px-4 text-white hover:bg-blue-700">Edit Requests</a>
    <a href="core/handleForms.php?logoutUserBtn=1" class="block py-2 px-4 text-white hover:bg-blue-700">Logout</a>
  </div>
</nav>

<script>
  // Simple toggle for mobile menu
  document.getElementById('mobile-menu-button').addEventListener('click', function() {
    document.getElementById('mobile-menu').classList.toggle('hidden');
  });
</script>