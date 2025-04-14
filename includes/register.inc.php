<?php
// Check if form is submitted
if (isset($_POST['register-submit'])) {
    
    // Include database connection
    require_once 'config.php';
    
    // Get form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input
    if (empty($firstname) || empty($lastname) || empty($email) || empty($phone) || 
        empty($dob) || empty($gender) || empty($address) || empty($password) || empty($confirm_password)) {
        header("Location: ../register.php?error=emptyfields");
        exit();
    }
    
    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../register.php?error=invalidemail");
        exit();
    }
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        header("Location: ../register.php?error=passwordmismatch");
        exit();
    }
    
    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        header("Location: ../register.php?error=emailtaken");
        exit();
    }
    $stmt->close();
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $sql = "INSERT INTO users (firstname, lastname, email, phone, dob, gender, address, password, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $firstname, $lastname, $email, $phone, $dob, $gender, $address, $hashed_password);
    
    if ($stmt->execute()) {
        // Registration successful
        header("Location: ../register.php?signup=success");
        exit();
    } else {
        // Registration failed
        header("Location: ../register.php?error=sqlerror");
        exit();
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
    
} else {
    // If not submitted through the form, redirect to registration page
    header("Location: ../register.php");
    exit();
}
?>