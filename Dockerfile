# PHP + Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions for MySQL and email sending
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy source code to Apache directory
COPY src/ /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Ensure .env is copied (optional if you mount it)
COPY .env /var/www/html/.env

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Expose Apache port
EXPOSE 80
