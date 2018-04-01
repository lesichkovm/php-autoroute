<?php

/**
 * This function will automatically convert a specified route to
 * a controller and method, and will execute these.
 * if no controller method is found an exception will be thrown
 * <code>
 * autoroute(route);
 * </code>
 * @param string $route
 * @return array $options
 */
function autoroute($route = '', $options = []) {

    function camelize($string, $firstCharCaps = false) {
        $stringTrimmed = trim($string);
        if ($stringTrimmed == "") {
            return "";
        }
        $words = explode('_', $stringTrimmed);
        $wordsCamelized = array_map('ucfirst', $words);
        $stringCamelized = implode('', $wordsCamelized);
        $final = $firstCharCaps ? $stringCamelized : lcfirst($stringCamelized);
        //echo $final;
        return $final;
    }

    function unslug($slug) {
        if (is_array($slug)) {
            return array_map(function($e) {
                $e = unslug($e);
                return camelize($e, true);
            }, $slug);
        }
        return trim(str_replace('-', '_', $slug));
    }

    function listControllerMethod($route, $defaultNamespace, $defaultController, $defaultMethod, $useRequestMethod) {
        $requestMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $path = explode('/', $route);
        $method = unslug(array_pop($path));
        $controller = trim(implode('\\', unslug($path)));

        if ($method == '') {
            $method = $defaultMethod;
        }

        if ($controller == '') {
            $controller = $defaultController;
        }

        $namespace = $defaultNamespace == "" ? '' : $defaultNamespace . '\\';

        $controllerCamelized = camelize($namespace . $controller, true) . 'Controller';

        if ($useRequestMethod == 'yes') {
            $methodCamelized = camelize(strtolower($requestMethod) . '_' . $method);
            if (method_exists($controllerCamelized, $methodCamelized)) {
                return [$controllerCamelized, $methodCamelized];
            }
            return [$controllerCamelized, camelize('any_' . $method)];
        }

        return [$controllerCamelized, camelize($method)];
    }

    $defaultNamespace = $options['default_namespace'] ?? '';
    $defaultController = $options['default_controller'] ?? 'Guest';
    $defaultMethod = $options['default_method'] ?? 'index';
    $useRequestMethod = $options['use_request_method'] ?? false;
    list($controller, $method) = listControllerMethod($route, $defaultNamespace, $defaultController, $defaultMethod, $useRequestMethod);


    try {
        $reflectionMethod = new ReflectionMethod($controller, $method);
        if ($reflectionMethod->isPublic() == true) {
            die($reflectionMethod->invokeArgs(new $controller, []));
        }
        die('No public access allowed');
    } catch (ReflectionException $e) {
        $e->controller = $controller;
        $e->method = $method;
        throw $e;
    }
}
