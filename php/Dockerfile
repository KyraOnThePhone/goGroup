# -------------------------
# Basis: PHP 8.4 mit Apache
# -------------------------
FROM php:8.4-apache

USER root

# -------------------------
# Systembibliotheken für PHP & MSSQL
# -------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    gnupg2 \
    ca-certificates \
    apt-transport-https \
    build-essential \
    libssl-dev \
    libzip-dev \
    zlib1g-dev \
    libxml2-dev \
    unixodbc \
    unixodbc-dev \
    autoconf \
    pkg-config \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# -------------------------
# Microsoft Repo einrichten (Debian 12 / Bookworm)
# -------------------------
RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > /etc/apt/trusted.gpg.d/microsoft.gpg \
    && echo "deb [arch=amd64] https://packages.microsoft.com/debian/12/prod bookworm main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools \
    && rm -rf /var/lib/apt/lists/*

# -------------------------
# PHP Extensions installieren
# -------------------------
RUN docker-php-ext-install mysqli pdo pdo_mysql zip xml opcache session

# -------------------------
# PECL SQL Server Extensions
# -------------------------
RUN pecl install sqlsrv-5.11.1 pdo_sqlsrv-5.11.1 \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# -------------------------
# Apache konfigurieren
# -------------------------
RUN a2enmod rewrite headers \
    && mkdir -p /var/www/html \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Directory Access konfigurieren über separate conf-Datei
RUN echo '<Directory /var/www/html>' > /etc/apache2/conf-available/zz-custom.conf \
    && echo '    Options Indexes FollowSymLinks' >> /etc/apache2/conf-available/zz-custom.conf \
    && echo '    AllowOverride All' >> /etc/apache2/conf-available/zz-custom.conf \
    && echo '    Require all granted' >> /etc/apache2/conf-available/zz-custom.conf \
    && echo '</Directory>' >> /etc/apache2/conf-available/zz-custom.conf \
    && a2enconf zz-custom

# -------------------------
# PHP.ini Anpassungen (Sessions)
# -------------------------
RUN echo "session.save_handler = files" >> /usr/local/etc/php/conf.d/00-session.ini \
    && echo "session.save_path = /tmp" >> /usr/local/etc/php/conf.d/00-session.ini \
    && echo "session.gc_maxlifetime = 1440" >> /usr/local/etc/php/conf.d/00-session.ini \
    && chmod 644 /usr/local/etc/php/conf.d/00-session.ini

# -------------------------
# Arbeitsverzeichnis & Ports
# -------------------------
WORKDIR /var/www/html
EXPOSE 80

# -------------------------
# Start
# -------------------------
CMD ["apache2-foreground"]


# Xdebug installieren
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Xdebug konfigurieren
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini