# Use the official PHP 8.1 Alpine image as the base image
FROM php:8.1-fpm-alpine

# Install required packages for SQL Server support
RUN apk --no-cache add \
    unixodbc unixodbc-dev freetds freetds-dev && \
    ln -s /usr/lib/libodbcsdk.so /usr/lib/libodbcsdk_r.so && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS unixodbc-dev && \
    pecl install pdo_sqlsrv sqlsrv && \
    docker-php-ext-enable pdo_sqlsrv sqlsrv && \
    apk del .build-deps && \
    rm -rf /tmp/pear

# Install other necessary packages (nginx, supervisor, etc.)
RUN apk --no-cache add \
    curl \
    nginx \
    supervisor

# Configure nginx
COPY config/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY config/fpm-pool.conf /etc/php81/php-fpm.d/www.conf
COPY config/php.ini /etc/php81/conf.d/custom.ini

# Configure supervisord
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup document root
WORKDIR /var/www/html

# Make sure files/folders needed by the processes are accessible when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html /run /var/lib/nginx /var/log/nginx

# Switch to use a non-root user from here on
USER nobody

# Expose the port nginx is reachable on
EXPOSE 8080

# Start supervisor to run nginx and php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up & running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/health
