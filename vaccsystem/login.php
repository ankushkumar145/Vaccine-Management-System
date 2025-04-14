<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VaxTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-green-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.html" class="text-2xl font-bold">VaxTrack</a>
            <div class="hidden md:flex space-x-6">
                <a href="index.html" class="hover:text-green-200">Home</a>
                <a href="index.html#services" class="hover:text-green-200">Services</a>
                <a href="index.html#about" class="hover:text-green-200">About</a>
                <a href="index.html#contact" class="hover:text-green-200">Contact</a>
            </div>
            <div class="flex space-x-3">
                <a href="login.php" class="bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-green-100 transition">Login</a>
                <a href="register.php" class="bg-green-700 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-800 transition">Register</a>
            </div>
            <button class="md:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-green-600 text-white py-4 px-6">
                    <h2 class="text-2xl font-bold">Login to Your Account</h2>
                </div>
                <div class="p-6">
                    <?php
                    // Display error message if any
                    if (isset($_GET['error'])) {
                        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">';
                        
                        switch ($_GET['error']) {
                            case 'emptyfields':
                                echo '<p>Please fill in all fields.</p>';
                                break;
                            case 'wrongcredentials':
                                echo '<p>Invalid email or password.</p>';
                                break;
                            default:
                                echo '<p>An error occurred. Please try again.</p>';
                        }
                        
                        echo '</div>';
                    }
                    ?>
                    <form action="includes/login.inc.php" method="POST">
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                        <div class="mb-6">
                            <label for="password" class="block text-gray-700 mb-2">Password</label>
                            <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            <div class="flex justify-end mt-2">
                                <a href="forgot-password.php" class="text-sm text-green-600 hover:underline">Forgot Password?</a>
                            </div>
                        </div>
                        <button type="submit" name="login-submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition">Login</button>
                    </form>
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">Don't have an account? <a href="register.php" class="text-green-600 hover:underline">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <p class="text-gray-400">&copy; 2025 VaxTrack. All rights reserved.</p>
                <div class="mt-2">
                    <a href="#" class="text-gray-400 hover:text-white mx-2">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white mx-2">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-white mx-2">Support</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>