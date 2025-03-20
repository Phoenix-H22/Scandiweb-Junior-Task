<?php

namespace App\Core\Router;

/**
 * trait UrlEngine is responsible for getting the request method, path and params
 */
trait UrlEngine
{
    public function method(): string
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

    public function path(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $baseDir = '/ScandiWeb-Senior-Test/public';
        if (str_starts_with($path, $baseDir)) {
            $path = substr($path, strlen($baseDir));
        }
        return $path ?: '/';
    }

    /**
     * params method is responsible for getting the request params
     *
     * @return array
     */
    public function params(): ?array
    {
        $params = $_SERVER['REQUEST_URI'];
        $params = explode('/', $params);
        $params = array_filter($params);
        $params = array_slice($params, 1);
        if ($params) {
            $params["id"] = $params[0] ?? null;
            if ($params["id"] != null) {
                unset($params[0]);
            }
        } else {
            $params = null;
        }
        return $params;
    }

}
