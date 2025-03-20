<?php

use App\Core\Middleware\EnvironmentMiddleware;
use App\Core\Router\Route;

$app = new Route();

$app::get('/seed', 'DatabaseController', 'seed', [
    new EnvironmentMiddleware('development'),
]);

$app::get('/seed-gallery', 'DatabaseController', 'seedGalleryData', [
    new EnvironmentMiddleware('development'),
]);

// Add GraphQL route
$app::post('/graphql', 'GraphQLController', 'handle');
$app->run();
