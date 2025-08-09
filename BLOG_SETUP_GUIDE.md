# Blog Feature Setup Guide

## Overview
The blog feature allows users to write and publish their own blogs on the AgriGrow platform. New blogs appear at the top of the blog list, and users can read full articles by clicking on them.

## Features
- ✅ Write and publish your own blogs
- ✅ Blog title, cover image, content, and tags
- ✅ Latest blogs appear on homepage
- ✅ Full blog reading experience
- ✅ Social media sharing
- ✅ Responsive design
- ✅ User authentication required for writing

## Setup Instructions

### 1. Database Setup
First, run the blog database setup script:

1. Open your web browser
2. Navigate to: `http://localhost/Crop_suggestion/src/setup_blog_database.php`
3. This will create the `blogs` table and insert sample data

### 2. Database Structure
The `blogs` table includes:
- `id`: Unique blog identifier
- `title`: Blog title (required)
- `cover_image_url`: URL to cover image (required)
- `content`: Blog content (required)
- `tags`: Comma-separated tags
- `author_id`: Reference to user who wrote the blog
- `author_name`: Display name of the author
- `created_at`: Publication date
- `status`: Published or draft status

### 3. Files Created/Modified

#### New Files:
- `src/blog.php` - Main blog listing page
- `src/write_blog.php` - Blog writing form
- `src/view_blog.php` - Individual blog reading page
- `src/blog_database.sql` - Database schema
- `src/setup_blog_database.php` - Setup script

#### Modified Files:
- `src/homePage.php` - Updated to show latest blogs from database

## How to Use

### For Users:

#### Writing a Blog:
1. Login to your account
2. Go to the Blog page
3. Click "Write Your Own Blog" button
4. Fill in:
   - **Blog Title**: Your blog's title
   - **Cover Image URL**: URL to an image (you can use services like Imgur)
   - **Content**: Your blog content (supports line breaks)
   - **Tags**: Comma-separated tags (optional)
5. Click "Publish Blog"

#### Reading Blogs:
1. View latest blogs on the homepage
2. Click "Read More" to read full articles
3. Use social sharing buttons to share blogs

### For Administrators:

#### Managing Blogs:
- All blogs are stored in the `blogs` table
- You can manually edit blogs in the database
- Blogs are ordered by creation date (newest first)

## Image Guidelines

### Cover Images:
- Use high-quality images (recommended: 1200x630 pixels)
- Supported formats: JPG, PNG, WebP
- You can use image hosting services like:
  - Imgur (https://imgur.com)
  - ImgBB (https://imgbb.com)
  - Or any public image URL

### Example Image URLs:
```
https://i.imgur.com/example.jpg
https://example.com/image.png
../photos/home/your-image.jpg
```

## Content Guidelines

### Writing Tips:
- Start with a compelling introduction
- Use clear, simple language
- Include practical tips and examples
- Break up long paragraphs
- Add relevant images
- End with actionable takeaways

### Content Formatting:
- Use line breaks for paragraphs
- Use bullet points (•) for lists
- Keep paragraphs short and readable
- Use headings to organize content

## Troubleshooting

### Common Issues:

#### Database Connection Error:
- Ensure MySQL is running
- Check database credentials in PHP files
- Verify the `crop` database exists

#### Images Not Loading:
- Check image URL is accessible
- Ensure image URL is public
- Try uploading to an image hosting service

#### Permission Issues:
- Ensure users are logged in to write blogs
- Check session management
- Verify user authentication

### Error Messages:
- **"Blog title is required"**: Fill in the title field
- **"Cover image URL is required"**: Provide a valid image URL
- **"Blog content is required"**: Add content to your blog

## Security Features

- User authentication required for writing blogs
- Input validation and sanitization
- SQL injection protection
- XSS protection through htmlspecialchars()
- Session-based user management

## Customization

### Styling:
- All styling uses Tailwind CSS classes
- Colors can be modified in the CSS files
- Responsive design for mobile devices

### Functionality:
- Add more fields to the blog form
- Implement blog categories
- Add comment system
- Create blog search functionality
- Add blog editing capabilities

## Support

If you encounter any issues:
1. Check the error messages
2. Verify database setup
3. Ensure all files are in the correct locations
4. Check file permissions
5. Review the troubleshooting section above

## Future Enhancements

Potential improvements:
- Blog categories and filtering
- Comment system
- Blog editing for authors
- Blog search functionality
- Blog analytics
- Email notifications for new blogs
- Blog approval system for admins

