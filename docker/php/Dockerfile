FROM php:7.4-cli

ARG WWWGROUP=1000

WORKDIR /var/www/html

ENV TZ=Asia/Shanghai

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN cp /etc/apt/sources.list /etc/apt/sources.list.bak
RUN echo "deb http://mirrors.aliyun.com/debian/ bullseye main non-free contrib" > /etc/apt/sources.list
RUN echo "deb-src http://mirrors.aliyun.com/debian/ bullseye main non-free contrib" >> /etc/apt/sources.list
RUN echo "deb http://mirrors.aliyun.com/debian-security/ bullseye-security main" >> /etc/apt/sources.list
RUN echo "deb-src http://mirrors.aliyun.com/debian-security/ bullseye-security main" >> /etc/apt/sources.list
RUN echo "deb http://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib" >> /etc/apt/sources.list
RUN echo "deb-src http://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib" >> /etc/apt/sources.list
RUN echo "deb http://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib" >> /etc/apt/sources.list
RUN echo "deb-src http://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib" >> /etc/apt/sources.list
RUN apt-get clean

RUN apt-get update
RUN apt-get install -y vim
RUN apt-get install -y wget
RUN apt-get install -y libpq-dev
RUN apt-get install -y zlib1g-dev
RUN apt-get install -y libpng-dev
RUN apt-get install -y libjpeg-dev
RUN apt-get install -y libfreetype6-dev
RUN apt-get install -y libmemcached-dev
RUN apt-get install -y libc-client-dev
RUN apt-get install -y libkrb5-dev
RUN apt-get install -y libzip-dev
RUN apt-get install -y libxml2-dev
RUN apt-get install -y libldap2-dev
RUN apt-get install -y imagemagick
RUN apt-get install -y libmagickwand-dev
RUN apt-get install -y supervisor

RUN docker-php-ext-install pgsql
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install gd
RUN pecl install memcached && docker-php-ext-enable memcached
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap
RUN docker-php-ext-install mysqli pdo_mysql
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install soap
RUN docker-php-ext-install intl
RUN pecl install pcov && docker-php-ext-enable pcov
RUN pecl install msgpack && docker-php-ext-enable msgpack
RUN pecl install igbinary && docker-php-ext-enable igbinary
RUN docker-php-ext-install ldap
RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN docker-php-ext-install pcntl
RUN pecl install imagick && docker-php-ext-enable imagick
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
RUN apt-get -y autoremove
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN groupadd --force -g $WWWGROUP laravel
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 laravel

COPY php.ini $PHP_INI_DIR/conf.d/99-laravel.ini
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000

ENTRYPOINT ["start-container"]