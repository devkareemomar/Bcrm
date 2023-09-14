# Bevatel Master System;
> under development

backend for the new bevatel ecosystem . 

- CMS to manage customers' websites
- CRM to manage companies' customers and relationships
- OmniChannel Platform to manage different messaging channels
- SMS Dashboard to manage text sms and campaigns through different providers


## Developing

- ### Built With
    - PHP 8 
    - LARAVEL 9

- ### Packages

    - maatwebsite/excel 3.1
    - nwidart/laravel-modules 9
    - santigarcor/laratrust 7
    - tymon/jwt-auth

- ### Documentation api 
    - <a href="https://documenter.getpostman.com/view/9995081/VV4zRw7N" target="_blank">Go Documentation</a>



## Prerequisites
you must install apfd extension for php or multipart request won't work with PUT requests

1- install php-pear
```
sudo apt-get install php-pear
```

2- install php8-dev
```
sudo apt-get install php8-dev
```

3- install apfd php extension
```
sudo pecl install apfd
```

4- edit php.ini file
```
sudo nano /etc/php/8.0/apache2/php.ini 
```

5- add this line and save file
```
extension=apfd.so
```
you must run seeder in all system and run seeder for cms module

1- run general seeder
```
php artisan db:seed
```
1- run seeder cms module
```
php artisan module:seed Cms
```



## Setting up Dev

