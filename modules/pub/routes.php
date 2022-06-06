<?php

$routes->group('login', ['namespace' => $routes_namespace . 'Login\Controllers\\', 'filter' => 'web-redirect-if-auth-filter'], function($routes) {
    $routes->get('/',   'LoginController::index', ['as' => 'login']);
    $routes->post('/',  'LoginController::login', ['as' => 'do-login']);
});