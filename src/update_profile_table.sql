-- Update farmer_profiles table to use file paths instead of binary data
-- Run this script in phpMyAdmin or MySQL command line

USE crop;

-- Add new column for profile image path
ALTER TABLE farmer_profiles ADD COLUMN profile_image_path VARCHAR(255) AFTER profile_image;

-- Drop the old binary profile_image column (optional - uncomment if you want to remove it)
-- ALTER TABLE farmer_profiles DROP COLUMN profile_image;

-- Show the updated table structure
DESCRIBE farmer_profiles;

-- Show success message
SELECT 'farmer_profiles table updated successfully!' as message;

