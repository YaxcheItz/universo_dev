#!/usr/bin/env bash

# Create storage link if it doesn't exist
php artisan storage:link

# Start PHP server
php artisan serve --host=0.0.0.0 --port=$PORT
