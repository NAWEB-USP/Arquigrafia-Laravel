FROM php:5.6-apache

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt pcntl \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

####
RUN a2enmod rewrite
RUN service apache2 restart
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /app
COPY . /app
RUN COMPOSER_MEMORY_LIMIT=-1 composer install
CMD php artisan serve --host=0.0.0.0 --port=8181
EXPOSE 8181
####
#
# RUN docker-php-ext-install pdo_mysql
# RUN a2enmod rewrite
#
# RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
#
# COPY default.conf /etc/apache2/sites-enabled/000-default.conf
#
# WORKDIR /var/www/app
#
# COPY composer.json ./
#
#
#
# # CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
# # RUN COMPOSER_MEMORY_LIMIT=-1 composer install
# # CMD ["php", "artisan", "serve"]
#
# COPY . .
#
#
# RUN composer install
# CMD php artisan migrate --env=production --package=cmgmyr/messenger
# CMD php artisan serve --host=0.0.0.0 --port=80
#
# EXPOSE 80

# RUN service apache2 restart

# ADD . /var/www/app
# ADD ./public /var/www/html
