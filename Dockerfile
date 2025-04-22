FROM php:8.3-apache

# Install required extensions
RUN apt-get update && apt-get install -y apt-utils git sudo nano zip curl
RUN docker-php-ext-install opcache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Setup working directory (actual code is mounted in volumes)
WORKDIR /var/www/html

# Make sure both entrypoints are executable
COPY docker/api-entrypoint.sh /api-entrypoint.sh
COPY docker/test-entrypoint.sh /test-entrypoint.sh
RUN chmod +x /api-entrypoint.sh /test-entrypoint.sh

# Port expose
EXPOSE 80
