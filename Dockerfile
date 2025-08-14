# PHP + Apache
FROM php:8.2-apache

# Enable Apache modules
RUN a2enmod rewrite headers

# PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Composer (safe if you don't use it)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Start script that binds Apache to $PORT (Render/Railway)
COPY docker/apache-run.sh /usr/local/bin/apache-run.sh
RUN chmod +x /usr/local/bin/apache-run.sh

WORKDIR /var/www/html

# Install PHP deps if composer.json exists (won't fail if it doesn't)
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader || true

# Copy your app (your PHP lives in src/)
COPY src/ ./

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Default expose (local); platform will pass $PORT anyway
EXPOSE 8080
CMD ["apache-run.sh"]
