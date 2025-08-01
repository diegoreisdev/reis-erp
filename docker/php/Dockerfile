FROM php:8.1-fpm

# Instala dependências necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    lsb-release \
    ca-certificates \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

# Configurar e instalar extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Instalar extensões PHP necessárias para CI3
RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \
    zip \
    exif \
    gd \
    mbstring \
    xml

# Instalar Node.js LTS
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário www não-root
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

# Diretório de trabalho
WORKDIR /var/www

# Copiar projeto com permissões adequadas
COPY --chown=www:www . /var/www

# Configurar permissões específicas do CodeIgniter 3
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 777 /var/www/application/cache \
    && chmod -R 777 /var/www/application/logs \
    && chmod -R 755 /var/www/uploads 2>/dev/null || true

# Configurações do PHP para desenvolvimento
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini

EXPOSE 9000

USER www

CMD ["php-fpm"]