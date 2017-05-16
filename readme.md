# Fit2u

## Требования (Requirements)

* PHP 5.6+ (`pdo_mysql`, `zip`, `gd`, `mcrypt`, TODO: описать все)
* PHP Composer
* Laravel 5.4

## Развертывание на сервере (Deploy, TODO: ...)

### Nginx

```
server {
  index index.php index.html;
  server_name mydomain;

  error_log /var/log/nginx/error.log;
  access_log /var/log/nginx/access.log;

  root /code/public;

  location / {
    try_files $uri $uri/ /index.php?$query_string;
  }

  location ~ \.php$ {
    try_files $uri =404;
    
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_pass php:9000;
    fastcgi_index index.php;

    include fastcgi_params;

    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_param PATH_INFO $fastcgi_path_info;
  }
}
```

### Первый запуск (aka Cold deploy)

Статические ресурсы (`Assets`) должны быть скомпилированы/перекомпилированы в локальном окружении с флагом `--production` и закоммичены в репозиторий. На сервере сборка статических ресурсов производиться не будет, поэтому можно не вызывать команду `npm install`(установка `node_modules`) на сервере.
`Composer` по-умолчанию устанавливает пакеты из секции `require-dev`, поэтому на сервере следует отдельно вызывать `composer install` с флагом `--no-dev`.

```
$ git clone ... project
$ cd project
$ composer install --no-dev
$ php artisan key:generate
```

TODO: Создание и настройка БД:

```
$ mv .env.example .env
$ mysql
 > CREATE DATABASE ...;
$ php artisan migrate
// TODO: $ php artisan db:seed --class=ProductionSeeder
```

### Непрерывная интеграция (Continuous Integration aka CI)

Последующие изменения в коде "подтягиваются" таким образом:

```
$ git pull origin master
$ composer update
$ php artisan migrate
$ php artisan db:seed --class=...
```
