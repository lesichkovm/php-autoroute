# PHP Autoroute

The "set-and-forget" smart router script.

## Installation ##

### A. Use composer (preferred) ###

```bash
composer require lesichkovm/php-autoroute
```

### B. Manually via composer ###

Add the following to your composer file:

```json
   "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/lesichkovm/php-autoroute.git"
        }
    ],
    "require": {
        "lesichkovm/php-autoroute": "dev-master"
    },
```

## How does it work?
Just from the route (URI) it will find and execute the appropriate controller and method. Pass the current route and autoroute will call the associated controller and method.

## Requirements
Your controller classes must be either included (i.e. using require_once), or you must have an autoload function registered (recommended). 

See http://php.net/manual/en/language.oop5.autoload.php for how to register and autoload classes.

## Example routes

Route: /admin/user-management/view-users

Executes: Admin\UserManagementController@viewUsers

Route: user/admin/test/home

Executes: User\Admin\TestController@home

## Example routes using request method

Route: /admin/user-management/view-users

Executes: Admin\UserManagementController@getViewUsers


Route: user/admin/test/home

Executes: User\Admin\TestController@getHome

## How to use

### Simple One Line Example ###

```php
autoroute($_SERVER['REQUEST_URI']);
```

### Advanced Options with Exception Catching ###

```php
// 1. Get the current route
$route = isset($_REQUEST['route']) ? $_REQUEST['route'] : '';

// 2. Autoroute
try {
    autoroute($route, [
        'default_method' => 'index',
        'default_controller' => 'Guest',
        'default_namespace' => 'App\\Controllers',
        'use_request_method' => true,
    ]);
} catch (ReflectionException $re) {
    // Page not found
    die('Not found:' . $re->controller . '@' . $re->method);
} catch (Exception $e) {
    // Other non routing related exception
    // Deal with exception (i.e. send yourself a mail)
    die('Exception occurred');
}
```

