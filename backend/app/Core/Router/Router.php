<?php

namespace App\Core\Router;

/**
 * Router class is responsible for adding routes to the map depending on the request method
 */
trait Router
{
    private static array $map;

    public static function get(string $url, string $class, string $method, array $middlewares = []): void
    {
        self::$map['get'][$url] = [
            'class' => $class,
            'method' => $method,
            'middlewares' => $middlewares,
        ];
    }
    public static function post(string $url, string $class, string $method, array $middlewares = []): void
    {
        self::$map['post'][$url] = [
            'class' => $class,
            'method' => $method,
            'middlewares' => $middlewares,
        ];
    }
    public static function put(string $url, string $class, string $method, array $middlewares = []): void
    {
        self::$map['put'][$url] = [
            'class' => $class,
            'method' => $method,
            'middlewares' => $middlewares,
        ];
    }
    public static function delete(string $url, string $class, string $method, array $middlewares = []): void
    {
        self::$map['delete'][$url] = [
            'class' => $class,
            'method' => $method,
            'middlewares' => $middlewares,
        ];
    }
    public static function getMap(): array
    {
        return self::$map;
    }
}
