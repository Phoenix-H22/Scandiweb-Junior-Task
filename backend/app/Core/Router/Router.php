<?php

namespace App\Core\Router;
/**
 * Router class is responsible for adding routes to the map depending on the request method
 */

trait Router
{
    private static $map;
    /**
     * get method is responsible for adding get routes to the map
     *
     * @param string $url
     * @param string $class
     * @param string $method
     * @return void
     */
    public static function get($url, $class, $method)
    {

        self::$map['get'][$url] = [
            'class'=>$class,
            'method'=>$method
        ];
    }
    /**
     * post method is responsible for adding post routes to the map
     *
     * @param string $url
     * @param string $class
     * @param string $method
     * @return void
     */
    public static function post($url, $class, $method)
    {

        self::$map['post'][$url] = [
            'class'=>$class,
            'method'=>$method
        ];
    }
    /**
     * put method is responsible for adding put routes to the map
     *
     * @param string $url
     * @param string $class
     * @param string $method
     * @return void
     */
    public static function put($url, $class, $method)
    {
        self::$map['put'][$url] = [
            'class' => $class,
            'method' => $method,
        ];
    }
    /**
     * delete method is responsible for adding delete routes to the map
     *
     * @param string $url
     * @param string $class
     * @param string $method
     * @return void
     */
    public static function delete($url, $class, $method)
    {
        self::$map['delete'][$url] = [
            'class' => $class,
            'method' => $method,
        ];
    }
    /**
     * getMap method is responsible for returning the map
     *
     * @return array
     */

    public static function getMap()
    {
        return self::$map;
    }
}
