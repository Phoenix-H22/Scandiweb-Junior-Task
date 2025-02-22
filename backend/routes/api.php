<?php

use App\Core\Router\Route;

$app = new Route();

$app::get('/seed', 'DatabaseController', 'seed');
$app::get('/seed-gallery', 'DatabaseController', 'seedGalleryData');

// Add GraphQL route
$app::post('/graphql', 'GraphQLController', 'handle');
$app->run();
