# PHP-Autoroute

The set-and-forget smart router script.

## Installation ##
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

## Usage

```php
// 1. Get the current route
$route = isset($_REQUEST['route']) ? $_REQUEST['route'] : '';

// 2. Autoroute
autoroute($route,['use_request_method' => true]);
```

## Example Usage with Exception Catching

```php
// 1. Get the current route
$route = isset($_REQUEST['route']) ? $_REQUEST['route'] : '';

// 2. Autoroute
try {
    autoroute($route);
} catch (\Eception $e) {
    // Deal with exception (i.e. send yourself a mail)
}
```

