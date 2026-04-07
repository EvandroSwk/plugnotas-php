FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer update --no-interaction --prefer-dist --no-scripts

COPY . .

CMD ["vendor/bin/phpunit"]
