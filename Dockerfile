# Apache + PHP
FROM php:8.2-apache

# System libs + PHP extensions you’ll likely need
RUN apt-get update && apt-get install -y git unzip libzip-dev \
 && docker-php-ext-install mysqli pdo pdo_mysql zip \
 && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite (pretty URLs)
RUN a2enmod rewrite

# Use /src as the web root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/src
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

# Copy app code
COPY . /var/www/html

# Install Composer in the image and install PHP deps (if composer.json exists)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
RUN test -f composer.json && composer install --no-dev --optimize-autoloader || true

# Correct permissions
RUN chown -R www-data:www-data /var/www/html

# Render sets $PORT. Make Apache listen on it.
COPY render-start.sh /usr/local/bin/render-start.sh
RUN chmod +x /usr/local/bin/render-start.sh

CMD ["bash", "-lc", "/usr/local/bin/render-start.sh"]
