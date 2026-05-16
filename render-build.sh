#!/usr/bin/env bash
# exit on error
set -o errexit

composer install --no-dev --optimize-autoloader

# Install and build frontend assets
npm install
npm run build

# Run database migrations
# The --force flag is required for production
php artisan migrate --force
