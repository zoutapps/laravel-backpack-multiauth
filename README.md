# Abandoned

As backpack improved the way we can use auth guards, middlewares and so on, laravel-backpack-multiauth will not get any more updates.


# Laravel-Backpack-Multiauth

[![StyleCI](https://styleci.io/repos/95968915/shield)](https://styleci.io/repos/95968915)
[![Build Status](https://travis-ci.org/zoutapps/laravel-backpack-multiauth.svg?branch=master)](https://travis-ci.org/zoutapps/laravel-backpack-multiauth)
[![Latest Stable Version](https://poser.pugx.org/zoutapps/laravel-backpack-multiauth/v/stable)](https://packagist.org/packages/zoutapps/laravel-backpack-multiauth)
[![Total Downloads](https://poser.pugx.org/zoutapps/laravel-backpack-multiauth/downloads)](https://packagist.org/packages/zoutapps/laravel-backpack-multiauth)
[![License](https://poser.pugx.org/zoutapps/laravel-backpack-multiauth/license)](https://packagist.org/packages/zoutapps/laravel-backpack-multiauth)

Easy out of the box multiauth in Laravel and in [backpackforlaravel.com](http://backpackforlaravel.com).

And with [laravel-permisson](https://github.com/spatie/laravel-permission) you can also create pseudo user models to provide authentication vie roles.

- `php artisan zoutapps:multiauth` Generates a new auth guard and sets everything up.
- `php artisan zoutapps:roleauth` Generates a user subclass with role and sets up corresponding guards.
- `php artisan zoutapps:backpack:multiauth`Swaps the default backpack auth model and guard with a newly created.
- `php artisan zoutapps:backpack:roleauth` Swaps the default backpack auth model and guard with a newly created role based.


## What it does?
With one simple command you can setup multi/role auth for your Laravel 5.4 project.  
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

As this package is currently only in beta and so there is no stable version you need to add `"zoutapps/laravel-backpack-multiauth": "dev-master"` to the `require-dev` section of your `composer.json` and perform `composer update`

### Step 2: Add the Service Provider

You'll only want to use this package for local development, so you don't want to update the production `providers` array in `config/app.php`. Instead, add the provider in `app/Providers/AppServiceProvider.php`, like so:

```php
public function register()
{
	if ($this->app->environment() == 'local') {
		$this->app->register(ZoutApps\LaravelBackpackAuth\AuthServiceProvider::class);
	}
}
```

### Step 3: Run the commands

By running the commands you can setup multi/role auth for laravel or switch to an new model for laravel-backpack.

```
php artisan zoutapps:multiauth {singular_lowercase_name_of_guard}

// Examples
php artisan zoutapps:multiauth admin
php artisan zoutapps:multiauth employee
php artisan zoutapps:multiauth customer
```

```
php artisan zoutapps:roleauth {singular_lowercase_name_of_guard}

// Examples
php artisan zoutapps:roleauth admin
php artisan zoutapps:roleauth employee
php artisan zoutapps:roleauth customer
```

```
php artisan zoutapps:backpack:multiauth {singular_lowercasae_name_of_guard}

// Example
php artisan zoutapps:backpack:multiauth administrator
```

```
php artisan zoutapps:backpack:roleauth {singular_lowercasae_name_of_guard} {exact_role_name}

// Example
php artisan zoutapps:backpack:multiauth administrator
```

**Notice:**  
You can provide the `-f` flag to force overwrite existing files. If you did not provide `-f` we will always ask you before overwriting.

**Alternatives:**
If you want to install multiauth files in a subdomain you must pass the option `--domain`.
```
php artisan zoutapps:multiauth admin --domain
php artisan zoutapps:multiauth employee --domain
php artisan zoutapps:multiauth customer --domain
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

If you want to see which files are generated or touched provide the `-v` flag.

If you don't want model and migration use `--model` flag.
```
php artisan zoutapps:multiauth admin --model
```

If you don't want views use `--views` flag.
```
php artisan zoutapps:multiauth admin --views
```

If you don't want routes in your `routes/web.php` file, use `--routes` flag.

```
php artisan zoutapps:multiauth admin --routes
```

## Note
If you want to adapt the redirect path once your `guard` is logged out, add and override the following method in
your `{guard}Auth\LoginController`:

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

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for mor information what was changed.

## Credits

- [Zout Apps](http://zoutapps.de)
- [Oliver Ziegler](https://github.com/OliverZiegler)
- [All Contributors](../../contributors)

This package was influenced by several Tutorials and walkthroughs for Laravel MultiAuth, spatie/laravel-permission 
and several [Laracasts](https://laracasts.com).  

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
