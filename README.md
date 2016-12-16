# PHP-Autoroute

The set-and-forget smart router script.

## How does it work?
Just from the route (URI) it will find and execute the appropriate controller and method.

## Requirements
Your controllers must be either included (i.e. using require_once), or you must have an autoload function registered (recommended). See http://php.net/manual/en/language.oop5.autoload.php for how to register and autoload classes.

## Example routes

Route: /admin/user-management/view-users
Executes: Admin\UserManagementController@viewUsers

Route: user/admin/test/home
Executes: User\Admin\TestController@home

## Usage

```php
// 1. Get the current route
$route = isset($_REQUEST['route']) ? $_REQUEST['route'] : '';

// 2. Autoroute
autoroute($route);
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

