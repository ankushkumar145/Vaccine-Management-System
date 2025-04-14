<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - VaxTrack</title>
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

    <!-- Registration Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-green-600 text-white py-4 px-6">
                    <h2 class="text-2xl font-bold">Create an Account</h2>
                </div>
                <div class="p-6">
                    <?php
                    // Display error message if any
                    if (isset($_GET['error'])) {
                        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">';
                        
                        switch ($_GET['error']) {
                            case 'emptyfields':
                                echo '<p>Please fill in all required fields.</p>';
                                break;
                            case 'invalidemail':
                                echo '<p>Please enter a valid email address.</p>';
                                break;
                            case 'passwordmismatch':
                                echo '<p>Passwords do not match.</p>';
                                break;
                            case 'emailtaken':
                                echo '<p>Email is already registered. Please use a different email or login.</p>';
                                break;
                            case 'invalidphone':
                                echo '<p>Please enter a valid phone number.</p>';
                                break;
                            default:
                                echo '<p>An error occurred. Please try again.</p>';
                        }
                        
                        echo '</div>';
                    }
                    
                    // Display success message
                    if (isset($_GET['signup']) && $_GET['signup'] == 'success') {
                        echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">';
                        echo '<p>Registration successful! You can now <a href="login.php" class="font-bold underline">login</a>.</p>';
                        echo '</div>';
                    }
                    ?>
                    <form action="includes/register.inc.php" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="firstname" class="block text-gray-700 mb-2">First Name *</label>
                                <input type="text" id="firstname" name="firstname" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label for="lastname" class="block text-gray-700 mb-2">Last Name *</label>
                                <input type="text" id="lastname" name="lastname" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="email" class="block text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label for="phone" class="block text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="dob" class="block text-gray-700 mb-2">Date of Birth *</label>
                                <input type="date" id="dob" name="dob" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label for="gender" class="block text-gray-700 mb-2">Gender *</label>
                                <select id="gender" name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                    <option value="prefer_not_to_say">Prefer not to say</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="address" class="block text-gray-700 mb-2">Address *</label>
                            <textarea id="address" name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="password" class="block text-gray-700 mb-2">Password *</label>
                                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                <p class="text-xs text-gray-500 mt-1">Password must be at least 8 characters long with letters and numbers.</p>
                            </div>
                            <div>
                                <label for="confirm_password" class="block text-gray-700 mb-2">Confirm Password *</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                        </div>
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="terms" name="terms" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" required>
                                <label for="terms" class="ml-2 block text-gray-700">
                                    I agree to the <a href="#" class="text-green-600 hover:underline">Terms of Service</a> and <a href="#" class="text-green-600 hover:underline">Privacy Policy</a> *
                                </label>
                            </div>
                        </div>
                        <button type="submit" name="register-submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition">Register</button>
                    </form>
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">Already have an account? <a href="login.php" class="text-green-600 hover:underline">Login here</a></p>
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