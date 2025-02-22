<?php

namespace App\Core\Errors;

class Errors
{
    /**
     * E404 method is responsible for rendering 404 page
     *
     * @return void
     */
    public static function E404():void
    {
        header('Content-Type: application/json');

        http_response_code(404);
        echo json_encode([
            'status' => 404,
            'message' => 'Page not found'
        ]);
        die();
    }
    /**
     * E500 method is responsible for rendering 500 page
     *
     * @return void
     */
    public static function E500($request = null , $message = null): void
    {
        header('Content-Type: application/json');

        http_response_code(500);
        echo json_encode([
            'status' => 500,
            'message' => 'Internal server error'
        ]);
        die();
    }
}
