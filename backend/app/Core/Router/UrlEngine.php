<?php

namespace App\Core\Router;
/**
 * trait UrlEngine is responsible for getting the request method, path and params
 */

trait UrlEngine
{
    /**
     * method method is responsible for getting the request method
     *
     * @return string
     */
    public function method()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            return 'put';
        } elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            return 'delete';
        } else {
            return strtolower($_SERVER['REQUEST_METHOD']);
        }
    }

    /**
     * path method is responsible for getting the request path
     *
     * @return string
     */

    public function path()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $baseDir = '/ScandiWeb-Senior-Test/public';
        if (strpos($path, $baseDir) === 0) {
            $path = substr($path, strlen($baseDir));
        }
        $resolvedPath = $path ?: '/';

        return $resolvedPath;
    }

    /**
     * params method is responsible for getting the request params
     *
     * @return array
     */
    public function params()
    {
        $params = $_SERVER['REQUEST_URI'];
        $params = explode('/', $params);
        $params = array_filter($params);
        $params = array_slice($params, 1);
        if($params){
            $params["id"] = $params[0]??null;
            if($params["id"]!=null){
                unset($params[0]);
            }
        }else{
            $params = null;
        }
        return $params;
    }

}
