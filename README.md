<p align="center"><img src="https://user-images.githubusercontent.com/1066486/95141713-2d250880-0772-11eb-9ee8-bc07c11628d0.png" width="512"></p>
<p align="center">
    <a href="https://github.com/Activisme-be/ragnarok-docs">Documentation</a>
    <strong>|</strong>
    <a href="https://github.com/Activisme-be/Ragnarok/graphs/contributors">Contributors</a>
</p>
   
## About Ragnarok Boilerplate

The Ragnarok boilerplate is a quick starter template written in the DDD approach for all your project ideas. 
The boilerplate contains the following features: 

- Announcements
- User Management
- Role Management
- Profile Settings
- Log overview
- API key management

## Synchronization 

Currently the Ragnarok boilerplate is synchronized with the Laravel 8.2.0 starter template. 

## How To Use

This project is using [Laravel 8.*](https://laravel.com/docs/8.x) and Database MySQL.
Several things need to setup like creating the key, setup the environment database login, etc.

Here is how to use this project step by step:
- After you clone this repo, don't forget to do (Make sure you install composer first, refer [here](https://getcomposer.org)):
```composer install```
- Setup your environment, copy the .env.example to .env, and filling those credential database.
- After you do the install composer, do the following ```php artisan key:generate```
- Then do migrate and seeding the database with ```php artisan migrate:refresh --seed```
- After you done those steps, time to serving the laravel by using ```php artisan serve```
- Then go to your browser (by default it would using port 8000), so you can access with ```localhost:8000/home```

## Default Login Credentials

There is some login credential that currently we can use based on:

```
U: webmaster@domain.tld
P: password

U: developer@domain.tld
P: password

U: user@domain.tld
P: password
```

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Tim Joosten via [topairy@gmail.com](mailto:topairy@gmail.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework and Ragnarok is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
