# Stage 1: Use official PHP 8.2 with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies needed for Composer packages
RUN apt-get update && apt-get install -y \
    zip unzip git curl libonig-dev libxml2-dev && \
    rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 10000 (Render default)
EXPOSE 10000

# Start Apache
CMD ["apache2-foreground"]
