#!/usr/bin/env bash
# exit on error
set -o errexit

# Install PHP dependencies
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Install Node dependencies
npm ci

# Build assets
npm run build

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

