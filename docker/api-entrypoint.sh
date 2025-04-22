#!/bin/bash

set -e

# Install dependencies
composer install --no-interaction

# Wait for database to be ready
echo "⏳ Waiting for the database..."
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
  sleep 1
done

echo "✅ Database is ready!"

# Run migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Set permissions for Symfony
chown -R www-data var

# Enable the Apache rewrite module
a2enmod rewrite

# Start Apache in the foreground
exec apache2ctl -D FOREGROUND
