FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libsqlite3-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install dependencies
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Install Node dependencies and build assets
RUN npm ci && npm run build

# Set permissions
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache /var/www/database

# Expose port (Render uses PORT env variable)
EXPOSE ${PORT:-8080}

# Start script
CMD mkdir -p /var/www/database && \
    touch /var/www/database/database.sqlite && \
    chmod 666 /var/www/database/database.sqlite && \
    php artisan config:clear && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
