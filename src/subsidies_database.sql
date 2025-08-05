-- Crop Suggestion Database Setup
-- Run this script in phpMyAdmin or MySQL command line

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS crop;
USE crop;

-- Create farmer_profiles table
CREATE TABLE IF NOT EXISTS farmer_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    address TEXT,
    farm_size DECIMAL(10,2),
    theme ENUM('light', 'dark') DEFAULT 'dark',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create subsidies table
CREATE TABLE IF NOT EXISTS subsidies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    amount DECIMAL(12,2),
    eligibility_criteria TEXT,
    application_deadline DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create subsidy_applications table
CREATE TABLE IF NOT EXISTS subsidy_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT,
    subsidy_id INT,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    documents_submitted TEXT,
    FOREIGN KEY (farmer_id) REFERENCES farmer_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (subsidy_id) REFERENCES subsidies(id) ON DELETE CASCADE
);

-- Create crop_recommendations table
CREATE TABLE IF NOT EXISTS crop_recommendations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT,
    soil_type VARCHAR(50),
    climate_zone VARCHAR(50),
    season VARCHAR(20),
    recommended_crops TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmer_profiles(id) ON DELETE CASCADE
);

-- Insert sample subsidies data
INSERT INTO subsidies (title, description, amount, eligibility_criteria, application_deadline) VALUES
('PM-KISAN Scheme', 'Direct income support of Rs. 6000 per year to eligible farmer families', 6000.00, 'Small and marginal farmers with landholding up to 2 hectares', '2024-12-31'),
('PM Fasal Bima Yojana', 'Crop insurance scheme to protect farmers against crop loss', 5000.00, 'All farmers growing notified crops', '2024-11-30'),
('Soil Health Card Scheme', 'Free soil testing and recommendations for farmers', 0.00, 'All farmers', '2024-10-31'),
('PMKSY - Micro Irrigation', 'Subsidy for drip and sprinkler irrigation systems', 15000.00, 'Farmers with landholding up to 5 hectares', '2024-09-30'),
('National Agriculture Market (eNAM)', 'Online trading platform for agricultural commodities', 2000.00, 'All registered farmers', '2024-08-31');

-- Create admin user (password: admin123)
INSERT INTO farmer_profiles (name, email, password, user_type) VALUES
('Admin User', 'admin@agrigrow.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); 