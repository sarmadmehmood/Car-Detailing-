<?php
/**
 * Database Connection File
 * Crysta Auto Care - Car Detailing Website
 * 
 * Connects to MySQL database and creates tables if they don't exist.
 */

$host = 'localhost';
$dbname = 'crysta_db';
$username = 'root';
$password = '1234567890';

// Create connection without database first (to create DB if needed)
$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($dbname);

// Create bookings table
$conn->query("CREATE TABLE IF NOT EXISTS `bookings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `service` VARCHAR(100) NOT NULL,
    `car_type` VARCHAR(50) NOT NULL,
    `car_name` VARCHAR(100) DEFAULT NULL,
    `booking_date` DATE NOT NULL,
    `booking_time` VARCHAR(20) DEFAULT NULL,
    `payment_method` VARCHAR(50) NOT NULL,
    `payment_proof` VARCHAR(255) DEFAULT NULL,
    `location` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Add new columns if they don't exist (for existing installations)
$result = $conn->query("SHOW COLUMNS FROM bookings LIKE 'car_name'");
if ($result->num_rows === 0) {
    $conn->query("ALTER TABLE bookings ADD COLUMN `car_name` VARCHAR(100) DEFAULT NULL AFTER `car_type`");
}
$result = $conn->query("SHOW COLUMNS FROM bookings LIKE 'booking_time'");
if ($result->num_rows === 0) {
    $conn->query("ALTER TABLE bookings ADD COLUMN `booking_time` VARCHAR(20) DEFAULT NULL AFTER `booking_date`");
}

// Add location column if it doesn't exist
$result = $conn->query("SHOW COLUMNS FROM bookings LIKE 'location'");
if ($result->num_rows === 0) {
    $conn->query("ALTER TABLE bookings ADD COLUMN `location` VARCHAR(255) DEFAULT NULL AFTER `payment_proof`");
}

// Create gallery table
$conn->query("CREATE TABLE IF NOT EXISTS `gallery` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `video` VARCHAR(255) NOT NULL,
    `title` VARCHAR(200) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Create blogs table
$conn->query("CREATE TABLE IF NOT EXISTS `blogs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Set charset
$conn->set_charset("utf8mb4");
?>
