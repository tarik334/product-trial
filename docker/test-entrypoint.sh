#!/bin/bash

set -e

# Install dependencies
composer install --no-interaction

# Set permissions for Symfony
chown -R www-data var

# Enable the Apache rewrite module
a2enmod rewrite

# Start Apache in the foreground
exec apache2ctl -D FOREGROUND
