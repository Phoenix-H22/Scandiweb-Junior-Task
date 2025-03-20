<?php

namespace App\Core\Router;

use App\Core\Errors\Errors;
use ReflectionClass;

/**
 * Route class is responsible for routing the application
 */
class Route
{

    use Router;
    use UrlEngine;

    private const API_PREFIX = '/api';


    /**
     * run method is responsible for running routes and calling the appropriate controller
     */
    public function run()
    {
        $path = $this->path();

        if (str_starts_with($path, self::API_PREFIX)) {
            $path = substr($path, strlen(self::API_PREFIX));
        }

        if (empty($path)) {
            $path = '/';
        }

        $callable = $this->match($this->method(), $path);

        if (!$callable) {
            Errors::E404();
        }

        $middlewares = $callable['middlewares'] ?? [];
        foreach ($middlewares as $middleware) {
            $middleware->handle();
        }

        $class = "App\\Controllers\\" . $callable['class'];
        $method = $callable['method'];
        if (!class_exists($class)) {
            var_dump("Class does not exist:", $class);
            die();
        }

        $classInstance = new $class();

        if (!method_exists($classInstance, $method)) {
            var_dump("Method does not exist:", $method);
            die();
        }

        if (!is_callable([$classInstance, $method])) {
            var_dump("Method is not callable:", $classInstance, $method);
            die();
        }


        $args = array_values($_REQUEST);


        call_user_func_array([$classInstance, $method], $args);
    }


    /**
     * match method is responsible for matching the url with the routes
     *
     * @param string $method
     * @param string $url
     * @return bool | array
     */

    private function match($method, $url)
    {
        foreach (self::$map[$method] as $uri => $call) {
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $uri);
            $pattern = "@^" . $pattern . "$@";

            if (preg_match($pattern, $url, $matches)) {
                $params = array_filter(
                    $matches,
                    fn($key) => !is_numeric($key),
                    ARRAY_FILTER_USE_KEY
                );


                $_REQUEST = array_merge($_REQUEST, $params);

                return $call;
            }
        }

        return false;
    }

}
