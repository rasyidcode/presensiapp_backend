<?php

// $result = class_exists('\Modules\Pub\Home\Controllers\HomeController');
// print_r($result ? 'true' : 'false');die();
$routes->group('', ['namespace' => $routes_namespace], function($routes) {
    $routes->get('/', 'Home\Controllers\HomeController::index');
    $routes->get('/login', 'Login\Controllers\LoginController::handleLogin');
});