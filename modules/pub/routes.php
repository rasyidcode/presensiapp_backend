<?php

$routes->group('', ['namespace' => $routes_namespace], function($routes) {
    $routes->get('/', 'Home\Controllers\HomeController::index');
    $routes->get('/login', 'Login\Controllers\LoginController::handleLogin');
});