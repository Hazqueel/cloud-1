# Use the official PHP image with Apache
FROM php:8.0-apache

# Install necessary PHP extensions and MySQL client
RUN apt-get update && \
    apt-get install -y default-mysql-client && \
    docker-php-ext-install mysqli && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copy the custom Apache configuration
COPY ./src/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable the rewrite module
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy application files into the container
COPY ./src/ /var/www/html/

# Expose port 80
EXPOSE 80