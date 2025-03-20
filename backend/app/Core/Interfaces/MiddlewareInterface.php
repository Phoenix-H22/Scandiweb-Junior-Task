<?php

namespace App\Core\Interfaces;
interface MiddlewareInterface
{
    /**
     * Handle an incoming request.
     * Return boolean or throw exception if check fails.
     *
     * @return void
     */
    public function handle(): void;
}