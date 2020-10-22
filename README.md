<p align="center"><img src="https://laravel.com/img/logotype.min.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Project

About our project.

## Website unionp

-   [UnionPartners](http://www.unionp.kz/).

## Technologies used

```bash
#  Backend
-   Php 7.4
-   Laravel 7
-   Mysql
-   Queue(worker)
-   Rabbitmq
-   Socket.io
-   Workerman
-   STMP
-   SMS

#   Frontend
-   Javascript
-   vuejs
-   ajax
-   jquery
-   css3
-   html5
-   oneui
```

## Composer

-   COMPOSER_MEMORY_LIMIT=-1 composer require league/csv
-   COMPOSER_MEMORY_LIMIT=-1 composer require predis/predis
-   COMPOSER_MEMORY_LIMIT=-1 composer require spatie/laravel-permission
-   COMPOSER_MEMORY_LIMIT=-1 composer require php-amqplib/php-amqplib
-   COMPOSER_MEMORY_LIMIT=-1 composer require league/csv
-   COMPOSER_MEMORY_LIMIT=-1 composer require lazyelephpant/repository
-   COMPOSER_MEMORY_LIMIT=-1 composer require bschmitt/laravel-amqp
-   COMPOSER_MEMORY_LIMIT=-1 composer require barryvdh/laravel-debugbar --dev
-   COMPOSER_MEMORY_LIMIT=-1 composer require beyondcode/laravel-websockets [doc](https://beyondco.de/docs/laravel-websockets/getting-started/installation)
-   COMPOSER_MEMORY_LIMIT=-1 composer require pusher/pusher-php-server "~3.0"

## Error

```bash
memory_limit /etc/php/php.ini memory_limit=512M
redis install redis

*/1 * * * * flock -n /tmp/bot-cron.lock -c "php /home/run/Laravel/unionp/artisan queue:work"
30 08 * * * flock -n /tmp/bot-cron.lock -c "php /home/run/Laravel/unionp/artisan start:sms"
```
