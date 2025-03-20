<?php

namespace App\Core\Middleware;

use App\Core\Interfaces\MiddlewareInterface;

class EnvironmentMiddleware implements MiddlewareInterface
{
    private string $allowedEnv;
    public function __construct(string $allowedEnv = 'development')
    {
        $this->allowedEnv = $allowedEnv;
    }

    public function handle(): void
    {
        $currentEnv = ENVIRONMENT;

        if ($currentEnv !== $this->allowedEnv) {
            header('HTTP/1.1 403 Forbidden');
            exit("Access forbidden in '{$currentEnv}' environment.");
        }
    }
}
