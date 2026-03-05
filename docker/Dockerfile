FROM php:8.2-cli

RUN apt-get update \
    && apt-get install -y git unzip curl libzip-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN if [ -f composer.json ]; then composer install --no-interaction --prefer-dist; fi

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
