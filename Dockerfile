FROM php:8.2-apache

# Install PHP extensions
RUN apt-get update && apt-get install -y libzip-dev unzip \
 && docker-php-ext-install mysqli pdo pdo_mysql zip \
 && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set Apache document root to /var/www/html/src
ENV APACHE_DOCUMENT_ROOT=/var/www/html/src
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

# Copy app files into container
COPY . /var/www/html

# Set correct ownership
RUN chown -R www-data:www-data /var/www/html

# Start Apache with the correct port
COPY render-start.sh /usr/local/bin/render-start.sh
RUN chmod +x /usr/local/bin/render-start.sh

CMD ["bash", "-lc", "/usr/local/bin/render-start.sh"]
