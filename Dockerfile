# Use the official PHP image from the Docker Hub
FROM php:8.3-cli

# COPY READMEs/config_files/php-ini.production /code/READMEs/config_files/php-ini.production

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libmariadb-dev \
    default-mysql-client

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql sockets

# Copy the production php.ini file to the active configuration file
# RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Ensure the extension directory is correctly set in php.ini
RUN echo "extension_dir=$(php-config --extension-dir)" >> /usr/local/etc/php/php.ini

# Enable the mysqli extension in php.ini
# RUN echo "extension=mysqli.so" >> /usr/local/etc/php/php.ini

# ENTRYPOINT [ "php", "-S", "0.0.0.0:8000", "-t", "/app/public" ]