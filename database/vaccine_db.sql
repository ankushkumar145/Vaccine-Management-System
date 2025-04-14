-- Create database
CREATE DATABASE IF NOT EXISTS vaccine_db;
USE vaccine_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('male', 'female', 'other', 'prefer_not_to_say') NOT NULL,
    address TEXT NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'super_admin') NOT NULL DEFAULT 'admin',
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Vaccines table
CREATE TABLE IF NOT EXISTS vaccines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    manufacturer VARCHAR(100) NOT NULL,
    doses_required INT NOT NULL DEFAULT 1,
    days_between_doses INT DEFAULT NULL,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Locations table
CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    zipcode VARCHAR(20) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    capacity_per_day INT NOT NULL,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Appointments table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    vaccine_id INT NOT NULL,
    location_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    notes TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (vaccine_id) REFERENCES vaccines(id),
    FOREIGN KEY (location_id) REFERENCES locations(id)
);

-- Vaccination records table
CREATE TABLE IF NOT EXISTS vaccination_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    administered_by VARCHAR(100) NOT NULL,
    batch_number VARCHAR(50) NOT NULL,
    notes TEXT,
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Reminders table
CREATE TABLE IF NOT EXISTS reminders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    appointment_id INT NOT NULL,
    reminder_type ENUM('email', 'sms') NOT NULL,
    reminder_date DATETIME NOT NULL,
    status ENUM('pending', 'sent', 'failed') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Insert sample data for testing

-- Admin user
INSERT INTO admins (name, email, password, role, created_at) VALUES 
('Admin User', 'admin@vaxtrack.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', NOW());

-- Sample vaccines
INSERT INTO vaccines (name, description, manufacturer, doses_required, days_between_doses, active, created_at) VALUES 
('COVID-19 Vaccine', 'Protects against COVID-19 infection', 'Pfizer-BioNTech', 2, 21, TRUE, NOW()),
('Influenza Vaccine', 'Annual flu vaccine', 'GlaxoSmithKline', 1, NULL, TRUE, NOW()),
('Hepatitis B Vaccine', 'Protects against Hepatitis B virus', 'Merck', 3, 30, TRUE, NOW()),
('MMR Vaccine', 'Measles, Mumps, and Rubella vaccine', 'Merck', 2, 28, TRUE, NOW()),
('Tetanus Vaccine', 'Tetanus toxoid vaccine', 'Sanofi Pasteur', 1, NULL, TRUE, NOW());

-- Sample locations
INSERT INTO locations (name, address, city, state, zipcode, phone, email, capacity_per_day, active, created_at) VALUES 
('City Hospital Vaccination Center', '123 Main Street', 'Metropolis', 'NY', '10001', '(555) 123-4567', 'cityhospital@example.com', 100, TRUE, NOW()),
('Community Health Clinic', '456 Oak Avenue', 'Metropolis', 'NY', '10002', '(555) 987-6543', 'chc@example.com', 50, TRUE, NOW()),
('Westside Medical Center', '789 Pine Road', 'Metropolis', 'NY', '10003', '(555) 456-7890', 'westside@example.com', 75, TRUE, NOW()),
('Eastside Pharmacy', '321 Elm Street', 'Metropolis', 'NY', '10004', '(555) 234-5678', 'eastside@example.com', 30, TRUE, NOW());