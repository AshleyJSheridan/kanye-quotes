# Kanye Quotes App

## About

Test application acting as a multi-call proxy to https://api.kanye.rest/ with user registration and authentication.

## Requirements

* PHP
* MySQL

## Installation

* Clone repo.
* Install dependencies with `composer install`.
* Edit your local `.env` file to point to the correct MySQL DB locally (copy the `.env.example` template to `.env` first.)
* Run migrations with `php artisan migrate`.

## Usage

### Running Project

In order to use the app, you will need to first run the project. You can do this either by setting it up in  a web server running locally (like Apache or Nginx), or run it via the local built-in PHP server:

```
php artisan serve
```

### Creating Users

Then create a new user:

```
curl -X POST http://localhost:8000/api/user/register -H "Content-Type: application/json" -d '{"name": "Test","email": "test@test.com","password": "CorrectHorseBatteryStaple"}'
```

### Logging In/Out

Once the user is created, you can log in via:

```
 curl -X POST http://localhost:8000/api/user/login -H "Content-Type: application/json" -d '{"email": "test@test.com","password": "CorrectHorseBatteryStaple"}'
```

This will return a token that you will use in logout and quotes calls.

To log out:

```
curl -X POST http://localhost:8000/api/user/logout -H "Authorization: Bearer <TOKEN>"
```

### Fetching Quotes

To fetch 5 random quotes, call the quotes endpoint as a GET request:

```
curl -X GET http://localhost:8000/api/quotes -H "Authorization: Bearer <TOKEN>"
```

These quotes will be cached against the token for a period of 30 minutes. You can force new quotes to be fetched with the refresh endpoint called as a POST:

```
curl -X POST localhost:8000/api/quotes/refresh -H "Authorization: Bearer <TOKEN>>"
```

## Tests

There are tests covering the API endpoints, which can be run with:

```
php artisan test
```

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
