<?php
/**
 * This function will recognize the Controller@Method action
 * to be executed depending on the URI used. 
 * <code>
 * include('autoroute.php');
 * $route = isset($_REQUEST['route']) ? trim($_REQUEST['route']) : '';
 * autoroute($route); // Start autorouting
 * </code>
 *
 * @param string $route
 * @return array
 */
function autoroute($route = '') {

    function camelize($string, $first_char_caps = false) {
        if ($first_char_caps == true) {
            $string[0] = strtoupper($string[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $string);
    }

    $path = explode('/', $route);
    $method = trim(str_replace('-', '_', array_pop($path)));
    $path = array_map(function($e) {
        return camelize($e, true);
    }, $path);
    $controller = trim(implode('\\', $path));
    if ($method == '') {
        $method = 'home';
    }
    if ($controller == '') {
        $controller = 'Guest';
    }
    $controllerCamelized = camelize($controller, true) . 'Controller';
    $methodCamelized = camelize($method);
    try {
        $reflectionMethod = new ReflectionMethod($controllerCamelized, $methodCamelized);
        if ($reflectionMethod->isPublic() == true) {
            die($reflectionMethod->invokeArgs(new $controllerCamelized, []));
        }
        die('No public access allowed');
    } catch (Exception $e) {
        //die('Not found:' . $controllerCamelized . '@' . $methodCamelized);
        throw $e;
    }
}
