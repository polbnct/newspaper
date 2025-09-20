<?php
// This gets the current page's filename to highlight the active link
$current_page = basename($_SERVER['PHP_SELF']);

// Helper function to apply active class styling
function active_class($page_name, $current_page) {
    return ($page_name == $current_page) ? 'bg-blue-900 font-bold' : '';
}
?>
<nav class="bg-blue-800 shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-center py-3">
            <!-- Site Title / Logo -->
            <div>
                <a href="index.php" class="text-white text-2xl md:text-3xl" style="font-family: 'Luckiest Guy', cursive;">
                    Writer's Panel
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="index.php" class="py-2 px-3 text-white rounded-md hover:bg-blue-700 transition-colors text-lg <?php echo active_class('index.php', $current_page); ?>">
                    Dashboard
                </a>
                <!-- You can add more links here in the future -->
                <a href="core/handleForms.php?logoutUserBtn=1" class="py-2 px-4 bg-pink-500 text-white rounded-md hover:bg-pink-600 transition-colors font-bold text-lg">
                    Logout
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-white focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu (hidden by default) -->
    <div id="mobile-menu" class="md:hidden hidden bg-blue-700">
        <a href="index.php" class="block py-3 px-6 text-white hover:bg-blue-600 transition-colors <?php echo active_class('index.php', $current_page); ?>">Dashboard</a>
        <a href="core/handleForms.php?logoutUserBtn=1" class="block py-3 px-6 text-white hover:bg-blue-600 transition-colors">Logout</a>
    </div>
</nav>