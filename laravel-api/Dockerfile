# Use PHP 8.4 FPM as base image
FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create startup script
RUN echo '#!/bin/bash\n\
# Wait for database to be ready\n\
echo "Waiting for database..."\n\
sleep 10\n\
\n\
# Always generate OAuth keys (force regeneration)\n\
echo "Generating OAuth keys..."\n\
cd /var/www/html\n\
php artisan passport:keys --force\n\
echo "OAuth keys generated successfully"\n\
\n\
# Verify OAuth keys exist\n\
if [ -f storage/oauth-private.key ] && [ -f storage/oauth-public.key ]; then\n\
    echo "OAuth keys verified successfully"\n\
    ls -la storage/oauth-*.key\n\
else\n\
    echo "ERROR: OAuth keys not found!"\n\
    exit 1\n\
fi\n\
\n\
# Generate API documentation with correct URL\n\
echo "Generating API documentation..."\n\
if [ ! -z "$APP_DOC_URL" ]; then\n\
    echo "Using APP_DOC_URL: $APP_DOC_URL"\n\
    # Replace the placeholder in apiblueprint.apib\n\
    sed -i.bak "s|{{APP_DOC_URL}}|$APP_DOC_URL|g" docs/api/apiblueprint.apib\n\
    # Generate the documentation\n\
    apibmerge docs/api/blueprint docs/api/apiblueprint.apib\n\
    aglio --theme-variables flatly --theme-template triple -i docs/api/blueprint/apidocs.apib -o resources/views/apidocs.blade.php\n\
    # Restore the original file\n\
    mv docs/api/apiblueprint.apib.bak docs/api/apiblueprint.apib\n\
    echo "API documentation generated with URL: $APP_DOC_URL"\n\
else\n\
    echo "APP_DOC_URL not set, using default URL"\n\
    composer api-docs\n\
fi\n\
\n\
# Run database migrations\n\
echo "Running database migrations..."\n\
php artisan migrate --force\n\
\n\
# Start supervisor\n\
echo "Starting supervisor..."\n\
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 80
EXPOSE 80

# Start application with migrations
CMD ["/usr/local/bin/start.sh"]
