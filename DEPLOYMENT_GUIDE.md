# Deployment Guide for PHP Mailer

## Issues Fixed:
1. ✅ Removed duplicate PHPMailer loading
2. ✅ Added proper environment variable handling
3. ✅ Improved error handling
4. ✅ Made code deployment-ready

## Environment Variables Setup

### For Railway:
1. Go to your Railway project dashboard
2. Navigate to "Variables" tab
3. Add these environment variables:
   ```
   EMAIL_USER=your-gmail@gmail.com
   EMAIL_PASS=your-app-password
   ```

### For Render:
1. Go to your Render service dashboard
2. Navigate to "Environment" tab
3. Add these environment variables:
   ```
   EMAIL_USER=your-gmail@gmail.com
   EMAIL_PASS=your-app-password
   ```

## Gmail App Password Setup

1. **Enable 2-Factor Authentication** on your Gmail account
2. **Generate App Password**:
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Select "Mail" and "Other (Custom name)"
   - Name it "AgriGrow App"
   - Copy the generated 16-character password

3. **Use the App Password** as `EMAIL_PASS` (not your regular Gmail password)

## Testing the Setup

1. Deploy your changes
2. Test the feedback form at `/feedback.php`
3. Test the support form at `/support.php`
4. Check logs if emails don't send

## Troubleshooting

### Common Issues:
1. **"Email configuration not found"**: Environment variables not set
2. **"Failed to send email"**: Check Gmail app password and 2FA
3. **Composer autoloader issues**: Ensure `composer install` runs during deployment

### Debug Steps:
1. Check environment variables are set correctly
2. Verify Gmail app password is correct
3. Ensure 2FA is enabled on Gmail
4. Check deployment logs for PHP errors

## Files Modified:
- `src/feedback2.php` - Fixed PHPMailer implementation
- `src/support2.php` - Fixed PHPMailer implementation

## Next Steps:
1. Set environment variables in your deployment platform
2. Deploy the updated code
3. Test email functionality
4. Monitor logs for any issues
