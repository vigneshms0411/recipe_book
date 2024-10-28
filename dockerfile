# Use the official PHP image with Apache
FROM php:8.2-apache

# Copy your PHP frontend to the Apache web root
COPY . /var/www/html/

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# Install necessary PHP extensions for MongoDB
RUN docker-php-ext-install mysqli && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Expose web server port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
