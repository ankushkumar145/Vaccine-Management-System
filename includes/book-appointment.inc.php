<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if form is submitted
if (isset($_POST['book-appointment'])) {
    
    // Include database connection
    require_once 'config.php';
    
    // Get form data
    $user_id = $_SESSION['user_id'];
    $vaccine_id = $_POST['vaccine_id'];
    $location_id = $_POST['location_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = $_POST['notes'];
    
    // Validate input
    if (empty($vaccine_id) || empty($location_id) || empty($appointment_date) || empty($appointment_time)) {
        header("Location: ../appointment.php?error=emptyfields");
        exit();
    }
    
    // Validate date (must be current or future date)
    $current_date = date('Y-m-d');
    if ($appointment_date < $current_date) {
        header("Location: ../appointment.php?error=invaliddate");
        exit();
    }
    
    // Check if the time slot is available
    $sql = "SELECT id FROM appointments 
            WHERE location_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'cancelled'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $location_id, $appointment_date, $appointment_time);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        header("Location: ../appointment.php?error=slotnotavailable");
        exit();
    }
    $stmt->close();
    
    // Insert new appointment
    $sql = "INSERT INTO appointments (user_id, vaccine_id, location_id, appointment_date, appointment_time, notes, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())";
    $stmt = $conn->prepare($sql);
    $status = "pending";
    $stmt->bind_param("iiisss", $user_id, $vaccine_id, $location_id, $appointment_date, $appointment_time, $notes);
    
    if ($stmt->execute()) {
        // Booking successful
        header("Location: ../appointment.php?booking=success");
        exit();
    } else {
        // Booking failed
        header("Location: ../appointment.php?error=sqlerror");
        exit();
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
    
} else {
    // If not submitted through the form, redirect to appointment page
    header("Location: ../appointment.php");
    exit();
}
?>