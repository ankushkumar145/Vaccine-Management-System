<?php
// Start session
session_start();

// Check if form is submitted
if (isset($_POST['login-submit'])) {
    
    // Include database connection
    require_once 'config.php';
    
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Validate input
    if (empty($email) || empty($password)) {
        header("Location: ../login.php?error=emptyfields");
        exit();
    }
    
    // Check if user exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Verify password
        $passwordCheck = password_verify($password, $row['password']);
        
        if ($passwordCheck) {
            // Password is correct, create session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_firstname'] = $row['firstname'];
            $_SESSION['user_lastname'] = $row['lastname'];
            
            // Redirect to dashboard
            header("Location: ../dashboard.php");
            exit();
        } else {
            // Wrong password
            header("Location: ../login.php?error=wrongcredentials");
            exit();
        }
    } else {
        // User does not exist
        header("Location: ../login.php?error=wrongcredentials");
        exit();
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
    
} else {
    // If not submitted through the form, redirect to login page
    header("Location: ../login.php");
    exit();
}
?>