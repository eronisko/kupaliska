FROM php:8.0.6-fpm-alpine3.13
COPY --from=composer:2.0.13 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install

RUN cp .env.example .env \
    && php artisan key:generate

RUN touch database/kupaliska.sqlite \
    && php artisan migrate

CMD php artisan serve --host 0.0.0.0 --port 8000
