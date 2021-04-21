FROM php:7.4-cli
WORKDIR /var/www/app

ENV TZ=Europe/Amsterdam
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get -y update --fix-missing
RUN apt-get upgrade -y

# Install important libraries
RUN apt-get -y install --fix-missing apt-utils build-essential git curl libcurl4-openssl-dev zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy source files and config files to image.
COPY ./Helper/ .
