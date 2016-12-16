# PHP-Autoroute

This is a set-and-forget smart router script. Just from the route (URI) it will find and execute the appropriate controller and method.

# Usage

// 1. Get the current route
$route = isset($_REQUEST['route']) ? $_REQUEST['route'] : '';

// 2. Autoroute
try {
    autoroute($route);
} catch (\Eception $e) {
    // Deal with exception
}
