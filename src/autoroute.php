<?php

/**
 * This function will recognize the action to be executed by PHP
 * depending on the URI used. It will return an array with two entries
 * controller and method.
 * <code>
 * list(controller,method) = autoroute(route);
 * </code>
 * @param string $route
 * @return array
 */
function autoroute($route = '', $options = []) {

    function camelize($string, $first_char_caps = false) {
        if ($first_char_caps == true) {
            $string[0] = strtoupper($string[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $string);
    }

    function listControllerMethod($route, $useRequestMethod) {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $path = explode('/', $route);
        $method = trim(str_replace('-', '_', array_pop($path)));
        $path = array_map(function($e) {
            $e = trim(str_replace('-', '_', $e));
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

        if ($useRequestMethod == 'yes') {
            $methodCamelized = camelize(strtolower($requestMethod) . '_' . $method);
            if (method_exists($controllerCamelized, $methodCamelized)) {
                return [$controllerCamelized, $methodCamelized];
            }
            $methodCamelizedAny = camelize('any_' . $method);
            return [$controllerCamelized, $methodCamelizedAny];
        }

        $methodCamelized = camelize($method);
        return [$controllerCamelized, $methodCamelized];
    }

    $useRequestMethod = isset($options['use_request_method']) ? $options['use_request_method'] : false;
    list($controller, $method) = listControllerMethod($route, $useRequestMethod);


    try {
        $reflectionClass = new ReflectionClass($controller);

        $reflectionMethod = new ReflectionMethod($controller, $method);
        if ($reflectionMethod->isPublic() == true) {
            die($reflectionMethod->invokeArgs(new $controller, []));
        }
        die('No public access allowed');
    } catch (Exception $e) {
        //die('Not found:' . $controllerCamelized . '@' . $methodCamelized);
        die($e);
    }
    //return [$controllerCamelized, $methodCamelized];
}
