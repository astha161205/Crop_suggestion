# 🌾 AgriGrow - Crop Suggestion System

A comprehensive web application that helps farmers make informed decisions about crop selection based on soil type, weather conditions, and seasonal data. Built with PHP, MySQL, and modern web technologies.

## ✨ Features

### 🌱 Core Functionality
- **Crop Recommendation Engine** - AI-powered suggestions based on soil and weather data
- **Weather Integration** - Real-time weather data for accurate recommendations
- **User Authentication** - Secure login and registration system
- **Profile Management** - User profiles with application history

### 📧 Communication Features
- **Feedback System** - Users can submit feedback and suggestions
- **Support System** - Contact support with email notifications
- **Blog Platform** - Share agricultural insights and tips

### 🎨 User Experience
- **Dark Theme** - Modern, eye-friendly interface
- **Interactive UI** - Smooth animations and transitions
- **Accessibility** - Designed for all users

### 🛠️ Technical Features
- **PHP Mailer Integration** - Automated email notifications
- **Database Management** - MySQL with optimized queries
- **File Upload System** - Profile pictures and blog images
- **Security Features** - Input validation and SQL injection protection

## 🚀 Quick Start

### Prerequisites
- Docker and Docker Compose
- Git
- Modern web browser

### Local Development Setup

1. **Clone the repository**
   ```bash
   git clone <your-repo-url>
   cd Crop_suggestion
   ```

2. **Set up environment variables**
   ```bash
   # Copy the example environment file
   cp env.example .env
   
   # Edit .env with your credentials
   EMAIL_USER=your-gmail@gmail.com
   EMAIL_PASS=your-gmail-app-password
   WEATHER_API_KEY=your-weather-api-key
   GEMINI_API_KEY=your-gemini-api-key
   ```

3. **Start the application**
   ```bash
   docker-compose up --build
   ```

4. **Access the application**
   - Main app: http://localhost:8080
   - Test email config: http://localhost:8080/test_email_config.php

## 📋 Environment Variables

Create a `.env` file in the root directory with the following variables:

```env
# Email Configuration (Required for feedback/support)
EMAIL_USER=your-gmail@gmail.com
EMAIL_PASS=your-gmail-app-password

# API Keys (Required for weather and AI features)
WEATHER_API_KEY=your-weather-api-key
GEMINI_API_KEY=your-gemini-api-key

# Database Configuration (Auto-configured in Docker)
DB_HOST=db
DB_USER=root
DB_PASS=root
DB_NAME=crop
PORT=8080
```

## 🐳 Docker Deployment

### Local Docker Setup
```bash
# Build and start containers
docker-compose up --build

# Run in background
docker-compose up -d

# View logs
docker-compose logs -f

# Stop containers
docker-compose down
```

### Production Deployment

#### Railway Deployment
1. Connect your GitHub repository to Railway
2. Set environment variables in Railway dashboard:
   - `EMAIL_USER`
   - `EMAIL_PASS`
   - `WEATHER_API_KEY`
   - `GEMINI_API_KEY`
3. Deploy automatically

#### Render Deployment
1. Connect your GitHub repository to Render
2. Set environment variables in Render dashboard
3. Configure build command: `docker-compose up --build`
4. Deploy

## 📧 Email Setup

### Gmail Configuration
1. **Enable 2-Factor Authentication** on your Gmail account
2. **Generate App Password**:
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Select "Mail" and "Other (Custom name)"
   - Name it "AgriGrow App"
   - Copy the 16-character password
3. **Use the App Password** as `EMAIL_PASS` in your environment variables

### Testing Email Configuration
Visit `http://localhost:8080/test_email_config.php` to verify your email setup.

## 🗄️ Database Setup

The application uses MySQL for data storage. The database is automatically configured in Docker.

### Manual Database Setup (if needed)
```sql
-- Create database
CREATE DATABASE crop;

-- Import schema (if needed)
mysql -u root -p crop < src/setup_database.sql
```

## 📁 Project Structure

```
Crop_suggestion/
├── src/                    # Main application files
│   ├── index.php          # Homepage
│   ├── crop_recom.php     # Crop recommendation logic
│   ├── feedback.php       # Feedback form
│   ├── support.php        # Support system
│   ├── blog.php           # Blog platform
│   ├── config.php         # Database configuration
│   └── uploads/           # User uploads
├── photos/                # Static images
├── vendor/                # Composer dependencies
├── docker-compose.yml     # Docker configuration
├── Dockerfile            # Docker image definition
├── composer.json         # PHP dependencies
└── .env                  # Environment variables
```

## 🛠️ Development

### Adding New Features
1. Create feature branch: `git checkout -b feature/new-feature`
2. Implement changes
3. Test thoroughly
4. Submit pull request

### Code Style
- Follow PSR-12 coding standards
- Use meaningful variable names
- Add comments for complex logic
- Validate all user inputs

## 🐛 Troubleshooting

### Common Issues

#### Email Not Working
- Check Gmail 2FA is enabled
- Verify app password is correct
- Ensure environment variables are set
- Test with `/test_email_config.php`

#### Docker Issues
- **Line ending errors**: Run `dos2unix render-start.sh` (Linux/Mac)
- **Port conflicts**: Change port in `docker-compose.yml`
- **Permission errors**: Check file permissions

#### Database Connection Issues
- Verify database container is running
- Check environment variables
- Ensure database schema is imported

### Debug Steps
1. Check Docker logs: `docker-compose logs -f`
2. Verify environment variables are loaded
3. Test individual components
4. Check browser console for errors

## 📝 API Documentation

### Weather API
- **Endpoint**: `/weather.php`
- **Method**: GET
- **Parameters**: `location`, `api_key`
- **Response**: JSON weather data

### Crop Recommendation API
- **Endpoint**: `/crop_recom.php`
- **Method**: POST
- **Parameters**: `soil_type`, `weather`, `season`
- **Response**: JSON crop recommendations

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🙏 Acknowledgments

- Weather data provided by external APIs
- Crop recommendation algorithms based on agricultural research
- UI components inspired by modern web design principles

## 📞 Support

For support and questions:
- **Email**: [Your Support Email]
- **GitHub Issues**: [Repository Issues Page]
- **Documentation**: [Your Documentation URL]

---

**Made with ❤️ for the farming community**

