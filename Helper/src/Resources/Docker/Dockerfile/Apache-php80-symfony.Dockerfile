FROM php:8.0-apache as app
WORKDIR /var/www/app

ENV TZ=Europe/Amsterdam
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get -y update --fix-missing
RUN apt-get upgrade -y

# Install important libraries
RUN apt-get -y install --fix-missing apt-utils build-essential git curl libcurl4-openssl-dev zip
RUN docker-php-ext-install pdo_mysql

# Enable apache modules
RUN a2enmod rewrite headers

# Copy source files and config files to image.
COPY ./admin/app/ .
COPY ./config/ /

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y nano && pecl install xdebug
RUN docker-php-ext-enable xdebug

