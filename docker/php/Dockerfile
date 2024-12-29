FROM php:8.3

LABEL fr.asso.ort.lyon.authors="Billy VILLENA <billy.villena@lyon.ort.asso.fr>"

HEALTHCHECK CMD ["php", "-v"]

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y \
      git \
      libpq-dev \
      libzip-dev \
      unzip \
    && docker-php-ext-install pdo_pgsql zip \
    && apt-get clean

WORKDIR /app

# Define user to prevent root-user; set host current user gid and uid to bind both users for better permissions
RUN addgroup --gid 1001 app \
    && adduser --disabled-password --uid 1001 --gid 1001 app \
    && chown -R app /app

USER app

# Dependencies
COPY --chown=app:app ./composer.json ./composer.lock ./

RUN composer install

# Image ready!
