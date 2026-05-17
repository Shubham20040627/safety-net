FROM php:8.2-apache

# Install system dependencies and tools
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    gnupg

# Install Node.js & NPM (for compiling Vite assets)
RUN mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
    && apt-get update \
    && apt-get install nodejs -y

# Clear package manager cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions (including pdo_pgsql for your Render database!)
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Enable Apache rewrite engine for Laravel routes
RUN a2enmod rewrite

# Configure Apache Document Root to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory inside the container
WORKDIR /var/www/html

# Copy all project files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Run Composer installation for production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install npm dependencies and build minified production maps/assets
RUN npm install && npm run build

# Setup correct permissions for Laravel directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose web port
EXPOSE 80

# Auto-run migrations, database seeds, and start Apache server
CMD php artisan migrate --force && php artisan db:seed --force && apache2-foreground
