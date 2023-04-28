<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## How to run

- Run composer install.
- Setup .env 
    - DB_DATABASE
    - DB_USERNAME
    - DB_PASSWORD 
    - MIDTRANS_MERCHAT_ID
    - MIDTRANS_CLIENT_KEY
    - MIDTRANS_SERVER_KEY
- Run php artisan migrate --seed.
- Then run 'php artisan serve' | http:127.0.0.1:8000.

## How to run Collection API
- Postman
    - Open Postman.
    - Open menu collection -> import 'thunder-collection_Collection API_postman.json' -> files.
    - Open menu environment -> import 'thunder-environment_Trenteran_postman.json' -> files.

## How to run Unit Test
- Run 'php artisan test'.

## Setup Hook Midtrans
- Install ngrok | <a href="https://ngrok.com/download" target="_blank">Download Ngrok</a>
- After finish install, open terminal then enter 'ngrok http 8000'. 
- Open midtrans dashboard, then open menu settings -> configuration. | <a href="https://dashboard.sandbox.midtrans.com/settings/vtweb_configuration" target="_blank">Midtrans setting - configuration</a>
- Enter url on 'Payment Notification Url*' from result url ngrok. And after url from ngrok add your route. For my route api '/api/midtrans/web-notif-hook'.

## Test VA Bank Midtrans
- Open link  <a href="https://simulator.sandbox.midtrans.com/bca/va/index" target="_blank">Test VA Bank Midtrans</a>
- Enter VA number -> Inquire -> then pay for that.

## Test VA support: 
  - BCA, BNI, BRI.


