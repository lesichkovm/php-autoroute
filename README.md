# PHP-Autoroute

This is a set-and-forget smart router script. 

## How it works?
Just from the route (URI) it will find and execute the appropriate controller and method.

## Example routes

Route: user/admin/test/home
Executes: User\Admin\TestController@home

# Usage
```php
// 1. Get the current route
$route = isset($_REQUEST['route']) ? $_REQUEST['route'] : '';

// 2. Autoroute
try {
    autoroute($route);
} catch (\Eception $e) {
    // Deal with exception
}
```
