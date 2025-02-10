FROM php:8.2-apache

# Install PHP extensions for MySQL support
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory for the application
WORKDIR /var/www

# Copy public files to Apache's web root
COPY ./html /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose Apache port
EXPOSE 80
