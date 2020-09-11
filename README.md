<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Website unionp

-   [UnionPartners](http://www.unionp.kz/).

## Technologies used

Backend
Php 7.4
Laravel 7
Mysql
Queue(worker)
Rabbitmq
STMP
SMS

Frontend
Javascript
vuejs
ajax
jquery
css3
html5
oneui

## Composer

-   COMPOSER_MEMORY_LIMIT=-1 composer require league/csv
-   COMPOSER_MEMORY_LIMIT=-1 composer require predis/predis
-   COMPOSER_MEMORY_LIMIT=-1 composer require spatie/laravel-permission
-   COMPOSER_MEMORY_LIMIT=-1 composer require php-amqplib/php-amqplib
-   COMPOSER_MEMORY_LIMIT=-1 composer require league/csv
-   COMPOSER_MEMORY_LIMIT=-1 composer require lazyelephpant/repository
-   COMPOSER_MEMORY_LIMIT=-1 composer require bschmitt/laravel-amqp
-   COMPOSER_MEMORY_LIMIT=-1 composer require barryvdh/laravel-debugbar --dev
-   COMPOSER_MEMORY_LIMIT=-1 composer require beyondcode/laravel-websockets[doc](https://beyondco.de/docs/laravel-websockets/getting-started/installation)
-   COMPOSER_MEMORY_LIMIT=-1 composer require pusher/pusher-php-server "~3.0"

## Error

-   memory_limit /etc/php/php.ini memory_limit=512M
-   redis install redis

-   '\*\/1 \* \* \* \* flock -n /tmp/bot-cron.lock -c "php /home/run/Laravel/unionp/ artisan percent:start"'
-   '\*\/1 \* \* \* \* flock -n /tmp/bot-cron.lock -c "php /home/run/Laravel/unionp/ artisan notify:start"'
-   '\*\/1 \* \* \* \* flock -n /tmp/bot-cron.lock -c "php /home/run/Laravel/unionp/ artisan payment:start"'
