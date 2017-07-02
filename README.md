# Zout Apps Laravel-Backpack-Multiauth

[![StyleCI](https://styleci.io/repos/95968915/shield)](https://styleci.io/repos/95968915)
[![Build Status](https://travis-ci.org/zoutapps/laravel-backpack-multiauth.svg?branch=master)](https://travis-ci.org/zoutapps/laravel-backpack-multiauth)


Easy out of the box multi-auth in Laravel and in [backpackforlaravel.com](http://backpackforlaravel.com).

And with [laravel-permisson](https://github.com/spatie/laravel-permission) you can also create pseudo user models to provide authentication vie roles.


- `php artisan zoutapps:multiauth` Add new auth guard for multiauth in Laravel
- `php artisan zoutapps:roleauth` Add new User subclass model with global role scope to mimic MultiAuth with Roles from Spatie\Permission
- `php artisan zoutapps:backpackauth` Add new guard for Laravel-Backpack admin panel login

## What it does?
With one simple command you can setup multi/role auth for your Laravel 5.3 project.  
The package installs:
- Model 
- Migration 
- Controllers
- Notification
- Routes
  - routes/web.php
    - {guard}/login
    - {guard}/register
    - {guard}/logout
    - password reset routes
  - routes/{guard}.php
    - {guard}/home
- Middleware
- Views
- Guard
- Provider
- Password Broker
- Settings
- Scope

## Usage

### Step 1: Install Through Composer

`coming soon`

### Step 2: Add the Service Provider

You'll only want to use this package for local development, so you don't want to update the production `providers` array in `config/app.php`. Instead, add the provider in `app/Providers/AppServiceProvider.php`, like so:

```php
public function register()
{
	if ($this->app->environment() == 'local') {
		$this->app->register('ZoutApps\LaravelBackpackMultiAuth\AuthServiceProvider');
	}
}
```

### Step 3: Run the commands

By running the commands you can setup multi/role auth for laravel or switch to an new model for laravel-backpack.

```
php artisan zoutapps:multiauth {singular_lowercase_name_of_guard} -f

// Examples
php artisan zoutapps:multiauth admin -f
php artisan zoutapps:multiauth employee -f
php artisan zoutapps:multiauth customer -f
```

```
php artisan zoutapps:roleauth {singular_lowercase_name_of_guard} -f

// Examples
php artisan zoutapps:roleauth admin -f
php artisan zoutapps:roleauth employee -f
php artisan zoutapps:roleauth customer -f
```

```
php artisan zoutapps:backpackauth {singular_lowercasae_name_of_guard} -f

// Example
php artisan zoutapps:backpackauth administrator -f
```

**Notice:**
If you don't provide `-f` flag, it will not work. It is a protection against accidental activation.

**Alternative:**
If you want to install Multi-Auth files in a subdomain you must pass the option `--domain`.
```
php artisan multi-auth:install admin -f --domain
php artisan multi-auth:install employee -f --domain
php artisan multi-auth:install customer -f --domain
```

To be able to use this feature properly, you should add a key to your .env file:
```
APP_DOMAIN=yourdomain.com
```
This will allow us to use it in the routes file, prefixing it with the domain feature from Laravel routing system.  
Using it like so: `['domain' => '{guard}.' . env('APP_DOMAIN')]`.

### Step 4: Migrate the model (not neccessary if roleauth)

```
php artisan migrate
```

### Step 5: Try it

Go to: `http://url_to_your_project/guard/login`
Example: `http://project/admin/login`

## Options

If you don't want model and migration use `--model` flag.
```
php artisan multi-auth:install admin -f --model
```

If you don't want views use `--views` flag.
```
php artisan multi-auth:install admin -f --views
```

If you don't want routes in your `routes/web.php` file, use `--routes` flag.

```
php artisan multi-auth:install admin -f --routes
```

## Note
If you want to adapt the redirect path once your `guard` is logged out, add and override the following method in
your {guard}Auth\LoginController:

```php
/**
 * Get the path that we should redirect once logged out.
 * Adaptable to user needs.
 *
 * @return string
 */
public function logoutToPath() {
    return '/';
}
```

##Changelog

Please see [CHANGELOG](CHANGELOG.md) for mor information what was changed.

##Credits

- [Zout Apps](http://zoutapps.de)
- [Oliver Ziegler](https://github.com/OliverZiegler)
- [All Contributors](../../contributors)

This package was influenced by several Tutorials and walkthroughs for Laravel MultiAuth, spatie/laravel-permission 
and several [Laracasts](https://laracasts.com).  

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
