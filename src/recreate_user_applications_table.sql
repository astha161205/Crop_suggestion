-- Recreate user_applications table
-- Run this script in phpMyAdmin or MySQL command line to recreate the dropped table

USE crop;

-- Drop table if it exists (in case of partial recreation)
DROP TABLE IF EXISTS user_applications;

-- Create user_applications table
CREATE TABLE user_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(100) NOT NULL,
    subsidy_id INT NOT NULL,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected', 'under_review') DEFAULT 'pending',
    notes TEXT,
    
    -- Personal Details
    full_name VARCHAR(100),
    phone_number VARCHAR(15),
    address TEXT,
    district VARCHAR(100),
    state VARCHAR(100),
    pincode VARCHAR(10),
    
    -- Document Uploads (Image file paths)
    aadhar_card_image VARCHAR(255),
    pan_card_image VARCHAR(255),
    bank_passbook_image VARCHAR(255),
    land_documents_image VARCHAR(255),
    income_certificate_image VARCHAR(255),
    caste_certificate_image VARCHAR(255),
    profile_photo_image VARCHAR(255),
    signature_image VARCHAR(255),
    other_documents_images TEXT,
    
    -- Additional Details
    land_holding DECIMAL(10,2),
    annual_income DECIMAL(12,2),
    bank_account_number VARCHAR(50),
    ifsc_code VARCHAR(20),
    bank_name VARCHAR(100),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (subsidy_id) REFERENCES subsidies(id) ON DELETE CASCADE
);

-- Add indexes for better performance
CREATE INDEX idx_user_email ON user_applications(user_email);
CREATE INDEX idx_subsidy_id ON user_applications(subsidy_id);
CREATE INDEX idx_status ON user_applications(status);
CREATE INDEX idx_application_date ON user_applications(application_date);

-- Verify table creation
DESCRIBE user_applications;

-- Show success message
SELECT 'user_applications table created successfully!' as message;
