#!/bin/sh
set -e

composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist

# TODO: !!!

# Run Laravel migrations
# -----------------------------------------------------------
# Ensure the database schema is up to date.
# -----------------------------------------------------------
#php artisan migrate --force

# Clear and cache configurations
# -----------------------------------------------------------
# Improves performance by caching config and routes.
# -----------------------------------------------------------
#php artisan config:cache
#php artisan route:cache

# Run the default command
exec "$@"
