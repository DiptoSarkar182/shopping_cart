FROM richarvey/nginx-php-fpm:1.7.2

# Copy application files
COPY . /var/www/html
COPY conf/nginx/nginx-site.conf /etc/nginx/sites-enabled/default

# Set working directory
WORKDIR /var/www/html

# Install Composer dependencies during build
RUN composer install --no-dev --optimize-autoloader

# Image config
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Run the startup script
CMD ["/start.sh"]
