<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - VaxTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gray-50">
    <?php
    // Start session
    session_start();
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    // Include database connection
    require_once 'includes/config.php';
    
    // Get user information
    $userId = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Get available vaccines
    $sql = "SELECT * FROM vaccines WHERE active = 1 ORDER BY name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $vaccines = $stmt->get_result();
    
    // Get available locations
    $sql = "SELECT * FROM locations WHERE active = 1 ORDER BY name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $locations = $stmt->get_result();
    ?>

    <!-- Navigation -->
    <nav class="bg-green-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="dashboard.php" class="text-2xl font-bold">VaxTrack</a>
            <div class="hidden md:flex space-x-6">
                <a href="dashboard.php" class="hover:text-green-200">Dashboard</a>
                <a href="appointment.php" class="hover:text-green-200">Book Appointment</a>
                <a href="vaccination-history.php" class="hover:text-green-200">Vaccination History</a>
                <a href="profile.php" class="hover:text-green-200">Profile</a>
            </div>
            <div class="flex items-center space-x-4">
                <div class="hidden md:block">
                    <span class="text-sm">Welcome, <?php echo htmlspecialchars($user['firstname']); ?></span>
                </div>
                <div class="relative">
                    <button id="userMenuButton" class="flex items-center focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-green-700 flex items-center justify-center">
                            <span class="text-sm font-bold"><?php echo substr($user['firstname'], 0, 1) . substr($user['lastname'], 0, 1); ?></span>
                        </div>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden z-10">
                        <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <a href="settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                        <div class="border-t border-gray-100"></div>
                        <a href="includes/logout.inc.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                    </div>
                </div>
                <button class="md:hidden text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Appointment Booking Content -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Book Vaccination Appointment</h1>
        
        <?php
        // Display success message
        if (isset($_GET['booking']) && $_GET['booking'] == 'success') {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">';
            echo '<p class="font-bold">Success!</p>';
            echo '<p>Your appointment has been booked successfully. You can view it in your dashboard.</p>';
            echo '</div>';
        }
        
        // Display error message
        if (isset($_GET['error'])) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">';
            echo '<p class="font-bold">Error!</p>';
            
            switch ($_GET['error']) {
                case 'emptyfields':
                    echo '<p>Please fill in all required fields.</p>';
                    break;
                case 'invaliddate':
                    echo '<p>Please select a valid date and time.</p>';
                    break;
                case 'slotnotavailable':
                    echo '<p>The selected time slot is no longer available. Please choose another time.</p>';
                    break;
                default:
                    echo '<p>An error occurred. Please try again.</p>';
            }
            
            echo '</div>';
        }
        ?>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="includes/book-appointment.inc.php" method="POST" id="appointmentForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="vaccine" class="block text-gray-700 mb-2">Select Vaccine *</label>
                        <select id="vaccine" name="vaccine_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            <option value="">Select a vaccine</option>
                            <?php while ($vaccine = $vaccines->fetch_assoc()): ?>
                                <option value="<?php echo $vaccine['id']; ?>"><?php echo htmlspecialchars($vaccine['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label for="location" class="block text-gray-700 mb-2">Select Location *</label>
                        <select id="location" name="location_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            <option value="">Select a location</option>
                            <?php while ($location = $locations->fetch_assoc()): ?>
                                <option value="<?php echo $location['id']; ?>"><?php echo htmlspecialchars($location['name']); ?> - <?php echo htmlspecialchars($location['address']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="appointment_date" class="block text-gray-700 mb-2">Select Date *</label>
                        <input type="date" id="appointment_date" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    <div>
                        <label for="appointment_time" class="block text-gray-700 mb-2">Select Time *</label>
                        <select id="appointment_time" name="appointment_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required disabled>
                            <option value="">Select a date first</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="notes" class="block text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="terms" name="terms" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" required>
                        <label for="terms" class="ml-2 block text-gray-700">
                            I confirm that the information provided is accurate and I agree to the <a href="#" class="text-green-600 hover:underline">Terms of Service</a> *
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" name="book-appointment" class="bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition">Book Appointment</button>
                </div>
            </form>
        </div>
    </div>

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

    <script>
        // Toggle user menu
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');
        
        userMenuButton.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        });
        
        // Close the menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
        
        // Handle date and time selection
        const appointmentDate = document.getElementById('appointment_date');
        const appointmentTime = document.getElementById('appointment_time');
        const locationSelect = document.getElementById('location');
        
        appointmentDate.addEventListener('change', function() {
            if (this.value && locationSelect.value) {
                fetchAvailableTimeSlots(locationSelect.value, this.value);
            }
        });
        
        locationSelect.addEventListener('change', function() {
            if (this.value && appointmentDate.value) {
                fetchAvailableTimeSlots(this.value, appointmentDate.value);
            }
        });
        
        function fetchAvailableTimeSlots(locationId, date) {
            // In a real application, this would be an AJAX call to the server
            // For demonstration purposes, we'll simulate it with static time slots
            appointmentTime.disabled = false;
            appointmentTime.innerHTML = '<option value="">Select a time</option>';
            
            // Sample time slots (9 AM to 5 PM, 30-minute intervals)
            const startHour = 9;
            const endHour = 17;
            const interval = 30; // minutes
            
            for (let hour = startHour; hour < endHour; hour++) {
                for (let minute = 0; minute < 60; minute += interval) {
                    const timeValue = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}:00`;
                    const displayHour = hour > 12 ? hour - 12 : hour;
                    const amPm = hour >= 12 ? 'PM' : 'AM';
                    const displayTime = `${displayHour}:${minute.toString().padStart(2, '0')} ${amPm}`;
                    
                    const option = document.createElement('option');
                    option.value = timeValue;
                    option.textContent = displayTime;
                    appointmentTime.appendChild(option);
                }
            }
        }
    </script>
</body>
</html>