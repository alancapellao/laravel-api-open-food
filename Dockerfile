FROM php:8.2-apache

# Variáveis de ambiente
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APACHE_LOG_DIR /var/log/apache2

# Configuração do Apache
RUN a2enmod rewrite

# Configuração do PHP
RUN apt-get update -y && apt-get upgrade -y

# Instalação de dependências
RUN apt-get install -y \
  libzip-dev \
  cron \
  nano \
  zip \
  && docker-php-ext-install zip

# Instalação do GD
RUN apt-get update && apt-get install -y \
  libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd

# Instalação do PDO
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Instalação do Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
  --install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer 
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php -r "unlink('composer-setup.php');"

# Configuração do Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Informa o diretório de trabalho
WORKDIR /var/www/html

# Exposição da porta
EXPOSE 80